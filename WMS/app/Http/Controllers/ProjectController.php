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
            'due_date'           => 'required|date',
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

        $supervisor = User::where('first_name', $request->supervisor_name)->first();
        if ($supervisor) {
            // Send immediate notification (no delay) about the update
            $supervisor->notify(new ProjectUpdated($project));
        }
        
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
        if (Auth::user()->role === 'supervisor') {
            return view('projects.screate');
        }
        return view('projects.create');
    }

    // Store a new project and its tasks, scheduling notifications for supervisor and tasks
    public function store(Request $request)
    {
        $request->validate([
            'project_name'       => 'required|string|max:255',
            'project_description'=> 'required|string',
            'project_date'       => 'required|date',
            'due_date'           => 'required|date',
            'supervisor_name'    => 'required|string|max:255',
        ]);
    
        if (!session('tasks') || count(session('tasks')) === 0) {
            return redirect()->back()->with('error', 'At least one task is required');
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
    
        // Create tasks
        foreach (session('tasks') as $taskData) {
            $user = User::where('first_name', $taskData['assigned_staff'])->first();
            
            if ($user) {
                $task = Task::create([
                    'project_id'       => $project->id,
                    'task_name'        => $taskData['task_name'],
                    'task_description' => $taskData['task_description'],
                    'assigned_staff'   => $user->email,
                    'assigned_date'    => $taskData['assigned_date'],
                    'due_date'         => $taskData['due_date'],
                    'parent_id'        => $taskData['parent_id'] ?? null,
                ]);
                
                $taskDelay = Carbon::parse($taskData['assigned_date'])->startOfDay();
                // Schedule the assigned user notification
                $user->notify((new TaskAssigned($task))->delay($taskDelay));
            } else {
                Task::create([
                    'project_id'       => $project->id,
                    'task_name'        => $taskData['task_name'],
                    'task_description' => $taskData['task_description'],
                    'assigned_staff'   => $taskData['assigned_staff'],
                    'assigned_date'    => $taskData['assigned_date'],
                    'due_date'         => $taskData['due_date'],
                    'parent_id'        => $taskData['parent_id'] ?? null,
                ]);
            }
        }
    
        session()->forget('tasks');
    
        // Look up the supervisor and schedule the ProjectCreated notification
        $supervisor = User::where('first_name', $request->supervisor_name)->first();
        if ($supervisor) {
            $supervisor->notify((new ProjectCreated($project))->delay($supervisorDelay));
        }
    
        if (Auth::user()->role === 'supervisor') {
            return redirect()->route('supervisor.dashboard')->with('success', 'Project created successfully');
        } else {
            return redirect()->route('admin.dashboard')->with('success', 'Project created successfully');
        }
    }
}
