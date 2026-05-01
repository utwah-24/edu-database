<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasUuids;

    protected $fillable = [
        'year',
        'title',
        'cover_image',
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

    protected $appends = [
        'cover_image_url',
    ];

    public function getCoverImageUrlAttribute(): ?string
    {
        if (!filled($this->cover_image)) {
            return null;
        }

        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://') || str_starts_with($this->cover_image, '/storage/')) {
            return $this->cover_image;
        }

        $base = rtrim((string) config('app.image_base_url', ''), '/');
        if ($base !== '') {
            $trimmed = ltrim($this->cover_image, '/');
            $baseEndsWithStorage = preg_match('#/storage$#i', $base) === 1;
            $relativePath = $baseEndsWithStorage ? preg_replace('#^storage/#i', '', $trimmed) : $trimmed;
            $joined = $baseEndsWithStorage
                ? $base . '/' . $relativePath
                : $base . '/storage/' . $relativePath;

            return preg_replace('#(?<!:)/{2,}#', '/', $joined);
        }

        return Storage::disk('public')->url($this->cover_image);
    }

    // Relationships
    public function summaries()
    {
        return $this->hasMany(EventSummary::class);
    }

    public function themes()
    {
        return $this->hasMany(EventTheme::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class)->orderBy('order');
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
