<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 


class TaskController extends Controller
{
    // Show a specific task
    public function show(Task $task)
{
    // Fetch all users, or filter to only staff if needed:
    $users = User::where('role', 'staff')->get();
    
    if (Auth::user()->role === 'supervisor') {
        return view('tasks.sshow', compact('task', 'users'));
    } elseif (Auth::user()->role === 'staff') {
        return view('tasks.staffshow', compact('task', 'users'));
    }
    return view('tasks.show', compact('task', 'users'));
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
        // Check if the request is only updating the status (and optionally comment)
        if ($request->has('status') && !$request->hasAny(['task_name', 'assigned_staff', 'due_date', 'parent_id'])) {
            $request->validate([
                'status'  => 'required|string|in:assigned,in progress,completed,over due',
                'comment' => 'nullable|string',
            ]);

            $status = $request->input('status');
            $comment = $request->input('comment', $task->comment);

            // If the due date has passed and the status is not 'completed', force status to 'overdue'
            if (\Carbon\Carbon::parse($task->due_date) < now() && $status !== 'completed') {
                $status = 'overdue';
            }

            $task->update([
                'status'  => $status,
                'comment' => $comment,
            ]);

            return redirect()->back()->with('success', 'Task updated successfully');
        }

        // Otherwise, process the full update (including comment)
        $validated = $request->validate([
            'task_name'      => 'required|string|max:255',
            'task_description' => 'required|string',
            'assigned_staff' => 'required|string|max:255',
            'assigned_date'  => 'required|date',
            'due_date'       => 'required|date',
            'parent_id'      => 'nullable|exists:tasks,id',
            'comment'        => 'nullable|string',
        ]);

        $task->update($validated);

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
            'task_name'      => 'required|string|max:255',
            'task_description' => 'required|string',
            'assigned_staff' => 'required|string|max:255',
            'assigned_date'  => 'required|date',
            'due_date'       => 'required|date',
            'parent_id'      => 'nullable|exists:tasks,id',
        ]);

        // Store the task in the session
        $task = [
            'id'               => uniqid(), // Temporary ID for session
            'task_name'        => $request->task_name,
            'task_description' => $request->task_description,
            'assigned_staff'   => $request->assigned_staff,
            'assigned_date'    => $request->assigned_date,
            'due_date'         => $request->due_date,
            'parent_id'        => $request->parent_id,
        ];

        $tasks = session('tasks', []);
        $tasks[] = $task;
        session(['tasks' => $tasks]);

        return redirect()->route('projects.create')->with('success', 'Task added successfully');
    }
}