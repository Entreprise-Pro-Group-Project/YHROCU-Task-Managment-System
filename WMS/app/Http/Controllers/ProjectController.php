<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use App\Notifications\ProjectCreated;
use App\Notifications\ProjectUpdated;
use App\Notifications\ProjectDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
        if (Auth::user()->role === 'supervisor') {
            return view('projects.sshow', compact('project'));
        }
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        if (Auth::user()->role === 'supervisor') {
            return view('projects.sedit', compact('project'));
        }
        return view('projects.edit', compact('project'));
    }

    // Update a project (immediate notifications for updates)
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'project_name'       => 'required|string|max:255',
            'project_description'=> 'required|string',
            'project_date'       => 'required|date',
            'due_date' => 'required|date|after_or_equal:project_date',
            'supervisor_name'    => 'required|string|max:255',
        ]);

        // Update project details
        $project->update([
            'project_name'        => $request->project_name,
            'project_description' => $request->project_description,
            'project_date'        => $request->project_date,
            'due_date'            => $request->due_date,
            'supervisor_name'     => $request->supervisor_name,
        ]);

        // Array to map temporary task names to their actual IDs
        $taskIdMapping = [];

        // Process tasks
        if ($request->has('tasks')) {
            foreach ($request->tasks as $taskData) {
                if (!empty($taskData['task_name'])) {
                    // Find user by first name
                    $user = User::where('first_name', $taskData['assigned_staff'])->first();
    
                    // Get the correct parent ID if available
                    $parentId = $taskData['parent_id'] ?? null;
    
                    // Create or update task
                    $task = Task::updateOrCreate(
                        ['id' => $taskData['id'] ?? null],
                        [
                            'project_id'       => $project->id,
                            'task_name'        => $taskData['task_name'],
                            'task_description' => $taskData['task_description'],
                            'assigned_staff'   => $user ? $user->email : $taskData['assigned_staff'],
                            'assigned_date'    => $taskData['assigned_date'],
                            'due_date'         => $taskData['due_date'],
                            'parent_id'        => $parentId,
                        ]
                    );
    
                    // Store task ID for future parent reference
                    $taskIdMapping[$taskData['task_name']] = $task->id;
    
                    // Send notification to assigned staff
                    if ($user) {
                        $user->notify(new TaskAssigned($task));
                    }
                }
            }
        }
        
        // Notify the supervisor about the update.
        $supervisor = User::where('first_name', $request->supervisor_name)->first();
        if ($supervisor) {
            $supervisor->notify(new ProjectUpdated($project));
        }
        
        // viewing respective dasboard based on role
        if (Auth::user()->role === 'supervisor') {
            return redirect()->route('supervisor.dashboard')->with('success', 'Project updated successfully');
        } else {
            return redirect()->route('admin.dashboard')->with('success', 'Project updated successfully');
        }
    }

    // Delete a project (notify before removing from DB)
    public function destroy(Project $project)
    {
        $supervisor = User::where('first_name', $project->supervisor_name)->first();

        if ($supervisor) {
            $supervisor->notifyNow(new ProjectDeleted($project->id, $project->project_name));
        }

        // Now remove the project from the database
        $project->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Project deleted successfully');
    }
    
    public function create()
    {
        $users = User::where('role', 'staff')->get(); 

        if (Auth::user()->role === 'supervisor') {
            return view('projects.screate', compact('users'));
        }
        
        return view('projects.create', compact('users'));
    }

    // Store a new project and its tasks, scheduling notifications for supervisor and tasks
    public function store(Request $request)
    {
        $request->validate([
            'project_name'       => 'required|string|max:255',
            'project_description'=> 'required|string',
            'project_date'       => 'required|date',
            'due_date'           => 'required|date|after_or_equal:project_date',
            'supervisor_name'    => 'required|string|max:255',
            'tasks'              => 'nullable|string', // We'll expect a JSON string here
        ]);

        // Decode tasks from JSON
        $tasks = json_decode($request->tasks, true) ?? [];
        if (empty($tasks)) {
            return redirect()->back()->with('error', 'At least one task is required.');
        }

        // Create the project
        $project = Project::create([
            'project_name'       => $request->project_name,
            'project_description'=> $request->project_description,
            'project_date'       => $request->project_date,
            'due_date'           => $request->due_date,
            'supervisor_name'    => $request->supervisor_name,
        ]);

        // Delay for supervisor notification = project start date
        $supervisorDelay = Carbon::parse($project->project_date)->startOfDay();

        // Array to map temporary names to actual IDs if needed for sub-task parent references
        $taskIdMapping = [];

        foreach ($tasks as $taskData) {
            // Find user by first name
            $user = User::where('first_name', $taskData['assigned_staff'])->first();

            // Resolve parent_id if needed
            $parentId = $taskData['parent_id'] ?? null;
            if ($parentId && isset($taskIdMapping[$parentId])) {
                $parentId = $taskIdMapping[$parentId];
            } else {
                $parentId = null;
            }

            // Create the actual Task record
            $newTask = Task::create([
                'project_id'       => $project->id,
                'task_name'        => $taskData['task_name'],
                'task_description' => $taskData['task_description'],
                'assigned_staff'   => $user ? $user->email : $taskData['assigned_staff'],
                'assigned_date'    => $taskData['assigned_date'],
                'due_date'         => $taskData['due_date'],
                'parent_id'        => $parentId,
            ]);

            // Map the original 'task_name' or some unique key to the new Task ID
            $taskIdMapping[$taskData['task_name']] = $newTask->id;

            // Schedule notification for the assigned user (if found)
            if ($user) {
                $taskDelay = Carbon::parse($taskData['assigned_date'])->startOfDay();
                $user->notify((new TaskAssigned($newTask))->delay($taskDelay));
            }
        }

        // Look up the supervisor by first name
        $supervisor = User::where('first_name', $request->supervisor_name)->first();
        if ($supervisor) {
            // Schedule the ProjectCreated notification for the project's start date
            $supervisor->notify((new ProjectCreated($project))->delay($supervisorDelay));
        }

        if (Auth::user()->role === 'supervisor') {
            return redirect()->route('supervisor.dashboard')->with('success', 'Project created successfully');
        } else {
            return redirect()->route('admin.dashboard')->with('success', 'Project created successfully');
        }
    }
}