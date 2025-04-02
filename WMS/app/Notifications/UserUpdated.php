<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $changedFields;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\User $user
     * @param array $changedFields
     * @return void
     */
    public function __construct(User $user, array $changedFields)
    {
        $this->user = $user;
        $this->changedFields = $changedFields;
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
            ->subject('Your Account Has Been Updated')
            ->markdown('emails.user-updated', [
                'user' => $this->user,
                'changedFields' => $this->changedFields,
            ]);
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
            'user_id' => $this->user->id,
            'changed_fields' => array_keys($this->changedFields),
        ];
    }
}
