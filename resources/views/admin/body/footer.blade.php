  <!-- /.content-wrapper -->
  <footer class="main-footer">


  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script> 
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 rtl -->
<script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>

<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.world.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js')}}"></script>

<script src="{{ asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js')}}"></script>
{{-- AdminLTE demo scripts removed: dashboard.js/demo.js throw on non-dashboard
     pages (Sparkline "this.element is undefined"), which aborts page JS. --}}
<script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>


<!-- Bootstrap -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->

@yield('additional_js')

@auth
<!-- ═══════════════════════════════════════════════════
     FLOATING CHAT WIDGET
═══════════════════════════════════════════════════ -->
<style>
/* ── FAB button ── */
#fchat-fab {
    position: fixed; bottom: 28px; right: 28px; z-index: 9999;
    width: 54px; height: 54px; border-radius: 50%;
    background: linear-gradient(135deg,#1a6bff,#0a3d99);
    color: #fff; border: none; cursor: pointer;
    box-shadow: 0 4px 20px rgba(26,107,255,.45);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; transition: transform .2s, box-shadow .2s;
}
#fchat-fab:hover { transform: scale(1.1); box-shadow: 0 6px 28px rgba(26,107,255,.6); }
#fchat-badge {
    position: absolute; top: -4px; right: -4px;
    background: #ef4444; color: #fff; font-size: 10px; font-weight: 700;
    min-width: 18px; height: 18px; border-radius: 9px;
    display: none; align-items: center; justify-content: center;
    padding: 0 4px; border: 2px solid #fff;
}

/* ── Chat window ── */
#fchat-window {
    position: fixed; bottom: 92px; right: 28px; z-index: 9998;
    width: 340px; height: 480px; border-radius: 16px;
    background: #fff; box-shadow: 0 8px 40px rgba(0,0,0,.18);
    display: none; flex-direction: column; overflow: hidden;
    border: 1px solid rgba(26,107,255,.15);
    transition: opacity .2s, transform .2s;
}
#fchat-window.open { display: flex; }

/* header */
#fchat-header {
    background: linear-gradient(135deg,#0a1628,#0d2550);
    color: #fff; padding: 12px 16px;
    display: flex; align-items: center; gap: 10px; flex-shrink: 0;
}
#fchat-header-title { flex: 1; font-weight: 600; font-size: 14px; }
#fchat-header-sub   { font-size: 11px; opacity: .7; }
.fchat-hbtn {
    background: rgba(255,255,255,.12); border: none; color: #fff;
    width: 28px; height: 28px; border-radius: 6px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; font-size: 12px;
    transition: background .15s;
}
.fchat-hbtn:hover { background: rgba(255,255,255,.25); }

/* ── Panels ── */
#fchat-list-panel, #fchat-msg-panel { display: flex; flex-direction: column; flex: 1; overflow: hidden; }
#fchat-msg-panel { display: none; }

/* search */
#fchat-search-wrap { padding: 10px 12px; border-bottom: 1px solid #f0f4f8; flex-shrink: 0; }
#fchat-search {
    width: 100%; padding: 6px 10px; border: 1px solid #e2e8f0;
    border-radius: 8px; font-size: 13px; outline: none;
    background: #f8faff; color: #1e293b;
}
#fchat-search:focus { border-color: #1a6bff; }

