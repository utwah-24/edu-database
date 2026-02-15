<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'enrollment_date',
        'status',
        'final_grade',
        'letter_grade',
        'notes',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'final_grade' => 'decimal:2',
    ];

    /**
     * Get the student that owns the enrollment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course that owns the enrollment.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the grades for the enrollment.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
