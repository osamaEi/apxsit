@extends('students.index')

@section('content')
<style>
.chat-wrap {
    display: flex;
    height: calc(100vh - 120px);
    min-height: 500px;
    background: #f4f6f9;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.08);
}

/* ── Sidebar ── */
.chat-sidebar {
    width: 270px;
    flex-shrink: 0;
    background: #fff;
    border-right: 1px solid #e9ecef;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.sidebar-header {
    padding: 13px 16px;
    background: #1a6bff;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #e9ecef;
}
.sidebar-header h6 { margin:0; font-weight:600; font-size:.95rem; }
.sidebar-search {
    padding: 8px 12px;
    border-bottom: 1px solid #f0f0f0;
}
.sidebar-search input {
    width: 100%;
    padding: 5px 10px 5px 28px;
    border: 1px solid #e0e0e0;
    border-radius: 20px;
    font-size: .8rem;
    background: #f8f9fa url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23999' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.44 1.406a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z'/%3E%3C/svg%3E") no-repeat 10px center;
    outline: none;
}
.sidebar-section-title {
    padding: 7px 14px 3px;
    font-size: .68rem;
    font-weight: 700;
    color: #aaa;
    text-transform: uppercase;
    letter-spacing: .05em;
}
.sidebar-body { flex:1; overflow-y:auto; }
.sidebar-body::-webkit-scrollbar { width:4px; }
.sidebar-body::-webkit-scrollbar-thumb { background:#ddd; border-radius:4px; }

/* conv items */
.conv-item {
    display:flex; align-items:center; gap:10px;
    padding:9px 14px; cursor:pointer;
    border-left:3px solid transparent;
    transition:background .15s;
    list-style:none;
}
.conv-item:hover { background:#f8f9fa; }
.conv-item.active { background:#f0e6f0; border-left-color:#1a6bff; }
.conv-avatar {
    width:36px; height:36px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:.78rem; font-weight:600; flex-shrink:0;
    background:#e9ecef; color:#555;
}
.conv-avatar img { width:36px; height:36px; border-radius:50%; object-fit:cover; }
.conv-info { flex:1; min-width:0; }
.conv-name { font-size:.83rem; font-weight:600; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.conv-last { font-size:.73rem; color:#aaa; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.conv-badge { background:#ea5455; color:#fff; border-radius:20px; font-size:.62rem; padding:1px 6px; font-weight:700; flex-shrink:0; }

/* contact items */
.contact-item { display:flex; align-items:center; gap:10px; padding:7px 14px; cursor:pointer; transition:background .15s; }
.contact-item:hover { background:#f8f9fa; }
.contact-name { font-size:.82rem; font-weight:500; flex:1; }
.btn-chat-start {
    width:26px; height:26px; border-radius:50%; border:none;
    background:#f0e6f0; color:#1a6bff;
    display:flex; align-items:center; justify-content:center;
    font-size:.72rem; flex-shrink:0; cursor:pointer;
    transition:background .15s;
}
.btn-chat-start:hover { background:#1a6bff; color:#fff; }

/* ── Chat Main ── */
.chat-main { flex:1; display:flex; flex-direction:column; min-width:0; background:#f4f6f9; }
.chat-topbar {
    padding:11px 18px;
    background:#fff;
    border-bottom:1px solid #e9ecef;
    display:flex; align-items:center; justify-content:space-between;
    min-height:58px;
}
.participant-name { font-size:.95rem; font-weight:600; margin:0; }
.participant-status { font-size:.73rem; }
.chat-messages { flex:1; overflow-y:auto; padding:16px 20px; }
.chat-messages::-webkit-scrollbar { width:4px; }
.chat-messages::-webkit-scrollbar-thumb { background:#ddd; border-radius:4px; }

/* bubbles */
.msg-row { display:flex; margin-bottom:12px; }
.msg-row.me { justify-content:flex-end; }
.msg-bubble {
    max-width:68%; padding:8px 12px; border-radius:14px;
    font-size:.875rem; line-height:1.45;
}
.msg-row.me   .msg-bubble { background:#1a6bff; color:#fff; border-bottom-right-radius:4px; }
.msg-row.them .msg-bubble { background:#fff; color:#333; border-bottom-left-radius:4px; box-shadow:0 1px 3px rgba(0,0,0,.06); }
.msg-time { font-size:.67rem; margin-top:3px; opacity:.7; text-align:right; }
.msg-sender { font-size:.7rem; font-weight:600; margin-bottom:2px; opacity:.8; }
.date-divider { text-align:center; margin:12px 0; }
.date-divider span { background:#e9ecef; color:#888; font-size:.7rem; padding:2px 10px; border-radius:20px; }

/* input bar */
.chat-inputbar { padding:10px 14px; background:#fff; border-top:1px solid #e9ecef; }
.input-row {
    display:flex; align-items:center; gap:6px;
    background:#f4f6f9; border-radius:25px;
    padding:5px 5px 5px 13px;
}
.input-row input[type=text] { flex:1; border:none; background:transparent; font-size:.875rem; outline:none; }
.btn-icon {
    width:32px; height:32px; border-radius:50%; border:none;
    display:flex; align-items:center; justify-content:center;
    font-size:.83rem; cursor:pointer; transition:background .15s;
    background:transparent; color:#888;
}
.btn-icon:hover { background:#e9ecef; color:#555; }
.btn-send {
    width:32px; height:32px; border-radius:50%; border:none;
    background:#1a6bff; color:#fff;
    display:flex; align-items:center; justify-content:center;
    font-size:.83rem; cursor:pointer; flex-shrink:0;
}
.btn-send:hover { background:#1558d6; }
.attach-info { font-size:.78rem; color:#888; padding:4px 6px 0; }
.recording-indicator { color:#ea5455; font-weight:500; }
</style>

<div class="chat-wrap">
    {{-- Sidebar --}}
    <div class="chat-sidebar">
        <div class="sidebar-header">
            <h6><i class="fas fa-comments mr-2"></i>Messages</h6>
        </div>
        <div class="sidebar-search">
            <input type="text" id="sidebarSearch" placeholder="Search...">
        </div>
        <div class="sidebar-body">
            <div class="sidebar-section-title">Conversations</div>
            <ul class="conversation-list" style="margin:0; padding:0;"></ul>

            <div class="sidebar-section-title mt-2">Support Staff</div>
            @php $registers = \App\Models\User::where('role','Register')->orWhere('role','Admin')->orWhere('role','Sales')->get(); @endphp
            @foreach($registers as $user)
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
                    <i class="fas fa-comment" style="font-size:.7rem;"></i>
                </button>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Chat Main --}}
    <div class="chat-main">
        {{-- Topbar --}}
        <div class="chat-topbar">
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
                    <label for="attachment" class="btn-icon mb-0" style="cursor:pointer;" title="Attach file">
                        <i class="fas fa-paperclip"></i>
                    </label>
                    <input type="file" id="attachment" name="attachment" class="d-none">
                    <button type="button" id="record-voice" class="btn-icon" title="Voice message">
                        <i class="fas fa-microphone"></i>
                    </button>
                    <button type="button" id="stop-recording" class="btn-icon d-none" style="color:#ea5455;" title="Stop">
                        <i class="fas fa-stop-circle"></i>
                    </button>
                    <input type="text" id="message-input" name="body" placeholder="Type a message...">
                    <button type="submit" class="btn-send" title="Send">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="attach-info">
                    <span id="attachment-name"></span>
                    <span id="recording-status" class="recording-indicator"></span>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Group Info Modal --}}
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
                    <div class="form-group"><label>Users</label><div id="available-users-container"></div></div>
                    <div class="form-group"><label>Students</label><div id="available-students-container"></div></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="add-members-btn" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let mediaRecorder, audioChunks = [], recordingTime = 0, recordingInterval, isRecording = false;
    let activeConversationId = null, activeConversationIsGroup = false;
    const isStudent = true;
    const currentUserId = {{ Auth::guard('student')->id() }};

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

    function renderConversations(conversations) {
        let html = '';
        if (!conversations.length) {
            html = '<li class="conv-item" style="cursor:default; color:#aaa; font-size:.8rem;">No conversations yet</li>';
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
                    let other = c.users && c.users[0] ? c.users[0] : null;
                    if (other) {
                        name = other.name;
                        av = other.avatar
                            ? `<div class="conv-avatar"><img src="/storage/${other.avatar}"></div>`
                            : `<div class="conv-avatar" style="background:#ebf3ff;color:#3a6eff;"><i class="fas fa-user"></i></div>`;
                    }
                }

                const body = c.last_message ? c.last_message.body || '' : '';
                const isToken = body.length > 40 && !body.includes(' ');
                const last = c.last_message
                    ? (c.last_message.message_type === 'voice' ? '🎤 Voice'
                     : c.last_message.attachment ? '📎 File'
                     : isToken ? '💬 Message'
                     : body.substring(0, 32))
                    : '';

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
        activeConversationId = $(this).data('conversation-id');
        activeConversationIsGroup = $(this).data('is-group');
        $('.conversation-item').removeClass('active');
        $(this).addClass('active');
        $('#chatInputBar').removeClass('d-none');
        loadConversation(activeConversationId);
        loadMessages(activeConversationId);
    });

    function loadConversation(id) {
        $.get(`/chat/conversations/${id}`, function(r) {
            renderHeader(r.conversation);
            $('#conversation-id').val(id);
            $('#chatHeaderContent').css('display','flex');
        });
    }

    function renderHeader(c) {
        let name = '', status = '', avHtml = '', actions = '';
        if (c.is_group) {
            name = c.name;
            const total = (c.users||[]).length + (c.students||[]).length;
            status = `${total} members`;
            avHtml = c.avatar
                ? `<img src="/storage/${c.avatar}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">`
                : `<div class="conv-avatar" style="background:#f0e6f0;color:#1a6bff;"><i class="fas fa-users"></i></div>`;
            actions = `<button class="btn btn-sm btn-outline-secondary show-group-info" data-conversation-id="${c.id}"><i class="fas fa-info-circle mr-1"></i>Info</button>`;
        } else {
            const other = c.users && c.users[0] ? c.users[0] : null;
            if (other) {
                name = other.name;
                status = other.is_online
                    ? '<span style="color:#28c76f;"><i class="fas fa-circle" style="font-size:.5rem;"></i> Online</span>'
                    : '<span class="text-muted">Offline</span>';
                avHtml = other.avatar
                    ? `<img src="/storage/${other.avatar}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">`
                    : `<div class="conv-avatar" style="background:#ebf3ff;color:#3a6eff;"><i class="fas fa-user"></i></div>`;
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
        if (!messages.length) { $('#messages-container').html(''); return; }
        let html = '', curDate = null;
        messages.forEach(function(m) {
            const d = new Date(m.created_at).toLocaleDateString();
            if (d !== curDate) { html += `<div class="date-divider"><span>${d}</span></div>`; curDate = d; }
            const mine  = m.student_id == currentUserId;
            const side  = mine ? 'me' : 'them';
            const time  = new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
            let content = '';
            if (m.message_type === 'voice') {
                content = `<audio controls style="max-width:200px;"><source src="/storage/${m.voice_message}" type="audio/mpeg"></audio>`;
            } else if (m.attachment) {
                const isImg = m.attachment_type && m.attachment_type.startsWith('image/');
                content = isImg
                    ? `<a href="/storage/${m.attachment}" target="_blank"><img src="/storage/${m.attachment}" style="max-width:180px;border-radius:8px;"></a>`
                    : `<a href="/storage/${m.attachment}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file mr-1"></i>${m.attachment.split('/').pop()}</a>`;
            }
            if (m.body) content += `<div>${m.body}</div>`;
            let sender = '';
            if (activeConversationIsGroup && !mine) {
                const sn = m.user ? m.user.name : (m.student ? (m.student.first_name || m.student.name) : '');
                sender = `<div class="msg-sender">${sn}</div>`;
            }
            html += `<div class="msg-row ${side}"><div class="msg-bubble">${sender}${content}<div class="msg-time">${time}</div></div></div>`;
        });
        $('#messages-container').html(html);
    }

    function scrollBottom() {
        const el = document.getElementById('messages-container');
        if (el) el.scrollTop = el.scrollHeight;
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
            success: function() {
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
                $('#recording-status').text(`🔴 ${m}:${s<10?'0':''}${s}`);
            }, 1000);
        }).catch(() => alert('Cannot access microphone.'));
    });

    $('#stop-recording').on('click', function() {
        if (mediaRecorder && isRecording) {
            mediaRecorder.stop(); isRecording = false; clearInterval(recordingInterval);
            $('#record-voice').removeClass('d-none'); $('#stop-recording').addClass('d-none');
            $('#recording-status').text(`✅ Recorded (${Math.floor(recordingTime/60)}:${String(recordingTime%60).padStart(2,'0')})`);
            mediaRecorder.stream.getTracks().forEach(t => t.stop());
        }
    });

    function resetVoice() {
        audioChunks = []; recordingTime = 0; clearInterval(recordingInterval);
        $('#record-voice').removeClass('d-none'); $('#stop-recording').addClass('d-none');
        $('#recording-status').text('');
        if (mediaRecorder && mediaRecorder.stream) mediaRecorder.stream.getTracks().forEach(t => t.stop());
    }

    $(document).on('click', '.show-group-info', function() {
        $.get(`/chat/conversations/${$(this).data('conversation-id')}/group`, function(r) {
            let members = '';
            (r.group.users||[]).forEach(u => {
                members += `<div class="d-flex align-items-center justify-content-between py-1 border-bottom">
                    <span><i class="fas fa-user text-primary mr-2"></i>${u.name}</span>
                    <button class="btn btn-sm btn-outline-danger remove-member" data-user-id="${u.id}" data-conversation-id="${r.group.id}" data-type="user"><i class="fas fa-times"></i></button>
                </div>`;
            });
            $('#group-info-content').html(`
                <form id="update-group-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="conversation_id" value="${r.group.id}">
                    <div class="form-group"><label>Group Name</label>
                        <input type="text" class="form-control" name="name" value="${r.group.name}" required></div>
                    <div class="form-group"><label>Members</label><div class="border rounded p-2">${members}</div></div>
                </form>
                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-outline-danger leave-group" data-conversation-id="${r.group.id}"><i class="fas fa-sign-out-alt mr-1"></i>Leave</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>`);
            $('#groupInfoModal').modal('show');
        });
    });

    $(document).on('click', '.remove-member', function() {
        if (!confirm('Remove this member?')) return;
        const id = $(this).data('conversation-id');
        $.post(`/chat/conversations/${id}/remove-user`, {
            _token:'{{ csrf_token() }}', type:$(this).data('type'),
            user_id:$(this).data('user-id'), student_id:$(this).data('student-id')
        }, r => { if (activeConversationId==id) loadConversation(id); });
    });

    $(document).on('click', '.leave-group', function() {
        if (!confirm('Leave this group?')) return;
        const id = $(this).data('conversation-id');
        $.post(`/chat/conversations/${id}/leave`, {_token:'{{ csrf_token() }}'}, function() {
            $('#groupInfoModal').modal('hide');
            if (activeConversationId == id) {
                activeConversationId = null;
                $('#chatHeaderContent').hide();
                $('#messages-container').html('');
                $('#chatInputBar').addClass('d-none');
            }
            loadConversations();
        });
    });

    // Online status
    function updateStatus(s) {
        if (s instanceof Event || s === undefined) s = navigator.onLine;
        $.post('/chat/status', {is_online: s?1:0, _token:'{{ csrf_token() }}'});
    }
    window.addEventListener('online', updateStatus);
    window.addEventListener('offline', updateStatus);
    window.addEventListener('beforeunload', () => updateStatus(false));
    updateStatus(true);
});
</script>
@endsection
