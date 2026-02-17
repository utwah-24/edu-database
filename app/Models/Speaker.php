<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'title',
        'organization',
        'bio',
        'photo',
        'email',
        'linkedin',
        'twitter',
        'order'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
