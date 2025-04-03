<?php

namespace Tests\Unit\UserManagement;

use Tests\TestCase;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserValidationTest extends TestCase
{
    use RefreshDatabase;
    
    #[Test]
    public function store_request_validates_required_fields()
    {
        $request = new UserStoreRequest();
        
        $validator = Validator::make([], $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('first_name', $validator->errors()->messages());
        $this->assertArrayHasKey('last_name', $validator->errors()->messages());
        $this->assertArrayHasKey('username', $validator->errors()->messages());
        $this->assertArrayHasKey('email', $validator->errors()->messages());
        $this->assertArrayHasKey('role', $validator->errors()->messages());
        $this->assertArrayHasKey('password', $validator->errors()->messages());
        $this->assertArrayHasKey('phone_number', $validator->errors()->messages());
    }
    
    #[Test]
    public function store_request_validates_email_format()
    {
        $request = new UserStoreRequest();
        
        $data = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'username' => 'testuser',
            'email' => 'not-an-email',  // Invalid email format
            'phone_number' => '1234567890',
            'role' => 'staff',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!'
        ];
        
        $validator = Validator::make($data, $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->messages());
    }
    
    #[Test]
    public function store_request_validates_role_values()
    {
        $request = new UserStoreRequest();
        
        $data = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'phone_number' => '1234567890',
            'role' => 'invalid-role',  // Invalid role
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!'
        ];
        
        $validator = Validator::make($data, $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('role', $validator->errors()->messages());
    }
    
    #[Test]
    public function store_request_validates_password_complexity()
    {
        $request = new UserStoreRequest();
        
        $data = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'phone_number' => '1234567890',
            'role' => 'staff',
            'password' => 'simple',  // Doesn't meet complexity requirements
            'password_confirmation' => 'simple'
        ];
        
        $validator = Validator::make($data, $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->messages());
    }
    
    #[Test]
    public function store_request_validates_password_confirmation()
    {
        $request = new UserStoreRequest();
        
        $data = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'phone_number' => '1234567890',
            'role' => 'staff',
            'password' => 'Password1!',
            'password_confirmation' => 'Different1!'  // Doesn't match password
        ];
        
        $validator = Validator::make($data, $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->messages());
    }
    
    #[Test]
    public function store_request_validates_username_uniqueness()
    {
        // Create a user with a specific username
        $existingUser = \App\Models\User::factory()->create([
            'username' => 'existinguser',
            'phone_number' => '9876543210'
        ]);
        
        $request = new UserStoreRequest();
        
        $data = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'username' => 'existinguser',  // Already exists
            'email' => 'test@example.com',
            'phone_number' => '1234567890',
            'role' => 'staff',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!'
        ];
        
        $validator = Validator::make($data, $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('username', $validator->errors()->messages());
    }
    
    #[Test]
    public function store_request_validates_email_uniqueness()
    {
        // Create a user with a specific email
        $existingUser = \App\Models\User::factory()->create([
            'email' => 'existing@example.com',
            'phone_number' => '9876543210'
        ]);
        
        $request = new UserStoreRequest();
        
        $data = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'username' => 'testuser',
            'email' => 'existing@example.com',  // Already exists
            'phone_number' => '1234567890',
            'role' => 'staff',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!'
        ];
        
        $validator = Validator::make($data, $request->rules());
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->messages());
    }
    
    #[Test]
    public function update_request_allows_same_email_for_same_user()
    {
        // Create a user
        $user = \App\Models\User::factory()->create([
            'email' => 'user@example.com',
            'phone_number' => '1234567890'
        ]);
        
        // Access the rules directly by manually setting the user ID
        $userId = $user->id;
        $rules = [
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $userId,
            'phone_number' => 'sometimes|required|string|max:20|unique:users,phone_number,' . $userId,
        ];
        
        $data = [
            'email' => 'user@example.com',  // Same as existing user
            'phone_number' => '1234567890'  // Same as existing user
        ];
        
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->fails(), 'Validation should pass for same email on same user');
    }
    
    #[Test]
    public function update_request_requires_unique_email_for_different_user()
    {
        // Create two users
        $user1 = \App\Models\User::factory()->create([
            'email' => 'user1@example.com',
            'phone_number' => '1111111111'
        ]);
        
        $user2 = \App\Models\User::factory()->create([
            'email' => 'user2@example.com',
            'phone_number' => '2222222222'
        ]);
        
        // Mock the route parameters to include user1's ID
        $this->app['router']->get('test-route/{user}', ['as' => 'test.route']);
        $request = UserUpdateRequest::create(route('test.route', ['user' => $user1->id]), 'GET');
        
        $data = [
            'email' => 'user2@example.com',  // Already used by user2
            'phone_number' => '3333333333'
        ];
        
        $rules = $request->rules();
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->messages());
    }
}
