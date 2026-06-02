<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@devaeducation.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@devaeducation.com',
                'password' => Hash::make('admin123'),
                'role' => 'Admin',
                'type' => 'individual',
                'email_verified_at' => now(),
            ]
        );

        // Create Sales User (optional)
        User::updateOrCreate(
            ['email' => 'sales@devaeducation.com'],
            [
                'name' => 'Sales Manager',
                'email' => 'sales@devaeducation.com',
                'password' => Hash::make('sales123'),
                'role' => 'Sales',
                'type' => 'individual',
                'email_verified_at' => now(),
            ]
        );

        // Create Register User (optional)
        User::updateOrCreate(
            ['email' => 'register@devaeducation.com'],
            [
                'name' => 'Register Officer',
                'email' => 'register@devaeducation.com',
                'password' => Hash::make('register123'),
                'role' => 'Register',
                'type' => 'individual',
                'email_verified_at' => now(),
            ]
        );
    }
}
