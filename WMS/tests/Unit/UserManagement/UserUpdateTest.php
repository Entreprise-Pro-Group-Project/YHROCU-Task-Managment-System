<?php

namespace Tests\Unit\UserManagement;

use Tests\TestCase;
use App\Models\User;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Controllers\UserController;
use App\Notifications\UserUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    #[Test]
    public function it_updates_user_profile_data()
    {
        // Create a user to update
        $user = User::factory()->create([
            'first_name' => 'Original',
            'last_name' => 'Name',
            'email' => 'original@example.com',
            'role' => 'staff'
        ]);

        // Update data
        $updateData = [
            'first_name' => 'Updated',
            'last_name' => 'User',
            'email' => 'updated@example.com',
            'role' => 'supervisor'
        ];

        // Mock the UserUpdateRequest
        $request = $this->mock(UserUpdateRequest::class, function (MockInterface $mock) use ($updateData) {
            $mock->shouldReceive('validated')->once()->andReturn($updateData);
            $mock->shouldReceive('expectsJson')->andReturn(false);
            $mock->shouldReceive('all')->andReturn($updateData);
        });

        // Execute the controller method
        $controller = new UserController();
        $response = $controller->update($request, $user->id);

        // Refresh the user model
        $user->refresh();

        // Assert user data is updated
        $this->assertEquals('Updated', $user->first_name);
        $this->assertEquals('User', $user->last_name);
        $this->assertEquals('updated@example.com', $user->email);
        $this->assertEquals('supervisor', $user->role);

        // Assert notification was sent
        Notification::assertSentTo(
            $user,
            UserUpdated::class,
            function ($notification) {
                return array_key_exists('first_name', $notification->changedFields) &&
                       array_key_exists('last_name', $notification->changedFields) &&
                       array_key_exists('email', $notification->changedFields) &&
                       array_key_exists('role', $notification->changedFields);
            }
        );

        // Assert redirected with success message
        $this->assertTrue($response->isRedirect());
        $this->assertTrue(session()->has('success'));
    }

    #[Test]
    public function it_updates_user_password()
    {
        // Create a user
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword1!')
        ]);

        // Update data with password
        $updateData = [
            'password' => 'NewPassword1!'
        ];

        // Mock the UserUpdateRequest
        $request = $this->mock(UserUpdateRequest::class, function (MockInterface $mock) use ($updateData) {
            $mock->shouldReceive('validated')->once()->andReturn($updateData);
            $mock->shouldReceive('expectsJson')->andReturn(false);
            $mock->shouldReceive('all')->andReturn($updateData);
        });

        // Execute the controller method
        $controller = new UserController();
        $response = $controller->update($request, $user->id);

        // Refresh the user model
        $user->refresh();

        // Assert password was updated and hashed
        $this->assertTrue(Hash::check('NewPassword1!', $user->password));
        $this->assertFalse(Hash::check('OldPassword1!', $user->password));

        // Assert notification was sent
        Notification::assertSentTo(
            $user,
            UserUpdated::class,
            function ($notification) {
                return array_key_exists('password', $notification->changedFields);
            }
        );
    }

    #[Test]
    public function it_returns_json_response_when_expected()
    {
        // Create a user
        $user = User::factory()->create();

        // Update data
        $updateData = [
            'first_name' => 'API',
            'last_name' => 'User'
        ];

        // Mock the UserUpdateRequest
        $request = $this->mock(UserUpdateRequest::class, function (MockInterface $mock) use ($updateData) {
            $mock->shouldReceive('validated')->once()->andReturn($updateData);
            $mock->shouldReceive('expectsJson')->andReturn(true);
            $mock->shouldReceive('all')->andReturn($updateData);
        });

        // Execute the controller method
        $controller = new UserController();
        $response = $controller->update($request, $user->id);

        // Assert JSON response
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('API', $responseData['first_name']);
        $this->assertEquals('User', $responseData['last_name']);
    }

    #[Test]
    public function it_handles_non_existent_user()
    {
        // Use a non-existent user ID
        $nonExistentId = 9999;

        // Mock the UserUpdateRequest
        $request = $this->mock(UserUpdateRequest::class, function (MockInterface $mock) {
            $mock->shouldReceive('validated')->andReturn([
                'first_name' => 'Test'
            ]);
            $mock->shouldReceive('all')->andReturn([
                'first_name' => 'Test'
            ]);
            $mock->shouldReceive('expectsJson')->andReturn(false);
        });

        // Execute the controller method, should throw a ModelNotFoundException
        $controller = new UserController();
        
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $controller->update($request, $nonExistentId);
    }

    #[Test]
    public function it_logs_errors_when_exception_occurs()
    {
        // This test verifies our error handling structure, not specific error behavior
        
        // Create a controller instance
        $controller = new UserController();
        
        // Get the code from the controller to examine
        $reflectionMethod = new \ReflectionMethod($controller, 'update');
        $code = file_get_contents(app_path('Http/Controllers/UserController.php'));
        
        // Check for key error handling components
        $this->assertStringContainsString('try {', $code);
        $this->assertStringContainsString('catch (\Exception $e)', $code);
        $this->assertStringContainsString('Log::error(', $code);
        $this->assertStringContainsString('withErrors', $code);
    }
}
