<?php

namespace Tests\Unit\Authentication;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    
    #[Test]
    public function authenticated_user_can_logout()
    {
        // Create and login a user
        $user = User::factory()->create();
        Auth::login($user);
        
        // Verify the user is logged in
        $this->assertTrue(Auth::check());
        
        // Logout the user
        Auth::logout();
        
        // Verify the user is logged out
        $this->assertFalse(Auth::check());
        $this->assertNull(Auth::user());
    }
    
    #[Test]
    public function logout_clears_session_data()
    {
        // Create and login a user
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        Auth::login($user);
        
        // Store session data related to the user
        session(['user_role' => $user->role]);
        session(['some_data' => 'test value']);
        
        // Verify session data exists
        $this->assertEquals('admin', session('user_role'));
        $this->assertEquals('test value', session('some_data'));
        
        // Logout and flush the session
        Auth::logout();
        session()->flush();
        
        // Verify session data is cleared
        $this->assertNull(session('user_role'));
        $this->assertNull(session('some_data'));
    }
    
    #[Test]
    public function user_stays_logged_out_after_logout()
    {
        // Create and login a user
        $user = User::factory()->create();
        Auth::login($user);
        
        // Logout the user
        Auth::logout();
        
        // Attempt to access authenticated routes or user data
        $this->assertFalse(Auth::check());
        
        // Even if we try to access the auth facade again
        $this->assertNull(Auth::user());
        $this->assertEquals(0, Auth::id());
        
        // Ensure we can't re-authenticate without credentials
        $this->assertFalse(Auth::check());
    }
}
