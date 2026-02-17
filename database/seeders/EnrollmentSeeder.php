<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Enrollment;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Alice Cooper enrollments
        Enrollment::create([
            'student_id' => 1,
            'course_id' => 1, // CS101
            'enrollment_date' => '2025-09-01',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        Enrollment::create([
            'student_id' => 1,
            'course_id' => 4, // MATH101
            'enrollment_date' => '2025-09-01',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        // Bob Martinez enrollments
        Enrollment::create([
            'student_id' => 2,
            'course_id' => 1, // CS101
            'enrollment_date' => '2025-09-01',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        Enrollment::create([
            'student_id' => 2,
            'course_id' => 6, // PHYS101
            'enrollment_date' => '2025-09-01',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        // Catherine Lee enrollments
        Enrollment::create([
            'student_id' => 3,
            'course_id' => 4, // MATH101
            'enrollment_date' => '2025-09-01',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        Enrollment::create([
            'student_id' => 3,
            'course_id' => 8, // BUS101
            'enrollment_date' => '2025-09-01',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        // Daniel Brown enrollments (2nd year student)
        Enrollment::create([
            'student_id' => 4,
            'course_id' => 2, // CS201
            'enrollment_date' => '2025-01-15',
            'status' => 'completed',
            'final_grade' => 92.50,
            'letter_grade' => 'A',
        ]);

        Enrollment::create([
            'student_id' => 4,
            'course_id' => 5, // MATH201
            'enrollment_date' => '2025-01-15',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        // Emma Davis enrollments
        Enrollment::create([
            'student_id' => 5,
            'course_id' => 1, // CS101
            'enrollment_date' => '2025-09-01',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        Enrollment::create([
            'student_id' => 5,
            'course_id' => 10, // ENG101
            'enrollment_date' => '2025-09-01',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        // Frank Wilson enrollments (2nd year)
        Enrollment::create([
            'student_id' => 6,
            'course_id' => 2, // CS201
            'enrollment_date' => '2025-01-15',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        Enrollment::create([
            'student_id' => 6,
            'course_id' => 9, // BUS201
            'enrollment_date' => '2025-01-15',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        // Grace Thompson enrollments
        Enrollment::create([
            'student_id' => 7,
            'course_id' => 4, // MATH101
            'enrollment_date' => '2025-09-01',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        Enrollment::create([
            'student_id' => 7,
            'course_id' => 6, // PHYS101
            'enrollment_date' => '2025-09-01',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        // Henry Garcia enrollments
        Enrollment::create([
            'student_id' => 8,
            'course_id' => 1, // CS101
            'enrollment_date' => '2025-09-01',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);

        Enrollment::create([
            'student_id' => 8,
            'course_id' => 8, // BUS101
            'enrollment_date' => '2025-09-01',
            'status' => 'enrolled',
            'final_grade' => null,
            'letter_grade' => null,
        ]);
    }
}
