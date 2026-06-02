<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::updateOrCreate(
            ['email' => 'student@devaeducation.com'],
            [
                'first_name'              => 'Test',
                'last_name'               => 'Student',
                'email'                   => 'student@devaeducation.com',
                'password'                => Hash::make('student123'),
                'academic_year'           => '2024-2025',
                'date_of_birth'           => '2000-01-01',
                'passport_id'             => 'TEST123456',
                'passport_issue_date'     => '2020-01-01',
                'passport_expiry_date'    => '2030-01-01',
                'phone_number'            => '+1234567890',
                'gender'                  => 'Male',
                'marital_status'          => 'Single',
                'father_name'             => 'Father Name',
                'mother_name'             => 'Mother Name',
                'high_school_name'        => 'Test High School',
                'gpa'                     => '3.5',
                'status'                  => 'New',
                'application_date'        => now(),
            ]
        );
    }
}
