<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\ChangeLog;

class TaskObserver
{
    /**
     * The fields to include in the ChangeLog for before/after data.
     *
     * @var array
     */
    protected $fieldsToLog = [
        'task_name',
        'task_description',
        'assigned_staff',
        'assigned_date',
        'due_date',
        'parent_id',
        'comment',
        'status',
    ];

    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $changedBy = auth()->id() ?? 1;

        ChangeLog::create([
            'entity_type' => 'task',
            'entity_id'   => $task->id,
            'changed_by'  => $changedBy,
            'changes'     => [
                'action' => 'created',
                'before' => [],
                'after'  => $task->only($this->fieldsToLog),
            ],
        ]);
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        // Use getRawOriginal to get the initial values
        $original = $task->getRawOriginal();
        $oldData = array_intersect_key($original, array_flip($this->fieldsToLog));
        $newData = array_intersect_key($task->getAttributes(), array_flip($this->fieldsToLog));
        
        // Compare old and new data
        if ($oldData == $newData) {
            return; // No changes detected, so exit
        }
        
        // Proceed with logging
        $changedBy = auth()->id() ?? 1;
        ChangeLog::create([
            'entity_type' => 'task',
            'entity_id'   => $task->id,
            'changed_by'  => $changedBy,
            'changes'     => [
                'action' => 'updated',
                'before' => $oldData,
                'after'  => $newData,
            ],
        ]);
    }
    


    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task)
{
    $changedBy = auth()->id() ?? 1;
    $oldData = $task->only($this->fieldsToLog);

    ChangeLog::create([
        'entity_type' => 'task',
        'entity_id'   => $task->id,
        'changed_by'  => $changedBy,
        'changes'     => [
            'action' => 'deleted',
            'before' => $oldData,
            'after'  => [], // empty after
        ],
    ]);
}


    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        $changedBy = auth()->id() ?? 1;

        ChangeLog::create([
            'entity_type' => 'task',
            'entity_id'   => $task->id,
            'changed_by'  => $changedBy,
            'changes'     => [
                'action' => 'restored',
                'before' => [],
                'after'  => $task->only($this->fieldsToLog),
            ],
        ]);
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        $changedBy = auth()->id() ?? 1;
        $oldData = $task->only($this->fieldsToLog);

        ChangeLog::create([
            'entity_type' => 'task',
            'entity_id'   => $task->id,
            'changed_by'  => $changedBy,
            'changes'     => [
                'action' => 'force deleted',
                'before' => $oldData,
                'after'  => [],
            ],
        ]);
    }
}
