<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'student_id',
        'body',
        'attachment',
        'attachment_type',
        'voice_message',
        'voice_message_duration',
        'message_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'voice_message_duration' => 'float',
    ];

    public function getBodyAttribute($value)
    {
        if (!$value) return $value;
        try {
            $decrypted = Crypt::decryptString($value);
            // Strip PHP serialize wrapper if present: s:N:"...";
            if (preg_match('/^s:\d+:"(.*)";$/s', $decrypted, $m)) {
                return $m[1];
            }
            return $decrypted;
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Get the conversation that owns the message.
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the user that sent the message (if it was sent by a regular user).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the student that sent the message (if it was sent by a student).
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the read receipts for this message.
     */
    public function reads()
    {
        return $this->hasMany(MessageRead::class);
    }

    /**
     * Check if the message was sent by a student.
     */
    public function isSentByStudent()
    {
        return !is_null($this->student_id);
    }

    /**
     * Get the sender name (user or student)
     */
    public function getSenderNameAttribute()
    {
        if ($this->student_id) {
            return $this->student->name ?? 'Unknown Student';
        } else {
            return $this->user->name ?? 'Unknown User';
        }
    }

    /**
     * Get the sender full data (user or student)
     */
    public function getSenderAttribute()
    {
        if ($this->student_id) {
            return $this->student;
        } else {
            return $this->user;
        }
    }

    /**
     * Returns true if the given user or student has read this message
     */
    public function isReadBy($id, $isStudent = false)
    {
        $query = $this->reads();
        
        if ($isStudent) {
            $query->where('student_id', $id);
        } else {
            $query->where('user_id', $id);
        }
        
        return $query->exists();
    }
}