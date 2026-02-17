<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventResource extends Model
{
    protected $fillable = [
        'event_id',
        'title',
        'description',
        'file_path',
        'file_type',
        'url'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
