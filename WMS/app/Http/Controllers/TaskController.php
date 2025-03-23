<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskUpdated;
use App\Notifications\TaskDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    // Show a specific task
    public function show(Task $task)
    {
        // Retrieve all users with the 'staff' role, ordered by first name
        $users = User::where('role', 'staff')->orderBy('first_name')->get();
        
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
    // Retrieve all users with the 'staff' role, ordered by first name
    $users = User::where('role', 'staff')->orderBy('first_name')->get();

    if (Auth::user()->role === 'supervisor') {
        return view('tasks.sedit', compact('task', 'users'));
    }
    return view('tasks.edit', compact('task', 'users'));
}

public function updateDueDate(Request $request, Task $task)
{
    // Validate the due_date field
    $validated = $request->validate([
        'due_date' => 'required|date',
    ]);

    // Update only the due_date field
    $task->update($validated);

    if (Auth::user()->role === 'supervisor') {
        return redirect()->route('supervisor.dashboard')->with('success', 'Project created successfully');
    } else {
        return redirect()->route('admin.dashboard')->with('success', 'Project created successfully');
    }
    }



    // Update a task and notify the assigned user
    public function update(Request $request, Task $task)
    {
        // Check if the request is only updating status (and optionally comment)
        if ($request->has('status') && !$request->hasAny(['task_name', 'assigned_staff', 'due_date', 'parent_id'])) {
            $request->validate([
                'status'  => 'required|string|in:assigned,in progress,completed,over due',
                'comment' => 'nullable|string',
            ]);

            $status = $request->input('status');
            $comment = $request->input('comment', $task->comment);

            // If due date has passed and status is not completed, force status to 'overdue'
            if (\Carbon\Carbon::parse($task->due_date) < now() && $status !== 'completed') {
                $status = 'overdue';
            }

            $task->update([
                'status'  => $status,
                'comment' => $comment,
            ]);

            // Notify assigned user about the update
            $user = User::where('first_name', $task->assigned_staff)->first();
            if ($user) {
                $user->notify(new TaskUpdated($task));
            }

            return redirect()->back()->with('success', 'Task updated successfully');
        }

        // Process the full update (including comment)
        $validated = $request->validate([
            'task_name'         => 'required|string|max:255',
            'task_description'  => 'required|string',
            'assigned_staff'    => 'required|string|max:255',
            'assigned_date'     => 'required|date',
            'due_date'          => 'required|date',
            'parent_id'         => 'nullable|exists:tasks,id',
            'comment'           => 'nullable|string',
        ]);

        $task->update($validated);

        // Notify assigned user about the update
        $user = User::where('first_name', $task->assigned_staff)->first();
        if ($user) {
            $user->notify(new TaskUpdated($task));
        }

        return redirect()->route('admin.dashboard')->with('success', 'Task updated successfully');
    }

    // Delete a task and notify the assigned user
    public function destroy(Task $task)
    {
        // Look up the user by the email stored in assigned_staff before deletion
        $user = User::where('first_name', $task->assigned_staff)->first();
        if ($user) {
            $user->notify(new TaskDeleted($task));
        }
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

    // Store a new task in the session
    public function store(Request $request)
    {
        $request->validate([
            'task_name'         => 'required|string|max:255',
            'task_description'  => 'required|string',
            'assigned_staff'    => 'required|string|max:255',
            'assigned_date'     => 'required|date',
            'due_date'          => 'required|date',
            'parent_id'         => 'nullable|exists:tasks,id',
        ]);

        // Store the task in the session (temporary storage)
        $task = [
            'id'               => uniqid(), // Temporary ID for session
            'task_name'        => $request->task_name,
            'task_description' => $request->task_description,
            'assigned_staff'   => $request->assigned_staff,
            'assigned_date'    => $request->assigned_date,
            'due_date'         => $request->due_date,
            'parent_id'        => $request->parent_id, // This will only be set if it's a sub-task
        ];
    
        return redirect()->route('projects.create')->with('success', 'Task added successfully');
    }
}
