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

// ProjectController handles CRUD operations and notifications for projects and their tasks
class ProjectController extends Controller
{
    // Display a specific project based on user role
    public function show(Project $project)
    {
        if (Auth::user()->role === 'supervisor') {
            return view('projects.sshow', compact('project'));
        }
        return view('projects.show', compact('project'));
    }

    // Show the form for editing a project
    public function edit(Project $project)
    {
        // Get all staff users
        $users = User::where('role', 'staff')->get();
        // Get all supervisors
        $supervisors = User::where('role', 'supervisor')->get();
        
        if (Auth::user()->role === 'supervisor') {
            return view('projects.sedit', compact('project', 'users', 'supervisors'));
        }
        return view('projects.edit', compact('project', 'users', 'supervisors'));
    }

    // Update a project and its tasks, send notifications for updates
    public function update(Request $request, Project $project)
    {
        // Validate incoming request data
        $request->validate([
            'project_name'       => 'required|string|max:255',
            'project_description'=> 'required|string',
            'project_date'       => 'required|date',
            'due_date'          => 'required|date|after_or_equal:project_date',
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

        // Process each task from the request
        if ($request->has('tasks')) {
            foreach ($request->tasks as $taskData) {
                if (!empty($taskData['task_name'])) {
                    // Find user by first name
                    $user = User::where('first_name', $taskData['assigned_staff'])->first();
    
                    // Get the correct parent ID if available
                    $parentId = $taskData['parent_id'] ?? null;
    
                    // Check if this is a new task or an existing one
                    $isNewTask = !isset($taskData['id']);
                    
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

                    // Only send TaskAssigned notification for new tasks
                    if ($isNewTask && $user) {
                        $taskDelay = Carbon::parse($taskData['assigned_date'])->startOfDay();
                        $user->notify((new TaskAssigned($task))->delay($taskDelay));
                    }
                }
            }
        }
        
        // Notify the supervisor about the update
        $supervisor = User::where('first_name', $request->supervisor_name)->first();
        if ($supervisor) {
            $supervisor->notify(new ProjectUpdated($project));
        }
        
        // Redirect to the appropriate dashboard based on user role
        if (Auth::user()->role === 'supervisor') {
            return redirect()->route('supervisor.dashboard')->with('success', 'Project updated successfully');
        } else {
            return redirect()->route('admin.dashboard')->with('success', 'Project updated successfully');
        }
    }

    // Delete a project and notify the supervisor before deletion
    public function destroy(Project $project)
    {
        // Find supervisor by first name
        $supervisor = User::where('first_name', $project->supervisor_name)->first();

        if ($supervisor) {
            $supervisor->notifyNow(new ProjectDeleted($project->id, $project->project_name));
        }

        // Remove the project from the database
        $project->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Project deleted successfully');
    }
    
    // Show the form for creating a new project
    public function create()
    {
        // Get all staff users
        $users = User::where('role', 'staff')->get(); 
        // Get all supervisors
        $supervisors = User::where('role', 'supervisor')->get();

        if (Auth::user()->role === 'supervisor') {
            return view('projects.screate', compact('users', 'supervisors'));
        }
        
        return view('projects.create', compact('users', 'supervisors'));
    }

    // Store a new project and its tasks, schedule notifications for supervisor and tasks
    public function store(Request $request)
    {
        // Validate incoming request data
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

        // Array to map temporary names to actual IDs for sub-task parent references
        $taskIdMapping = [];

        // Create each task and schedule notifications
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

        // Redirect to the appropriate dashboard based on user role
        if (Auth::user()->role === 'supervisor') {
            return redirect()->route('supervisor.dashboard')->with('success', 'Project created successfully');
        } else {
            return redirect()->route('admin.dashboard')->with('success', 'Project created successfully');
        }
    }
}