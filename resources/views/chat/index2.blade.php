@extends('students.index')

@section('content')
    
    <!-- Custom CSS -->
{{-- @include('chat.style') --}}

  
    <div class="container">
        <div class="row">
            <!-- Conversations Sidebar -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Chats</h5>
                            @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register' ||  auth()->user()->role == 'Sales')

                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newGroupModal">
                                <i class="fas fa-users-cog"></i> New Group
                            </button>

                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush conversation-list">
                            <!-- Conversations will be loaded here via AJAX -->
                        </ul>
                    </div>
                </div>

                {{-- Display users list, visible for both regular users and students --}}

                @php

                 $resgisters = \App\Models\User::where('role','Register')->get();

                @endphp

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Registers</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush user-list">
                            @foreach($resgisters as $user)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle me-2" width="40" height="40" alt="User avatar">
                                        @else
                                        <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                           
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary start-conversation" data-user-id="{{ $user->id }}" data-type="user">
                                        <i class="fas fa-comment"></i>
                                    </button>
                                </div>
                            </li>
                            @endforeach 
                        </ul>
                    </div>
                </div>

           

                {{-- Display students list --}}
                @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register' )

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Students</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush student-list">
                            @foreach($students as $student)
                            {{-- Don't display current student if logged in as student --}}
                            @if(!Auth::guard('student')->check() || Auth::guard('student')->id() != $student->id)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        @if($student->avatar)
                                        <img src="{{ asset('storage/' . $student->avatar) }}" class="rounded-circle me-2" width="40" height="40" alt="Student avatar">
                                        @else
                                        <div class="bg-info rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $student->first_name }} {{ $student->last_name }}</h6>
                                           
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-info start-conversation" data-student-id="{{ $student->id }}" data-type="student">
                                        <i class="fas fa-comment"></i>
                                    </button>
                                </div>
                            </li>
                            @endif
                            @endforeach 
                        </ul>
                    </div>
                </div>

                @endif
            </div>
            
            <!-- Chat Area -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header chat-header">
                        <!-- Chat header will be populated via AJAX -->
                        <div class="text-center text-muted initial-message">
                            <p>Select a conversation to start chatting</p>
                        </div>
                    </div>
                    <div class="card-body chat-body" style="height: 450px; overflow-y: auto;">
                        <div id="messages-container">
                            <!-- Messages will be loaded here via AJAX -->
                        </div>
                    </div>
                    <div class="card-footer chat-footer d-none">
                        <form id="message-form" enctype="multipart/form-data">
                            <input type="hidden" id="conversation-id" name="conversation_id">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="message-input" name="body" placeholder="Type a message...">
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>
                            <div class="d-flex">
                                <!-- File upload -->
                                <div class="me-2">
                                    <label for="attachment" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-paperclip"></i> Attach File
                                    </label>
                                    <input type="file" id="attachment" name="attachment" class="d-none">
                                    <span id="attachment-name" class="small text-muted ms-2"></span>
                                </div>
                                
                                <!-- Voice message recording -->
                                <div>
                                    <button type="button" id="record-voice" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-microphone"></i> Record Voice
                                    </button>
                                    <button type="button" id="stop-recording" class="btn btn-sm btn-outline-danger d-none">
                                        <i class="fas fa-stop"></i> Stop Recording
                                    </button>
                                    <span id="recording-status" class="small text-danger ms-2"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Group Modal -->
    <div class="modal fade" id="newGroupModal" tabindex="-1" aria-labelledby="newGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newGroupModalLabel">New Group</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create-group-form" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="group-name" class="form-label">Group Name</label>
                            <input type="text" class="form-control" id="group-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="group-avatar" class="form-label">Group Avatar (Optional)</label>
                            <input type="file" class="form-control" id="group-avatar" name="group_avatar" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Users</label>
                            <div class="border p-2 rounded" style="max-height: 150px; overflow-y: auto; border-color: #444 !important;">
                                @foreach($users as $user)
                                {{-- Don't include current user if logged in as regular user --}}
                                @if(Auth::guard('student')->check() || Auth::id() != $user->id)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="user_ids[]" value="{{ $user->id }}" id="user-{{ $user->id }}">
                                    <label class="form-check-label" for="user-{{ $user->id }}">
                                        {{ $user->name }}
                                    </label>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        {{-- <div class="mb-3">
                            <label class="form-label">Select Students</label>
                            <div class="border p-2 rounded" style="max-height: 150px; overflow-y: auto; border-color: #444 !important;">
                                @foreach($students as $student)
                                @if(!Auth::guard('student')->check() || Auth::guard('student')->id() != $student->id)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="student-{{ $student->id }}">
                                    <label class="form-check-label" for="student-{{ $student->id }}">
                                        {{ $student->name }}
                                    </label>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div> --}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="create-group-btn" class="btn btn-primary">Create Group</button>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register' ||  auth()->user()->role == 'Sales')
    <div class="modal fade" id="groupInfoModal" tabindex="-1" aria-labelledby="groupInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupInfoModalLabel">Group Info</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="group-info-content">
                    <!-- Group info will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Add Members Modal -->
    <div class="modal fade" id="addMembersModal" tabindex="-1" aria-labelledby="addMembersModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMembersModalLabel">Add Members</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-members-form">
                        <input type="hidden" id="add-members-conversation-id" name="conversation_id">
                        <div class="mb-3">
                            <label class="form-label">Add Users</label>
                            <div id="available-users-container">
                                <!-- Available users will be loaded here via AJAX -->
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Add Students</label>
                            <div id="available-students-container">
                                <!-- Available students will be loaded here via AJAX -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="add-members-btn" class="btn btn-primary">Add Members</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
  

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    

    <!-- Main Chat JavaScript -->
    <script>
        $(document).ready(function() {
            // Voice recording variables
            let mediaRecorder;
            let audioChunks = [];
            let recordingTime = 0;
            let recordingInterval;
            let isRecording = false;
            
            // Active conversation variables
            let activeConversationId = null;
            let activeConversationIsGroup = false;
            
            // Check if current user is a student
            const isStudent = {{ Auth::guard('student')->check() ? 'true' : 'false' }};
            const currentUserId = {{ Auth::guard('student')->check() ? Auth::guard('student')->id() : Auth::id() }};
            
            // Load conversations on page load
            loadConversations();
            
            // Set up polling for new messages and conversation updates
            setInterval(function() {
                if (activeConversationId) {
                    loadMessages(activeConversationId);
                }
                loadConversations();
            }, 10000); // Poll every 10 seconds
            
            // AJAX to get conversations
            function loadConversations() {
                $.ajax({
                    url: '/chat/conversations',
                    type: 'GET',
                    success: function(response) {
                        renderConversations(response.conversations);
                    },
                    error: function(error) {
                        console.error('Error loading conversations:', error);
                    }
                });
            }
            
            // Render conversations list
            function renderConversations(conversations) {
                let html = '';
                if (conversations.length === 0) {
                    html = '<li class="list-group-item text-center text-muted">No conversations yet</li>';
                } else {
                    conversations.forEach(function(conversation) {
                        let isActive = activeConversationId == conversation.id ? 'active bg-light' : '';
                        let unreadBadge = conversation.unread_count > 0 ? 
                            `<span class="badge bg-danger rounded-pill">${conversation.unread_count}</span>` : '';
                        
                        let displayName = '';
                        let avatar = '';
                        
                        if (conversation.is_group) {
                            displayName = conversation.name;
                            if (conversation.avatar) {
                                avatar = `<img src="/storage/${conversation.avatar}" class="rounded-circle me-2" width="40" height="40" alt="Group avatar">`;
                            } else {
                                avatar = `<div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                            <i class="fas fa-users"></i>
                                          </div>`;
                            }
                        } else {
                            // For one-on-one conversations, check if it's with a user or student
                            if (conversation.users && conversation.users.length > 0) {
                                // There's a regular user in this conversation
                                let otherUser = null;
                                
                                // If current user is a student, show the regular user
                                if (isStudent) {
                                    otherUser = conversation.users[0];
                                } else {
                                    // If current user is a regular user, find the other user
                                    otherUser = conversation.users.find(user => user.id !== currentUserId);
                                    
                                    // If no other user found, check if there's a student
                                    if (!otherUser && conversation.students && conversation.students.length > 0) {
                                        otherUser = conversation.students[0];
                                    }
                                }
                                
                                if (otherUser) {
                                    displayName = otherUser.name;
                                    if (otherUser.avatar) {
                                        avatar = `<img src="/storage/${otherUser.avatar}" class="rounded-circle me-2" width="40" height="40" alt="User avatar">`;
                                    } else {
                                        // Use different icon for students
                                        if (conversation.students && conversation.students.find(student => student.id === otherUser.id)) {
                                            avatar = `<div class="bg-info rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user-graduate"></i>
                                                  </div>`;
                                        } else {
                                            avatar = `<div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                  </div>`;
                                        }
                                    }
                                }
                            } else if (conversation.students && conversation.students.length > 0) {
                                // For student-to-student conversation
                                let otherStudent = conversation.students.find(student => student.id !== currentUserId);
                                
                                if (otherStudent) {
                                    displayName = otherStudent.name;
                                    if (otherStudent.avatar) {
                                        avatar = `<img src="/storage/${otherStudent.avatar}" class="rounded-circle me-2" width="40" height="40" alt="Student avatar">`;
                                    } else {
                                        avatar = `<div class="bg-info rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user-graduate"></i>
                                              </div>`;
                                    }
                                }
                            }
                        }
                        
                        let lastMessageText = '';
                        if (conversation.last_message) {
                            if (conversation.last_message.message_type === 'voice') {
                                lastMessageText = '🎤 Voice message';
                            } else if (conversation.last_message.attachment) {
                                lastMessageText = '📎 Attachment';
                            } else {
                                lastMessageText = conversation.last_message.body ? 
                                    conversation.last_message.body.substring(0, 25) + (conversation.last_message.body.length > 5 ? '...' : '') : '';
                            }
                        }
                        
                        html += `
                        <li class="list-group-item ${isActive} conversation-item" data-conversation-id="${conversation.id}" data-is-group="${conversation.is_group}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    ${avatar}
                                    <div>
                                        <h6 class="mb-0">${displayName}</h6>
                                        <small class="text-muted">${lastMessageText}</small>
                                    </div>
                                </div>
                                ${unreadBadge}
                            </div>
                        </li>`;
                    });
                }
                
                $('.conversation-list').html(html);
            }
            
            // Click handler for conversations
            $(document).on('click', '.conversation-item', function() {
                const conversationId = $(this).data('conversation-id');
                const isGroup = $(this).data('is-group');
                
                activeConversationId = conversationId;
                activeConversationIsGroup = isGroup;
                
                $('.conversation-item').removeClass('active bg-light');
                $(this).addClass('active bg-light');
                
                loadConversation(conversationId);
                loadMessages(conversationId);
            });
            
            // Load conversation details
            function loadConversation(conversationId) {
                $.ajax({
                    url: `/chat/conversations/${conversationId}`,
                    type: 'GET',
                    success: function(response) {
                        renderConversationHeader(response.conversation);
                        $('#conversation-id').val(conversationId);
                        $('.chat-footer').removeClass('d-none');
                        $('.initial-message').addClass('d-none');
                    },
                    error: function(error) {
                        console.error('Error loading conversation:', error);
                    }
                });
            }
            
            // Render conversation header
            function renderConversationHeader(conversation) {
                let headerHtml = '';
                
                if (conversation.is_group) {
                    let avatar = conversation.avatar ? 
                        `<img src="/storage/${conversation.avatar}" class="rounded-circle me-2" width="40" height="40" alt="Group avatar">` :
                        `<div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                            <i class="fas fa-users"></i>
                         </div>`;
                    
                    // Count total members (users + students)
                    const totalMembers = (conversation.users ? conversation.users.length : 0) + 
                                         (conversation.students ? conversation.students.length : 0);
                    
                    headerHtml = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            ${avatar}
                            <div>
                                <h5 class="mb-0">${conversation.name}</h5>
                                <small class="text-muted">${totalMembers} members</small>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary show-group-info" data-conversation-id="${conversation.id}">
                            <i class="fas fa-info-circle"></i> Group Info
                        </button>
                    </div>`;
                } else {
                    // For one-on-one conversations
                    let otherParticipant = null;
                    let isOtherParticipantStudent = false;
                    
                    // Check if current user is a student
                    if (isStudent) {
                        // If student, look for a user or another student
                        if (conversation.users && conversation.users.length > 0) {
                            otherParticipant = conversation.users[0];
                        } else if (conversation.students) {
                            otherParticipant = conversation.students.find(student => student.id !== currentUserId);
                            isOtherParticipantStudent = true;
                        }
                    } else {
                        // If regular user, look for another user or a student
                        if (conversation.users) {
                            otherParticipant = conversation.users.find(user => user.id !== currentUserId);
                        }
                        
                        // If no other user, check for a student
                        if (!otherParticipant && conversation.students && conversation.students.length > 0) {
                            otherParticipant = conversation.students[0];
                            isOtherParticipantStudent = true;
                        }
                    }
                    
                    if (otherParticipant) {
                        let avatar = otherParticipant.avatar ? 
                            `<img src="/storage/${otherParticipant.avatar}" class="rounded-circle me-2" width="40" height="40" alt="Participant avatar">` :
                            `<div class="bg-${isOtherParticipantStudent ? 'info' : 'primary'} rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                <i class="fas fa-${isOtherParticipantStudent ? 'user-graduate' : 'user'}"></i>
                             </div>`;
                        
                        let statusHtml = otherParticipant.is_online ? 
                            '<small class="text-success">Online</small>' : 
                            '<small class="text-muted">Offline</small>';
                        
                        headerHtml = `
                        <div class="d-flex align-items-center">
                            ${avatar}
                            <div>
                                <h5 class="mb-0">${otherParticipant.name}</h5>
                                ${statusHtml}
                                ${isOtherParticipantStudent ? '<small class="badge bg-info ms-2">Student</small>' : ''}
                            </div>
                        </div>`;
                    }
                }
                
                $('.chat-header').html(headerHtml);
            }
            
            // Load messages
            function loadMessages(conversationId) {
                $.ajax({
                    url: `/chat/conversations/${conversationId}/messages`,
                    type: 'GET',
                    success: function(response) {
                        renderMessages(response.messages);
                        scrollToBottom();
                    },
                    error: function(error) {
                        console.error('Error loading messages:', error);
                    }
                });
            }
            
       // Part of the renderMessages function that was cut off
            // Render messages
            function renderMessages(messages) {
                let html = '';
                
                if (messages.length === 0) {
                    html = '<div class="text-center text-muted my-4">No messages yet</div>';
                } else {
                    let currentDate = null;
                    
                    messages.forEach(function(message) {
                        const messageDate = new Date(message.created_at).toLocaleDateString();
                        
                        if (currentDate !== messageDate) {
                            html += `<div class="text-center my-3">
                                        <span class="badge bg-secondary">${messageDate}</span>
                                     </div>`;
                            currentDate = messageDate;
                        }
                        
                        // Check if message is from current user (either student or regular user)
                        const isCurrentUser = isStudent ? 
                            (message.student_id == currentUserId) : 
                            (message.user_id == currentUserId);
                            
                        const messageClass = isCurrentUser ? 'text-end' : 'text-start';
                        const bubbleClass = isCurrentUser ? 'bg-primary text-white' : 'bg-light';
                        const messageTime = new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        
                        let messageContent = '';
                        
                        if (message.message_type === 'voice') {
                            messageContent = `
                                <div class="mb-1">
                                    <audio controls class="w-100">
                                        <source src="/storage/${message.voice_message}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                                <small class="text-muted">Voice Message (${formatDuration(message.voice_message_duration)})</small>`;
                        } else if (message.attachment) {
                            const isImage = message.attachment_type && message.attachment_type.startsWith('image/');
                            if (isImage) {
                                messageContent = `
                                    <div class="mb-1">
                                        <a href="/storage/${message.attachment}" target="_blank">
                                            <img src="/storage/${message.attachment}" class="img-thumbnail" style="max-width: 200px;" alt="Attachment">
                                        </a>
                                    </div>`;
                            } else {
                                const fileName = message.attachment.split('/').pop();
                                messageContent = `
                                    <div class="mb-1">
                                        <a href="/storage/${message.attachment}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-file"></i> ${fileName}
                                        </a>
                                    </div>`;
                            }
                        }
                        
                        if (message.body) {
                            messageContent += `<div>${message.body}</div>`;
                        }
                        
                        // Show sender name if it's a group conversation
                        let senderInfo = '';
                        if (activeConversationIsGroup && !isCurrentUser) {
                            let senderName = 'Unknown';
                            let senderType = '';
                            
                            if (message.user && message.user.name) {
                                senderName = message.user.name;
                            } else if (message.student && message.student.name) {
                                senderName = message.student.name;
                                senderType = ' <small class="badge bg-info">Student</small>';
                            }
                            
                            senderInfo = `<div class="small mb-1 text-${isCurrentUser ? 'light' : 'muted'}">${senderName}${senderType}</div>`;
                        }
                        
                        html += `
                        <div class="${messageClass} mb-3 message-item">
                            <div class="d-inline-block ${bubbleClass} rounded p-2" style="max-width: 75%;">
                                ${senderInfo}
                                ${messageContent}
                                <div class="text-end mt-1">
                                    <small class="text-${isCurrentUser ? 'light' : 'muted'}">${messageTime}</small>
                                </div>
                            </div>
                        </div>`;
                    });
                }
                
                $('#messages-container').html(html);
            }
            
            // Format duration from seconds to MM:SS
            function formatDuration(seconds) {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = Math.floor(seconds % 60);
                return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
            }
            
            // Scroll to bottom of chat
            function scrollToBottom() {
                const chatBody = $('.chat-body');
                chatBody.scrollTop(chatBody.prop('scrollHeight'));
            }
            
            // Start a conversation with a user or student
            $(document).on('click', '.start-conversation', function() {
                const userId = $(this).data('user-id');
                const studentId = $(this).data('student-id');
                const type = $(this).data('type');
                
                // Build the request data based on participant type
                let requestData = {
                    _token: '{{ csrf_token() }}',
                    type: type
                };
                
                if (type === 'user') {
                    requestData.user_id = userId;
                } else {
                    requestData.student_id = studentId;
                }
                
                $.ajax({
                    url: '/chat/conversations',
                    type: 'POST',
                    data: requestData,
                    success: function(response) {
                        loadConversations();
                        
                        // Automatically select the new conversation
                        setTimeout(function() {
                            $(`.conversation-item[data-conversation-id="${response.conversation.id}"]`).click();
                        }, 300);
                    },
                    error: function(error) {
                        console.error('Error starting conversation:', error);
                    }
                });
            });
            
            // Send message form submit
            $('#message-form').on('submit', function(e) {
                e.preventDefault();
                
                const messageInput = $('#message-input');
                const attachmentInput = $('#attachment');
                
                // Check if we have any content to send
                if (!messageInput.val() && !attachmentInput[0].files[0] && !isRecording && audioChunks.length === 0) {
                    return;
                }
                
                const formData = new FormData(this);
                
                // Add voice message if exists
                if (audioChunks.length > 0) {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/mpeg' });
                    formData.append('voice_message', audioBlob, 'recording.mp3');
                    formData.append('duration', recordingTime);
                }
                
                $.ajax({
                    url: '/chat/messages',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Add new message to the chat
                        const messages = [response.message];
                        renderMessages([...Array.from($('#messages-container .message-item')), ...messages]);
                        scrollToBottom();
                        
                        // Reset form
                        messageInput.val('');
                        attachmentInput.val('');
                        $('#attachment-name').text('');
                        resetVoiceRecording();
                    },
                    error: function(error) {
                        console.error('Error sending message:', error);
                        alert('Error sending message. Please try again.');
                    }
                });
            });
            
            // Handle file selection
            $('#attachment').on('change', function() {
                const fileName = $(this).val().split('\\').pop();
                $('#attachment-name').text(fileName ? fileName : '');
            });
            
            // Voice recording
            $('#record-voice').on('click', function() {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ audio: true })
                        .then(function(stream) {
                            mediaRecorder = new MediaRecorder(stream);
                            
                            mediaRecorder.ondataavailable = function(e) {
                                audioChunks.push(e.data);
                            };
                            
                            mediaRecorder.start();
                            isRecording = true;
                            
                            // Show recording UI
                            $('#record-voice').addClass('d-none');
                            $('#stop-recording').removeClass('d-none');
                            $('#recording-status').text('Recording... 0:00');
                            
                            // Start recording timer
                            recordingTime = 0;
                            recordingInterval = setInterval(function() {
                                recordingTime++;
                                const minutes = Math.floor(recordingTime / 60);
                                const seconds = recordingTime % 60;
                                $('#recording-status').text(`Recording... ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`);
                            }, 1000);
                        })
                        .catch(function(err) {
                            console.error('Error accessing microphone:', err);
                            alert('Error accessing microphone. Please check your browser permissions.');
                        });
                } else {
                    alert('Your browser does not support audio recording.');
                }
            });
            
            // Stop voice recording
            $('#stop-recording').on('click', function() {
                if (mediaRecorder && isRecording) {
                    mediaRecorder.stop();
                    isRecording = false;
                    
                    clearInterval(recordingInterval);
                    
                    // Show preview UI
                    $('#record-voice').removeClass('d-none');
                    $('#stop-recording').addClass('d-none');
                    $('#recording-status').text(`Voice message recorded (${formatDuration(recordingTime)})`);
                    
                    // Stop all audio tracks
                    mediaRecorder.stream.getTracks().forEach(track => track.stop());
                }
            });
            
            // Reset voice recording state
            function resetVoiceRecording() {
                audioChunks = [];
                recordingTime = 0;
                clearInterval(recordingInterval);
                
                $('#record-voice').removeClass('d-none');
                $('#stop-recording').addClass('d-none');
                $('#recording-status').text('');
                
                if (mediaRecorder && mediaRecorder.stream) {
                    mediaRecorder.stream.getTracks().forEach(track => track.stop());
                }
            }
            
            // Create group chat
            $('#create-group-btn').on('click', function() {
                const formData = new FormData($('#create-group-form')[0]);
                formData.append('_token', '{{ csrf_token() }}');
                
                $.ajax({
                    url: '/chat/conversations/group',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Reset form and close modal
                        $('#create-group-form')[0].reset();
                        $('#newGroupModal').modal('hide');
                        
                        // Refresh conversations
                        loadConversations();
                        
                        // Automatically select the new group
                        setTimeout(function() {
                            $(`.conversation-item[data-conversation-id="${response.conversation.id}"]`).click();
                        }, 300);
                    },
                    error: function(error) {
                        console.error('Error creating group:', error);
                        alert('Error creating group. Please try again.');
                    }
                });
            });
            
            // Show group info
            $(document).on('click', '.show-group-info', function() {
                const conversationId = $(this).data('conversation-id');
                
                $.ajax({
                    url: `/chat/conversations/${conversationId}/group`,
                    type: 'GET',
                    success: function(response) {
                        renderGroupInfo(response.group);
                        $('#groupInfoModal').modal('show');
                    },
                    error: function(error) {
                        console.error('Error loading group info:', error);
                    }
                });
            });
            
            // Render group info
            function renderGroupInfo(group) {
                let usersHtml = '';
                let studentsHtml = '';
                
                // Render regular users
                if (group.users && group.users.length > 0) {
                    group.users.forEach(function(user) {
                        const isCurrentUser = !isStudent && user.id == currentUserId;
                        const removeButton = isCurrentUser ? '' : 
                            `<button type="button" class="btn btn-sm btn-danger remove-member" data-user-id="${user.id}" data-conversation-id="${group.id}" data-type="user">
                                <i class="fas fa-times"></i>
                             </button>`;
                        
                        let avatar = user.avatar ? 
                            `<img src="/storage/${user.avatar}" class="rounded-circle me-2" width="30" height="30" alt="User avatar">` :
                            `<div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                <i class="fas fa-user"></i>
                             </div>`;
                        
                        usersHtml += `
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                ${avatar}
                                ${user.name}
                                ${isCurrentUser ? '<span class="badge bg-primary ms-2">You</span>' : ''}
                            </div>
                            ${removeButton}
                        </div>`;
                    });
                }
                
                // Render students
                if (group.students && group.students.length > 0) {
                    group.students.forEach(function(student) {
                        const isCurrentUser = isStudent && student.id == currentUserId;
                        const removeButton = isCurrentUser ? '' : 
                            `<button type="button" class="btn btn-sm btn-danger remove-member" data-student-id="${student.id}" data-conversation-id="${group.id}" data-type="student">
                                <i class="fas fa-times"></i>
                             </button>`;
                        
                        let avatar = student.avatar ? 
                            `<img src="/storage/${student.avatar}" class="rounded-circle me-2" width="30" height="30" alt="Student avatar">` :
                            `<div class="bg-info rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                <i class="fas fa-user-graduate"></i>
                             </div>`;
                        
                        studentsHtml += `
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                ${avatar}
                                ${student.name}
                                <span class="badge bg-info ms-2">Student</span>
                                ${isCurrentUser ? '<span class="badge bg-primary ms-1">You</span>' : ''}
                            </div>
                            ${removeButton}
                        </div>`;
                    });
                }
                
                // Combine members HTML
                let membersHtml = usersHtml + studentsHtml;
                
                // Group avatar
                let groupAvatar = group.avatar ? 
                    `<img src="/storage/${group.avatar}" class="rounded-circle mb-2" width="100" height="100" alt="Group avatar">` :
                    `<div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 100px; height: 100px;">
                        <i class="fas fa-users fa-3x"></i>
                     </div>`;
                
                const html = `
                <form id="update-group-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="conversation_id" value="${group.id}">
                    
                    <div class="mb-3 text-center">
                        ${groupAvatar}
                        <input type="file" class="form-control mt-2" id="update-group-avatar" name="group_avatar" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="update-group-name" class="form-label">Group Name</label>
                        <input type="text" class="form-control" id="update-group-name" name="name" value="${group.name}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Members (${(group.users ? group.users.length : 0) + (group.students ? group.students.length : 0)})</label>
                        <div class="list-group">
                            ${membersHtml}
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-primary w-100 show-add-members" data-conversation-id="${group.id}">
                            <i class="fas fa-user-plus"></i> Add Members
                        </button>
                    </div>
                </form>
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-danger leave-group" data-conversation-id="${group.id}">
                        <i class="fas fa-sign-out-alt"></i> Leave Group
                    </button>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary update-group">Save Changes</button>
                    </div>
                </div>`;
                
                $('#group-info-content').html(html);
            }
            
            // Update group
            $(document).on('click', '.update-group', function() {
                const formData = new FormData($('#update-group-form')[0]);
                const conversationId = formData.get('conversation_id');
                
                $.ajax({
                    url: `/chat/conversations/${conversationId}/group`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#groupInfoModal').modal('hide');
                        
                        // Refresh the conversation if it's the active one
                        if (activeConversationId == conversationId) {
                            loadConversation(conversationId);
                        }
                        
                        // Refresh conversations list
                        loadConversations();
                    },
                    error: function(error) {
                        console.error('Error updating group:', error);
                        alert('Error updating group. Please try again.');
                    }
                });
            });
            
            // Remove user or student from group
            $(document).on('click', '.remove-member', function() {
                const userId = $(this).data('user-id');
                const studentId = $(this).data('student-id');
                const conversationId = $(this).data('conversation-id');
                const type = $(this).data('type');
                
                let confirmMessage = 'Are you sure you want to remove this member from the group?';
                
                if (confirm(confirmMessage)) {
                    // Build the request data
                    let requestData = {
                        _token: '{{ csrf_token() }}',
                        type: type
                    };
                    
                    if (type === 'user') {
                        requestData.user_id = userId;
                    } else {
                        requestData.student_id = studentId;
                    }
                    
                    $.ajax({
                        url: `/chat/conversations/${conversationId}/remove-user`,
                        type: 'POST',
                        data: requestData,
                        success: function(response) {
                            // Refresh group info
                            renderGroupInfo(response.group);
                            
                            // Refresh conversation if it's the active one
                            if (activeConversationId == conversationId) {
                                loadConversation(conversationId);
                            }
                        },
                        error: function(error) {
                            console.error('Error removing member:', error);
                            alert('Error removing member. Please try again.');
                        }
                    });
                }
            });
            
            // Leave group
            $(document).on('click', '.leave-group', function() {
                const conversationId = $(this).data('conversation-id');
                
                if (confirm('Are you sure you want to leave this group?')) {
                    $.ajax({
                        url: `/chat/conversations/${conversationId}/leave`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#groupInfoModal').modal('hide');
                            
                            // Clear active conversation if it's the one we're leaving
                            if (activeConversationId == conversationId) {
                                activeConversationId = null;
                                activeConversationIsGroup = false;
                                $('.chat-header').html('<div class="text-center text-muted"><p>Select a conversation to start chatting</p></div>');
                                $('#messages-container').html('');
                                $('.chat-footer').addClass('d-none');
                                $('.initial-message').removeClass('d-none');
                            }
                            
                            // Refresh conversations list
                            loadConversations();
                        },
                        error: function(error) {
                            console.error('Error leaving group:', error);
                            alert('Error leaving group. Please try again.');
                        }
                    });
                }
            });
            
            // Show add members modal
            $(document).on('click', '.show-add-members', function() {
                const conversationId = $(this).data('conversation-id');
                
                // Load available users and students
                $.ajax({
                    url: `/chat/users/available/${conversationId}`,
                    type: 'GET',
                    success: function(response) {
                        renderAvailableUsers(response.users, response.students, conversationId);
                        $('#groupInfoModal').modal('hide');
                        $('#addMembersModal').modal('show');
                    },
                    error: function(error) {
                        console.error('Error loading available members:', error);
                    }
                });
            });
            
            // Render available users and students for adding to group
            function renderAvailableUsers(users, students, conversationId) {
                // Render available users
                let usersHtml = '';
                if (!users || users.length === 0) {
                    usersHtml = '<div class="alert alert-info">No more users available to add</div>';
                } else {
                    usersHtml = '<div class="border p-2 rounded" style="max-height: 150px; overflow-y: auto;">';
                    users.forEach(function(user) {
                        usersHtml += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="user_ids[]" value="${user.id}" id="add-user-${user.id}">
                            <label class="form-check-label" for="add-user-${user.id}">
                                ${user.name}
                            </label>
                        </div>`;
                    });
                    usersHtml += '</div>';
                }
                
                // Render available students
                let studentsHtml = '';
                if (!students || students.length === 0) {
                    studentsHtml = '<div class="alert alert-info">No more students available to add</div>';
                } else {
                    studentsHtml = '<div class="border p-2 rounded" style="max-height: 150px; overflow-y: auto;">';
                    students.forEach(function(student) {
                        studentsHtml += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="student_ids[]" value="${student.id}" id="add-student-${student.id}">
                            <label class="form-check-label" for="add-student-${student.id}">
                                ${student.name} <span class="badge bg-info">Student</span>
                            </label>
                        </div>`;
                    });
                    studentsHtml += '</div>';
                }
                
                $('#available-users-container').html(usersHtml);
                $('#available-students-container').html(studentsHtml);
                $('#add-members-conversation-id').val(conversationId);
            }
            
            // Add members to group
            $(document).on('click', '#add-members-btn', function() {
                const formData = new FormData($('#add-members-form')[0]);
                formData.append('_token', '{{ csrf_token() }}');
                const conversationId = formData.get('conversation_id');
                
                // Check if any members are selected
                const userIds = formData.getAll('user_ids[]');
                const studentIds = formData.getAll('student_ids[]');
                
                if (userIds.length === 0 && studentIds.length === 0) {
                    alert('Please select at least one member to add');
                    return;
                }
                
                $.ajax({
                    url: `/chat/conversations/${conversationId}/add-users`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#addMembersModal').modal('hide');
                        
                        // Show group info again
                        renderGroupInfo(response.group);
                        $('#groupInfoModal').modal('show');
                        
                        // Refresh conversation if it's the active one
                        if (activeConversationId == conversationId) {
                            loadConversation(conversationId);
                        }
                    },
                    error: function(error) {
                        console.error('Error adding members:', error);
                        alert('Error adding members. Please try again.');
                    }
                });
            });
            
            // Update user online status
            window.addEventListener('online', updateUserStatus);
            window.addEventListener('offline', updateUserStatus);
            window.addEventListener('beforeunload', function() {
                updateUserStatus(false);
            });
            
            function updateUserStatus(status) {
                // If status is an event, determine status based on navigator.onLine
                if (status instanceof Event || status === undefined) {
                    status = navigator.onLine;
                }
                
                $.ajax({
                    url: '/chat/status',
                    type: 'POST',
                    data: {
                        is_online: status === true ? 1 : 0,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Status updated:', status);
                    },
                    error: function(error) {
                        console.error('Error updating status:', error);
                    }
                });
            }
            
            // Initialize by setting online status
            updateUserStatus(true);
            
            // Optional: Laravel Echo integration for real-time messaging
            if (typeof Echo !== 'undefined') {
                // Listen for new messages
                Echo.private(`conversation.${activeConversationId}`)
                    .listen('MessageSent', (e) => {
                        // Only add the message if it's not from the current user
                        const isSenderCurrentUser = isStudent ? 
                            (e.message.student_id == currentUserId) : 
                            (e.message.user_id == currentUserId);
                            
                        if (!isSenderCurrentUser) {
                            // Add new message to the chat
                            const messages = $('#messages-container .message-item').toArray();
                            messages.push(e.message);
                            renderMessages(messages);
                            scrollToBottom();
                            
                            // Play notification sound
                            const notificationSound = document.getElementById('notification-sound');
                            if (notificationSound) {
                                notificationSound.play();
                            }
                        }
                    });

                // Listen for user status changes
                Echo.channel('user-status')
                    .listen('UserStatus', (e) => {
                        // Update user status in the user list
                        $(`.user-status-${e.user.id}`).html(
                            e.user.is_online ? 
                            '<span class="text-success">Online</span>' : 
                            '<span>Offline</span>'
                        );
                        
                        // If this user is part of the active conversation, update the header
                        if (activeConversationId && !activeConversationIsGroup) {
                            const conversationHeader = $('.chat-header');
                            if (conversationHeader.find(`h5:contains("${e.user.name}")`).length > 0) {
                                const statusText = conversationHeader.find('small').first();
                                if (e.user.is_online) {
                                    statusText.removeClass('text-muted').addClass('text-success').text('Online');
                                } else {
                                    statusText.removeClass('text-success').addClass('text-muted').text('Offline');
                                }
                            }
                        }
                    });
                    
                // Listen for student status changes
                Echo.channel('student-status')
                    .listen('StudentStatus', (e) => {
                        // Update student status in the students list
                        $(`.student-status-${e.student.id}`).html(
                            e.student.is_online ? 
                            '<span class="text-success">Online</span>' : 
                            '<span>Offline</span>'
                        );
                        
                        // If this student is part of the active conversation, update the header
                        if (activeConversationId && !activeConversationIsGroup) {
                            const conversationHeader = $('.chat-header');
                            if (conversationHeader.find(`h5:contains("${e.student.name}")`).length > 0) {
                                const statusText = conversationHeader.find('small').first();
                                if (e.student.is_online) {
                                    statusText.removeClass('text-muted').addClass('text-success').text('Online');
                                } else {
                                    statusText.removeClass('text-success').addClass('text-muted').text('Offline');
                                }
                            }
                        }
                    });
            }
        });
    </script>
@endsection