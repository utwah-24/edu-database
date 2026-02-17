<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Student 1
        $user1 = User::create([
            'name' => 'Alice Cooper',
            'email' => 'alice.cooper@student.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $user1->id,
            'student_id' => 'STU-2024-001',
            'date_of_birth' => '2003-05-15',
            'gender' => 'female',
            'phone' => '+1-555-3001',
            'address' => '123 Main Street, New York, NY 10001',
            'guardian_name' => 'Mary Cooper',
            'guardian_phone' => '+1-555-3101',
            'guardian_email' => 'mary.cooper@email.com',
            'admission_date' => '2024-09-01',
            'enrollment_status' => 'active',
            'blood_group' => 'A+',
            'medical_conditions' => null,
        ]);

        // Student 2
        $user2 = User::create([
            'name' => 'Bob Martinez',
            'email' => 'bob.martinez@student.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $user2->id,
            'student_id' => 'STU-2024-002',
            'date_of_birth' => '2004-03-20',
            'gender' => 'male',
            'phone' => '+1-555-3002',
            'address' => '456 Oak Avenue, Brooklyn, NY 11201',
            'guardian_name' => 'Carlos Martinez',
            'guardian_phone' => '+1-555-3102',
            'guardian_email' => 'carlos.martinez@email.com',
            'admission_date' => '2024-09-01',
            'enrollment_status' => 'active',
            'blood_group' => 'B+',
            'medical_conditions' => null,
        ]);

        // Student 3
        $user3 = User::create([
            'name' => 'Catherine Lee',
            'email' => 'catherine.lee@student.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $user3->id,
            'student_id' => 'STU-2024-003',
            'date_of_birth' => '2003-11-08',
            'gender' => 'female',
            'phone' => '+1-555-3003',
            'address' => '789 Elm Street, Queens, NY 11354',
            'guardian_name' => 'James Lee',
            'guardian_phone' => '+1-555-3103',
            'guardian_email' => 'james.lee@email.com',
            'admission_date' => '2024-09-01',
            'enrollment_status' => 'active',
            'blood_group' => 'O+',
            'medical_conditions' => null,
        ]);

        // Student 4
        $user4 = User::create([
            'name' => 'Daniel Brown',
            'email' => 'daniel.brown@student.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $user4->id,
            'student_id' => 'STU-2023-015',
            'date_of_birth' => '2002-07-12',
            'gender' => 'male',
            'phone' => '+1-555-3004',
            'address' => '321 Pine Road, Manhattan, NY 10002',
            'guardian_name' => 'Patricia Brown',
            'guardian_phone' => '+1-555-3104',
            'guardian_email' => 'patricia.brown@email.com',
            'admission_date' => '2023-09-01',
            'enrollment_status' => 'active',
            'blood_group' => 'AB+',
            'medical_conditions' => 'Asthma (controlled)',
        ]);

        // Student 5
        $user5 = User::create([
            'name' => 'Emma Davis',
            'email' => 'emma.davis@student.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $user5->id,
            'student_id' => 'STU-2024-004',
            'date_of_birth' => '2003-09-25',
            'gender' => 'female',
            'phone' => '+1-555-3005',
            'address' => '654 Maple Drive, Bronx, NY 10451',
            'guardian_name' => 'Robert Davis',
            'guardian_phone' => '+1-555-3105',
            'guardian_email' => 'robert.davis@email.com',
            'admission_date' => '2024-09-01',
            'enrollment_status' => 'active',
            'blood_group' => 'A-',
            'medical_conditions' => null,
        ]);

        // Student 6
        $user6 = User::create([
            'name' => 'Frank Wilson',
            'email' => 'frank.wilson@student.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $user6->id,
            'student_id' => 'STU-2023-020',
            'date_of_birth' => '2002-12-30',
            'gender' => 'male',
            'phone' => '+1-555-3006',
            'address' => '987 Cedar Lane, Staten Island, NY 10301',
            'guardian_name' => 'Susan Wilson',
            'guardian_phone' => '+1-555-3106',
            'guardian_email' => 'susan.wilson@email.com',
            'admission_date' => '2023-09-01',
            'enrollment_status' => 'active',
            'blood_group' => 'O-',
            'medical_conditions' => null,
        ]);

        // Student 7
        $user7 = User::create([
            'name' => 'Grace Thompson',
            'email' => 'grace.thompson@student.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $user7->id,
            'student_id' => 'STU-2024-005',
            'date_of_birth' => '2004-01-18',
            'gender' => 'female',
            'phone' => '+1-555-3007',
            'address' => '147 Birch Street, Queens, NY 11375',
            'guardian_name' => 'Thomas Thompson',
            'guardian_phone' => '+1-555-3107',
            'guardian_email' => 'thomas.thompson@email.com',
            'admission_date' => '2024-09-01',
            'enrollment_status' => 'active',
            'blood_group' => 'B-',
            'medical_conditions' => null,
        ]);

        // Student 8
        $user8 = User::create([
            'name' => 'Henry Garcia',
            'email' => 'henry.garcia@student.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $user8->id,
            'student_id' => 'STU-2024-006',
            'date_of_birth' => '2003-06-22',
            'gender' => 'male',
            'phone' => '+1-555-3008',
            'address' => '258 Willow Avenue, Brooklyn, NY 11211',
            'guardian_name' => 'Maria Garcia',
            'guardian_phone' => '+1-555-3108',
            'guardian_email' => 'maria.garcia@email.com',
            'admission_date' => '2024-09-01',
            'enrollment_status' => 'active',
            'blood_group' => 'A+',
            'medical_conditions' => null,
        ]);
    }
}
