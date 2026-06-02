<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\City;
use App\Models\User;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'university_id',
        'country_id',
        'city_id',
        'created_by',
        'title',
        'description',
        'image',
        'is_active',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the university that owns the announcement.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the country that owns the announcement.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the city that owns the announcement.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the user that created the announcement.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include active announcements.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include published announcements.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Check if announcement is published.
     */
    public function isPublished()
    {
        return $this->published_at && $this->published_at->lte(now());
    }

    /**
     * Get the status label attribute.
     */
    public function getStatusLabelAttribute()
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        
        if (!$this->published_at) {
            return 'Draft';
        }
        
        if ($this->published_at->gt(now())) {
            return 'Scheduled';
        }
        
        return 'Published';
    }

    /**
     * Get the status color attribute.
     */
    public function getStatusColorAttribute()
    {
        if (!$this->is_active) {
            return 'danger';
        }
        
        if (!$this->published_at) {
            return 'warning';
        }
        
        if ($this->published_at->gt(now())) {
            return 'info';
        }
        
        return 'success';
    }
}