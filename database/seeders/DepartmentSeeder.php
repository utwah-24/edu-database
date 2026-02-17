<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => 'Computer Science',
            'description' => 'Department of Computer Science and Information Technology offering undergraduate and graduate programs in software engineering, artificial intelligence, and data science.',
            'code' => 'CS',
            'head' => 'Dr. Robert Anderson',
            'email' => 'cs@university.edu',
            'phone' => '+1-555-1001',
            'is_active' => true,
        ]);

        Department::create([
            'name' => 'Mathematics',
            'description' => 'Department of Mathematics specializing in pure and applied mathematics, statistics, and mathematical modeling.',
            'code' => 'MATH',
            'head' => 'Prof. Maria Garcia',
            'email' => 'math@university.edu',
            'phone' => '+1-555-1002',
            'is_active' => true,
        ]);

        Department::create([
            'name' => 'Physics',
            'description' => 'Department of Physics conducting research and education in theoretical physics, quantum mechanics, and astrophysics.',
            'code' => 'PHYS',
            'head' => 'Dr. James Thompson',
            'email' => 'physics@university.edu',
            'phone' => '+1-555-1003',
            'is_active' => true,
        ]);

        Department::create([
            'name' => 'Business Administration',
            'description' => 'School of Business Administration offering programs in management, finance, marketing, and entrepreneurship.',
            'code' => 'BUS',
            'head' => 'Dr. Linda Chen',
            'email' => 'business@university.edu',
            'phone' => '+1-555-1004',
            'is_active' => true,
        ]);

        Department::create([
            'name' => 'Engineering',
            'description' => 'Department of Engineering providing education in mechanical, electrical, civil, and chemical engineering.',
            'code' => 'ENG',
            'head' => 'Prof. David Wilson',
            'email' => 'engineering@university.edu',
            'phone' => '+1-555-1005',
            'is_active' => true,
        ]);
    }
}
