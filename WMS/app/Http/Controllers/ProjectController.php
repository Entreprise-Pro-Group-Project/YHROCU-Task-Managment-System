<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
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
            'project_date'    => 'required|date',
            'due_date'        => 'required|date',
            'supervisor_name' => 'required|string|max:255',
        ]);

        $project->update($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Project updated successfully');
    }

    // Delete a project
    public function destroy(Project $project)
    {
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

    // Store a new project and its tasks and notify the user.
    public function store(Request $request)
    {
        $request->validate([
            'project_name'    => 'required|string|max:255',
            'project_date'    => 'required|date',
            'due_date'        => 'required|date',
            'supervisor_name' => 'required|string|max:255',
        ]);
    
        // Ensure at least one task is added
        if (!session('tasks') || count(session('tasks')) === 0) {
            return redirect()->back()->with('error', 'At least one task is required');
        }
    
        // Create the project
        $project = Project::create([
            'project_name'    => $request->project_name,
            'project_date'    => $request->project_date,
            'due_date'        => $request->due_date,
            'supervisor_name' => $request->supervisor_name,
        ]);
    
        // Create tasks for the project
        foreach (session('tasks') as $taskData) {
            // Find the user by name
            $user = User::where('first_name', $taskData['assigned_staff'])->first();
            
            if ($user) {
                // Create the task and store the actual email in the assigned_staff column
                $task = Task::create([
                    'project_id'     => $project->id,
                    'task_name'      => $taskData['task_name'],
                    'assigned_staff' => $user->email, // Store user's email
                    'due_date'       => $taskData['due_date'],
                    'parent_id'      => $taskData['parent_id'] ?? null,
                ]);
                
                // Notify the user via email
                $user->notify(new TaskAssigned($task));
            } else {
                // Optionally, create the task without notification if user not found.
                Task::create([
                    'project_id'     => $project->id,
                    'task_name'      => $taskData['task_name'],
                    'assigned_staff' => $taskData['assigned_staff'], // Fallback, though ideally this shouldn't happen.
                    'due_date'       => $taskData['due_date'],
                    'parent_id'      => $taskData['parent_id'] ?? null,
                ]);
            }
        }
    
        // Clear tasks from session
        session()->forget('tasks');

        // Look up the supervisor by first name (adjust if needed)
        $supervisor = \App\Models\User::where('first_name', $request->supervisor_name)->first();
        if ($supervisor) {
        // Notify the supervisor about the new project
        $supervisor->notify(new \App\Notifications\ProjectCreated($project));
        }

    
        return redirect()->route('admin.dashboard')->with('success', 'Project and tasks created successfully');
    }
}