/* tabs */
#fchat-tabs { display: flex; gap: 4px; padding: 0 12px 8px; flex-shrink: 0; }
.fchat-tab {
    flex: 1; padding: 4px; border: none; border-radius: 6px;
    font-size: 12px; font-weight: 500; cursor: pointer;
    background: #f1f5f9; color: #64748b; transition: all .15s;
}
.fchat-tab.active { background: #1a6bff; color: #fff; }

/* conversation list */
#fchat-convlist { flex: 1; overflow-y: auto; }
.fchat-conv-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px; cursor: pointer; border-bottom: 1px solid #f8faff;
    transition: background .12s;
}
.fchat-conv-item:hover { background: #f4f7ff; }
.fchat-conv-item.active { background: #eef2ff; }
.fchat-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg,#1a6bff,#0a3d99);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 600; flex-shrink: 0; position: relative;
}
.fchat-online-dot {
    position: absolute; bottom: 1px; right: 1px;
    width: 9px; height: 9px; border-radius: 50%;
    background: #10b981; border: 2px solid #fff;
}
.fchat-conv-info { flex: 1; min-width: 0; }
.fchat-conv-name { font-size: 13px; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.fchat-conv-last { font-size: 11px; color: #94a3b8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 1px; }
.fchat-conv-unread {
    background: #1a6bff; color: #fff; font-size: 10px; font-weight: 700;
    min-width: 18px; height: 18px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center; padding: 0 4px; flex-shrink: 0;
}

/* user list */
#fchat-userlist { flex: 1; overflow-y: auto; }
.fchat-user-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 14px; cursor: pointer; border-bottom: 1px solid #f8faff;
    transition: background .12s;
}
.fchat-user-item:hover { background: #f4f7ff; }

/* messages */
#fchat-msg-back {
    padding: 10px 12px; border-bottom: 1px solid #f0f4f8; flex-shrink: 0;
    display: flex; align-items: center; gap: 8px;
}
#fchat-msg-back > button:first-child {
    background: rgba(26,107,255,.08); border: none; color: #1a6bff; font-size: 13px;
    width: 28px; height: 28px; border-radius: 7px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    transition: background .15s;
}
#fchat-msg-back > button:first-child:hover { background: rgba(26,107,255,.16); }
#fchat-msg-name { font-size: 13px; font-weight: 600; color: #1e293b; }
body.dark-mode #fchat-msg-back > button:first-child { background: rgba(26,107,255,.15); color: #6ea8fe; }
body.dark-mode #fchat-msg-name { color: #d0d8e8; }

#fchat-messages {
    flex: 1; overflow-y: auto; padding: 12px 14px;
    display: flex; flex-direction: column; gap: 8px;
    background: #f8faff;
}
.fchat-bubble-wrap { display: flex; align-items: flex-end; gap: 6px; }
.fchat-bubble-wrap.mine { flex-direction: row-reverse; }
.fchat-bubble {
    max-width: 72%; padding: 8px 12px; border-radius: 14px;
    font-size: 13px; line-height: 1.5; word-break: break-word;
}
.fchat-bubble.theirs {
    background: #fff; color: #1e293b;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
}
.fchat-bubble.mine {
    background: linear-gradient(135deg,#1a6bff,#0a3d99);
    color: #fff; border-bottom-right-radius: 4px;
}
.fchat-time { font-size: 10px; color: #94a3b8; margin-top: 2px; text-align: right; }

/* input area */
#fchat-input-wrap {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 12px; border-top: 1px solid #f0f4f8; flex-shrink: 0;
    background: #fff;
}
#fchat-input {
    flex: 1; padding: 8px 12px; border: 1px solid #e2e8f0;
    border-radius: 20px; font-size: 13px; outline: none;
    background: #f8faff; color: #1e293b; resize: none;
    max-height: 80px; overflow-y: auto;
}
#fchat-input:focus { border-color: #1a6bff; background: #fff; }
#fchat-send {
    width: 36px; height: 36px; border-radius: 50%; border: none;
    background: linear-gradient(135deg,#1a6bff,#0a3d99);
    color: #fff; cursor: pointer; font-size: 14px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: transform .15s;
}
#fchat-send:hover { transform: scale(1.1); }

