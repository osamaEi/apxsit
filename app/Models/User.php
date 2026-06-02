<?php

namespace App\Models;

use App\Models\Message;
use App\Models\MessageRead;
use App\Models\Conversation;
use App\Models\Notification;
use Nnjeim\World\Models\Country;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\SubagentCountryCommission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country_id',
        'phone',
        'role',
        'type',
        'parent_id',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
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
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function contractedCountries()
    {
        return $this->belongsToMany(Country::class, 'subagent_country_commissions')
                    ->withPivot('commission_rate')
                    ->withTimestamps();
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the subagents for this user.
     */
    public function subagents()
    {
        return $this->hasMany(User::class, 'parent_id');
    }


    public function isSubagent()
    {
        return !is_null($this->parent_id);
    }

    /**
     * Check if user has subagents.
     */
    public function hasSubagents()
    {
        return $this->subagents()->count() > 0;
    }

    /**
     * Check if user is an individual.
     */
    public function isIndividual()
    {
        return $this->type === 'individual';
    }

    /**
     * Check if user is a company.
     */
    public function isCompany()
    {
        return $this->type === 'company';
    }
    /**
     * Get the students created by this user.
     */
    public function createdStudents()
    {
        return $this->hasMany(Student::class, 'created_by');
    }
    public function countryCommissions()
    {
        return $this->hasMany(SubagentCountryCommission::class, 'user_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the student registrations processed by this user.
     */
    public function processedRegistrations()
    {
        return $this->hasMany(StudentRegistration::class, 'user_id');
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Define possible user roles
     */
    const ROLE_ADMIN = 'Admin';
    const ROLE_SALES = 'Sales';
    const ROLE_REGISTER = 'Register';
    const ROLE_EMPLOYEE = 'Employee';

    /**
     * Get all available roles
     */
    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_SALES,
            self::ROLE_REGISTER,
            self::ROLE_EMPLOYEE,
        ];
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }

    /**
     * Get the user's unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    /**
     * Get the count of unread notifications.
     */
    public function unreadNotificationsCount()
    {
        return $this->unreadNotifications()->count();
    }

    public function conversations()
{
    return $this->belongsToMany(Conversation::class, 'conversation_user')
        ->withTimestamps();
}

/**
 * Get all messages sent by the user.
 */
public function messages()
{
    return $this->hasMany(Message::class);
}

/**
 * Get all message read receipts for the user.
 */
public function messageReads()
{
    return $this->hasMany(MessageRead::class);
}
}