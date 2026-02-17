<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Speaker extends Model
{
    use HasUuids;

    protected $fillable = [
        'event_id',
        'topic_id',
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

    // Speaker belongs to one topic
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
