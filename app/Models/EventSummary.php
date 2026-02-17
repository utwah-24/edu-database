<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSummary extends Model
{
    protected $fillable = [
        'event_id',
        'summary'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
