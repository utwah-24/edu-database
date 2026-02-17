<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Topic extends Model
{
    use HasUuids;

    protected $fillable = [
        'event_id',
        'title',
        'topic_date',
        'content',
        'topic_picture',
        'order'
    ];

    protected $casts = [
        'topic_date' => 'date'
    ];

    // One topic belongs to one event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // One topic can have many speakers
    public function speakers()
    {
        return $this->hasMany(Speaker::class)->orderBy('order');
    }
}
