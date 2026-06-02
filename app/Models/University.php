<?php

namespace App\Models;

use App\Models\Program;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class University extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'logo',
        'city_id',
        'country_id',
        'address',
        'description',
        'is_active',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the country that owns the university.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the city that owns the university.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Scope a query to only include active universities.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
// In app/Models/University.php

public function programs()
{
    return $this->hasMany(Program::class);
}
    /**
     * Get university types for dropdown lists.
     */
    public static function getTypes()
    {
        return [
            'public' => 'Public',
            'private' => 'Private',
            'community' => 'Community College',
            'technical' => 'Technical Institute',
        ];
    }
}