<?php

namespace App\Models;

use App\Models\User;
use App\Models\Degree;
use App\Models\Message;
use App\Models\Program;
use App\Models\Application;
use App\Models\MessageRead;
use App\Models\Conversation;
use Nnjeim\World\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable

{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'academic_year',
        'study_country_id',
        'employee_id',
        'is_transfer',
        
        // Passport information
        'date_of_birth',
        'passport_id',
        'passport_issue_date',
        'passport_expiry_date',
        'needs_visa_support',
        
        // Personal information
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'country_of_residence_id',
        'nationality_id',
        'gender',
        'marital_status',
        
        // Family information
        'father_name',
        'mother_name',
        'emergency_email',
        'emergency_phone',
        
        // Education information
        'applying_degree_id',
        'high_school_name',
        'high_school_country_id',
        'gpa',
        
        // Document paths
        'photo_path',
        'passport_path',
        'transcript_path',
        'diploma_path',
        'denklik_path',
        'certificate_path',
        'other_documents_path',
        
        // Status and meta
        'status',
        'notes',
        'application_date',
        'password',
        'processed_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'passport_issue_date' => 'date',
        'passport_expiry_date' => 'date',
        'is_transfer' => 'boolean',
        'needs_visa_support' => 'boolean',
        'application_date' => 'date',
    ];

    /**
     * Get the user that is responsible for this student.
     */
    public function responsibleEmployee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    
    /**
     * Get the user that processed this student's application.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the study country of the student.
     */
    public function studyCountry()
    {
        return $this->belongsTo(Country::class, 'study_country_id');
    }

    /**
     * Get the country of residence of the student.
     */
    public function countryOfResidence()
    {
        return $this->belongsTo(Country::class, 'country_of_residence_id');
    }

    /**
     * Get the nationality country of the student.
     */
    public function nationality()
    {
        return $this->belongsTo(Country::class, 'nationality_id');
    }

    /**
     * Get the high school country of the student.
     */
    public function highSchoolCountry()
    {
        return $this->belongsTo(Country::class, 'high_school_country_id');
    }

    /**
     * Get the program the student is applying to.
     */
    public function applyingDegree()
    {
        return $this->belongsTo(Degree::class, 'applying_degree_id');
    }

    /**
     * Get available genders for dropdown.
     */
    public static function getGenders()
    {
        return [
            'Male' => 'Male',
            'Female' => 'Female',
            'Other' => 'Other',
        ];
    }

    /**
     * Get available marital statuses for dropdown.
     */
    public static function getMaritalStatuses()
    {
        return [
            'Single' => 'Single',
            'Married' => 'Married',
            'Divorced' => 'Divorced',
            'Widowed' => 'Widowed',
        ];
    }

    /**
     * Get available application statuses for dropdown.
     */
    public static function getStatuses()
    {
        return [

            'Accepted' => 'Accepted',

            'Cancelled' => 'Cancelled',
        ];
    }

    /**
     * Get full name of the student.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Scope a query to only include students with a specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include students from a specific study country.
     */
    public function scopeStudyCountry($query, $countryId)
    {
        return $query->where('study_country_id', $countryId);
    }

    /**
     * Scope a query to only include students assigned to a specific employee.
     */
    public function scopeEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope a query to only include students that need visa support.
     */
    public function scopeNeedingVisaSupport($query)
    {
        return $query->where('needs_visa_support', true);
    }

    /**
     * Scope a query to only include transfer students.
     */
    public function scopeTransfer($query)
    {
        return $query->where('is_transfer', true);
    }

    /**
     * Scope a query to only include students with documents.
     */
    public function scopeWithDocuments($query)
    {
        return $query->whereNotNull('photo_path')
                    ->whereNotNull('passport_path')
                    ->whereNotNull('transcript_path');
    }

    /**
     * Scope a query to only include students without complete documents.
     */
    public function scopeWithoutCompleteDocuments($query)
    {
        return $query->where(function($q) {
            $q->whereNull('photo_path')
              ->orWhereNull('passport_path')
              ->orWhereNull('transcript_path');
        });
    }


    public function applications() {

        return $this->hasMany(Application::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_student')
            ->withTimestamps();
    }

    /**
     * Get the messages sent by this student.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the message reads by this student.
     */
    public function messageReads()
    {
        return $this->hasMany(MessageRead::class);
    }
}