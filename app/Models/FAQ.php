<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
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
