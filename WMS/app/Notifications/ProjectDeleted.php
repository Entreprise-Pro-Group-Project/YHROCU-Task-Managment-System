<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectDeleted extends Notification implements ShouldQueue
{
    use Queueable;

    public $projectId;
    public $projectName;

    /**
     * Create a new notification instance.
     *
     * @param int $projectId
     * @param string $projectName
     */
    public function __construct(int $projectId, string $projectName)
    {
        $this->projectId   = $projectId;
        $this->projectName = $projectName;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Project Deleted: ' . $this->projectName)
            ->line('The project "' . $this->projectName . '" has been deleted.')
            ->line('Project ID: ' . $this->projectId)
            ->action('View Projects', url('/projects'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'project_id'   => $this->projectId,
            'project_name' => $this->projectName,
        ];
    }
}
