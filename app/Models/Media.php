<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Media extends Model
{
    use HasUuids;

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
