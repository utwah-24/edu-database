<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in the correct order to respect foreign key constraints
        
        // 1. Event Management System (Events first, then related content)
        $this->call([
            EventSeeder::class,
        ]);

        // 2. Education System
        $this->call([
            DepartmentSeeder::class,     // Must be first (no dependencies)
            TeacherSeeder::class,        // Depends on: User, Department
            StudentSeeder::class,        // Depends on: User
            CourseSeeder::class,         // Depends on: Department, Teacher
            EnrollmentSeeder::class,     // Depends on: Student, Course
            GradeSeeder::class,          // Depends on: Enrollment
        ]);

        $this->command->info('✅ All seeders completed successfully!');
        $this->command->info('📊 Database populated with:');
        $this->command->info('   - 3 Events (2024, 2025, 2026) with full content');
        $this->command->info('   - 5 Departments');
        $this->command->info('   - 6 Teachers');
        $this->command->info('   - 8 Students');
        $this->command->info('   - 10 Courses');
        $this->command->info('   - 16 Enrollments');
        $this->command->info('   - 14 Grade records');
    }
}
