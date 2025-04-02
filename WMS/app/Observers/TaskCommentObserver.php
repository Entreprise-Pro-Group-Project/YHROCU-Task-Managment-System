<?php

namespace App\Observers;

use App\Models\TaskComment;
use App\Models\ChangeLog;
use Illuminate\Support\Facades\Log;

class TaskCommentObserver
{
    /**
     * Handle the TaskComment "created" event.
     */
    public function created(TaskComment $comment): void
    {
        Log::info('TaskCommentObserver created event fired', $comment->toArray());
        $changedBy = auth()->id() ? auth()->id() : 1;
        ChangeLog::create([
            'entity_type' => 'task_comment',
            'entity_id'   => $comment->id,
            'changed_by'  => $changedBy,
            'changes'     => [
                'action' => 'created',
                'before' => [],
                'after'  => $comment->toArray(),
            ],
        ]);
    }

    /**
     * Handle the TaskComment "updated" event.
     */
    public function updated(TaskComment $comment): void
    {
        $changedBy = auth()->id() ? auth()->id() : 1;
        $oldData = $comment->getOriginal();
        $newData = $comment->toArray();

        ChangeLog::create([
            'entity_type' => 'task_comment',
            'entity_id'   => $comment->id,
            'changed_by'  => $changedBy,
            'changes'     => [
                'action' => 'updated',
                'before' => $oldData,
                'after'  => $newData,
            ],
        ]);
    }

    /**
     * Handle the TaskComment "deleted" event.
     */
    public function deleted(TaskComment $comment): void
    {
        $changedBy = auth()->id() ? auth()->id() : 1;
        $oldData = $comment->toArray();

        ChangeLog::create([
            'entity_type' => 'task_comment',
            'entity_id'   => $comment->id,
            'changed_by'  => $changedBy,
            'changes'     => [
                'action' => 'deleted',
                'before' => $oldData,
                'after'  => [],
            ],
        ]);
    }
}
