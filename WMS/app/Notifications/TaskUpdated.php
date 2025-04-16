<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TaskUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3; // Number of times to attempt sending
    public $timeout = 30; // Maximum number of seconds the job can run
    public $maxExceptions = 3; // Maximum number of exceptions to catch before failing
    
    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->afterCommit = true; // Only dispatch after database transactions are committed
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        try {
            Log::info('Preparing task update email', [
                'task_id' => $this->task->id,
                'recipient' => $notifiable->email
            ]);

            return (new MailMessage)
                ->subject('Task Updated: ' . $this->task->task_name)
                ->markdown('emails.task-updated', [
                    'task' => $this->task,
                    'notifiable' => $notifiable,
                ]);
        } catch (\Exception $e) {
            Log::error('Error preparing task update email', [
                'task_id' => $this->task->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function failed(\Exception $e)
    {
        Log::error('Task update notification failed', [
            'task_id' => $this->task->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'task_id'   => $this->task->id,
            'task_name' => $this->task->task_name,
        ];
    }
}
