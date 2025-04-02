<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        // Validate the incoming comment data
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        // Create a new comment associated with the task
        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(), // Assumes the user is authenticated
            'comment' => $request->input('comment'),
        ]);

        // Redirect back to the task detail page with a success message
        return redirect()
            ->route('tasks.show', $task->id)
            ->with('success', 'Comment added successfully!');
    }
}
