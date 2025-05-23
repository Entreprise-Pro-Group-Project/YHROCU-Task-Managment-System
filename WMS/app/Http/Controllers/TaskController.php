<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskUpdated;
use App\Notifications\TaskDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    // Show a specific task
    public function show(Task $task)
    {
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
        $users = User::where('role', 'staff')->orderBy('first_name')->get();
        
        $projectTasks = Task::where('project_id', $task->project_id)->where('id', '!=', $task->id)->get();  // exclude self from parent list
        
        if (Auth::user()->role === 'supervisor') {
            return view('tasks.sedit', compact('task', 'users', 'projectTasks'));
        }
        
        return view('tasks.edit', compact('task', 'users', 'projectTasks'));
    }

    
    public function updateDueDate(Request $request, Task $task)
    {  
        // Validate the due_date field
        $validated = $request->validate([
            'due_date' => 'required|date',
        ]);
        
        // Update only the due_date field
        $task->update($validated);

        // Notify assigned user about the update
        $user = User::where('email', $task->assigned_staff)->first();
        if ($user) {
            $user->notify(new TaskUpdated($task));
        }
        
        if (Auth::user()->role === 'supervisor') {
            return redirect()->route('supervisor.dashboard')->with('success', 'Project created successfully');
        } else {
            return redirect()->route('admin.dashboard')->with('success', 'Project created successfully');
        }
    }

    // Update the parent task
    public function updateParent(Request $request, Task $task)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:tasks,id|not_in:' . $task->id, // prevent self-reference
        ]);
            
        $task->update([
            'parent_id' => $validated['parent_id'],
        ]);

        // Notify assigned user about the update
        $user = User::where('email', $task->assigned_staff)->first();
        if ($user) {
            $user->notify(new TaskUpdated($task));
        }
            
        return redirect()->back()->with('success', 'Parent task updated successfully.');
    }

    // Reassign a task to a different staff member
    public function reassign(Request $request, Task $task)
    {
        $validated = $request->validate([
            'assigned_staff' => 'required|string|max:255',
        ]);

        $task->update($validated);

        // Notify assigned user about the update
        $user = User::where('email', $task->assigned_staff)->first();
        if ($user) {
            $user->notify(new TaskUpdated($task));
        }

        return redirect()->back()->with('success', 'Task reassigned successfully.');
    }


    // Update a task and notify the assigned user
    public function update(Request $request, Task $task)
    {
        try {
            // Staff comment and status update
            if ($request->has('status') && !$request->hasAny(['task_name', 'assigned_staff', 'due_date', 'parent_id'])) {
                $request->validate([
                    'status'  => 'required|string|in:assigned,in progress,completed,over due',
                    'comment' => 'nullable|string',
                ]);

                $status = $request->input('status');
                $comment = $request->input('comment', $task->comment);

                // If the due date has passed and the status is not 'completed', force status to 'over due'
                if (\Carbon\Carbon::parse($task->due_date) < now() && $status !== 'completed') {
                    $status = 'over due';
                }

                $task->update([
                    'status'  => $status,          
                    'comment' => $comment,
                ]);

                // Lookup the assigned staff by email instead of first_name
                // Notify assigned user about the update
                $user = User::where('email', $task->assigned_staff)->first();
                if ($user) {
                    Log::info('Sending task update notification', [
                        'task_id' => $task->id,
                        'user_id' => $user->id,
                        'status' => $status
                    ]);
                    
                    try {
                        $user->notify(new TaskUpdated($task));
                        Log::info('Task update notification sent successfully', [
                            'task_id' => $task->id,
                            'user_id' => $user->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send task update notification', [
                            'task_id' => $task->id,
                            'user_id' => $user->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                } else {
                    Log::warning('Could not find user to notify about task update', [
                        'task_id' => $task->id,
                        'assigned_staff_email' => $task->assigned_staff
                    ]);
                }

                return redirect()->back()->with('success', 'Task updated successfully');
            }

            // Otherwise, process the full update (including comment)
            $validated = $request->validate([
                'task_name'      => 'required|string|max:255',
                'task_description' => 'required|string',
                'assigned_staff' => 'required|string|max:255',
                'assigned_date'  => 'required|date',
                'due_date'       => 'required|date',
                'status'        => 'required|string|in:assigned,in progress,completed',
                'comment'       => 'nullable|string',
            ]);

            $task->update($validated);

            // Notify assigned user about the update
            $user = User::where('email', $task->assigned_staff)->first();
            if ($user) {
                Log::info('Sending task update notification for full update', [
                    'task_id' => $task->id,
                    'user_id' => $user->id
                ]);
                
                try {
                    $user->notify(new TaskUpdated($task));
                    Log::info('Task update notification sent successfully', [
                        'task_id' => $task->id,
                        'user_id' => $user->id
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send task update notification', [
                        'task_id' => $task->id,
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Add custom redirect based on user role
            if (Auth::user()->role === 'supervisor') {
                return redirect()->route('supervisor.dashboard')->with('success', 'Task updated successfully');
            }
            
            return redirect()->route('admin.dashboard')->with('success', 'Task updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating task', [
                'task_id' => $task->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Failed to update task. Please try again.');
        }
    }

    // Delete a task
    public function destroy(Task $task)
    {
        try {
            // Notify assigned user about the deletion
            $user = User::where('email', $task->assigned_staff)->first();
            if ($user) {
                $user->notify(new TaskDeleted($task));
            }

            $task->delete();

            return redirect()->route('admin.dashboard')->with('success', 'Task deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting task', [
                'task_id' => $task->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Failed to delete task. Please try again.');
        }
    }

    // Show the form for creating a new task
    public function create()
    {
        if (Auth::user()->role === 'supervisor') {
            return view('tasks.screate');
        }
        return view('tasks.create');
    }
}