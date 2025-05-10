<?php

test('registration screen can be rendered', function () {
    // Skip this test as registration is not available in this application
    $this->markTestSkipped('Registration page is not available in this application. Only admins can register users.');
    
    /*
    $response = $this->get('/register');
    $response->assertStatus(200);
    */
});

test('new users can register', function () {
    // Skip this test as user self-registration is not allowed in this application
    // Only admins can register users
    $this->markTestSkipped('User self-registration is not enabled in this application. Only admins can register users.');
    
    /*
    $response = $this->post('/register', [
        'first_name' => 'Test',
        'last_name' => 'User',
        'username' => 'testuser',
        'phone_number' => '1234567890',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
    */
});
