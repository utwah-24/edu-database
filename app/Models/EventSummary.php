<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EventSummary extends Model
{
    use HasUuids;

    protected $fillable = [
        'event_id',
        'summary'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
