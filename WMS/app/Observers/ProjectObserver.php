<?php
namespace App\Observers;

use App\Models\Project;
use App\Models\ChangeLog;

class ProjectObserver
{
    protected $fieldsToLog = [
        'project_name',
        'project_description',
        'supervisor_name',
        'project_date',
        'due_date',
        // ...any other fields you want to track
    ];

    public function created(Project $project): void
    {
        $changedBy = auth()->id() ?? 1;

        ChangeLog::create([
            'entity_type' => 'project',
            'entity_id'   => $project->id,
            'changed_by'  => $changedBy,
            'changes'     => [
                'action' => 'created',
                'before' => [],
                'after'  => $project->only($this->fieldsToLog),
            ],
        ]);
    }

    public function updated(Project $project): void
    {
        $original = $project->getRawOriginal(); // old values
        $oldData  = array_intersect_key($original, array_flip($this->fieldsToLog));
        $newData  = array_intersect_key($project->getAttributes(), array_flip($this->fieldsToLog));

        // If nothing changed in these fields, skip logging
        if ($oldData == $newData) {
            return;
        }

        $changedBy = auth()->id() ?? 1;
        ChangeLog::create([
            'entity_type' => 'project',
            'entity_id'   => $project->id,
            'changed_by'  => $changedBy,
            'changes'     => [
                'action' => 'updated',
                'before' => $oldData,
                'after'  => $newData,
            ],
        ]);
    }

    // Add 'deleted', 'restored', etc. if needed
}
