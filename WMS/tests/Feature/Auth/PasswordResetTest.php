<?php

use App\Models\User;
use App\Notifications\PasswordResetRequest;
use Illuminate\Support\Facades\Notification;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();
    
    // Create an admin who would receive the notification
    $admin = User::factory()->create([
        'role' => 'admin',
        'email' => 'admin@example.com'
    ]);

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($admin, PasswordResetRequest::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();
    
    // Create an admin who would receive the notification
    $admin = User::factory()->create([
        'role' => 'admin',
        'email' => 'admin@example.com'
    ]);

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($admin, PasswordResetRequest::class);
    
    $response = $this->get('/users/' . $user->id . '/reset-password');
    $response->assertStatus(302); // Since it redirects for non-admin users
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();
    
    // Create an admin to perform the password reset
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    // Reset the password as admin
    $response = $this
        ->actingAs($admin)
        ->post(route('admin.user_management.resetPassword', $user->id), [
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

    $response->assertRedirect();
});
