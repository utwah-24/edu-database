<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'year',
        'title',
        'location',
        'start_date',
        'end_date',
        'is_published'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_published' => 'boolean'
    ];

    // Relationships
    public function summaries()
    {
        return $this->hasMany(EventSummary::class);
    }

    public function themes()
    {
        return $this->hasMany(EventTheme::class);
    }

    public function programmes()
    {
        return $this->hasMany(EventProgramme::class)->orderBy('order');
    }

    public function resources()
    {
        return $this->hasMany(EventResource::class);
    }

    public function speakers()
    {
        return $this->hasMany(Speaker::class)->orderBy('order');
    }

    public function faqs()
    {
        return $this->hasMany(FAQ::class)->orderBy('order');
    }

    public function media()
    {
        return $this->hasMany(Media::class)->orderBy('order');
    }

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class)->orderBy('order');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class)->orderBy('order');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
