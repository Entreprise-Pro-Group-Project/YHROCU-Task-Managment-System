<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeLog;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Fetch search term from request
        $search = $request->input('search');
        
        $query = ChangeLog::with('user')->latest();
        
        // Apply search filter if provided – adjust the fields as needed
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('entity_type', 'like', "%{$search}%")
                  ->orWhere('changed_by', 'like', "%{$search}%");
                  // or add additional filtering on changes column using JSON search if needed
            });
        }
        
        // Use paginate instead of a fixed limit so the view gets the right row count
        $logs = $query->paginate(50);

        // 2) Collect IDs for tasks, comments, users
        $taskIDs = [];
        $commentIDs = [];
        $userIDs = [];

        foreach ($logs as $log) {
            $changesArray = is_array($log->changes) ? $log->changes : json_decode($log->changes, true);
            $action = strtolower($changesArray['action'] ?? '');
            $before = $changesArray['before'] ?? [];
            $after  = $changesArray['after'] ?? [];
        
            // DO NOT do $after['task_name'] = $tasks[$after['task_id']] for tasks,
            // or you'll overwrite historical data.
        
            // For comment logs only (if you want a fallback):
                if ($log->entity_type === 'task_comment' && isset($after['task_id'])) {
                    // do a DB lookup only for comments
                    if (isset($tasks[$after['task_id']])) {
                        $after['task_name'] = $tasks[$after['task_id']];
                    }
                    unset($after['task_id']);
                }
                
        
            // For deleted tasks, you might copy old name from "before" to "after" if you prefer:
            if ($log->entity_type === 'task' && $action === 'deleted') {
                if (isset($before['task_name']) && !isset($after['task_name'])) {
                    $after['task_name'] = $before['task_name'];
                }
            }
        
            $changesArray['after'] = $after;
            $log->changes = $changesArray;
        }
        
        

        // Remove duplicates
        $taskIDs = array_unique($taskIDs);
        $commentIDs = array_unique($commentIDs);
        $userIDs = array_unique($userIDs);

        // 3) Batch-lookup tasks, comments, users
        $tasks = Task::whereIn('id', $taskIDs)->pluck('task_name', 'id');
        $comments = TaskComment::whereIn('id', $commentIDs)->pluck('comment', 'id');
        $users = User::whereIn('id', $userIDs)
            ->select('id','first_name','last_name')
            ->get()
            ->keyBy('id');

        // 4) Transform each log
        foreach ($logs as $log) {
            $changesArray = is_array($log->changes) ? $log->changes : json_decode($log->changes, true);
            $after = $changesArray['after'] ?? [];

            // Replace task_id with task_name

            // Put it back
            $changesArray['after'] = $after;
            $log->changes = $changesArray;
        }

        // 5) Return the logs to a Blade view
        return view('activity-log-table', compact('logs'));
    }
}
