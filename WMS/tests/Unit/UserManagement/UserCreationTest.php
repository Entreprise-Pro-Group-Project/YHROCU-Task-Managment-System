<?php

namespace Tests\Unit\UserManagement;

use Tests\TestCase;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use App\Http\Controllers\UserController;
use App\Notifications\UserCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

class UserCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        Session::start();
    }

    #[Test]
    public function it_creates_user_with_correct_data()
    {
        // Sample user data
        $userData = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'phone_number' => '1234567890',
            'role' => 'staff',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!'
        ];

        // Mock the UserStoreRequest
        $request = $this->mock(UserStoreRequest::class, function (MockInterface $mock) use ($userData) {
            $mock->shouldReceive('validated')->once()->andReturn($userData);
            $mock->shouldReceive('expectsJson')->andReturn(false);
            $mock->shouldReceive('all')->andReturn($userData);
        });

        // Create controller and call store method
        $controller = new UserController();
        $response = $controller->store($request);

        // Assert user was created in database
        $this->assertDatabaseHas('users', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'role' => 'staff'
        ]);

        // Assert hash was used for password
        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('Password1!', $user->password));

        // Assert notification was sent
        Notification::assertSentTo(
            $user,
            UserCreated::class
        );
    }

    #[Test]
    public function it_returns_json_response_when_expected()
    {
        $userData = [
            'first_name' => 'API',
            'last_name' => 'User',
            'username' => 'apiuser',
            'email' => 'api@example.com',
            'phone_number' => '9876543210',
            'role' => 'staff',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!'
        ];

        $request = $this->mock(UserStoreRequest::class, function (MockInterface $mock) use ($userData) {
            $mock->shouldReceive('validated')->once()->andReturn($userData);
            $mock->shouldReceive('expectsJson')->andReturn(true);
            $mock->shouldReceive('all')->andReturn($userData);
        });

        $controller = new UserController();
        $response = $controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertEquals('API', $responseData['first_name']);
        $this->assertEquals('User', $responseData['last_name']);
        $this->assertEquals('apiuser', $responseData['username']);
    }

    #[Test]
    public function it_logs_errors_when_exception_occurs()
    {
        // Mock UserStoreRequest that will throw an exception
        $request = $this->mock(UserStoreRequest::class, function (MockInterface $mock) {
            $mock->shouldReceive('validated')->once()->andThrow(new \Exception('Test exception'));
            $mock->shouldReceive('expectsJson')->andReturn(false);
            $mock->shouldReceive('all')->andReturn([
                'first_name' => 'Test',
                'last_name' => 'User'
            ]);
        });

        $controller = new UserController();
        $response = $controller->store($request);

        // Assert redirected back with errors
        $this->assertTrue($response->isRedirect());
        $this->assertTrue(session()->hasOldInput());
        $this->assertTrue(session()->has('errors'));
    }
}
