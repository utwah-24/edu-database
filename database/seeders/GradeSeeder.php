<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Grades for Daniel Brown's CS201 course (enrollment_id = 7, completed)
        Grade::create([
            'enrollment_id' => 7,
            'assignment_name' => 'Homework 1 - Arrays and Linked Lists',
            'assignment_type' => 'homework',
            'grade' => 95.00,
            'max_grade' => 100.00,
            'weight' => 10.00,
            'grade_date' => '2025-02-01',
            'remarks' => 'Excellent work on implementation',
        ]);

        Grade::create([
            'enrollment_id' => 7,
            'assignment_name' => 'Homework 2 - Trees and Graphs',
            'assignment_type' => 'homework',
            'grade' => 88.00,
            'max_grade' => 100.00,
            'weight' => 10.00,
            'grade_date' => '2025-02-15',
            'remarks' => 'Good understanding of concepts',
        ]);

        Grade::create([
            'enrollment_id' => 7,
            'assignment_name' => 'Quiz 1',
            'assignment_type' => 'quiz',
            'grade' => 90.00,
            'max_grade' => 100.00,
            'weight' => 15.00,
            'grade_date' => '2025-03-01',
            'remarks' => 'Strong performance',
        ]);

        Grade::create([
            'enrollment_id' => 7,
            'assignment_name' => 'Midterm Exam',
            'assignment_type' => 'midterm',
            'grade' => 92.00,
            'max_grade' => 100.00,
            'weight' => 25.00,
            'grade_date' => '2025-03-20',
            'remarks' => 'Excellent problem-solving skills',
        ]);

        Grade::create([
            'enrollment_id' => 7,
            'assignment_name' => 'Final Project - Algorithm Visualization',
            'assignment_type' => 'project',
            'grade' => 95.00,
            'max_grade' => 100.00,
            'weight' => 30.00,
            'grade_date' => '2025-05-10',
            'remarks' => 'Outstanding project implementation',
        ]);

        Grade::create([
            'enrollment_id' => 7,
            'assignment_name' => 'Class Participation',
            'assignment_type' => 'participation',
            'grade' => 100.00,
            'max_grade' => 100.00,
            'weight' => 10.00,
            'grade_date' => '2025-05-15',
            'remarks' => 'Active participation throughout semester',
        ]);

        // Grades for Alice Cooper's CS101 (enrollment_id = 1, in progress)
        Grade::create([
            'enrollment_id' => 1,
            'assignment_name' => 'Homework 1 - Python Basics',
            'assignment_type' => 'homework',
            'grade' => 85.00,
            'max_grade' => 100.00,
            'weight' => 10.00,
            'grade_date' => '2025-09-15',
            'remarks' => 'Good start, minor syntax errors',
        ]);

        Grade::create([
            'enrollment_id' => 1,
            'assignment_name' => 'Quiz 1 - Variables and Data Types',
            'assignment_type' => 'quiz',
            'grade' => 92.00,
            'max_grade' => 100.00,
            'weight' => 15.00,
            'grade_date' => '2025-09-22',
            'remarks' => 'Strong understanding of fundamentals',
        ]);

        // Grades for Bob Martinez's CS101 (enrollment_id = 3)
        Grade::create([
            'enrollment_id' => 3,
            'assignment_name' => 'Homework 1 - Python Basics',
            'assignment_type' => 'homework',
            'grade' => 78.00,
            'max_grade' => 100.00,
            'weight' => 10.00,
            'grade_date' => '2025-09-15',
            'remarks' => 'Need to review control structures',
        ]);

        Grade::create([
            'enrollment_id' => 3,
            'assignment_name' => 'Quiz 1 - Variables and Data Types',
            'assignment_type' => 'quiz',
            'grade' => 88.00,
            'max_grade' => 100.00,
            'weight' => 15.00,
            'grade_date' => '2025-09-22',
            'remarks' => 'Good improvement',
        ]);

        // Grades for Emma Davis's CS101 (enrollment_id = 9)
        Grade::create([
            'enrollment_id' => 9,
            'assignment_name' => 'Homework 1 - Python Basics',
            'assignment_type' => 'homework',
            'grade' => 98.00,
            'max_grade' => 100.00,
            'weight' => 10.00,
            'grade_date' => '2025-09-15',
            'remarks' => 'Excellent work, clean code',
        ]);

        // Grades for Catherine Lee's MATH101 (enrollment_id = 5)
        Grade::create([
            'enrollment_id' => 5,
            'assignment_name' => 'Homework 1 - Limits',
            'assignment_type' => 'homework',
            'grade' => 90.00,
            'max_grade' => 100.00,
            'weight' => 10.00,
            'grade_date' => '2025-09-10',
            'remarks' => 'Strong analytical skills',
        ]);

        Grade::create([
            'enrollment_id' => 5,
            'assignment_name' => 'Quiz 1 - Derivatives',
            'assignment_type' => 'quiz',
            'grade' => 95.00,
            'max_grade' => 100.00,
            'weight' => 15.00,
            'grade_date' => '2025-09-25',
            'remarks' => 'Excellent problem-solving',
        ]);
    }
}
