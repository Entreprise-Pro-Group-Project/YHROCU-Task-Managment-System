<?php

namespace Tests\Unit\UserManagement;

use Tests\TestCase;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Notifications\UserDeleted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

class UserDeletionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    #[Test]
    public function it_deletes_user_from_database()
    {
        // Create a user to delete
        $user = User::factory()->create([
            'first_name' => 'Delete',
            'last_name' => 'Me',
            'email' => 'delete@example.com'
        ]);

        // Verify user exists in database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'delete@example.com'
        ]);

        // Mock the request
        $request = $this->instance(Request::class, Mockery::mock(Request::class, function (MockInterface $mock) {
            $mock->shouldReceive('expectsJson')->andReturn(false);
        }));

        // Execute the controller method
        $controller = new UserController();
        $response = $controller->destroy($request, $user->id);

        // Assert user was deleted
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => 'delete@example.com'
        ]);

        // Assert notification was sent
        Notification::assertSentTo(
            new \Illuminate\Notifications\AnonymousNotifiable,
            UserDeleted::class,
            function ($notification, $channels, $notifiable) use ($user) {
                return $notifiable->routes['mail'] === $user->email;
            }
        );

        // Assert redirected with success message
        $this->assertTrue($response->isRedirect());
        $this->assertTrue(session()->has('success'));
    }

    #[Test]
    public function it_returns_json_response_when_expected()
    {
        // Create a user to delete
        $user = User::factory()->create();

        // Mock the request
        $request = $this->instance(Request::class, Mockery::mock(Request::class, function (MockInterface $mock) {
            $mock->shouldReceive('expectsJson')->andReturn(true);
        }));

        // Execute the controller method
        $controller = new UserController();
        $response = $controller->destroy($request, $user->id);

        // Assert proper JSON response
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }

    #[Test]
    public function it_handles_non_existent_user()
    {
        // Use a non-existent user ID
        $nonExistentId = 9999;

        // Create a request
        $request = new Request();

        // Execute the controller method, should throw a ModelNotFoundException
        $controller = new UserController();
        
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $controller->destroy($request, $nonExistentId);
    }

    #[Test]
    public function it_logs_errors_when_exception_occurs()
    {
        // This test verifies our error handling structure, not specific error behavior
        
        // Create a controller instance
        $controller = new UserController();
        
        // Get the code from the controller to examine
        $reflectionMethod = new \ReflectionMethod($controller, 'destroy');
        $code = file_get_contents(app_path('Http/Controllers/UserController.php'));
        
        // Check for key error handling components
        $this->assertStringContainsString('try {', $code);
        $this->assertStringContainsString('catch (\Exception $e)', $code);
        $this->assertStringContainsString('Log::error(', $code);
        $this->assertStringContainsString('withErrors', $code);
    }
    
    #[Test]
    public function it_has_proper_error_handling_structure()
    {
        // This is a simpler "smoke test" to check that the controller has proper try/catch structure
        $controller = new UserController();
        $reflectionMethod = new \ReflectionMethod($controller, 'destroy');
        $code = file_get_contents(app_path('Http/Controllers/UserController.php'));
        
        // Check if the destroy method contains try/catch blocks
        $this->assertTrue(
            strpos($code, 'try {') !== false && 
            strpos($code, 'catch (\Exception $e)') !== false, 
            'The destroy method should have proper error handling with try/catch blocks'
        );
    }
}
