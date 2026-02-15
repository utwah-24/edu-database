<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'employee_id',
        'phone',
        'specialization',
        'hire_date',
        'employment_type',
        'bio',
        'office_location',
        'is_active',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user associated with the teacher.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department of the teacher.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the courses taught by the teacher.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
