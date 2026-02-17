<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EventTheme extends Model
{
    use HasUuids;

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
