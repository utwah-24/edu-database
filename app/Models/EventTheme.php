<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTheme extends Model
{
    protected $fillable = [
        'event_id',
        'theme',
        'description'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
