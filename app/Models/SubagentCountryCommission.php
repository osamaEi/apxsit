<?php

namespace App\Models;

use Nnjeim\World\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubagentCountryCommission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'country_id',
        'commission_rate',
    ];

    /**
     * Get the subagent that owns the commission.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the country associated with this commission.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}