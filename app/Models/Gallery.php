<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'event_id',
        'title',
        'image_path',
        'caption',
        'order'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
