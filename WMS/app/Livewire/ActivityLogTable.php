<?php

namespace App\Livewire;

use App\Models\ChangeLog;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class ActivityLogTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    
    public $projectId;
    public $perPage = 10; // default rows per page
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filterAction = '';
    public $filterEntityType = '';
    public $filterDateRange = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
        'filterAction' => ['except' => ''],
        'filterEntityType' => ['except' => ''],
        'filterDateRange' => ['except' => ''],
    ];

    public function mount($projectId = null)
    {
        $this->projectId = $projectId;
    }

    // Reset page when filters change
    public function updatingPerPage()
    {
        $this->resetPage();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingFilterAction()
    {
        $this->resetPage();
    }
    
    public function updatingFilterEntityType()
    {
        $this->resetPage();
    }
    
    public function updatingFilterDateRange()
    {
        $this->resetPage();
    }

    // Refresh method
    public function refresh()
    {
        $this->resetPage();
    }
    
    // Sort method
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    // Reset filters
    public function resetFilters()
    {
        dd('resetFilters triggered');
    }
    

    public function render()
    {
        // Base query
        $query = ChangeLog::with('user');
        
        // Project filter
        if ($this->projectId) {
            $projectId = $this->projectId;
            $query->where(function ($q) use ($projectId) {
                // Logs for tasks in the project
                $q->where(function ($q1) use ($projectId) {
                    $q1->where('entity_type', 'task')
                       ->whereIn('entity_id', function ($subquery) use ($projectId) {
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
                    // Logs for task comments that belong to tasks in the project
                    $q3->where('entity_type', 'task_comment')
                       ->whereIn('entity_id', function ($subquery) use ($projectId) {
                           $subquery->select('id')
                                    ->from('task_comments')
                                    ->whereIn('task_id', function ($sq) use ($projectId) {
                                        $sq->select('id')
                                           ->from('tasks')
                                           ->where('project_id', $projectId);
                                    });
                       });
                });
            });
        }
        
        // Search filter
        if (!empty($this->search)) {
            $search = '%' . $this->search . '%';
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('first_name', 'like', $search)
                              ->orWhere('last_name', 'like', $search);
                })
                ->orWhere('entity_type', 'like', $search)
                ->orWhere('entity_id', 'like', $search)
                ->orWhere('changes', 'like', $search);
            });
        }
        
        // Action filter
        if (!empty($this->filterAction)) {
            $query->whereJsonContains('changes->action', $this->filterAction);
        }
        
        // Entity type filter
        if (!empty($this->filterEntityType)) {
            $query->where('entity_type', $this->filterEntityType);
        }
        
        // Date range filter
        if (!empty($this->filterDateRange)) {
            $now = Carbon::now();
            
            switch ($this->filterDateRange) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', $now->copy()->subDay()->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [
                        $now->copy()->startOfWeek(),
                        $now->copy()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [
                        $now->copy()->startOfMonth(),
                        $now->copy()->endOfMonth()
                    ]);
                    break;
            }
        }
        
        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);
        
        // Get paginated results
        $logs = $query->paginate($this->perPage);

        // Batch lookup for tasks and users
        $taskIDs = [];
        $userIDs = [];
        foreach ($logs as $log) {
            $changesArray = is_array($log->changes)
                ? $log->changes
                : json_decode($log->changes, true);
            $after = $changesArray['after'] ?? [];

            if ($log->entity_type === 'task') {
                $taskIDs[] = $log->entity_id;
            }
            if ($log->entity_type === 'task_comment' && isset($after['task_id'])) {
                $taskIDs[] = $after['task_id'];
            }
            if (isset($after['user_id'])) {
                $userIDs[] = $after['user_id'];
            }
        }
        $taskIDs = array_unique($taskIDs);
        $userIDs = array_unique($userIDs);

        $tasks = Task::whereIn('id', $taskIDs)->pluck('task_name', 'id');
        $users = User::whereIn('id', $userIDs)
            ->select('id', 'first_name', 'last_name')
            ->get()
            ->keyBy('id');

        // Transform logs: replace raw IDs with friendly names.
        foreach ($logs as $log) {
            $changesArray = is_array($log->changes)
                ? $log->changes
                : json_decode($log->changes, true);
            $after = $changesArray['after'] ?? [];

            if ($log->entity_type === 'task') {
                if (isset($tasks[$log->entity_id])) {
                    $after['task_name'] = $tasks[$log->entity_id];
                }
            }
            if ($log->entity_type === 'task_comment' && isset($after['task_id'])) {
                if (isset($tasks[$after['task_id']])) {
                    $after['task_name'] = $tasks[$after['task_id']];
                }
                unset($after['task_id']);
            }
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
    }
}