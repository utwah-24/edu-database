<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EventResource extends Model
{
    use HasUuids;

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
