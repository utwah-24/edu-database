<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FAQ extends Model
{
    use HasUuids;
    
    protected $table = 'faqs';
    
    protected $fillable = [
        'event_id',
        'question',
        'answer',
        'order'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
