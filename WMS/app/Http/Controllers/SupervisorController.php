<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    // Display the admin dashboard with all projects
    public function dashboard()
    {
        $projects = \App\Models\Project::all(); // Example: Fetch all projects
        return view('supervisor.dashboard', compact('projects'));
    }

    // Show a specific project
    public function showProject(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    // Show the form for editing a project
    public function editProject(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    // Update a project
    public function updateProject(Request $request, Project $project)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_date' => 'required|date',
            'due_date' => 'required|date',
            'supervisor_name' => 'required|string|max:255',
        ]);

        $project->update($request->all());

        return redirect()->route('supervisor.dashboard')->with('success', 'Project updated successfully');
    }

    // Show a specific task
    public function showTask(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    // Show the form for editing a task
    public function editTask(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    // Update a task
    public function updateTask(Request $request, Task $task)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'assigned_staff' => 'required|string|max:255',
            'due_date' => 'required|date',
            'parent_id' => 'nullable|exists:tasks,id',
        ]);

        $task->update($request->all());

        return redirect()->route('supervisor.dashboard')->with('success', 'Task updated successfully');
    }
}