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

class ProjectController extends Controller
{
    // Show a specific project
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
        if (Auth::user()->role === 'supervisor') {
            return view('projects.sedit', compact('project'));
        }
        return view('projects.edit', compact('project'));
    }

    // Update a project
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'project_name'    => 'required|string|max:255',
            'project_description' => 'required|string',
            'project_date'    => 'required|date',
            'due_date'        => 'required|date',
            'supervisor_name' => 'required|string|max:255',
        ]);

        $project->update($request->all());

        // Update or add new tasks
        if ($request->has('tasks')) {
            foreach ($request->tasks as $taskData) {
                if (!empty($taskData['task_name'])) {
                    Task::updateOrCreate(
                        ['id' => $taskData['id'] ?? null],
                        array_merge($taskData, ['project_id' => $project->id])
                    );
                }
            }
        }
        
        // Notify the supervisor about the update.
        $supervisor = User::where('first_name', $request->supervisor_name)->first();
        if ($supervisor) {
            $supervisor->notify(new ProjectUpdated($project));
        }
        
        if (Auth::user()->role === 'supervisor') {
            return redirect()->route('supervisor.dashboard')->with('success', 'Project updates successfully');
        } else {
            return redirect()->route('admin.dashboard')->with('success', 'Project updated successfully');
        }
    }

    // Delete a project
    public function destroy(Project $project)
    {
        // Look up the supervisor by the project's supervisor_name (first name)
        $supervisor = User::where('first_name', $project->supervisor_name)->first();
        if ($supervisor) {
            // Notify the supervisor about the deletion before removing the project
            $supervisor->notify(new ProjectDeleted($project));
        }
        
        $project->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Project deleted successfully');
    }
    
    // Show the form for creating a new project
    public function create()
    {
        if (Auth::user()->role === 'supervisor') {
            return view('projects.screate');
        }
        return view('projects.create');
    }

    // Store a new project and its tasks and notify the supervisor.
    public function store(Request $request)
    {
        $request->validate([
            'project_name'    => 'required|string|max:255',
            'project_description' => 'required|string',
            'project_date'    => 'required|date',
            'due_date'        => 'required|date',
            'supervisor_name' => 'required|string|max:255',
            'tasks' => 'nullable|string' // Store tasks as JSON in a hidden input field
        ]);
    
        // Ensure at least one task is added
        $tasks = json_decode($request->tasks, true) ?? [];
        
        if (empty($tasks)) {
            return redirect()->back()->with('error', 'At least one task is required.');
        }
    
        // Create the project
        $project = Project::create([
            'project_name'    => $request->project_name,
            'project_description' => $request->project_description,
            'project_date'    => $request->project_date,
            'due_date'        => $request->due_date,
            'supervisor_name' => $request->supervisor_name,
        ]);
    
        // Array to map temporary task names to their actual IDs
        $taskIdMapping = [];

        foreach ($tasks as $task) {
            // Find user by first name, fallback to null if not found
            $user = User::where('first_name', $task['assigned_staff'])->first();

            // Get the actual parent ID if available
            $parentId = $task['parent_id'] ?? null;
            if ($parentId && isset($taskIdMapping[$parentId])) {
                $parentId = $taskIdMapping[$parentId];
            } else {
                $parentId = null;
            }
    
            // Ensure a valid user exists before proceeding
            if ($user) {
                $newTask = Task::create([  // Store the created Task instance
                    'project_id'      => $project->id,
                    'task_name'       => $task['task_name'],
                    'task_description'=> $task['task_description'],
                    'assigned_staff'  => $user->email, // Store user's email instead of name
                    'assigned_date'   => $task['assigned_date'],
                    'due_date'        => $task['due_date'],
                    'parent_id'       => $parentId, // Optional parent_id for subtasks
                ]);

                // Store task ID for referencing parent_id
                $taskIdMapping[$task['task_name']] = $newTask->id;

                // Notify the user via email
                $user->notify(new TaskAssigned($newTask));

            } else {
                // Optionally, create the task without notification if user not found.
                Task::create([
                    'project_id'       => $project->id,
                    'task_name'        => $task['task_name'],
                    'task_description' => $task['task_description'],
                    'assigned_staff'   => $task['assigned_staff'], // Fallback, though ideally this shouldn't happen.
                    'assigned_date'    => $task['assigned_date'],
                    'due_date'         => $task['due_date'],
                    'parent_id'        => $parentId, // Optional parent_id for subtasks
                ]);
            }
        }
    
        // Look up the supervisor by first name (from supervisor_name field)
        $supervisor = User::where('first_name', $request->supervisor_name)->first();
        if ($supervisor) {
            // Notify the supervisor about the new project
            $supervisor->notify(new ProjectCreated($project));
        }

        if (Auth::user()->role === 'supervisor') {
            return redirect()->route('supervisor.dashboard')->with('success', 'Project created successfully');
        } else {
            return redirect()->route('admin.dashboard')->with('success', 'Project created successfully');
        }
    }
}