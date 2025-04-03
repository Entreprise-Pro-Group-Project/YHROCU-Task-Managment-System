<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $plainPassword;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\User $user
     * @param string $plainPassword
     * @return void
     */
    public function __construct(User $user, string $plainPassword)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
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
            ->subject('Your Account Has Been Created')
            ->markdown('emails.user-created', [
                'user' => $this->user,
                'plainPassword' => $this->plainPassword,
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
            'username' => $this->user->username,
        ];
    }
}
