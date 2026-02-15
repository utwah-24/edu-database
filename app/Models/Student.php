<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'student_id',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'admission_date',
        'enrollment_status',
        'blood_group',
        'medical_conditions',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
    ];

    /**
     * Get the user associated with the student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the enrollments for the student.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the courses the student is enrolled in.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('enrollment_date', 'status', 'final_grade', 'letter_grade', 'notes')
            ->withTimestamps();
    }
}
