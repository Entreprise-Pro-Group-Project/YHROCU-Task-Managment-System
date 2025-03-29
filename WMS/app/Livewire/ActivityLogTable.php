<?php

namespace App\Livewire;

use App\Models\ChangeLog;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogTable extends Component
{
    use WithPagination;

    public $projectId;

    public function mount($projectId = null)
    {
        $this->projectId = $projectId;
    }

    public function render()
    {
        // Adjust the query to filter logs for the given project, if needed.
        // For example, logs for tasks in the project, project logs, and task comment logs related to tasks in the project.
        $logs = ChangeLog::with('user')
            ->when($this->projectId, function ($query) {
                $projectId = $this->projectId;
                $query->where(function ($q) use ($projectId) {
                    $q->where(function ($q1) use ($projectId) {
                        // Logs for tasks in the project
                        $q1->where('entity_type', 'task')
                           ->whereIn('entity_id', function($subquery) use ($projectId) {
                               $subquery->select('id')
                                        ->from('tasks')
                                        ->where('project_id', $projectId);
                           });
                    })
                    ->orWhere(function ($q2) use ($projectId) {
                        // Logs for project updates
                        $q2->where('entity_type', 'project')
                           ->where('entity_id', $projectId);
                    })
                    ->orWhere(function ($q3) use ($projectId) {
                        // Logs for task comments: look up comments that belong to tasks in the project
                        $q3->where('entity_type', 'task_comment')
                           ->whereIn('entity_id', function($subquery) use ($projectId) {
                               $subquery->select('id')
                                        ->from('task_comments')
                                        ->whereIn('task_id', function($sq) use ($projectId) {
                                            $sq->select('id')
                                               ->from('tasks')
                                               ->where('project_id', $projectId);
                                        });
                           });
                    });
                });
            })
            ->latest()
            ->paginate(10);

        // Batch lookup tasks and users
        $taskIDs = [];
        $userIDs = [];
        foreach ($logs as $log) {
            $changesArray = is_array($log->changes) ? $log->changes : json_decode($log->changes, true);
            $after = $changesArray['after'] ?? [];
            // For task logs, entity_id is the task ID.
            if ($log->entity_type === 'task') {
                $taskIDs[] = $log->entity_id;
            }
            // For task comment logs, if after data has task_id then add that.
            if ($log->entity_type === 'task_comment' && isset($after['task_id'])) {
                $taskIDs[] = $after['task_id'];
            }
            // If after data contains user_id, collect it.
            if (isset($after['user_id'])) {
                $userIDs[] = $after['user_id'];
            }
        }
        $taskIDs = array_unique($taskIDs);
        $userIDs = array_unique($userIDs);

        $tasks = Task::whereIn('id', $taskIDs)->pluck('task_name', 'id');
        $users = User::whereIn('id', $userIDs)
            ->select('id','first_name','last_name')
            ->get()
            ->keyBy('id');

        // Transform logs: replace raw IDs with friendly names.
        foreach ($logs as $log) {
            $changesArray = is_array($log->changes) ? $log->changes : json_decode($log->changes, true);
            $after = $changesArray['after'] ?? [];

            // For task logs, set task_name using entity_id if not already set.
            if ($log->entity_type === 'task') {
                if (isset($tasks[$log->entity_id])) {
                    $after['task_name'] = $tasks[$log->entity_id];
                }
            }

            // For task comment logs, if a task_id exists in after, replace it with task_name.
            if ($log->entity_type === 'task_comment' && isset($after['task_id'])) {
                if (isset($tasks[$after['task_id']])) {
                    $after['task_name'] = $tasks[$after['task_id']];
                }
                unset($after['task_id']);
            }

            // Replace user_id with user_name
            if (isset($after['user_id'])) {
                if (isset($users[$after['user_id']])) {
                    $u = $users[$after['user_id']];
                    $after['user_name'] = $u->first_name . ' ' . $u->last_name;
                }
                unset($after['user_id']);
            }

            $changesArray['after'] = $after;
            $log->changes = $changesArray;
        }


            $projectName = null;
    if ($this->projectId) {
        $project = Project::find($this->projectId);
        $projectName = $project ? $project->project_name : 'Unknown Project';
    }

    return view('livewire.activity-log-table', [
        'logs' => $logs,
        'projectName' => $projectName,
    ]);

        return view('livewire.activity-log-table', compact('logs'));
    }
}
