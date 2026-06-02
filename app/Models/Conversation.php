<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'is_group',
        'avatar'
    ];

    /**
     * The users that belong to the conversation.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_user')
            ->withTimestamps();
    }

    /**
     * The students that belong to the conversation.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'conversation_student')
            ->withTimestamps();
    }

    /**
     * Get all messages for this conversation.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the last message of the conversation.
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    /**
     * Get or create a conversation between two users.
     */
    public static function getConversation($userId1, $userId2)
    {
        // Get conversations that both users are in
        $conversations = self::whereHas('users', function ($query) use ($userId1) {
            $query->where('users.id', $userId1);
        })->whereHas('users', function ($query) use ($userId2) {
            $query->where('users.id', $userId2);
        })->where('is_group', false)->get();

        // Filter conversations to find one that has exactly these two users
        foreach ($conversations as $conversation) {
            if ($conversation->users->count() == 2) {
                return $conversation;
            }
        }

        // No conversation found, create a new one
        $conversation = self::create([
            'is_group' => false,
        ]);

        // Attach both users
        $conversation->users()->attach([$userId1, $userId2]);

        return $conversation;
    }

    /**
     * Get or create a conversation between a user and a student.
     */
    public static function getConversationWithStudent($id, $targetId, $targetType)
    {
        if ($targetType === 'user') {
            // User-to-student conversation
            $conversations = self::whereHas('users', function ($query) use ($targetId) {
                $query->where('users.id', $targetId);
            })->whereHas('students', function ($query) use ($id) {
                $query->where('students.id', $id);
            })->where('is_group', false)->get();
        } else {
            // Student-to-user conversation
            $conversations = self::whereHas('users', function ($query) use ($id) {
                $query->where('users.id', $id);
            })->whereHas('students', function ($query) use ($targetId) {
                $query->where('students.id', $targetId);
            })->where('is_group', false)->get();
        }

        // Filter conversations to find one that has exactly one user and one student
        foreach ($conversations as $conversation) {
            if ($conversation->users->count() == 1 && $conversation->students->count() == 1) {
                return $conversation;
            }
        }

        // No conversation found, create a new one
        $conversation = self::create([
            'is_group' => false,
        ]);

        if ($targetType === 'user') {
            // Attach user and student
            $conversation->users()->attach([$targetId]);
            $conversation->students()->attach([$id]);
        } else {
            // Attach user and student
            $conversation->users()->attach([$id]);
            $conversation->students()->attach([$targetId]);
        }

        return $conversation;
    }

    /**
     * Get or create a conversation between two students.
     */
    public static function getConversationBetweenStudents($studentId1, $studentId2)
    {
        // Get conversations that both students are in
        $conversations = self::whereHas('students', function ($query) use ($studentId1) {
            $query->where('students.id', $studentId1);
        })->whereHas('students', function ($query) use ($studentId2) {
            $query->where('students.id', $studentId2);
        })->where('is_group', false)->get();

        // Filter conversations to find one that has exactly these two students
        foreach ($conversations as $conversation) {
            if ($conversation->students->count() == 2 && $conversation->users->count() == 0) {
                return $conversation;
            }
        }

        // No conversation found, create a new one
        $conversation = self::create([
            'is_group' => false,
        ]);

        // Attach both students
        $conversation->students()->attach([$studentId1, $studentId2]);

        return $conversation;
    }
}