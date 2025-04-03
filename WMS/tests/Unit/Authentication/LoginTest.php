<?php

namespace Tests\Unit\Authentication;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    
    #[Test]
    public function user_can_login_with_correct_credentials()
    {
        // Create a user with known credentials
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password1!')
        ]);
        
        // Attempt to login
        $result = Auth::attempt([
            'email' => 'test@example.com',
            'password' => 'Password1!'
        ]);
        
        // Assert the login was successful
        $this->assertTrue($result);
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }
    
    #[Test]
    public function user_cannot_login_with_incorrect_password()
    {
        // Create a user
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password1!')
        ]);
        
        // Attempt to login with wrong password
        $result = Auth::attempt([
            'email' => 'test@example.com',
            'password' => 'WrongPassword1!'
        ]);
        
        // Assert the login failed
        $this->assertFalse($result);
        $this->assertFalse(Auth::check());
    }
    
    #[Test]
    public function user_cannot_login_with_non_existent_email()
    {
        // Attempt to login with non-existent email
        $result = Auth::attempt([
            'email' => 'nonexistent@example.com',
            'password' => 'Password1!'
        ]);
        
        // Assert the login failed
        $this->assertFalse($result);
        $this->assertFalse(Auth::check());
    }
    
    #[Test]
    public function login_throttling_works_after_too_many_attempts()
    {
        // This is more of an integration test and might need to be moved
        $this->markTestSkipped('Login throttling is better tested in a feature test');
    }
    
    #[Test]
    public function auth_session_has_proper_user_data()
    {
        // Create a user with specific role
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password1!'),
            'role' => 'admin',
            'first_name' => 'Test',
            'last_name' => 'Admin'
        ]);
        
        // Login the user
        Auth::login($user);
        
        // Assert the user is logged in
        $this->assertTrue(Auth::check());
        
        // Assert the session has the correct user data
        $this->assertEquals('admin', Auth::user()->role);
        $this->assertEquals('Test', Auth::user()->first_name);
        $this->assertEquals('Admin', Auth::user()->last_name);
    }
}
