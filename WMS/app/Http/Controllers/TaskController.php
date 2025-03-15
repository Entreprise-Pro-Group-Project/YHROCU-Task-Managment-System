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
        } elseif (Auth::user()->role === 'staff') {
            return view('tasks.staffshow', compact('task'));
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
<<<<<<< Updated upstream
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
=======
        $request->validate([
            'task_name' => 'required|string|max:255',
>>>>>>> Stashed changes
            'assigned_staff' => 'required|string|max:255',
            'due_date'       => 'required|date',
            'parent_id'      => 'nullable|exists:tasks,id',
            'comment'        => 'nullable|string',
        ]);

<<<<<<< Updated upstream
        $task->update($validated);
=======
        $task->update($request->all());
>>>>>>> Stashed changes

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
            'assigned_staff' => 'required|string|max:255',
            'due_date'       => 'required|date',
            'parent_id'      => 'nullable|exists:tasks,id',
        ]);

        // Store the task in the session
        $task = [
            'id'            => uniqid(), // Temporary ID for session
            'task_name'     => $request->task_name,
            'assigned_staff'=> $request->assigned_staff,
            'due_date'      => $request->due_date,
            'parent_id'     => $request->parent_id,
        ];

        $tasks = session('tasks', []);
        $tasks[] = $task;
        session(['tasks' => $tasks]);

        return redirect()->route('projects.create')->with('success', 'Task added successfully');
    }
}
