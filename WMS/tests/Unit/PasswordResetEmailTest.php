<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordChanged;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordResetEmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that when a user requests a password reset,
     * admin users receive a notification
     */
    public function test_user_can_request_password_reset_and_email_is_sent_to_admin()
    {
        // Prevent actual emails from being sent
        Notification::fake();
        
        // Create a user who will request password reset
        $user = User::factory()->create([
            'email' => 'user@example.com'
        ]);
        
        // Create an admin who should receive the notification
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com'
        ]);
        
        // Send the password reset request
        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);
        
        // Check that the request was successful and redirects back
        $response->assertStatus(302);
        $response->assertSessionHas('status');
        
        // Assert that a notification was sent to the admin
        Notification::assertSentTo(
            $admin,
            PasswordResetRequest::class
        );
    }

    /**
     * Test that when an admin resets a user's password,
     * the user receives a notification with the new password
     */
    public function test_admin_can_reset_password_and_notification_is_sent_to_user()
    {
        // Prevent actual emails from being sent
        Notification::fake();
        
        // Create an admin who will reset the password
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        
        // Create a user whose password will be reset
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);
        
        // Reset the password as admin
        $response = $this
            ->actingAs($admin)
            ->post(route('admin.user_management.resetPassword', $user->id), [
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ]);
        
        // Check that the request was successful
        $response->assertRedirect();
        
        // Assert that a PasswordChanged notification was sent to the user
        Notification::assertSentTo(
            $user,
            PasswordChanged::class
        );
    }

    /**
     * Test that non-existent users can't request password resets
     */
    public function test_nonexistent_user_cannot_request_password_reset()
    {
        // Prevent actual emails from being sent
        Notification::fake();
        
        // Create an admin who would receive the notification
        User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com'
        ]);
        
        // Send the request with a non-existent email
        $response = $this->post(route('password.email'), [
            'email' => 'nonexistent@example.com',
        ]);
        
        // Should redirect back with an error
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
        
        // No notifications should be sent
        Notification::assertNothingSent();
    }

    /**
     * Test that the PasswordChanged notification contains
     * the necessary data in the viewData for the email template
     */
    public function test_password_changed_notification_contains_required_data()
    {
        // Create a user whose password will be reset
        $user = User::factory()->create([
            'first_name' => 'Test', 
            'last_name' => 'User'
        ]);
        
        // The new password that will be set
        $newPassword = 'BrandNew123!';
        
        // Create a notification instance
        $notification = new PasswordChanged($user, $newPassword);
        
        // Get the Mail representation
        $mailMessage = $notification->toMail($user);
        
        // Check if viewData contains the required data
        $this->assertEquals($user, $mailMessage->viewData['user']);
        $this->assertEquals($newPassword, $mailMessage->viewData['newPassword']);
        $this->assertStringContainsString('login', $mailMessage->viewData['loginUrl']);
    }

    /**
     * Test that the PasswordResetRequest notification contains
     * the necessary data in the viewData for the email template
     */
    public function test_password_reset_request_notification_contains_required_data()
    {
        // Create a user who will request a password reset
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com'
        ]);
        
        // Create an admin who will receive the notification
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);
        
        // Create a notification instance
        $notification = new PasswordResetRequest($user);
        
        // Get the Mail representation
        $mailMessage = $notification->toMail($admin);
        
        // Check if viewData contains the required data
        $this->assertEquals($user, $mailMessage->viewData['user']);
        $this->assertStringContainsString('users?reset_user=' . $user->id, $mailMessage->viewData['resetUrl']);
        $this->assertEquals($admin, $mailMessage->viewData['notifiable']);
    }
} 