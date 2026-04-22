<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EventProgramme extends Model
{
    use HasUuids;

    protected $fillable = [
        'event_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'speaker',
        'event_pdf',
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
