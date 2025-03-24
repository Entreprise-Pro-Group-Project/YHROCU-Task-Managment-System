<?php

namespace App\Livewire;

use App\Models\ChangeLog;
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
        $logs = ChangeLog::with('user')
            ->when($this->projectId, function ($query) {
                $query->where('entity_type', 'task')
                      ->where('entity_id', $this->projectId);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.activity-log-table', [
            'logs' => $logs,
        ]);
    }
}
