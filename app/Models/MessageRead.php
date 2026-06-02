<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageRead extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'user_id',
        'student_id',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the message that was read.
     */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * Get the user that read the message (if it was read by a regular user).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the student that read the message (if it was read by a student).
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Check if the message was read by a student.
     */
    public function isReadByStudent()
    {
        return !is_null($this->student_id);
    }
}