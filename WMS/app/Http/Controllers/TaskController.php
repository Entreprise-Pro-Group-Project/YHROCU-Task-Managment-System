<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Show a specific task
    public function show(Task $task)
    {
        if (Auth::user()->role === 'supervisor') {
            return view('tasks.sshow', compact('task'));
        }
        return view('tasks.show', compact('task'));
    }

    // Show the form for editing a task
    public function edit(Task $task)
    {
        if (Auth::user()->role === 'supervisor') {
            return view('tasks.sedit', compact('task'));
        }

        return view('tasks.edit', compact('task'));
    }

    // Update a task
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'assigned_staff' => 'required|string|max:255',
            'due_date' => 'required|date',
            'parent_id' => 'nullable|exists:tasks,id',
        ]);

        $task->update($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Task updated successfully');
    }

    // Delete a task
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Task deleted successfully');
    }

    // Show the form for creating a new task
    public function create()
    {
        if (Auth::user()->role === 'supervisor') {
            return view('tasks.screate');
        }
        return view('tasks.create');
    }

    // Store a new task in the database
    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'assigned_staff' => 'required|string|max:255',
            'due_date' => 'required|date',
            'parent_id' => 'nullable|exists:tasks,id',
        ]);

        // Store the task in the session
        $task = [
            'id' => uniqid(), // Temporary ID for session
            'task_name' => $request->task_name,
            'assigned_staff' => $request->assigned_staff,
            'due_date' => $request->due_date,
            'parent_id' => $request->parent_id,
        ];

        $tasks = session('tasks', []);
        $tasks[] = $task;
        session(['tasks' => $tasks]);

        return redirect()->route('projects.create')->with('success', 'Task added successfully');
    }
}