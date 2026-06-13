@extends('admin.index')

@section('content')
<style>
/* ── Chat Layout ───────────────────────────────────── */
.chat-wrap {
    display: flex;
    height: calc(100vh - 120px);
    min-height: 500px;
    background: #f4f6f9;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.08);
}

/* ── Sidebar ───────────────────────────────────────── */
.chat-sidebar {
    width: 280px;
    flex-shrink: 0;
    background: #fff;
    border-right: 1px solid #e9ecef;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.sidebar-header {
    padding: 14px 16px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #1a6bff;
    color: #fff;
}
.sidebar-header h6 { margin: 0; font-weight: 600; font-size: .95rem; }
.sidebar-search {
    padding: 10px 12px;
    border-bottom: 1px solid #f0f0f0;
}
.sidebar-search input {
    width: 100%;
    padding: 6px 10px 6px 30px;
    border: 1px solid #e0e0e0;
    border-radius: 20px;
    font-size: .8rem;
    background: #f8f9fa url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23999' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.44 1.406a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z'/%3E%3C/svg%3E") no-repeat 10px center;
    outline: none;
}
.sidebar-section-title {
    padding: 8px 14px 4px;
    font-size: .7rem;
    font-weight: 700;
    color: #aaa;
    text-transform: uppercase;
    letter-spacing: .05em;
}
.sidebar-body {
    flex: 1;
    overflow-y: auto;
}
.sidebar-body::-webkit-scrollbar { width: 4px; }
.sidebar-body::-webkit-scrollbar-thumb { background: #ddd; border-radius: 4px; }

/* conversation items */
.conv-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    cursor: pointer;
    border-left: 3px solid transparent;
    transition: background .15s;
}
.conv-item:hover { background: #f8f9fa; }
.conv-item.active { background: #f0e6f0; border-left-color: #1a6bff; }
.conv-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; font-weight: 600; flex-shrink: 0;
    background: #e9ecef; color: #555;
}
.conv-avatar img { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; }
.conv-info { flex: 1; min-width: 0; }
.conv-name { font-size: .85rem; font-weight: 600; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.conv-last { font-size: .75rem; color: #aaa; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.conv-badge { background: #ea5455; color: #fff; border-radius: 20px; font-size: .65rem; padding: 1px 6px; font-weight: 700; flex-shrink: 0; }

/* contact items */
.contact-item {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 14px; cursor: pointer;
    transition: background .15s;
}
.contact-item:hover { background: #f8f9fa; }
.contact-name { font-size: .83rem; font-weight: 500; flex: 1; }
.btn-chat-start {
    width: 28px; height: 28px; border-radius: 50%; border: none;
    background: #f0e6f0; color: #1a6bff;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; flex-shrink: 0; cursor: pointer;
    transition: background .15s;
}
.btn-chat-start:hover { background: #1a6bff; color: #fff; }

/* ── Chat Area ─────────────────────────────────────── */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    background: #f4f6f9;
}
.chat-topbar {
    padding: 12px 18px;
    background: #fff;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 62px;
}
.chat-topbar .participant-name { font-size: 1rem; font-weight: 600; margin: 0; }
.chat-topbar .participant-status { font-size: .75rem; }
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 18px 20px;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-thumb { background: #ddd; border-radius: 4px; }

/* bubbles */
.msg-row { display: flex; margin-bottom: 14px; }
.msg-row.me { justify-content: flex-end; }
.msg-bubble {
    max-width: 68%;
    padding: 9px 13px;
    border-radius: 14px;
    font-size: .875rem;
    line-height: 1.45;
    position: relative;
}
.msg-row.me .msg-bubble   { background: #1a6bff; color: #fff; border-bottom-right-radius: 4px; }
.msg-row.them .msg-bubble { background: #fff; color: #333; border-bottom-left-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
.msg-time { font-size: .68rem; margin-top: 4px; opacity: .7; text-align: right; }
.msg-sender { font-size: .72rem; font-weight: 600; margin-bottom: 3px; opacity: .8; }
.date-divider { text-align: center; margin: 14px 0; }
.date-divider span { background: #e9ecef; color: #888; font-size: .72rem; padding: 3px 12px; border-radius: 20px; }
.no-conv { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #bbb; gap: 10px; }
.no-conv i { font-size: 3rem; }
.no-conv p { font-size: .9rem; }

/* ── Input Bar ─────────────────────────────────────── */
.chat-inputbar {
    padding: 12px 16px;
    background: #fff;
    border-top: 1px solid #e9ecef;
}
.input-row {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f4f6f9;
    border-radius: 25px;
    padding: 6px 6px 6px 14px;
}
.input-row input[type=text] {
    flex: 1; border: none; background: transparent;
    font-size: .875rem; outline: none;
}
.input-row .btn-icon {
    width: 34px; height: 34px; border-radius: 50%; border: none;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; cursor: pointer; transition: background .15s;
    background: transparent; color: #888;
}
.input-row .btn-icon:hover { background: #e9ecef; color: #555; }
.input-row .btn-send {
    width: 34px; height: 34px; border-radius: 50%; border: none;
    background: #1a6bff; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; cursor: pointer; flex-shrink: 0;
}
.input-row .btn-send:hover { background: #1558d6; }
.attach-row { display: flex; align-items: center; gap: 10px; margin-top: 8px; font-size: .8rem; color: #888; padding: 0 6px; }
.recording-indicator { color: #ea5455; font-weight: 500; }
</style>

<div class="chat-wrap">
    {{-- ── Sidebar ── --}}
    <div class="chat-sidebar">
        <div class="sidebar-header">
            <h6><i class="fas fa-comments mr-2"></i>Messages</h6>
            @if(in_array(auth()->user()->role, ['Admin','Register','Sales']))
            <button class="btn btn-sm btn-light py-1 px-2" style="font-size:.75rem;" data-toggle="modal" data-target="#newGroupModal">
                <i class="fas fa-users-cog mr-1"></i>New Group
            </button>
            @endif
        </div>

        <div class="sidebar-search">
            <input type="text" id="sidebarSearch" placeholder="Search conversations...">
        </div>

        <div class="sidebar-body">
            {{-- Conversations --}}
            <div class="sidebar-section-title">Conversations</div>
            <ul class="conversation-list" style="list-style:none; margin:0; padding:0;"></ul>

            {{-- Users --}}
            @if(in_array(auth()->user()->role, ['Admin','Register','Sales']))
            <div class="sidebar-section-title mt-2">Users</div>
            @foreach($users as $user)
            <div class="contact-item">
                <div class="conv-avatar" style="background:#ebf3ff; color:#3a6eff;">
                    @if($user->avatar)
                        <img src="{{ asset('storage/'.$user->avatar) }}" alt="">
                    @else
                        {{ strtoupper(substr($user->name,0,1)) }}
                    @endif
                </div>
                <span class="contact-name">{{ $user->name }}</span>
                <button class="btn-chat-start start-conversation" data-user-id="{{ $user->id }}" data-type="user" title="Start chat">
                    <i class="fas fa-comment"></i>
                </button>
            </div>
            @endforeach

            {{-- Students --}}
            <div class="sidebar-section-title mt-2">Students</div>
            @foreach($students as $student)
                @if(!Auth::guard('student')->check() || Auth::guard('student')->id() != $student->id)
                <div class="contact-item">
                    <div class="conv-avatar" style="background:#e7f9f0; color:#28c76f;">
                        @if($student->avatar)
                            <img src="{{ asset('storage/'.$student->avatar) }}" alt="">
                        @else
                            {{ strtoupper(substr($student->first_name,0,1)) }}
                        @endif
                    </div>
                    <span class="contact-name">{{ $student->first_name }} {{ $student->last_name }}</span>
                    <button class="btn-chat-start start-conversation" data-student-id="{{ $student->id }}" data-type="student" title="Start chat">
                        <i class="fas fa-comment"></i>
                    </button>
                </div>
                @endif
            @endforeach
            @endif
        </div>
    </div>

    {{-- ── Chat Main ── --}}
    <div class="chat-main">
        {{-- Topbar --}}
        <div class="chat-topbar" style="position:relative;">
            {{-- shown only when no conversation selected --}}
            <div id="noChatSelected" class="d-flex align-items-center text-muted" style="gap:.5rem; display:none !important;">
                <i class="fas fa-comments fa-lg" style="color:#ddd;"></i>
                <span style="font-size:.9rem;">Select a conversation to start chatting</span>
            </div>
            {{-- shown only when a conversation is active --}}
            <div id="chatHeaderContent" style="display:none; width:100%; align-items:center; justify-content:space-between;">
                <div class="d-flex align-items-center" style="gap:10px;">
                    <div class="conv-avatar" id="headerAvatar"></div>
                    <div>
                        <p class="participant-name mb-0" id="headerName"></p>
                        <span class="participant-status text-muted" id="headerStatus"></span>
                    </div>
                </div>
                <div id="headerActions"></div>
            </div>
        </div>

        {{-- Messages --}}
        <div class="chat-messages" id="messages-container"></div>

        {{-- Input --}}
        <div class="chat-inputbar d-none" id="chatInputBar">
            <form id="message-form" enctype="multipart/form-data">
                <input type="hidden" id="conversation-id" name="conversation_id">
                <div class="input-row">
                    <label for="attachment" class="btn-icon mb-0" title="Attach file" style="cursor:pointer;">
                        <i class="fas fa-paperclip"></i>
                    </label>
                    <input type="file" id="attachment" name="attachment" class="d-none">
                    <button type="button" id="record-voice" class="btn-icon" title="Voice message">
                        <i class="fas fa-microphone"></i>
                    </button>
                    <button type="button" id="stop-recording" class="btn-icon d-none" title="Stop recording" style="color:#ea5455;">
                        <i class="fas fa-stop-circle"></i>
                    </button>
                    <input type="text" id="message-input" name="body" placeholder="Type a message...">
                    <button type="submit" class="btn-send" title="Send">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="attach-row">
                    <span id="attachment-name"></span>
                    <span id="recording-status" class="recording-indicator"></span>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Modals ── --}}
<div class="modal fade" id="newGroupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Group</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="create-group-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Group Name</label>
                        <input type="text" class="form-control" id="group-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Group Avatar <small class="text-muted">(Optional)</small></label>
                        <input type="file" class="form-control-file" id="group-avatar" name="group_avatar" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label>Select Users</label>
                        <div class="border rounded p-2" style="max-height:150px; overflow-y:auto;">
                            @foreach($users as $user)
                            @if(Auth::guard('student')->check() || Auth::id() != $user->id)
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="user_ids[]" value="{{ $user->id }}" id="u{{ $user->id }}">
                                <label class="custom-control-label" for="u{{ $user->id }}">{{ $user->name }}</label>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="create-group-btn" class="btn btn-primary">Create Group</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="groupInfoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Group Info</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="group-info-content"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="addMembersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Members</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="add-members-form">
                    <input type="hidden" id="add-members-conversation-id" name="conversation_id">
                    <div class="form-group">
                        <label>Add Users</label>
                        <div id="available-users-container"></div>
                    </div>
                    <div class="form-group">
                        <label>Add Students</label>
                        <div id="available-students-container"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="add-members-btn" class="btn btn-primary">Add Members</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let mediaRecorder, audioChunks = [], recordingTime = 0, recordingInterval, isRecording = false;
    let activeConversationId = null, activeConversationIsGroup = false;
    const isStudent = {{ Auth::guard('student')->check() ? 'true' : 'false' }};
    const currentUserId = {{ Auth::guard('student')->check() ? Auth::guard('student')->id() : Auth::id() }};

    loadConversations();
    setInterval(function() {
        if (activeConversationId) loadMessages(activeConversationId);
        loadConversations();
    }, 10000);

    // Sidebar search
    $('#sidebarSearch').on('input', function() {
        const q = $(this).val().toLowerCase();
        $('.conv-item').each(function() {
            $(this).toggle($(this).find('.conv-name').text().toLowerCase().includes(q));
        });
    });

    function loadConversations() {
        $.get('/chat/conversations', function(r) { renderConversations(r.conversations); });
    }

    function avatar(name, color, bg, icon) {
        return `<div class="conv-avatar" style="background:${bg};color:${color};"><i class="fas fa-${icon}"></i></div>`;
    }

    function renderConversations(conversations) {
        let html = '';
        if (!conversations.length) {
            html = '<div class="px-3 py-2 text-muted" style="font-size:.8rem;">No conversations yet</div>';
        } else {
            conversations.forEach(function(c) {
                const active = activeConversationId == c.id ? 'active' : '';
                const badge  = c.unread_count > 0 ? `<span class="conv-badge">${c.unread_count}</span>` : '';
                let name = '', av = '';

                if (c.is_group) {
                    name = c.name;
                    av = c.avatar
                        ? `<div class="conv-avatar"><img src="/storage/${c.avatar}"></div>`
                        : `<div class="conv-avatar" style="background:#f0e6f0;color:#1a6bff;"><i class="fas fa-users"></i></div>`;
                } else {
                    let other = null, isStu = false;
                    if (isStudent) {
                        other = c.users && c.users[0] ? c.users[0] : (c.students && c.students.find(s => s.id !== currentUserId));
                    } else {
                        other = c.users && c.users.find(u => u.id !== currentUserId);
                        if (!other && c.students && c.students.length) { other = c.students[0]; isStu = true; }
                    }
                    if (other) {
                        name = other.name || (other.first_name + ' ' + other.last_name);
                        av   = other.avatar
                            ? `<div class="conv-avatar"><img src="/storage/${other.avatar}"></div>`
                            : `<div class="conv-avatar" style="background:${isStu?'#e7f9f0':'#ebf3ff'};color:${isStu?'#28c76f':'#3a6eff'};"><i class="fas fa-${isStu?'user-graduate':'user'}"></i></div>`;
                    }
                }

                let last = '';
                if (c.last_message) {
                    const body = c.last_message.body || '';
                    // Skip token-like strings (long, no spaces)
                    const isToken = body.length > 40 && !body.includes(' ');
                    last = c.last_message.message_type === 'voice' ? '🎤 Voice'
                         : c.last_message.attachment ? '📎 File'
                         : isToken ? '💬 Message'
                         : body.substring(0, 35);
                }

                html += `<li class="conv-item ${active} conversation-item" data-conversation-id="${c.id}" data-is-group="${c.is_group}">
                    ${av}
                    <div class="conv-info">
                        <p class="conv-name">${name}</p>
                        <p class="conv-last">${last}</p>
                    </div>
                    ${badge}
                </li>`;
            });
        }
        $('.conversation-list').html(html);
    }

    $(document).on('click', '.conversation-item', function() {
        activeConversationId   = $(this).data('conversation-id');
        activeConversationIsGroup = $(this).data('is-group');
        $('.conversation-item').removeClass('active');
        $(this).addClass('active');
        // Hide placeholder immediately — don't wait for AJAX
        $('#noChatSelected').hide();
        $('#chatInputBar').removeClass('d-none');
        loadConversation(activeConversationId);
        loadMessages(activeConversationId);
    });

    function loadConversation(id) {
        $.get(`/chat/conversations/${id}`, function(r) {
            renderHeader(r.conversation);
            $('#conversation-id').val(id);
            $('#chatInputBar').removeClass('d-none');
            $('#noChatSelected').hide();
            $('#chatHeaderContent').css('display','flex');
        });
    }

    function renderHeader(c) {
        let name = '', status = '', avHtml = '', actions = '';
        if (c.is_group) {
            name   = c.name;
            const total = (c.users ? c.users.length : 0) + (c.students ? c.students.length : 0);
            status = `${total} members`;
            avHtml = c.avatar
                ? `<img src="/storage/${c.avatar}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;">`
                : `<div class="conv-avatar" style="background:#f0e6f0;color:#1a6bff;"><i class="fas fa-users"></i></div>`;
            actions = `<button class="btn btn-sm btn-outline-secondary show-group-info" data-conversation-id="${c.id}"><i class="fas fa-info-circle mr-1"></i>Info</button>`;
        } else {
            let other = null, isStu = false;
            if (isStudent) {
                other = c.users && c.users[0] ? c.users[0] : (c.students && c.students.find(s => s.id !== currentUserId));
            } else {
                other = c.users && c.users.find(u => u.id !== currentUserId);
                if (!other && c.students && c.students.length) { other = c.students[0]; isStu = true; }
            }
            if (other) {
                name   = other.name || (other.first_name + ' ' + other.last_name);
                status = other.is_online
                    ? '<span style="color:#28c76f;"><i class="fas fa-circle" style="font-size:.55rem;"></i> Online</span>'
                    : '<span class="text-muted">Offline</span>';
                avHtml = other.avatar
                    ? `<img src="/storage/${other.avatar}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;">`
                    : `<div class="conv-avatar" style="background:${isStu?'#e7f9f0':'#ebf3ff'};color:${isStu?'#28c76f':'#3a6eff'};"><i class="fas fa-${isStu?'user-graduate':'user'}"></i></div>`;
            }
        }
        $('#headerAvatar').html(avHtml);
        $('#headerName').text(name);
        $('#headerStatus').html(status);
        $('#headerActions').html(actions);
    }

    function loadMessages(id) {
        $.get(`/chat/conversations/${id}/messages`, function(r) {
            renderMessages(r.messages);
            scrollBottom();
        });
    }

    function renderMessages(messages) {
        if (!messages.length) {
            $('#messages-container').html('');
            return;
        }
        let html = '', curDate = null;
        messages.forEach(function(m) {
            const d = new Date(m.created_at).toLocaleDateString();
            if (d !== curDate) {
                html += `<div class="date-divider"><span>${d}</span></div>`;
                curDate = d;
            }
            const mine  = isStudent ? m.student_id == currentUserId : m.user_id == currentUserId;
            const side  = mine ? 'me' : 'them';
            const time  = new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
            let content = '';

            if (m.message_type === 'voice') {
                content = `<audio controls style="max-width:220px;"><source src="/storage/${m.voice_message}" type="audio/mpeg"></audio>`;
            } else if (m.attachment) {
                const isImg = m.attachment_type && m.attachment_type.startsWith('image/');
                content = isImg
                    ? `<a href="/storage/${m.attachment}" target="_blank"><img src="/storage/${m.attachment}" style="max-width:200px;border-radius:8px;"></a>`
                    : `<a href="/storage/${m.attachment}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file mr-1"></i>${m.attachment.split('/').pop()}</a>`;
            }
            if (m.body) content += `<div>${m.body}</div>`;

            let sender = '';
            if (activeConversationIsGroup && !mine) {
                const sn = m.user ? m.user.name : (m.student ? (m.student.first_name || m.student.name) : '');
                sender = `<div class="msg-sender">${sn}</div>`;
            }

            html += `<div class="msg-row ${side}">
                <div class="msg-bubble">
                    ${sender}${content}
                    <div class="msg-time">${time}</div>
                </div>
            </div>`;
        });
        $('#messages-container').html(html);
    }

    function scrollBottom() {
        const el = document.getElementById('messages-container');
        el.scrollTop = el.scrollHeight;
    }

    $(document).on('click', '.start-conversation', function() {
        const data = { _token: '{{ csrf_token() }}', type: $(this).data('type') };
        if (data.type === 'user') data.user_id = $(this).data('user-id');
        else data.student_id = $(this).data('student-id');
        $.post('/chat/conversations', data, function(r) {
            loadConversations();
            setTimeout(() => $(`.conversation-item[data-conversation-id="${r.conversation.id}"]`).click(), 300);
        });
    });

    $('#message-form').on('submit', function(e) {
        e.preventDefault();
        if (!$('#message-input').val() && !$('#attachment')[0].files[0] && !audioChunks.length) return;
        const fd = new FormData(this);
        if (audioChunks.length) {
            fd.append('voice_message', new Blob(audioChunks, {type:'audio/mpeg'}), 'recording.mp3');
            fd.append('duration', recordingTime);
        }
        $.ajax({ url:'/chat/messages', type:'POST', data:fd, processData:false, contentType:false,
            success: function(r) {
                loadMessages(activeConversationId);
                $('#message-input').val(''); $('#attachment').val(''); $('#attachment-name').text('');
                resetVoice();
            }
        });
    });

    $('#attachment').on('change', function() { $('#attachment-name').text($(this).val().split('\\').pop()); });

    $('#record-voice').on('click', function() {
        navigator.mediaDevices.getUserMedia({audio:true}).then(function(stream) {
            mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
            mediaRecorder.start(); isRecording = true;
            $('#record-voice').addClass('d-none'); $('#stop-recording').removeClass('d-none');
            recordingTime = 0;
            recordingInterval = setInterval(() => {
                recordingTime++;
                const m = Math.floor(recordingTime/60), s = recordingTime%60;
                $('#recording-status').text(`🔴 Recording ${m}:${s<10?'0':''}${s}`);
            }, 1000);
        }).catch(() => alert('Cannot access microphone.'));
    });

    $('#stop-recording').on('click', function() {
        if (mediaRecorder && isRecording) {
            mediaRecorder.stop(); isRecording = false;
            clearInterval(recordingInterval);
            $('#record-voice').removeClass('d-none'); $('#stop-recording').addClass('d-none');
            $('#recording-status').text(`✅ Voice recorded (${Math.floor(recordingTime/60)}:${String(recordingTime%60).padStart(2,'0')})`);
            mediaRecorder.stream.getTracks().forEach(t => t.stop());
        }
    });

    function resetVoice() {
        audioChunks = []; recordingTime = 0; clearInterval(recordingInterval);
        $('#record-voice').removeClass('d-none'); $('#stop-recording').addClass('d-none');
        $('#recording-status').text('');
        if (mediaRecorder && mediaRecorder.stream) mediaRecorder.stream.getTracks().forEach(t => t.stop());
    }

    $('#create-group-btn').on('click', function() {
        const fd = new FormData($('#create-group-form')[0]);
        fd.append('_token', '{{ csrf_token() }}');
        $.ajax({ url:'/chat/conversations/group', type:'POST', data:fd, processData:false, contentType:false,
            success: function(r) {
                $('#create-group-form')[0].reset();
                $('#newGroupModal').modal('hide');
                loadConversations();
                setTimeout(() => $(`.conversation-item[data-conversation-id="${r.conversation.id}"]`).click(), 300);
            }
        });
    });

    $(document).on('click', '.show-group-info', function() {
        $.get(`/chat/conversations/${$(this).data('conversation-id')}/group`, function(r) {
            renderGroupInfo(r.group);
            $('#groupInfoModal').modal('show');
        });
    });

    function renderGroupInfo(g) {
        let members = '';
        (g.users||[]).forEach(u => {
            const isMe = !isStudent && u.id == currentUserId;
            const rm   = isMe ? '' : `<button class="btn btn-sm btn-outline-danger remove-member" data-user-id="${u.id}" data-conversation-id="${g.id}" data-type="user"><i class="fas fa-times"></i></button>`;
            members += `<div class="d-flex align-items-center justify-content-between py-1">
                <span><i class="fas fa-user text-primary mr-2"></i>${u.name}${isMe?' <span class="badge badge-primary">You</span>':''}</span>${rm}</div>`;
        });
        (g.students||[]).forEach(s => {
            const isMe = isStudent && s.id == currentUserId;
            const rm   = isMe ? '' : `<button class="btn btn-sm btn-outline-danger remove-member" data-student-id="${s.id}" data-conversation-id="${g.id}" data-type="student"><i class="fas fa-times"></i></button>`;
            members += `<div class="d-flex align-items-center justify-content-between py-1">
                <span><i class="fas fa-user-graduate text-success mr-2"></i>${s.name||s.first_name}${isMe?' <span class="badge badge-primary">You</span>':''}</span>${rm}</div>`;
        });
        $('#group-info-content').html(`
            <form id="update-group-form" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="conversation_id" value="${g.id}">
                <div class="form-group"><label>Group Name</label>
                    <input type="text" class="form-control" id="update-group-name" name="name" value="${g.name}" required></div>
                <div class="form-group"><label>Avatar</label>
                    <input type="file" class="form-control-file" name="group_avatar" accept="image/*"></div>
                <div class="form-group"><label>Members (${(g.users||[]).length+(g.students||[]).length})</label>
                    <div class="border rounded p-2">${members}</div></div>
                <button type="button" class="btn btn-outline-primary btn-block show-add-members" data-conversation-id="${g.id}">
                    <i class="fas fa-user-plus mr-1"></i>Add Members</button>
            </form>
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-outline-danger leave-group" data-conversation-id="${g.id}"><i class="fas fa-sign-out-alt mr-1"></i>Leave</button>
                <div>
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary update-group ml-1">Save</button>
                </div>
            </div>`);
    }

    $(document).on('click', '.update-group', function() {
        const fd = new FormData($('#update-group-form')[0]);
        const id = fd.get('conversation_id');
        $.ajax({ url:`/chat/conversations/${id}/group`, type:'POST', data:fd, processData:false, contentType:false,
            success: function() { $('#groupInfoModal').modal('hide'); loadConversations(); if (activeConversationId==id) loadConversation(id); }
        });
    });

    $(document).on('click', '.remove-member', function() {
        if (!confirm('Remove this member?')) return;
        const id = $(this).data('conversation-id'), type = $(this).data('type');
        const data = { _token:'{{ csrf_token() }}', type };
        if (type==='user') data.user_id = $(this).data('user-id');
        else data.student_id = $(this).data('student-id');
        $.post(`/chat/conversations/${id}/remove-user`, data, r => renderGroupInfo(r.group));
    });

    $(document).on('click', '.leave-group', function() {
        if (!confirm('Leave this group?')) return;
        const id = $(this).data('conversation-id');
        $.post(`/chat/conversations/${id}/leave`, {_token:'{{ csrf_token() }}'}, function() {
            $('#groupInfoModal').modal('hide');
            if (activeConversationId == id) {
                activeConversationId = null;
                $('#noChatSelected').show();
                $('#chatHeaderContent').hide();
                $('#messages-container').html('');
                $('#chatInputBar').addClass('d-none');
            }
            loadConversations();
        });
    });

    $(document).on('click', '.show-add-members', function() {
        const id = $(this).data('conversation-id');
        $.get(`/chat/users/available/${id}`, function(r) {
            let uHtml = r.users && r.users.length ? '<div class="border rounded p-2" style="max-height:130px;overflow-y:auto;">' + r.users.map(u=>`<div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" name="user_ids[]" value="${u.id}" id="au${u.id}"><label class="custom-control-label" for="au${u.id}">${u.name}</label></div>`).join('') + '</div>' : '<p class="text-muted small">No users available</p>';
            let sHtml = r.students && r.students.length ? '<div class="border rounded p-2" style="max-height:130px;overflow-y:auto;">' + r.students.map(s=>`<div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" name="student_ids[]" value="${s.id}" id="as${s.id}"><label class="custom-control-label" for="as${s.id}">${s.name} <span class="badge badge-success">Student</span></label></div>`).join('') + '</div>' : '<p class="text-muted small">No students available</p>';
            $('#available-users-container').html(uHtml);
            $('#available-students-container').html(sHtml);
            $('#add-members-conversation-id').val(id);
            $('#groupInfoModal').modal('hide');
            $('#addMembersModal').modal('show');
        });
    });

    $('#add-members-btn').on('click', function() {
        const fd = new FormData($('#add-members-form')[0]);
        fd.append('_token', '{{ csrf_token() }}');
        const id = fd.get('conversation_id');
        if (!fd.getAll('user_ids[]').length && !fd.getAll('student_ids[]').length) { alert('Select at least one member'); return; }
        $.ajax({ url:`/chat/conversations/${id}/add-users`, type:'POST', data:fd, processData:false, contentType:false,
            success: function(r) { $('#addMembersModal').modal('hide'); renderGroupInfo(r.group); $('#groupInfoModal').modal('show'); if (activeConversationId==id) loadConversation(id); }
        });
    });

    // Online status
    function updateStatus(s) {
        if (s instanceof Event || s===undefined) s = navigator.onLine;
        $.post('/chat/status', {is_online: s?1:0, _token:'{{ csrf_token() }}'});
    }
    window.addEventListener('online', updateStatus);
    window.addEventListener('offline', updateStatus);
    window.addEventListener('beforeunload', () => updateStatus(false));
    updateStatus(true);
});
</script>
@endsection
