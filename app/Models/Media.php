<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'event_id',
        'title',
        'type',
        'file_path',
        'thumbnail',
        'description',
        'order'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
