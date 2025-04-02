<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create a default admin user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'phone_number' => '1234567890',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create a supervisor user
        User::create([
            'first_name' => 'Supervisor',
            'last_name' => 'User',
            'username' => 'supervisor',
            'email' => 'supervisor@example.com',
            'phone_number' => '2345678901',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
        ]);

        // Create staff users
        User::create([
            'first_name' => 'Staff',
            'last_name' => 'One',
            'username' => 'staff1',
            'email' => 'staff1@example.com',
            'phone_number' => '3456789012',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        User::create([
            'first_name' => 'Staff',
            'last_name' => 'Two',
            'username' => 'staff2',
            'email' => 'staff2@example.com',
            'phone_number' => '4567890123',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
    }
}
