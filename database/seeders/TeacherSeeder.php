<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Teacher 1 - Computer Science
        $user1 = User::create([
            'name' => 'Dr. Sarah Johnson',
            'email' => 'sarah.johnson@university.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Teacher::create([
            'user_id' => $user1->id,
            'department_id' => 1, // Computer Science
            'employee_id' => 'EMP-CS-001',
            'phone' => '+1-555-2001',
            'specialization' => 'Artificial Intelligence & Machine Learning',
            'hire_date' => '2018-08-15',
            'employment_type' => 'full-time',
            'bio' => 'Dr. Johnson specializes in AI and ML with 10+ years of research experience. Published over 30 papers in top-tier conferences.',
            'office_location' => 'Building A, Room 301',
            'is_active' => true,
        ]);

        // Teacher 2 - Computer Science
        $user2 = User::create([
            'name' => 'Prof. Michael Chen',
            'email' => 'michael.chen@university.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Teacher::create([
            'user_id' => $user2->id,
            'department_id' => 1, // Computer Science
            'employee_id' => 'EMP-CS-002',
            'phone' => '+1-555-2002',
            'specialization' => 'Software Engineering & Cloud Computing',
            'hire_date' => '2019-01-10',
            'employment_type' => 'full-time',
            'bio' => 'Expert in software architecture and distributed systems. Former software architect at major tech companies.',
            'office_location' => 'Building A, Room 302',
            'is_active' => true,
        ]);

        // Teacher 3 - Mathematics
        $user3 = User::create([
            'name' => 'Dr. Emily Rodriguez',
            'email' => 'emily.rodriguez@university.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Teacher::create([
            'user_id' => $user3->id,
            'department_id' => 2, // Mathematics
            'employee_id' => 'EMP-MATH-001',
            'phone' => '+1-555-2003',
            'specialization' => 'Applied Mathematics & Statistics',
            'hire_date' => '2017-09-01',
            'employment_type' => 'full-time',
            'bio' => 'Specializes in statistical modeling and data analysis. Consultant for several research institutions.',
            'office_location' => 'Building B, Room 201',
            'is_active' => true,
        ]);

        // Teacher 4 - Physics
        $user4 = User::create([
            'name' => 'Prof. David Kumar',
            'email' => 'david.kumar@university.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Teacher::create([
            'user_id' => $user4->id,
            'department_id' => 3, // Physics
            'employee_id' => 'EMP-PHYS-001',
            'phone' => '+1-555-2004',
            'specialization' => 'Quantum Physics & Nanotechnology',
            'hire_date' => '2016-07-20',
            'employment_type' => 'full-time',
            'bio' => 'Leading researcher in quantum mechanics. Winner of several prestigious science awards.',
            'office_location' => 'Building C, Room 401',
            'is_active' => true,
        ]);

        // Teacher 5 - Business Administration
        $user5 = User::create([
            'name' => 'Dr. Amanda Taylor',
            'email' => 'amanda.taylor@university.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Teacher::create([
            'user_id' => $user5->id,
            'department_id' => 4, // Business Administration
            'employee_id' => 'EMP-BUS-001',
            'phone' => '+1-555-2005',
            'specialization' => 'Marketing & Digital Business',
            'hire_date' => '2020-02-15',
            'employment_type' => 'full-time',
            'bio' => 'Expert in digital marketing and e-commerce. Former marketing director at Fortune 500 company.',
            'office_location' => 'Building D, Room 101',
            'is_active' => true,
        ]);

        // Teacher 6 - Engineering (Part-time)
        $user6 = User::create([
            'name' => 'John Williams',
            'email' => 'john.williams@university.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Teacher::create([
            'user_id' => $user6->id,
            'department_id' => 5, // Engineering
            'employee_id' => 'EMP-ENG-001',
            'phone' => '+1-555-2006',
            'specialization' => 'Mechanical Engineering',
            'hire_date' => '2021-09-01',
            'employment_type' => 'part-time',
            'bio' => 'Practicing engineer with industry experience. Teaches advanced engineering design.',
            'office_location' => 'Building E, Room 205',
            'is_active' => true,
        ]);
    }
}
