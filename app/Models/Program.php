<?php

namespace App\Models;

use App\Models\University;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'university_id',
        'department',
        'degree',
        'language',
        'before_discount',
        'after_discount',
        'cash_discount',
        'deposit_payment',
        'shift_type',
        'siblings_discount',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'before_discount' => 'decimal:2',
        'after_discount' => 'decimal:2',
        'cash_discount' => 'decimal:2',
        'deposit_payment' => 'decimal:2',
        'siblings_discount' => 'decimal:2',
    ];

    /**
     * Get the university that owns the program.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get available degrees for dropdown.
     */
    public static function getDegrees()
    {
        return [
            'Bachelor' => 'Bachelor',
            'Master' => 'Master',
            'PhD' => 'PhD',
            'Diploma' => 'Diploma',
            'Certificate' => 'Certificate',
        ];
    }

    /**
     * Get available languages for dropdown.
     */
    public static function getLanguages()
    {
        return [
            'English' => 'English',
            'Turkish' => 'Turkish',
            'Arabic' => 'Arabic',
            'French' => 'French',
            'German' => 'German',
            'Russian' => 'Russian',
            'Spanish' => 'Spanish',
            'Chinese' => 'Chinese',
        ];
    }

    /**
     * Get available shift types for dropdown.
     */
    public static function getShiftTypes()
    {
        return [
            'Day' => 'Day',
            'Evening' => 'Evening',
            'Weekend' => 'Weekend', 
            'Online' => 'Online',
            'Hybrid' => 'Hybrid',
        ];
    }

    /**
     * Get available statuses for dropdown.
     */
    public static function getStatuses()
    {
        return [
            'Active' => 'Active',
            'Inactive' => 'Inactive',
            'Coming Soon' => 'Coming Soon',
            'Full' => 'Full',
        ];
    }

    /**
     * Calculate discount percentage.
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->before_discount <= 0) {
            return 0;
        }
        
        $discount = $this->before_discount - $this->after_discount;
        $percentage = ($discount / $this->before_discount) * 100;
        
        return round($percentage, 2);
    }

    /**
     * Scope a query to only include active programs.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}