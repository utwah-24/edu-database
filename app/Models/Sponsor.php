<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'tier',
        'logo',
        'website',
        'description',
        'order'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
