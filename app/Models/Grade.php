<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'enrollment_id',
        'assignment_name',
        'assignment_type',
        'grade',
        'max_grade',
        'weight',
        'grade_date',
        'remarks',
    ];

    protected $casts = [
        'grade' => 'decimal:2',
        'max_grade' => 'decimal:2',
        'weight' => 'decimal:2',
        'grade_date' => 'date',
    ];

    /**
     * Get the enrollment that owns the grade.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
}
