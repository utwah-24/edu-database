<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventProgramme extends Model
{
    protected $fillable = [
        'event_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'speaker',
        'order'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
