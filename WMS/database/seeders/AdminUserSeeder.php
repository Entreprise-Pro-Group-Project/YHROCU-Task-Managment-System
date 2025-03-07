<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'username' => 'admin',
            'phone_number' => '1234567890',
            'role' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        // Create Supervisor User
        User::create([
            'first_name' => 'Supervisor',
            'last_name' => 'User',
            'username' => 'supervisor',
            'phone_number' => '2345678901',
            'role' => 'supervisor',
            'email' => 'supervisor@supervisor.com',
            'password' => Hash::make('password'),
        ]);

        // Create Staff User
        User::create([
            'first_name' => 'Staff',
            'last_name' => 'User',
            'username' => 'staff',
            'phone_number' => '3456789012',
            'role' => 'staff',
            'email' => 'staff@staff.com',
            'password' => Hash::make('password'),
        ]);
    }
}
