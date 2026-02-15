<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'credits',
        'department_id',
        'teacher_id',
        'semester',
        'academic_year',
        'max_students',
        'level',
        'room',
        'schedule',
        'is_active',
    ];

    protected $casts = [
        'schedule' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the department that owns the course.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the teacher that teaches the course.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the enrollments for the course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the students enrolled in the course.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot('enrollment_date', 'status', 'final_grade', 'letter_grade', 'notes')
            ->withTimestamps();
    }
}
