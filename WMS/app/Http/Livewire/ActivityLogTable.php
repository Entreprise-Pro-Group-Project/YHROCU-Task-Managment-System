<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ChangeLog;
use Carbon\Carbon;

class ActivityLogTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Basic properties
    public $projectId;
    public $projectName;
    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc'; // Default to most recent first

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount($projectId = null, $projectName = null)
    {
        $this->projectId = $projectId;
        $this->projectName = $projectName;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function resetSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function refresh()
    {
        // Force a refresh of the component
    }

    public function render()
    {
        // Base query
        $query = ChangeLog::with('user');
        
        // Apply project filter if provided
        if ($this->projectId) {
            $query->where('project_id', $this->projectId);
        }
        
        // Apply search filter
        if ($this->search) {
            $searchTerm = '%' . strtolower($this->search) . '%';
            $query->where(function($q) use ($searchTerm) {
                // Search in user names
                $q->whereHas('user', function($uq) use ($searchTerm) {
                    $uq->whereRaw('LOWER(CONCAT(first_name, " ", last_name)) LIKE ?', [$searchTerm]);
                })
                // Search in entity type
                ->orWhere('entity_type', 'like', $searchTerm)
                // Search in entity ID
                ->orWhere('entity_id', 'like', $searchTerm)
                // Search in changes (as a JSON string)
                ->orWhereRaw('LOWER(changes) LIKE ?', [$searchTerm]);
            });
        }
        
        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);
        
        // Get paginated results
        $logs = $query->paginate($this->perPage);
        
        return view('livewire.activity-log-table', compact('logs'));
    }
}