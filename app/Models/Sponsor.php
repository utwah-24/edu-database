<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Sponsor extends Model
{
    use HasUuids;

    protected $fillable = [
        'event_id',
        'name',
        'tier',
        'logo',
        'website',
        'description',
        'order'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
