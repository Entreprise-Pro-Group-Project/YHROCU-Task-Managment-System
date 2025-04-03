<?php

namespace Tests\Unit\UserManagement;

use Tests\TestCase;
use App\Models\User;
use App\Notifications\UserCreated;
use App\Notifications\UserUpdated;
use App\Notifications\UserDeleted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Mockery;
use PHPUnit\Framework\Attributes\Test;

class UserEmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    #[Test]
    public function user_created_notification_contains_correct_data()
    {
        // Create a user
        $user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'username' => 'testuser'
        ]);

        $plainPassword = 'TestPassword1!';

        // Create and send the notification
        $notification = new UserCreated($user, $plainPassword);
        $user->notify($notification);

        // Assert notification was sent
        Notification::assertSentTo(
            $user,
            UserCreated::class,
            function ($notification) use ($user, $plainPassword) {
                // Check notification contains correct data
                $this->assertEquals($user->id, $notification->user->id);
                $this->assertEquals($plainPassword, $notification->plainPassword);
                
                // Check mail content
                $mail = $notification->toMail($user);
                $this->assertStringContainsString('Your Account Has Been Created', $mail->subject);
                
                // Check array representation
                $array = $notification->toArray($user);
                $this->assertEquals($user->id, $array['user_id']);
                $this->assertEquals($user->username, $array['username']);
                
                return true;
            }
        );
    }

    #[Test]
    public function user_updated_notification_contains_correct_data()
    {
        // Create a user
        $user = User::factory()->create([
            'first_name' => 'Updated',
            'last_name' => 'User'
        ]);

        // Changed fields
        $changedFields = [
            'first_name' => 'New Name',
            'role' => 'supervisor',
            'password' => 'NewPassword1!'
        ];

        // Create and send the notification
        $notification = new UserUpdated($user, $changedFields);
        $user->notify($notification);

        // Assert notification was sent
        Notification::assertSentTo(
            $user,
            UserUpdated::class,
            function ($notification) use ($user, $changedFields) {
                // Check notification contains correct data
                $this->assertEquals($user->id, $notification->user->id);
                $this->assertEquals($changedFields, $notification->changedFields);
                
                // Check mail content
                $mail = $notification->toMail($user);
                $this->assertStringContainsString('Your Account Has Been Updated', $mail->subject);
                
                // Check array representation
                $array = $notification->toArray($user);
                $this->assertEquals($user->id, $array['user_id']);
                $this->assertEquals(array_keys($changedFields), $array['changed_fields']);
                
                return true;
            }
        );
    }

    #[Test]
    public function user_deleted_notification_contains_correct_data()
    {
        // Create a user
        $user = User::factory()->create([
            'first_name' => 'Delete',
            'last_name' => 'Me',
            'email' => 'delete@example.com',
            'username' => 'deleteme'
        ]);

        // Create the notification (this stores user data before deletion)
        $notification = new UserDeleted($user);
        
        // Directly send to an email route
        Notification::route('mail', $user->email)
            ->notify($notification);

        // Assert notification was sent
        Notification::assertSentTo(
            new \Illuminate\Notifications\AnonymousNotifiable,
            UserDeleted::class,
            function ($notification, $channels, $notifiable) use ($user) {
                // Check notification route
                $this->assertEquals($user->email, $notifiable->routes['mail']);
                
                // Check user data was stored correctly
                $this->assertEquals($user->id, $notification->userData['id']);
                $this->assertEquals($user->first_name, $notification->userData['first_name']);
                $this->assertEquals($user->last_name, $notification->userData['last_name']);
                $this->assertEquals($user->email, $notification->userData['email']);
                
                // Check mail content
                $mail = $notification->toMail($notifiable);
                $this->assertStringContainsString('Your Account Has Been Removed', $mail->subject);
                
                // Check array representation
                $array = $notification->toArray($notifiable);
                $this->assertEquals($user->id, $array['user_id']);
                $this->assertEquals($user->email, $array['email']);
                
                return true;
            }
        );
    }

    #[Test]
    public function notification_channel_is_email()
    {
        // Create notifications of each type
        $user = User::factory()->create();
        
        $createdNotification = new UserCreated($user, 'password');
        $updatedNotification = new UserUpdated($user, ['first_name' => 'New']);
        $deletedNotification = new UserDeleted($user);
        
        // Check that all notifications use mail channel
        $this->assertEquals(['mail'], $createdNotification->via($user));
        $this->assertEquals(['mail'], $updatedNotification->via($user));
        $this->assertEquals(['mail'], $deletedNotification->via($user));
    }
}
