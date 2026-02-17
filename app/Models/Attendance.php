<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Attendance extends Model
{
    use HasUuids;

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
        'organization',
        'registration_type',
        'checked_in',
        'checked_in_at'
    ];

    protected $casts = [
        'checked_in' => 'boolean',
        'checked_in_at' => 'datetime'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
