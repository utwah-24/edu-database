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
        'is_key_speaker',
        'is_session_leader',
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

    protected $casts = [
        'is_key_speaker' => 'boolean',
        'is_session_leader' => 'boolean',
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
