<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Computer Science Courses
        Course::create([
            'code' => 'CS101',
            'name' => 'Introduction to Programming',
            'description' => 'Fundamental concepts of programming using Python. Topics include variables, control structures, functions, and basic data structures.',
            'credits' => 3,
            'department_id' => 1,
            'teacher_id' => 2, // Prof. Michael Chen
            'semester' => 'Fall',
            'academic_year' => '2025-2026',
            'max_students' => 40,
            'level' => 'undergraduate',
            'room' => 'CS Building Room 101',
            'schedule' => json_encode(['Monday' => '09:00-10:30', 'Wednesday' => '09:00-10:30']),
            'is_active' => true,
        ]);

        Course::create([
            'code' => 'CS201',
            'name' => 'Data Structures and Algorithms',
            'description' => 'Advanced programming concepts including arrays, linked lists, trees, graphs, sorting, and searching algorithms.',
            'credits' => 4,
            'department_id' => 1,
            'teacher_id' => 1, // Dr. Sarah Johnson
            'semester' => 'Spring',
            'academic_year' => '2025-2026',
            'max_students' => 35,
            'level' => 'undergraduate',
            'room' => 'CS Building Room 201',
            'schedule' => json_encode(['Tuesday' => '10:00-12:00', 'Thursday' => '10:00-12:00']),
            'is_active' => true,
        ]);

        Course::create([
            'code' => 'CS301',
            'name' => 'Artificial Intelligence',
            'description' => 'Introduction to AI concepts, machine learning, neural networks, and practical applications.',
            'credits' => 3,
            'department_id' => 1,
            'teacher_id' => 1, // Dr. Sarah Johnson
            'semester' => 'Fall',
            'academic_year' => '2025-2026',
            'max_students' => 30,
            'level' => 'graduate',
            'room' => 'CS Building Room 301',
            'schedule' => json_encode(['Monday' => '14:00-16:30', 'Wednesday' => '14:00-15:30']),
            'is_active' => true,
        ]);

        // Mathematics Courses
        Course::create([
            'code' => 'MATH101',
            'name' => 'Calculus I',
            'description' => 'Limits, derivatives, integration, and applications of calculus to real-world problems.',
            'credits' => 4,
            'department_id' => 2,
            'teacher_id' => 3, // Dr. Emily Rodriguez
            'semester' => 'Fall',
            'academic_year' => '2025-2026',
            'max_students' => 45,
            'level' => 'undergraduate',
            'room' => 'Math Building Room 102',
            'schedule' => json_encode(['Monday' => '08:00-09:30', 'Wednesday' => '08:00-09:30', 'Friday' => '08:00-09:00']),
            'is_active' => true,
        ]);

        Course::create([
            'code' => 'MATH201',
            'name' => 'Linear Algebra',
            'description' => 'Vector spaces, matrices, linear transformations, eigenvalues, and eigenvectors.',
            'credits' => 3,
            'department_id' => 2,
            'teacher_id' => 3, // Dr. Emily Rodriguez
            'semester' => 'Spring',
            'academic_year' => '2025-2026',
            'max_students' => 35,
            'level' => 'undergraduate',
            'room' => 'Math Building Room 201',
            'schedule' => json_encode(['Tuesday' => '11:00-12:30', 'Thursday' => '11:00-12:30']),
            'is_active' => true,
        ]);

        // Physics Courses
        Course::create([
            'code' => 'PHYS101',
            'name' => 'General Physics I',
            'description' => 'Mechanics, thermodynamics, and wave motion. Includes laboratory component.',
            'credits' => 4,
            'department_id' => 3,
            'teacher_id' => 4, // Prof. David Kumar
            'semester' => 'Fall',
            'academic_year' => '2025-2026',
            'max_students' => 40,
            'level' => 'undergraduate',
            'room' => 'Physics Building Room 105',
            'schedule' => json_encode(['Monday' => '13:00-14:30', 'Wednesday' => '13:00-14:30', 'Friday' => '13:00-15:00']),
            'is_active' => true,
        ]);

        Course::create([
            'code' => 'PHYS301',
            'name' => 'Quantum Mechanics',
            'description' => 'Principles of quantum mechanics, wave functions, operators, and applications.',
            'credits' => 3,
            'department_id' => 3,
            'teacher_id' => 4, // Prof. David Kumar
            'semester' => 'Spring',
            'academic_year' => '2025-2026',
            'max_students' => 25,
            'level' => 'graduate',
            'room' => 'Physics Building Room 301',
            'schedule' => json_encode(['Tuesday' => '15:00-17:30', 'Thursday' => '15:00-16:30']),
            'is_active' => true,
        ]);

        // Business Administration Courses
        Course::create([
            'code' => 'BUS101',
            'name' => 'Introduction to Business',
            'description' => 'Overview of business principles, management, marketing, finance, and entrepreneurship.',
            'credits' => 3,
            'department_id' => 4,
            'teacher_id' => 5, // Dr. Amanda Taylor
            'semester' => 'Fall',
            'academic_year' => '2025-2026',
            'max_students' => 50,
            'level' => 'undergraduate',
            'room' => 'Business Building Room 101',
            'schedule' => json_encode(['Monday' => '11:00-12:30', 'Wednesday' => '11:00-12:30']),
            'is_active' => true,
        ]);

        Course::create([
            'code' => 'BUS201',
            'name' => 'Digital Marketing',
            'description' => 'Social media marketing, SEO, content marketing, and digital advertising strategies.',
            'credits' => 3,
            'department_id' => 4,
            'teacher_id' => 5, // Dr. Amanda Taylor
            'semester' => 'Spring',
            'academic_year' => '2025-2026',
            'max_students' => 35,
            'level' => 'undergraduate',
            'room' => 'Business Building Room 205',
            'schedule' => json_encode(['Tuesday' => '14:00-15:30', 'Thursday' => '14:00-15:30']),
            'is_active' => true,
        ]);

        // Engineering Courses
        Course::create([
            'code' => 'ENG101',
            'name' => 'Engineering Fundamentals',
            'description' => 'Introduction to engineering design, problem-solving, and technical communication.',
            'credits' => 3,
            'department_id' => 5,
            'teacher_id' => 6, // John Williams
            'semester' => 'Fall',
            'academic_year' => '2025-2026',
            'max_students' => 35,
            'level' => 'undergraduate',
            'room' => 'Engineering Building Room 101',
            'schedule' => json_encode(['Monday' => '16:00-17:30', 'Wednesday' => '16:00-17:30']),
            'is_active' => true,
        ]);
    }
}
