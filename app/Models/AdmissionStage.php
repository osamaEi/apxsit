<?php

namespace App\Models;

use App\Models\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmissionStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'order',
        'color',
        'is_active'
    ];

    /**
     * Get all applications that are currently in this stage.
     */
    public function applications()
    {
        return $this->belongsToMany(Application::class, 'application_stage')
            ->withPivot('created_by', 'notes', 'is_completed', 'completed_at', 'completed_by')
            ->withTimestamps();
    }

    /**
     * Get the next stage in the admission process.
     */
    public function nextStage()
    {
        return $this->where('order', '>', $this->order)
            ->where('is_active', true)
            ->orderBy('order')
            ->first();
    }

    /**
     * Get the previous stage in the admission process.
     */
    public function previousStage()
    {
        return $this->where('order', '<', $this->order)
            ->where('is_active', true)
            ->orderBy('order', 'desc')
            ->first();
    }
}