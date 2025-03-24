<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\ChangeLog; // <-- Import the ChangeLog model
use App\Notifications\TaskUpdated;
use App\Notifications\TaskDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        // 1) Capture old data before updating
        $oldData = $task->only([
            'task_name', 'task_description', 'assigned_staff',
            'assigned_date', 'due_date', 'parent_id', 'comment', 'status'
        ]);

        // 2) Update only the due_date field
        $task->update($validated);

        // 3) Capture new data after updating
        $newData = $task->only([
            'task_name', 'task_description', 'assigned_staff',
            'assigned_date', 'due_date', 'parent_id', 'comment', 'status'
        ]);

        // 4) Log the change
        ChangeLog::create([
            'entity_type' => 'task',
            'entity_id'   => $task->id,
            'changed_by'  => Auth::id() ?? 1,
            'changes'     => [
                'action' => 'updated',
                'before' => $oldData,
                'after'  => $newData,
            ],
        ]);

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
                'status'  => 'required|string|in:assigned,in progress,completed,over due,overdue',
                'comment' => 'nullable|string',
            ]);

            // 1) Capture old data
            $oldData = $task->only([
                'task_name', 'task_description', 'assigned_staff',
                'assigned_date', 'due_date', 'parent_id', 'comment', 'status'
            ]);

            $status = $request->input('status');
            $comment = $request->input('comment', $task->comment);

            // If due date has passed and status is not completed, force status to 'overdue'
            if (Carbon::parse($task->due_date) < now() && $status !== 'completed') {
                $status = 'overdue';
            }

            // 2) Update
            $task->update([
                'status'  => $status,
                'comment' => $comment,
            ]);

            // 3) Capture new data
            $newData = $task->only([
                'task_name', 'task_description', 'assigned_staff',
                'assigned_date', 'due_date', 'parent_id', 'comment', 'status'
            ]);

            // 4) Log the update
            ChangeLog::create([
                'entity_type' => 'task',
                'entity_id'   => $task->id,
                'changed_by'  => Auth::id() ?? 1,
                'changes'     => [
                    'action' => 'updated',
                    'before' => $oldData,
                    'after'  => $newData,
                ],
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

        // 1) Capture old data
        $oldData = $task->only([
            'task_name', 'task_description', 'assigned_staff',
            'assigned_date', 'due_date', 'parent_id', 'comment', 'status'
        ]);

        // 2) Update
        $task->update($validated);

        // 3) Capture new data
        $newData = $task->only([
            'task_name', 'task_description', 'assigned_staff',
            'assigned_date', 'due_date', 'parent_id', 'comment', 'status'
        ]);

        // 4) Log the update
        ChangeLog::create([
            'entity_type' => 'task',
            'entity_id'   => $task->id,
            'changed_by'  => Auth::id() ?? 1,
            'changes'     => [
                'action' => 'updated',
                'before' => $oldData,
                'after'  => $newData,
            ],
        ]);

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

        // 1) Capture old data
        $oldData = $task->only([
            'task_name', 'task_description', 'assigned_staff',
            'assigned_date', 'due_date', 'parent_id', 'comment', 'status'
        ]);

        // 2) Delete the task
        $task->delete();

        // 3) Log the deletion
        ChangeLog::create([
            'entity_type' => 'task',
            'entity_id'   => $task->id,
            'changed_by'  => Auth::id() ?? 1,
            'changes'     => [
                'action' => 'deleted',
                'before' => $oldData,
                'after'  => [],
            ],
        ]);

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

    // Reassign a task to a different staff member
    public function reassign(Request $request, Task $task)
    {
        // Validate that a staff member was selected
        $validated = $request->validate([
            'assigned_staff' => 'required|string|max:255',
        ]);

        // 1) Capture old data
        $oldData = $task->only([
            'task_name', 'task_description', 'assigned_staff',
            'assigned_date', 'due_date', 'parent_id', 'comment', 'status'
        ]);

        // 2) Update the task with the new assigned_staff
        $task->update($validated);

        // 3) Capture new data
        $newData = $task->only([
            'task_name', 'task_description', 'assigned_staff',
            'assigned_date', 'due_date', 'parent_id', 'comment', 'status'
        ]);

        // 4) Log the reassignment
        ChangeLog::create([
            'entity_type' => 'task',
            'entity_id'   => $task->id,
            'changed_by'  => Auth::id() ?? 1,
            'changes'     => [
                'action' => 'reassigned',
                'before' => $oldData,
                'after'  => $newData,
            ],
        ]);

        return redirect()->back()->with('success', 'Task reassigned successfully.');
    }

    // Store a new task in the database
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

        // 1) Create the task
        $task = Task::create([
            'task_name'        => $request->task_name,
            'task_description' => $request->task_description,
            'assigned_staff'   => $request->assigned_staff,
            'assigned_date'    => $request->assigned_date,
            'due_date'         => $request->due_date,
            'parent_id'        => $request->parent_id, // This will only be set if it's a sub-task
        ]);

        // 2) Log the creation
        ChangeLog::create([
            'entity_type' => 'task',
            'entity_id'   => $task->id,
            'changed_by'  => Auth::id() ?? 1,
            'changes'     => [
                'action' => 'created',
                'before' => [],
                'after'  => [
                    'task_name'        => $task->task_name,
                    'task_description' => $task->task_description,
                    'assigned_staff'   => $task->assigned_staff,
                    'assigned_date'    => $task->assigned_date,
                    'due_date'         => $task->due_date,
                    'parent_id'        => $task->parent_id,
                ],
            ],
        ]);

        return redirect()->route('projects.create')->with('success', 'Task added successfully');
    }
}
