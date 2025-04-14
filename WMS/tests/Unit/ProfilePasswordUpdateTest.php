<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

class ProfilePasswordUpdateTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test admin can update password from profile page
     */
    public function test_admin_can_update_password_from_profile_page()
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('CurrentPassword123!'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'CurrentPassword123!',
                'password' => 'NewAdminPassword123!',
                'password_confirmation' => 'NewAdminPassword123!',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertTrue(Hash::check('NewAdminPassword123!', $user->refresh()->password));
    }

    /**
     * Test supervisor can update password from profile page
     */
    public function test_supervisor_can_update_password_from_profile_page()
    {
        $user = User::factory()->create([
            'role' => 'supervisor',
            'password' => Hash::make('CurrentPassword123!'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'CurrentPassword123!',
                'password' => 'NewSupervisorPass123!',
                'password_confirmation' => 'NewSupervisorPass123!',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertTrue(Hash::check('NewSupervisorPass123!', $user->refresh()->password));
    }

    /**
     * Test staff can update password from profile page
     */
    public function test_staff_can_update_password_from_profile_page()
    {
        $user = User::factory()->create([
            'role' => 'staff',
            'password' => Hash::make('CurrentPassword123!'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'CurrentPassword123!',
                'password' => 'NewStaffPassword123!',
                'password_confirmation' => 'NewStaffPassword123!',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertTrue(Hash::check('NewStaffPassword123!', $user->refresh()->password));
    }

    /**
     * Test user cannot update password with incorrect current password
     */
    public function test_user_cannot_update_password_with_incorrect_current_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('CurrentPassword123!'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'WrongPassword123!',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ]);

        $response
            ->assertSessionHasErrorsIn('updatePassword', 'current_password')
            ->assertRedirect('/profile');
        
        $this->assertFalse(Hash::check('NewPassword123!', $user->refresh()->password));
    }

    /**
     * Test password confirmation must match
     */
    public function test_password_confirmation_must_match_for_profile_update()
    {
        $user = User::factory()->create([
            'password' => Hash::make('CurrentPassword123!'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'CurrentPassword123!',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'DifferentPassword123!',
            ]);

        $response
            ->assertSessionHasErrorsIn('updatePassword', 'password')
            ->assertRedirect('/profile');
        
        $this->assertFalse(Hash::check('NewPassword123!', $user->refresh()->password));
    }
    
    /**
     * Test password complexity requirements are enforced
     */
    public function test_password_must_meet_complexity_requirements()
    {
        $user = User::factory()->create([
            'password' => Hash::make('CurrentPassword123!'),
        ]);

        // Test password without special character
        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'CurrentPassword123!',
                'password' => 'NewPassword123',
                'password_confirmation' => 'NewPassword123',
            ]);

        $response
            ->assertSessionHasErrorsIn('updatePassword', 'password')
            ->assertRedirect('/profile');
            
        $this->assertFalse(Hash::check('NewPassword123', $user->refresh()->password));
    }

    /**
     * Test status session is set after successful password update
     */
    public function test_status_is_set_after_password_update()
    {
        $user = User::factory()->create([
            'password' => Hash::make('CurrentPassword123!'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'CurrentPassword123!',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ]);

        $response
            ->assertSessionHas('status', 'password-updated')
            ->assertRedirect('/profile');
    }
} 