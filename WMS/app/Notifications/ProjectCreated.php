<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Optional, for queuing
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Project;

class ProjectCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $project;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Project $project
     * @return void
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Project Created: ' . $this->project->project_name)
                    ->line('A new project has been created and assigned to you as supervisor.')
                    ->line('Project Name: ' . $this->project->project_name)
                    ->line('Project Date: ' . $this->project->project_date)
                    ->line('Due Date: ' . $this->project->due_date)
                    ->action('View Project', url('/projects/' . $this->project->id))
                    ->line('Thank you for using YHROCU Workflow Management System!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'project_id'   => $this->project->id,
            'project_name' => $this->project->project_name,
        ];
    }
}
