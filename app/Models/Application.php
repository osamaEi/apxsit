<?php

namespace App\Models;

use App\Models\User;
use App\Models\Student;
use App\Models\University;
use App\Models\AdmissionStage;
use App\Models\ApplicationFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;
 

    protected $guarded = [];

    protected $table="applications";

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function university()
    {
        return $this->belongsTo(University::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function files()
    {
        return $this->hasMany(ApplicationFiles::class);
    }
    /**
 * Get all status history for this application.
 */
public function statusLogs()
{
    return $this->belongsToMany(AdmissionStage::class, 'application_stage')
        ->withPivot('created_by', 'notes', 'is_completed', 'completed_at', 'completed_by')
        ->withTimestamps()
        ->orderBy('created_at', 'desc');
}
    /**
     * Get document files for this application.
     */
    public function documents()
    {
        return $this->files()->where('file_type', 'document');
    }
    
    /**
     * Get certificate files for this application.
     */
    public function certificates()
    {
        return $this->files()->where('file_type', 'certificate');
    }


    public function stages()
    {
        return $this->belongsToMany(AdmissionStage::class, 'application_stage')
            ->withPivot('is_completed', 'completed_at', 'notes', 'created_by', 'completed_by')
            ->withTimestamps();
    }

    /**
     * Get the current stage of the application
     */
    public function currentStage()
    {
        return $this->stages()
            ->orderBy('application_stage.created_at', 'desc')
            ->first();
    }

    /**
     * Get completed stages of the application
     */
    public function completedStages()
    {
        return $this->stages()
            ->wherePivot('is_completed', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Add a new stage to the application
     * 
     * @param string $stageName
     * @param array $pivotData
     * @return \App\Models\AdmissionStage|null
     */
    public function addStage($stageName, array $pivotData = [])
    {
        $stage = AdmissionStage::where('name', $stageName)->first();
        
        if (!$stage) {
            return null;
        }
        
        $this->stages()->attach($stage->id, $pivotData);
        
        return $stage;
    }

    const STATUSES = [
        'Pending Review' => 'Pending Review',
        'Awaiting App Fees Payment' => 'Awaiting App Fees Payment',
        'Awaiting Conditional Acceptance' => 'Awaiting Conditional Acceptance',
        'Conditional Acceptance' => 'Conditional Acceptance',
        'Awaiting Payment' => 'Awaiting Payment',
        'Paid' => 'Paid',
        'Awaiting Final Acceptance' => 'Awaiting Final Acceptance',
        'Final Acceptance' => 'Final Acceptance',
        'Awaiting Student Card' => 'Awaiting Student Card',
        'Completed' => 'Completed',
        'Invalid' => 'Invalid',
        'Already Registered' => 'Already Registered',
        'Paid Another' => 'Paid Another',
        'Refused' => 'Refused',
        'Awaiting Student' => 'Awaiting Student',
        'Refund Request (Visa Rejected)' => 'Refund Request (Visa Rejected)',
        'Refund Request (Other)' => 'Refund Request (Other)',
        'Refunded' => 'Refunded',
        'Freeze' => 'Freeze',
        'Free Scholarship' => 'Free Scholarship',
        '100% Scholarship' => '100% Scholarship',
        'Cancelled' => 'Cancelled',
        'Quota Full' => 'Quota Full',
        'Student Duplicated' => 'Student Duplicated'
    ];
}