/* empty state */
.fchat-empty { text-align: center; padding: 30px 20px; color: #94a3b8; font-size: 13px; }
.fchat-empty i { font-size: 32px; margin-bottom: 8px; opacity: .4; display: block; }

/* dark mode */
body.dark-mode #fchat-window  { background: #0f2040; border-color: rgba(255,255,255,.08); }
body.dark-mode #fchat-search  { background: #0a1628; border-color: rgba(255,255,255,.12); color: #d0d8e8; }
body.dark-mode .fchat-conv-item:hover, body.dark-mode .fchat-user-item:hover { background: rgba(26,107,255,.1); }
body.dark-mode .fchat-conv-item.active { background: rgba(26,107,255,.18); }
body.dark-mode .fchat-conv-name, body.dark-mode #fchat-msg-name { color: #d0d8e8; }
body.dark-mode .fchat-conv-last { color: #4a6080; }
body.dark-mode .fchat-bubble.theirs { background: #0d1e38; color: #d0d8e8; }
body.dark-mode #fchat-messages { background: #0a1628; }
body.dark-mode #fchat-input-wrap { background: #0f2040; border-top-color: rgba(255,255,255,.08); }
body.dark-mode #fchat-input  { background: #0a1628; border-color: rgba(255,255,255,.12); color: #d0d8e8; }
body.dark-mode #fchat-search-wrap, body.dark-mode #fchat-msg-back { border-color: rgba(255,255,255,.06); }
body.dark-mode .fchat-conv-item, body.dark-mode .fchat-user-item { border-color: rgba(255,255,255,.04); }
body.dark-mode .fchat-bubble-wrap .fchat-time { color: #4a6080; }
body.dark-mode #fchat-tabs .fchat-tab { background: #0d1e38; color: #6a86aa; }
body.dark-mode #fchat-tabs .fchat-tab.active { background: #1a6bff; color: #fff; }
body.dark-mode #fchat-group-name { background: #0a1628; border-color: rgba(255,255,255,.12); color: #d0d8e8; }
body.dark-mode #fchat-grouplist > div { border-color: rgba(255,255,255,.06) !important; }
.fchat-member-item {
    display:flex; align-items:center; gap:10px;
    padding:8px 14px; cursor:pointer; border-bottom:1px solid #f8faff; transition:background .12s;
}
.fchat-member-item:hover { background:#f4f7ff; }
.fchat-member-item input[type=checkbox] { width:15px; height:15px; cursor:pointer; accent-color:#1a6bff; flex-shrink:0; }
body.dark-mode .fchat-member-item:hover { background:rgba(26,107,255,.1); }
body.dark-mode .fchat-member-item { border-color:rgba(255,255,255,.04); }
</style>

<!-- FAB -->
<button id="fchat-fab" title="Chat" onclick="fchatToggle()">
    <i class="fas fa-comment-dots"></i>
    <span id="fchat-badge"></span>
</button>

<!-- Window -->
<div id="fchat-window">

    <!-- Header -->
    <div id="fchat-header">
        <div class="fchat-avatar" style="width:34px;height:34px;font-size:13px;flex-shrink:0;">
            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
        </div>
        <div style="flex:1;min-width:0;">
            <div id="fchat-header-title">Messages</div>
            <div id="fchat-header-sub">{{ auth()->user()->name }}</div>
        </div>
        <button class="fchat-hbtn" onclick="fchatToggle()" title="Close" style="margin-left:auto;flex-shrink:0;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Conversation list panel -->
    <div id="fchat-list-panel">
        <div id="fchat-search-wrap">
            <input id="fchat-search" type="text" placeholder="Search conversations or people…" oninput="fchatSearch(this.value)">
        </div>
        <div id="fchat-tabs">
            <button class="fchat-tab active" onclick="fchatShowTab('convs',this)">Chats</button>
            <button class="fchat-tab"        onclick="fchatShowTab('users',this)">New Chat</button>
            <button class="fchat-tab"        onclick="fchatShowTab('group',this)">New Group</button>
        </div>
        <div id="fchat-convlist"></div>
        <div id="fchat-userlist" style="display:none;"></div>
        <div id="fchat-grouplist" style="display:none; flex-direction:column; flex:1; overflow:hidden;">
            <div style="padding:8px 12px; border-bottom:1px solid #f0f4f8; flex-shrink:0;">
                <input id="fchat-group-name" type="text" placeholder="Group name…"
                    style="width:100%;padding:6px 10px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none;background:#f8faff;color:#1e293b;">
            </div>
            <div style="padding:6px 12px 4px; font-size:11px; color:#94a3b8; flex-shrink:0;">Select members:</div>
            <div id="fchat-group-members" style="flex:1; overflow-y:auto;"></div>
            <div style="padding:8px 12px; border-top:1px solid #f0f4f8; flex-shrink:0;">
                <button onclick="fchatCreateGroup()"
                    style="width:100%;padding:7px;border:none;border-radius:8px;background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;font-size:13px;font-weight:600;cursor:pointer;">
                    <i class="fas fa-users"></i> Create Group
                </button>
            </div>
        </div>
    </div>

    <!-- Message panel -->
    <div id="fchat-msg-panel">
        <div id="fchat-msg-back">
            <button onclick="fchatBackToList()" title="Back" style="flex-shrink:0;"><i class="fas fa-arrow-left"></i></button>
            <div class="fchat-avatar" id="fchat-msg-avatar" style="width:32px;height:32px;font-size:12px;flex-shrink:0;"></div>
            <span id="fchat-msg-name"></span>
            <button class="fchat-hbtn" onclick="fchatToggle()" title="Close" style="margin-left:auto;background:rgba(0,0,0,.06);color:#64748b;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="fchat-messages"></div>
        <div id="fchat-input-wrap">
            <textarea id="fchat-input" placeholder="Type a message…" rows="1"
                      onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();fchatSend();}"></textarea>
            <button id="fchat-send" onclick="fchatSend()" title="Send">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    var CSRF      = '{{ csrf_token() }}';
    var ME        = {{ auth()->id() }};
    var ME_NAME   = '{{ auth()->user()->name }}';
    var URL_CONVS = '{{ route("chat.conversations") }}';
    var URL_SEND  = '{{ route("chat.send") }}';
    var URL_CREATE= '{{ route("chat.create") }}';
    var URL_USERS = '{{ route("chat.users") }}';

    var state = {
        open: false,
        tab: 'convs',           // 'convs' | 'users'
        convId: null,
        convName: '',
        convs: [],
        users: [],
        students: [],
        pollTimer: null,
        msgTimer: null,
        totalUnread: 0,
    };

    /* ── toggle ── */
    window.fchatToggle = function () {
        state.open = !state.open;
        var win = document.getElementById('fchat-window');
        win.classList.toggle('open', state.open);
        if (state.open && !state.convId) fchatLoadConvs();
    };

    /* ── tabs ── */
    window.fchatShowTab = function (tab, btn) {
        state.tab = tab;
        document.querySelectorAll('.fchat-tab').forEach(function(b){ b.classList.remove('active'); });
        btn.classList.add('active');
        document.getElementById('fchat-convlist').style.display   = tab === 'convs' ? ''      : 'none';
        document.getElementById('fchat-userlist').style.display   = tab === 'users' ? ''      : 'none';
        document.getElementById('fchat-grouplist').style.display  = tab === 'group' ? 'flex'  : 'none';
        if ((tab === 'users' || tab === 'group') && state.users.length === 0) fchatLoadUsers();
        if (tab === 'group') renderGroupMembers();
    };

    /* ── search ── */
    window.fchatSearch = function (q) {
        q = q.toLowerCase();
        if (state.tab === 'convs') {
            document.querySelectorAll('.fchat-conv-item').forEach(function(el){
                el.style.display = el.dataset.name.toLowerCase().includes(q) ? '' : 'none';
            });
        } else {
            document.querySelectorAll('.fchat-user-item').forEach(function(el){
                el.style.display = el.dataset.name.toLowerCase().includes(q) ? '' : 'none';
            });
        }
    };

    /* ── load conversations ── */
    function fchatLoadConvs() {
        fetch(URL_CONVS, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r){ return r.json(); })
            .then(function(data) {
                state.convs = data.conversations || [];
                renderConvs();
                updateBadge();
                clearTimeout(state.pollTimer);
                state.pollTimer = setTimeout(fchatLoadConvs, 8000);
            });
    }

    function renderConvs() {
        var el = document.getElementById('fchat-convlist');
        if (!state.convs.length) {
            el.innerHTML = '<div class="fchat-empty"><i class="fas fa-comments"></i>No conversations yet.<br>Start one from "New Chat".</div>';
            return;
        }
        state.totalUnread = 0;
        el.innerHTML = state.convs.map(function(c) {
            state.totalUnread += (c.unread_count || 0);
            var name = getConvName(c);
            var last = c.last_message ? (c.last_message.body || '📎 Attachment') : 'No messages yet';
            var unread = c.unread_count > 0
                ? '<span class="fchat-conv-unread">'+c.unread_count+'</span>' : '';
            return '<div class="fchat-conv-item" data-name="'+esc(name)+'" data-id="'+c.id+'" onclick="fchatOpenConv('+c.id+',\''+esc(name)+'\')">'
                + '<div class="fchat-avatar">'+initials(name)+'</div>'
                + '<div class="fchat-conv-info">'
                +   '<div class="fchat-conv-name">'+esc(name)+'</div>'
                +   '<div class="fchat-conv-last">'+esc(last)+'</div>'
                + '</div>'
                + unread
                + '</div>';
        }).join('');
    }

    function getConvName(c) {
        if (c.name) return c.name;
        var others = (c.users || []).filter(function(u){ return u.id !== ME; });
        var studs  = c.students || [];
        var names  = others.map(function(u){ return u.name; })
                           .concat(studs.map(function(s){ return (s.first_name||'') + ' ' + (s.last_name||''); }));
        return names.join(', ') || 'Unknown';
    }

    function updateBadge() {
        var b = document.getElementById('fchat-badge');
        if (state.totalUnread > 0) {
            b.style.display = 'flex';
            b.textContent   = state.totalUnread > 99 ? '99+' : state.totalUnread;
        } else {
            b.style.display = 'none';
        }
    }

    /* ── load users for new chat ── */
    function fchatLoadUsers() {
        fetch(URL_USERS, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r){ return r.json(); })
            .then(function(data) {
                state.users    = data.users    || [];
                state.students = data.students || [];
                renderUsers();
            });
    }

    function renderUsers() {
        var el = document.getElementById('fchat-userlist');
        var all = state.users.map(function(u){
            return { id: u.id, name: u.name, online: u.is_online, type: 'user' };
        }).concat(state.students.map(function(s){
            return { id: s.id, name: (s.first_name||'')+' '+(s.last_name||''), online: s.is_online, type: 'student' };
        }));

        if (!all.length) {
            el.innerHTML = '<div class="fchat-empty"><i class="fas fa-users"></i>No users found.</div>';
            return;
        }
        el.innerHTML = all.map(function(u) {
            var dot = u.online ? '<div class="fchat-online-dot"></div>' : '';
            var badge = u.type === 'student'
                ? '<span style="font-size:10px;background:#e0f2fe;color:#0369a1;padding:1px 5px;border-radius:4px;margin-left:4px;">Student</span>' : '';
            return '<div class="fchat-user-item" data-name="'+esc(u.name)+'" onclick="fchatStartConv('+u.id+',\''+u.type+'\',\''+esc(u.name)+'\')">'
                + '<div class="fchat-avatar">'+initials(u.name)+dot+'</div>'
                + '<div class="fchat-conv-info"><div class="fchat-conv-name">'+esc(u.name)+badge+'</div></div>'
                + '</div>';
        }).join('');
    }

    /* ── start conversation from user list ── */
    window.fchatStartConv = function (targetId, type, name) {
        fetch(URL_CREATE, {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With':'XMLHttpRequest' },
            body: JSON.stringify({ user_id: type==='user'?targetId:null, student_id: type==='student'?targetId:null, type: type })
        })
        .then(function(r){ return r.json(); })
        .then(function(data) {
            fchatOpenConv(data.conversation.id, name);
        });
    };

    /* ── open a conversation ── */
    window.fchatOpenConv = function (id, name) {
        state.convId   = id;
        state.convName = name;
        document.getElementById('fchat-list-panel').style.display  = 'none';
        document.getElementById('fchat-msg-panel').style.display   = 'flex';
        document.getElementById('fchat-msg-name').textContent      = name;
        document.getElementById('fchat-msg-avatar').textContent    = initials(name);
        document.getElementById('fchat-messages').innerHTML = '<div class="fchat-empty"><i class="fas fa-spinner fa-spin"></i>Loading…</div>';
        fchatLoadMessages();
    };

    /* ── back to list ── */
    window.fchatBackToList = function () {
        state.convId = null;
        clearTimeout(state.msgTimer);
        document.getElementById('fchat-msg-panel').style.display  = 'none';
        document.getElementById('fchat-list-panel').style.display = 'flex';
        fchatLoadConvs();
    };

    /* ── load messages ── */
    function fchatLoadMessages() {
        if (!state.convId) return;
        fetch('/chat/conversations/'+state.convId+'/messages', { headers: { 'X-Requested-With':'XMLHttpRequest' } })
            .then(function(r){ return r.json(); })
            .then(function(data) {
                renderMessages(data.messages || []);
                clearTimeout(state.msgTimer);
                state.msgTimer = setTimeout(fchatLoadMessages, 4000);
            });
    }

    function renderMessages(msgs) {
        var el = document.getElementById('fchat-messages');
        if (!msgs.length) {
            el.innerHTML = '<div class="fchat-empty"><i class="fas fa-comment-slash"></i>No messages yet. Say hello!</div>';
            return;
        }
        var atBottom = el.scrollHeight - el.scrollTop - el.clientHeight < 60;
        el.innerHTML = msgs.map(function(m) {
            var mine = m.user_id === ME;
            var cls  = mine ? 'mine' : 'theirs';
            var time = m.created_at ? new Date(m.created_at).toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'}) : '';
            var body = m.body ? esc(m.body) : '<em style="opacity:.6">📎 Attachment</em>';
            return '<div class="fchat-bubble-wrap '+cls+'">'
                + '<div class="fchat-bubble '+cls+'">'+body+'<div class="fchat-time">'+time+'</div></div>'
                + '</div>';
        }).join('');
        if (atBottom) el.scrollTop = el.scrollHeight;
    }

    /* ── send message ── */
    window.fchatSend = function () {
        var input = document.getElementById('fchat-input');
        var body  = input.value.trim();
        if (!body || !state.convId) return;
        input.value = '';
        var fd = new FormData();
        fd.append('conversation_id', state.convId);
        fd.append('body', body);
        fetch(URL_SEND, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}, body: fd })
            .then(function(r){ return r.json(); })
            .then(function(){ fchatLoadMessages(); });
    };

    /* ── group members list ── */
    function renderGroupMembers() {
        var el = document.getElementById('fchat-group-members');
        var all = state.users.map(function(u){
            return { id: u.id, name: u.name, type: 'user' };
        }).concat(state.students.map(function(s){
            return { id: s.id, name: (s.first_name||'')+' '+(s.last_name||''), type: 'student' };
        }));
        if (!all.length) {
            el.innerHTML = '<div class="fchat-empty"><i class="fas fa-users"></i>No users found.</div>';
            return;
        }
        el.innerHTML = all.map(function(u) {
            var badge = u.type === 'student'
                ? '<span style="font-size:10px;background:#e0f2fe;color:#0369a1;padding:1px 5px;border-radius:4px;margin-left:4px;">Student</span>' : '';
            return '<label class="fchat-member-item">'
                + '<input type="checkbox" value="'+u.id+'" data-type="'+u.type+'">'
                + '<div class="fchat-avatar" style="width:30px;height:30px;font-size:11px;">'+initials(u.name)+'</div>'
                + '<div class="fchat-conv-info"><div class="fchat-conv-name">'+esc(u.name)+badge+'</div></div>'
                + '</label>';
        }).join('');
    }

    /* ── create group conversation ── */
    window.fchatCreateGroup = function () {
        var name = document.getElementById('fchat-group-name').value.trim();
        if (!name) { document.getElementById('fchat-group-name').focus(); return; }
        var userIds = [], studentIds = [];
        document.querySelectorAll('#fchat-group-members input[type=checkbox]:checked').forEach(function(cb){
            if (cb.dataset.type === 'student') studentIds.push(parseInt(cb.value));
            else userIds.push(parseInt(cb.value));
        });
        if (userIds.length + studentIds.length === 0) {
            alert('Select at least one member.');
            return;
        }
        fetch('{{ route("chat.create-group") }}', {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With':'XMLHttpRequest' },
            body: JSON.stringify({ name: name, user_ids: userIds, student_ids: studentIds })
        })
        .then(function(r){ return r.json(); })
        .then(function(data) {
            document.getElementById('fchat-group-name').value = '';
            document.querySelectorAll('#fchat-group-members input[type=checkbox]').forEach(function(cb){ cb.checked = false; });
            fchatOpenConv(data.conversation.id, name);
        });
    };

    /* ── helpers ── */
    function initials(name) {
        return (name||'?').split(' ').slice(0,2).map(function(w){ return w[0]||''; }).join('').toUpperCase();
    }
    function esc(s) {
        return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Poll for badge count even when closed
    setTimeout(fchatLoadConvs, 3000);
})();
</script>
@endauth

<!-- ChartJS -->

