{{-- resources/views/chat/partials/message.blade.php --}}
<div id="message-{{ $message->id }}" class="message {{ Auth::id() === $message->user_id ? 'message-outgoing' : 'message-incoming' }} d-flex flex-column">
    @if(Auth::id() !== $message->user_id)
    <div class="d-flex align-items-center mb-1">
        <img src="{{ $message->user->avatar ?? asset('images/default-avatar.png') }}" alt="{{ $message->user->name }}" class="avatar-sm me-2">
        <span class="fw-bold">{{ $message->user->name }}</span>
    </div>
    @endif
    
    <div class="message-bubble">
        @if($message->message_type === 'voice')
            <div class="voice-message-container">
                <div class="voice-message-play-btn" data-url="{{ Storage::url($message->voice_message) }}">
                    <i class="fas fa-play"></i>
                </div>
                <div class="voice-message-waveform">
                    <div class="voice-waveform-placeholder"></div>
                </div>
                <div class="voice-message-duration">
                    {{ formatSeconds($message->voice_message_duration) }}
                </div>
            </div>
        @else
            @if($message->body)
                <div class="message-text">{{ $message->body }}</div>
            @endif
        @endif
        
        @if($message->attachment)
            <div class="message-attachment mt-2">
                @if(Str::startsWith($message->attachment_type, 'image/'))
                    <img src="{{ Storage::url($message->attachment) }}" alt="Attachment" class="image-attachment" data-bs-toggle="modal" data-bs-target="#imageModal" data-src="{{ Storage::url($message->attachment) }}">
                @else
                    <div class="attachment-file">
                        <a href="{{ Storage::url($message->attachment) }}" class="btn btn-sm btn-outline-primary" download>
                            <i class="fas fa-download me-1"></i>
                            {{ Str::afterLast($message->attachment, '/') }}
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
    
    <div class="message-meta d-flex align-items-center mt-1 {{ Auth::id() === $message->user_id ? 'justify-content-end' : 'justify-content-start' }}">
        <small class="message-time">
            {{ $message->created_at->format('g:i A') }}
        </small>
        
        @if(Auth::id() === $message->user_id)
            <small class="ms-1 message-status">
                @if($message->reads->count() > 0)
                    <i class="fas fa-check-double message-read"></i>
                @else
                    <i class="fas fa-check"></i>
                @endif
            </small>
        @endif
    </div>
</div>

{{-- resources/views/chat/partials/conversation.blade.php --}}
<a href="#" class="conversation-item list-group-item list-group-item-action d-flex align-items-center p-3" 
   data-conversation-id="{{ $conversation->id }}"
   data-is-group="{{ $conversation->is_group ? 'true' : 'false' }}">
    
    <div class="position-relative me-3">
        @if($conversation->is_group)
            <img src="{{ $conversation->avatar ? Storage::url($conversation->avatar) : asset('images/default-group.png') }}" 
                 alt="{{ $conversation->name }}" class="avatar">
        @else
            @php $otherUser = $conversation->users->where('id', '!=', Auth::id())->first(); @endphp
            @if($otherUser)
                <img src="{{ $otherUser->avatar ?? asset('images/default-avatar.png') }}" 
                     alt="{{ $otherUser->name }}" class="avatar">
                     
                @if($otherUser->is_online)
                    <span class="status-indicator status-online"></span>
                @endif
            @endif
        @endif
    </div>
    
    <div class="flex-grow-1 text-truncate">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-truncate">
                @if($conversation->is_group)
                    {{ $conversation->name }}
                @else
                    @php $otherUser = $conversation->users->where('id', '!=', Auth::id())->first(); @endphp
                    {{ $otherUser->name ?? 'Unknown User' }}
                @endif
            </h6>
            
            <small class="text-muted ms-auto me-2">
                {{ $conversation->lastMessage ? $conversation->lastMessage->created_at->diffForHumans(null, true) : '' }}
            </small>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <p class="text-muted text-truncate mb-0 small">
                @if($conversation->lastMessage)
                    @if($conversation->lastMessage->user_id === Auth::id())
                        <span class="text-muted me-1">You:</span>
                    @elseif($conversation->is_group)
                        <span class="text-muted me-1">{{ $conversation->lastMessage->user->name }}:</span>
                    @endif
                    
                    @if($conversation->lastMessage->message_type === 'voice')
                        <i class="fas fa-microphone me-1"></i> Voice message
                    @elseif($conversation->lastMessage->attachment && !$conversation->lastMessage->body)
                        <i class="fas fa-paperclip me-1"></i> Attachment
                    @else
                        {{ Str::limit($conversation->lastMessage->body, 30) }}
                    @endif
                @else
                    <span>No messages yet</span>
                @endif
            </p>
            
            @if(isset($unreadCount) && $unreadCount > 0)
                <div class="unread-count ms-2">{{ $unreadCount }}</div>
            @endif
        </div>
    </div>
</a>

{{-- resources/views/chat/partials/user-selection-item.blade.php --}}
<div class="user-selection-item">
    <div class="form-check d-flex align-items-center">
        <input class="form-check-input me-3" type="checkbox" value="{{ $user->id }}" id="user-{{ $user->id }}" name="user_ids[]">
        <label class="form-check-label d-flex align-items-center flex-grow-1" for="user-{{ $user->id }}">
            <img src="{{ $user->avatar ?? asset('images/default-avatar.png') }}" alt="{{ $user->name }}" class="avatar-sm me-2">
            <div>
                <div class="fw-medium">{{ $user->name }}</div>
                <small class="text-muted">{{ $user->email }}</small>
            </div>
        </label>
    </div>
</div>

{{-- resources/views/chat/partials/group-member-item.blade.php --}}
<div class="d-flex align-items-center justify-content-between py-2 border-bottom">
    <div class="d-flex align-items-center">
        <img src="{{ $user->avatar ?? asset('images/default-avatar.png') }}" alt="{{ $user->name }}" class="avatar-sm me-2">
        <div>
            <div class="fw-medium">{{ $user->name }}</div>
            <small class="text-muted">
                @if($user->id === Auth::id())
                    You
                @else
                    {{ $user->email }}
                @endif
            </small>
        </div>
    </div>
    
    @if($user->id !== Auth::id())
        <button class="btn btn-sm btn-outline-danger remove-member-btn" data-user-id="{{ $user->id }}">
            <i class="fas fa-times"></i>
        </button>
    @else
        <span class="badge bg-primary">You</span>
    @endif
</div>

{{-- Helper function for formatting seconds to MM:SS --}}
@php
function formatSeconds($seconds) {
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;
    return sprintf('%02d:%02d', $minutes, $remainingSeconds);
}
@endphp