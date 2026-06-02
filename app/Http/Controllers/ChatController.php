<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\UserStatus;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageRead;
use App\Models\User;
use App\Models\Student; // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\Facades\Image;

class ChatController extends Controller
{
    /**
     * Display the chat index page
     */
    public function index()
    {
        // Get both users and students
        $users = User::where('id', '!=', Auth::id())->get();
        $students = Student::all(); // Get all students
        
        return view('chat.index', compact('users', 'students'));
    }
    public function studentIndex()
    {
        // Ensure the user is authenticated as a student
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }
        
        $student = Auth::guard('student')->user();
        
        // Get all users (administrators/staff) that students can chat with
        $users = User::all();
        
        // Get other students if student-to-student chat is allowed
        // You can modify this based on your application requirements
        $otherStudents = Student::where('id', '!=', $student->id)->get();
        
        return view('chat.index2', compact('users', 'otherStudents', 'student'));
    }
    /**
     * Get all conversations for the authenticated user or student
     */
    public function getConversations()
    {
        // Check if the authenticated user is a student or regular user
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $conversations = $student->conversations()
                ->with(['users' => function ($query) use ($student) {
                    $query->where('users.id', '!=', $student->id);
                }, 'students', 'lastMessage.user', 'lastMessage.student'])
                ->get();
        } else {
            $conversations = Auth::user()->conversations()
                ->with(['users' => function ($query) {
                    $query->where('users.id', '!=', Auth::id());
                }, 'students', 'lastMessage.user', 'lastMessage.student'])
                ->get();
        }

        // Add unread message count for each conversation
        foreach ($conversations as $conversation) {
            // If current user is a student
            if (Auth::guard('student')->check()) {
                $student = Auth::guard('student')->user();
                $conversation->unread_count = Message::where('conversation_id', $conversation->id)
                    ->where(function($query) use ($student) {
                        $query->where('user_id', '!=', null) // Messages from regular users
                            ->orWhere(function($q) use ($student) {
                                $q->where('student_id', '!=', $student->id) // Messages from other students
                                  ->whereNotNull('student_id');
                            });
                    })
                    ->whereDoesntHave('reads', function ($query) use ($student) {
                        $query->where('student_id', $student->id);
                    })
                    ->count();
            } else {
                // Regular user logic (existing)
                $conversation->unread_count = Message::where('conversation_id', $conversation->id)
                    ->where(function($query) {
                        $query->where('user_id', '!=', Auth::id())
                              ->orWhereNotNull('student_id'); // Include messages from students
                    })
                    ->whereDoesntHave('reads', function ($query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->count();
            }
        }

        return response()->json([
            'conversations' => $conversations
        ]);
    }

    /**
     * Get specific conversation details
     */
    public function getConversation($conversationId)
    {
        $conversation = Conversation::with(['users' => function ($query) {
            $query->where('users.id', '!=', Auth::id());
        }, 'students'])->findOrFail($conversationId);
        
        // Check if user or student is part of this conversation
        $authorized = false;
        
        if (Auth::guard('student')->check()) {
            $authorized = $conversation->students()->where('students.id', Auth::guard('student')->id())->exists();
        } else {
            $authorized = $conversation->users()->where('users.id', Auth::id())->exists();
        }
        
        if (!$authorized) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'conversation' => $conversation
        ]);
    }

    /**
     * Get messages for a specific conversation
     */
    public function getMessages($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        
        // Check if user or student is part of this conversation
        $authorized = false;
        $currentId = null;
        
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $authorized = $conversation->students->contains($student->id);
            $currentId = $student->id;
        } else {
            $authorized = $conversation->users->contains(Auth::id());
            $currentId = Auth::id();
        }
        
        if (!$authorized) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()
            ->with('user', 'student') // Include student relationship
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                // Decrypt message body if it exists
                if ($message->body) {
                    try {
                        $message->body = Crypt::decrypt($message->body);
                    } catch (\Exception $e) {
                        // If decryption fails, keep original text
                    }
                }
                return $message;
            });

        // Mark messages as read
        foreach ($messages as $message) {
            if (Auth::guard('student')->check()) {
                $student = Auth::guard('student')->user();
                
                // If message is not from this student
                if ($message->student_id != $student->id) {
                    MessageRead::firstOrCreate([
                        'message_id' => $message->id,
                        'student_id' => $student->id,
                        'read_at' => now(),
                    ]);
                }
            } else {
                // Regular user logic
                if ($message->user_id != Auth::id()) {
                    MessageRead::firstOrCreate([
                        'message_id' => $message->id,
                        'user_id' => Auth::id(),
                        'read_at' => now(),
                    ]);
                }
            }
        }

        return response()->json([
            'messages' => $messages
        ]);
    }

    /**
     * Send a new message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'body' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240',
            'voice_message' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
            'duration' => 'nullable|numeric',
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);
        
        // Check if user or student is part of this conversation
        $authorized = false;
        
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $authorized = $conversation->students->contains($student->id);
        } else {
            $authorized = $conversation->users->contains(Auth::id());
        }
        
        if (!$authorized) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message = new Message();
        $message->conversation_id = $request->conversation_id;
        
        // Set the sender ID based on whether it's a student or user
        if (Auth::guard('student')->check()) {
            $message->student_id = Auth::guard('student')->id();
        } else {
            $message->user_id = Auth::id();
        }
        
        // Encrypt message body if provided
        if ($request->body) {
            $message->body = Crypt::encrypt($request->body);
        }

        // Handle file upload (image)
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $mime = $file->getMimeType();
            
            // Store file
            $path = $file->storeAs('attachments', $filename, 'public');
            
            $message->attachment = $path;
            $message->attachment_type = $mime;
        }
        
        // Handle voice message upload
        if ($request->hasFile('voice_message')) {
            $file = $request->file('voice_message');
            $filename = time() . '_voice_' . $file->getClientOriginalName();
            $path = $file->storeAs('voice_messages', $filename, 'public');
            
            $message->voice_message = $path;
            $message->voice_message_duration = $request->input('duration', 0);
            $message->message_type = 'voice';
        }

        $message->save();

        // For broadcasting, we need to decrypt the message body
        $messageForBroadcast = $message->toArray();
        if ($message->body) {
            try {
                $messageForBroadcast['body'] = Crypt::decrypt($message->body);
            } catch (\Exception $e) {
                // If decryption fails, keep encrypted text
            }
        }
        
        // Set the sender information for broadcast
        if (Auth::guard('student')->check()) {
            $messageForBroadcast['student'] = Auth::guard('student')->user();
        } else {
            $messageForBroadcast['user'] = Auth::user();
        }

        // Broadcast the message to all users/students in the conversation
        // broadcast(new MessageSent($messageForBroadcast))->toOthers();

        // Decrypted version for the response
        if (Auth::guard('student')->check()) {
            $responseMessage = $message->load('student');
        } else {
            $responseMessage = $message->load('user');
        }
        
        if ($responseMessage->body) {
            try {
                $responseMessage->body = Crypt::decrypt($responseMessage->body);
            } catch (\Exception $e) {
                // If decryption fails, keep encrypted text
            }
        }

        return response()->json([
            'message' => $responseMessage
        ]);
    }

    /**
     * Create a one-to-one conversation with a user or student
     */
    public function createConversation(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'student_id' => 'nullable|exists:students,id',
            'type' => 'required|in:user,student', // Add type to know if target is user or student
        ]);

        if ($request->type === 'user') {
            // Create conversation between current user/student and a regular user
            if (Auth::guard('student')->check()) {
                $student = Auth::guard('student')->user();
                $conversation = Conversation::getConversationWithStudent($student->id, $request->user_id, 'user');
            } else {
                $conversation = Conversation::getConversation(Auth::id(), $request->user_id);
            }
        } else {
            // Create conversation between current user/student and a student
            if (Auth::guard('student')->check()) {
                $student = Auth::guard('student')->user();
                $conversation = Conversation::getConversationBetweenStudents($student->id, $request->student_id);
            } else {
                $conversation = Conversation::getConversationWithStudent(Auth::id(), $request->student_id, 'student');
            }
        }

        return response()->json([
            'conversation' => $conversation->load(['users', 'students', 'lastMessage.user', 'lastMessage.student'])
        ]);
    }

    /**
     * Create a group conversation including students
     */
    public function createGroupConversation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'student_ids' => 'nullable|array', // Add student_ids field
            'student_ids.*' => 'exists:students,id',
            'group_avatar' => 'nullable|image|max:2048',
        ]);

        $conversation = Conversation::create([
            'name' => $request->name,
            'is_group' => true,
        ]);

        if ($request->hasFile('group_avatar')) {
            $file = $request->file('group_avatar');
            $filename = time() . '_group_' . $file->getClientOriginalName();
            
            // Store original file without processing
            $path = $file->storeAs('group_avatars', $filename, 'public');
            
            // Remove old avatar if exists
            if ($conversation->avatar) {
                Storage::disk('public')->delete($conversation->avatar);
            }
            
            $conversation->avatar = $path;
            $conversation->save();
        }

        // Add users to the group
        if ($request->has('user_ids') && is_array($request->user_ids)) {
            $userIds = $request->user_ids;
            
            // Add authenticated user if they're a regular user
            if (!Auth::guard('student')->check()) {
                $userIds[] = Auth::id();
            }
            
            if (!empty($userIds)) {
                $conversation->users()->attach($userIds);
            }
        } else if (!Auth::guard('student')->check()) {
            // If no user_ids provided but authenticated as regular user, add self
            $conversation->users()->attach([Auth::id()]);
        }
        
        // Add students to the group
        if ($request->has('student_ids') && is_array($request->student_ids)) {
            $studentIds = $request->student_ids;
            
            // Add authenticated student if they're a student
            if (Auth::guard('student')->check()) {
                $studentIds[] = Auth::guard('student')->id();
            }
            
            if (!empty($studentIds)) {
                $conversation->students()->attach($studentIds);
            }
        } else if (Auth::guard('student')->check()) {
            // If no student_ids provided but authenticated as student, add self
            $conversation->students()->attach([Auth::guard('student')->id()]);
        }

        // Load relationships
        $conversation->load(['users', 'students', 'lastMessage.user', 'lastMessage.student']);

        return response()->json([
            'conversation' => $conversation
        ]);
    }

    /**
     * Get group information
     */
    public function getGroupInfo($conversationId)
    {
        $conversation = Conversation::with(['users', 'students'])->findOrFail($conversationId);
        
        // Check if user or student is part of this conversation
        $authorized = false;
        
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $authorized = $conversation->students->contains($student->id);
        } else {
            $authorized = $conversation->users->contains(Auth::id());
        }
        
        if (!$authorized) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        // Check if it's a group
        if (!$conversation->is_group) {
            return response()->json(['message' => 'Not a group conversation'], 400);
        }
        
        return response()->json([
            'group' => $conversation
        ]);
    }
    
    /**
     * Update group information
     */
    public function updateGroupInfo(Request $request, $conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        
        // Check if user or student is part of this conversation
        $authorized = false;
        
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $authorized = $conversation->students->contains($student->id);
        } else {
            $authorized = $conversation->users->contains(Auth::id());
        }
        
        if (!$authorized) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        // Check if it's a group
        if (!$conversation->is_group) {
            return response()->json(['message' => 'Not a group conversation'], 400);
        }
        
        $request->validate([
            'name' => 'nullable|string|max:255',
            'group_avatar' => 'nullable|image|max:2048',
        ]);
        
        if ($request->has('name')) {
            $conversation->name = $request->name;
        }
        
        // Handle group avatar if provided
        if ($request->hasFile('group_avatar')) {
            $file = $request->file('group_avatar');
            $filename = time() . '_group_' . $file->getClientOriginalName();
            
            // Store original file without processing
            $path = $file->storeAs('group_avatars', $filename, 'public');
            
            // Remove old avatar if exists
            if ($conversation->avatar) {
                Storage::disk('public')->delete($conversation->avatar);
            }
            
            $conversation->avatar = $path;
        }
        
        $conversation->save();
        
        return response()->json([
            'group' => $conversation->load(['users', 'students'])
        ]);
    }

    /**
     * Add users or students to a group
     */
    public function addUsersToGroup(Request $request, $conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        
        // Check if user or student is part of this conversation
        $authorized = false;
        
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $authorized = $conversation->students->contains($student->id);
        } else {
            $authorized = $conversation->users->contains(Auth::id());
        }
        
        if (!$authorized) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        // Check if it's a group
        if (!$conversation->is_group) {
            return response()->json(['message' => 'Not a group conversation'], 400);
        }
        
        $request->validate([
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);
        
        // Add users
        if ($request->has('user_ids')) {
            // Get current users
            $currentUsers = $conversation->users->pluck('id')->toArray();
            
            // Filter new users
            $newUsers = array_diff($request->user_ids, $currentUsers);
            
            if (!empty($newUsers)) {
                $conversation->users()->attach($newUsers);
            }
        }
        
        // Add students
        if ($request->has('student_ids')) {
            // Get current students
            $currentStudents = $conversation->students->pluck('id')->toArray();
            
            // Filter new students
            $newStudents = array_diff($request->student_ids, $currentStudents);
            
            if (!empty($newStudents)) {
                $conversation->students()->attach($newStudents);
            }
        }
        
        return response()->json([
            'group' => $conversation->fresh()->load(['users', 'students'])
        ]);
    }

    /**
     * Remove a user or student from a group
     */
    public function removeUserFromGroup(Request $request, $conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        
        // Check if user or student is part of this conversation
        $authorized = false;
        
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $authorized = $conversation->students->contains($student->id);
        } else {
            $authorized = $conversation->users->contains(Auth::id());
        }
        
        if (!$authorized) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        // Check if it's a group
        if (!$conversation->is_group) {
            return response()->json(['message' => 'Not a group conversation'], 400);
        }
        
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'student_id' => 'nullable|exists:students,id',
            'type' => 'required|in:user,student', // Add type to know if target is user or student
        ]);
        
        if ($request->type === 'user') {
            // Cannot remove yourself
            if (!Auth::guard('student')->check() && $request->user_id == Auth::id()) {
                return response()->json(['message' => 'Cannot remove yourself from group'], 400);
            }
            
            $conversation->users()->detach($request->user_id);
        } else {
            // Cannot remove yourself
            if (Auth::guard('student')->check() && $request->student_id == Auth::guard('student')->id()) {
                return response()->json(['message' => 'Cannot remove yourself from group'], 400);
            }
            
            $conversation->students()->detach($request->student_id);
        }
        
        return response()->json([
            'group' => $conversation->fresh()->load(['users', 'students'])
        ]);
    }

    /**
     * Leave a group
     */
    public function leaveGroup($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        
        // Check if user or student is part of this conversation
        $authorized = false;
        
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $authorized = $conversation->students->contains($student->id);
            
            if ($authorized) {
                $conversation->students()->detach($student->id);
            }
        } else {
            $authorized = $conversation->users->contains(Auth::id());
            
            if ($authorized) {
                $conversation->users()->detach(Auth::id());
            }
        }
        
        if (!$authorized) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        // Check if it's a group
        if (!$conversation->is_group) {
            return response()->json(['message' => 'Not a group conversation'], 400);
        }
        
        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Update user or student online status
     */
    public function updateUserStatus(Request $request)
    {
        $request->validate([
            'is_online' => 'required|boolean',
        ]);

        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $student->update(['is_online' => $request->is_online]);
            
            // Optionally, broadcast student status change
            // broadcast(new StudentStatus($student))->toOthers();
        } else {
            $user = Auth::user();
            $user->update(['is_online' => $request->is_online]);
            
            broadcast(new UserStatus($user))->toOthers();
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Get users and students available to add to a group
     */
    public function getAvailableUsers($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        
        // Check if user or student is part of this conversation
        $authorized = false;
        
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $authorized = $conversation->students()->where('students.id', $student->id)->exists();
        } else {
            $authorized = $conversation->users()->where('users.id', Auth::id())->exists();
        }
        
        if (!$authorized) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        // Get users who are not in the conversation
        $currentUserIds = $conversation->users->pluck('id')->toArray();
        $availableUsers = User::whereNotIn('id', $currentUserIds)->get();
        
        // Get students who are not in the conversation
        $currentStudentIds = $conversation->students->pluck('id')->toArray();
        $availableStudents = Student::whereNotIn('id', $currentStudentIds)->get();
        
        return response()->json([
            'users' => $availableUsers,
            'students' => $availableStudents
        ]);
    }
}