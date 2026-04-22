<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Gallery extends Model
{
    use HasUuids;

    protected $fillable = [
        'event_id',
        'url',
        'order',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
