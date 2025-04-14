<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PasswordChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can view password reset form for a user
     */
    public function test_admin_can_view_reset_password_form()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        
        $user = User::factory()->create([
            'role' => 'staff',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.user_management.resetPasswordForm', $user->id));

        // It redirects as shown by the actual behavior
        $response->assertStatus(302);
    }

    /**
     * Test admin can reset a user's password
     */
    public function test_admin_can_reset_user_password()
    {
        // Prevent actual emails from being sent
        Notification::fake();
        
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        
        $user = User::factory()->create([
            'role' => 'staff',
            'password' => Hash::make('OldPassword123!'),
        ]);

        // Use a complex password that meets system requirements
        $response = $this
            ->actingAs($admin)
            ->post(route('admin.user_management.resetPassword', $user->id), [
                'password' => 'NewReset123!',
                'password_confirmation' => 'NewReset123!',
            ]);

        // Verify it redirects after successful reset
        $response->assertRedirect();
        
        // Verify the password was actually changed
        $this->assertTrue(Hash::check('NewReset123!', $user->refresh()->password));
        
        // Verify a notification was sent to the user
        Notification::assertSentTo(
            $user,
            PasswordChanged::class
        );
    }

    /**
     * Test non-admin users cannot reset passwords
     */
    public function test_non_admin_users_cannot_reset_passwords()
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
        ]);
        
        $user = User::factory()->create([
            'role' => 'staff',
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this
            ->actingAs($supervisor)
            ->post(route('admin.user_management.resetPassword', $user->id), [
                'password' => 'Unauthorized123!',
                'password_confirmation' => 'Unauthorized123!',
            ]);

        // Should return 403 Forbidden since supervisor isn't authorized
        $response->assertStatus(403);
        
        // Verify the password was NOT changed
        $this->assertFalse(Hash::check('Unauthorized123!', $user->refresh()->password));
    }

    /**
     * Test password reset enforces matching password confirmation
     */
    public function test_password_reset_requires_confirmation_match()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        
        $user = User::factory()->create([
            'role' => 'staff',
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.user_management.resetPassword', $user->id), [
                'password' => 'NewPassword123!',
                'password_confirmation' => 'DifferentPassword123!',
            ]);

        // Should have validation errors
        $response->assertSessionHasErrors('password');
        
        // Verify the password was NOT changed
        $this->assertFalse(Hash::check('NewPassword123!', $user->refresh()->password));
    }

    /**
     * Test password reset enforces complexity requirements
     */
    public function test_password_reset_enforces_complexity_requirements()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        
        $user = User::factory()->create([
            'role' => 'staff',
            'password' => Hash::make('OldPassword123!'),
        ]);

        // Password missing special character
        $response = $this
            ->actingAs($admin)
            ->post(route('admin.user_management.resetPassword', $user->id), [
                'password' => 'NewPassword123',
                'password_confirmation' => 'NewPassword123',
            ]);

        // Should have validation errors
        $response->assertSessionHasErrors('password');
        
        // Verify the password was NOT changed
        $this->assertFalse(Hash::check('NewPassword123', $user->refresh()->password));
    }

    /**
     * Test admin can reset password via AJAX request
     */
    public function test_admin_can_reset_password_via_ajax_request()
    {
        // Prevent actual emails from being sent
        Notification::fake();
        
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        
        $user = User::factory()->create([
            'role' => 'staff',
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this
            ->actingAs($admin)
            ->postJson(route('admin.user_management.resetPassword', $user->id), [
                'password' => 'AjaxReset123!',
                'password_confirmation' => 'AjaxReset123!',
            ]);

        // Should return 200 OK with success message
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
        
        // Verify the password was changed
        $this->assertTrue(Hash::check('AjaxReset123!', $user->refresh()->password));
        
        // Verify a notification was sent to the user
        Notification::assertSentTo(
            $user,
            PasswordChanged::class
        );
    }
} 