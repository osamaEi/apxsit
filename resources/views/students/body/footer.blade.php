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
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('dist/js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js')}}"></script>
<script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>


<!-- Bootstrap -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->

@yield('additional_js')

@auth('student')
<!-- ═══════════════════════ FLOATING CHAT (Student) ═══════════════════════ -->
<style>
#sfchat-fab {
    position:fixed; bottom:28px; right:28px; z-index:9999;
    width:54px; height:54px; border-radius:50%;
    background:linear-gradient(135deg,#1a6bff,#0a3d99);
    color:#fff; border:none; cursor:pointer;
    box-shadow:0 4px 20px rgba(26,107,255,.45);
    display:flex; align-items:center; justify-content:center;
    font-size:20px; transition:transform .2s, box-shadow .2s;
}
#sfchat-fab:hover { transform:scale(1.1); box-shadow:0 6px 28px rgba(26,107,255,.6); }
#sfchat-badge {
    position:absolute; top:-4px; right:-4px;
    background:#ef4444; color:#fff; font-size:10px; font-weight:700;
    min-width:18px; height:18px; border-radius:9px;
    display:none; align-items:center; justify-content:center;
    padding:0 4px; border:2px solid #fff;
}
#sfchat-window {
    position:fixed; bottom:92px; right:28px; z-index:9998;
    width:340px; height:480px; border-radius:16px;
    background:#fff; box-shadow:0 8px 40px rgba(0,0,0,.18);
    display:none; flex-direction:column; overflow:hidden;
    border:1px solid rgba(26,107,255,.15);
}
#sfchat-window.open { display:flex; }
#sfchat-header {
    background:linear-gradient(135deg,#0a1628,#0d2550);
    color:#fff; padding:12px 16px;
    display:flex; align-items:center; gap:10px; flex-shrink:0;
}
#sfchat-header-title { flex:1; font-weight:600; font-size:14px; }
#sfchat-header-sub   { font-size:11px; opacity:.7; }
.sfchat-hbtn {
    background:rgba(255,255,255,.12); border:none; color:#fff;
    width:28px; height:28px; border-radius:6px; cursor:pointer;
    display:flex; align-items:center; justify-content:center; font-size:12px;
    transition:background .15s; flex-shrink:0;
}
.sfchat-hbtn:hover { background:rgba(255,255,255,.25); }
#sfchat-list-panel, #sfchat-msg-panel { display:flex; flex-direction:column; flex:1; overflow:hidden; }
#sfchat-msg-panel { display:none; }
#sfchat-search-wrap { padding:10px 12px; border-bottom:1px solid #f0f4f8; flex-shrink:0; }
#sfchat-search {
    width:100%; padding:6px 10px; border:1px solid #e2e8f0;
    border-radius:8px; font-size:13px; outline:none;
    background:#f8faff; color:#1e293b;
}
#sfchat-convlist { flex:1; overflow-y:auto; }
.sfchat-conv-item {
    display:flex; align-items:center; gap:10px;
    padding:10px 14px; cursor:pointer; border-bottom:1px solid #f8faff; transition:background .12s;
}
.sfchat-conv-item:hover { background:#f4f7ff; }
.sfchat-avatar {
    width:38px; height:38px; border-radius:50%;
    background:linear-gradient(135deg,#1a6bff,#0a3d99);
    color:#fff; display:flex; align-items:center; justify-content:center;
    font-size:14px; font-weight:600; flex-shrink:0;
}
.sfchat-conv-info { flex:1; min-width:0; }
.sfchat-conv-name { font-size:13px; font-weight:600; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sfchat-conv-last { font-size:11px; color:#94a3b8; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:1px; }
.sfchat-conv-unread {
    background:#1a6bff; color:#fff; font-size:10px; font-weight:700;
    min-width:18px; height:18px; border-radius:9px;
    display:flex; align-items:center; justify-content:center; padding:0 4px;
}
#sfchat-msg-back {
    padding:10px 12px; border-bottom:1px solid #f0f4f8; flex-shrink:0;
    display:flex; align-items:center; gap:8px;
}
#sfchat-msg-back > button:first-child {
    background:rgba(26,107,255,.08); border:none; color:#1a6bff;
    width:28px; height:28px; border-radius:7px; cursor:pointer;
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
#sfchat-msg-name { font-size:13px; font-weight:600; color:#1e293b; }
#sfchat-messages {
    flex:1; overflow-y:auto; padding:12px 14px;
    display:flex; flex-direction:column; gap:8px; background:#f8faff;
}
.sfchat-bubble-wrap { display:flex; align-items:flex-end; gap:6px; }
.sfchat-bubble-wrap.mine { flex-direction:row-reverse; }
.sfchat-bubble {
    max-width:72%; padding:8px 12px; border-radius:14px;
    font-size:13px; line-height:1.5; word-break:break-word;
}
.sfchat-bubble.theirs { background:#fff; color:#1e293b; border-bottom-left-radius:4px; box-shadow:0 1px 4px rgba(0,0,0,.08); }
.sfchat-bubble.mine   { background:linear-gradient(135deg,#1a6bff,#0a3d99); color:#fff; border-bottom-right-radius:4px; }
.sfchat-time { font-size:10px; color:#94a3b8; margin-top:2px; text-align:right; }
#sfchat-input-wrap {
    display:flex; align-items:center; gap:8px;
    padding:10px 12px; border-top:1px solid #f0f4f8; flex-shrink:0; background:#fff;
}
#sfchat-input {
    flex:1; padding:8px 12px; border:1px solid #e2e8f0;
    border-radius:20px; font-size:13px; outline:none;
    background:#f8faff; color:#1e293b; resize:none; max-height:80px;
}
#sfchat-send {
    width:36px; height:36px; border-radius:50%; border:none;
    background:linear-gradient(135deg,#1a6bff,#0a3d99);
    color:#fff; cursor:pointer; font-size:14px;
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.sfchat-empty { text-align:center; padding:30px 20px; color:#94a3b8; font-size:13px; }
.sfchat-empty i { font-size:32px; margin-bottom:8px; opacity:.4; display:block; }
</style>

<button id="sfchat-fab" title="Messages" onclick="sfchatToggle()">
    <i class="fas fa-comment-dots"></i>
    <span id="sfchat-badge"></span>
</button>

<div id="sfchat-window">
    <div id="sfchat-header">
        <div class="sfchat-avatar" style="width:34px;height:34px;font-size:13px;flex-shrink:0;">
            {{ strtoupper(substr(Auth::guard('student')->user()->first_name,0,1)) }}
        </div>
        <div style="flex:1;min-width:0;">
            <div id="sfchat-header-title">Messages</div>
            <div id="sfchat-header-sub">{{ Auth::guard('student')->user()->first_name }} {{ Auth::guard('student')->user()->last_name }}</div>
        </div>
        <button class="sfchat-hbtn" onclick="sfchatToggle()" title="Close" style="margin-left:auto;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div id="sfchat-list-panel">
        <div id="sfchat-search-wrap">
            <input id="sfchat-search" type="text" placeholder="Search conversations…" oninput="sfchatSearch(this.value)">
        </div>
        <div id="sfchat-convlist"></div>
    </div>

    <div id="sfchat-msg-panel">
        <div id="sfchat-msg-back">
            <button onclick="sfchatBackToList()" title="Back"><i class="fas fa-arrow-left"></i></button>
            <div class="sfchat-avatar" id="sfchat-msg-avatar" style="width:32px;height:32px;font-size:12px;flex-shrink:0;"></div>
            <span id="sfchat-msg-name"></span>
            <button class="sfchat-hbtn" onclick="sfchatToggle()" style="margin-left:auto;background:rgba(0,0,0,.06);color:#64748b;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="sfchat-messages"></div>
        <div id="sfchat-input-wrap">
            <textarea id="sfchat-input" placeholder="Type a message…" rows="1"
                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sfchatSend();}"></textarea>
            <button id="sfchat-send" onclick="sfchatSend()"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<script>
(function(){
    var CSRF      = '{{ csrf_token() }}';
    var ME        = {{ Auth::guard('student')->id() }};
    var URL_CONVS = '{{ route("chat.conversations") }}';
    var URL_SEND  = '{{ route("chat.send") }}';

    var st = { open:false, convId:null, convs:[], totalUnread:0, pollTimer:null, msgTimer:null };

    window.sfchatToggle = function(){
        st.open = !st.open;
        document.getElementById('sfchat-window').classList.toggle('open', st.open);
        if(st.open && !st.convId) sfchatLoadConvs();
    };

    window.sfchatSearch = function(q){
        q = q.toLowerCase();
        document.querySelectorAll('.sfchat-conv-item').forEach(function(el){
            el.style.display = el.dataset.name.toLowerCase().includes(q) ? '' : 'none';
        });
    };

    function sfchatLoadConvs(){
        fetch(URL_CONVS, {headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(function(r){ return r.json(); })
            .then(function(data){
                st.convs = data.conversations || [];
                renderConvs();
                clearTimeout(st.pollTimer);
                st.pollTimer = setTimeout(sfchatLoadConvs, 8000);
            });
    }

    function renderConvs(){
        var el = document.getElementById('sfchat-convlist');
        st.totalUnread = 0;
        if(!st.convs.length){
            el.innerHTML = '<div class="sfchat-empty"><i class="fas fa-comments"></i>No conversations yet.</div>';
            updateBadge(); return;
        }
        el.innerHTML = st.convs.map(function(c){
            st.totalUnread += (c.unread_count||0);
            var name = getConvName(c);
            var last = c.last_message ? (c.last_message.body||'📎 Attachment') : 'No messages yet';
            var unread = c.unread_count > 0 ? '<span class="sfchat-conv-unread">'+c.unread_count+'</span>' : '';
            return '<div class="sfchat-conv-item" data-name="'+esc(name)+'" onclick="sfchatOpenConv('+c.id+',\''+esc(name)+'\')">'
                + '<div class="sfchat-avatar">'+initials(name)+'</div>'
                + '<div class="sfchat-conv-info">'
                +   '<div class="sfchat-conv-name">'+esc(name)+'</div>'
                +   '<div class="sfchat-conv-last">'+esc(last)+'</div>'
                + '</div>'+unread+'</div>';
        }).join('');
        updateBadge();
    }

    function getConvName(c){
        if(c.name) return c.name;
        var others = (c.users||[]).filter(function(u){ return true; });
        var studs  = (c.students||[]).filter(function(s){ return s.id !== ME; });
        var names  = others.map(function(u){ return u.name; })
                           .concat(studs.map(function(s){ return (s.first_name||'')+' '+(s.last_name||''); }));
        return names.join(', ') || 'Unknown';
    }

    function updateBadge(){
        var b = document.getElementById('sfchat-badge');
        if(st.totalUnread > 0){
            b.style.display = 'flex';
            b.textContent   = st.totalUnread > 99 ? '99+' : st.totalUnread;
        } else { b.style.display = 'none'; }
    }

    window.sfchatOpenConv = function(id, name){
        st.convId = id;
        document.getElementById('sfchat-list-panel').style.display = 'none';
        document.getElementById('sfchat-msg-panel').style.display  = 'flex';
        document.getElementById('sfchat-msg-name').textContent     = name;
        document.getElementById('sfchat-msg-avatar').textContent   = initials(name);
        document.getElementById('sfchat-messages').innerHTML = '<div class="sfchat-empty"><i class="fas fa-spinner fa-spin"></i>Loading…</div>';
        sfchatLoadMessages();
    };

    window.sfchatBackToList = function(){
        st.convId = null;
        clearTimeout(st.msgTimer);
        document.getElementById('sfchat-msg-panel').style.display  = 'none';
        document.getElementById('sfchat-list-panel').style.display = 'flex';
        sfchatLoadConvs();
    };

    function sfchatLoadMessages(){
        if(!st.convId) return;
        fetch('/chat/conversations/'+st.convId+'/messages', {headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(function(r){ return r.json(); })
            .then(function(data){
                renderMessages(data.messages||[]);
                clearTimeout(st.msgTimer);
                st.msgTimer = setTimeout(sfchatLoadMessages, 4000);
            });
    }

    function renderMessages(msgs){
        var el = document.getElementById('sfchat-messages');
        if(!msgs.length){
            el.innerHTML = '<div class="sfchat-empty"><i class="fas fa-comment-slash"></i>No messages yet. Say hello!</div>';
            return;
        }
        var atBottom = el.scrollHeight - el.scrollTop - el.clientHeight < 60;
        el.innerHTML = msgs.map(function(m){
            var mine = m.student_id === ME;
            var cls  = mine ? 'mine' : 'theirs';
            var time = m.created_at ? new Date(m.created_at).toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'}) : '';
            var body = m.body ? esc(m.body) : '<em style="opacity:.6">📎 Attachment</em>';
            return '<div class="sfchat-bubble-wrap '+cls+'">'
                + '<div class="sfchat-bubble '+cls+'">'+body+'<div class="sfchat-time">'+time+'</div></div>'
                + '</div>';
        }).join('');
        if(atBottom) el.scrollTop = el.scrollHeight;
    }

    window.sfchatSend = function(){
        var input = document.getElementById('sfchat-input');
        var body  = input.value.trim();
        if(!body || !st.convId) return;
        input.value = '';
        var fd = new FormData();
        fd.append('conversation_id', st.convId);
        fd.append('body', body);
        fetch(URL_SEND, {method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}, body:fd})
            .then(function(r){ return r.json(); })
            .then(function(){ sfchatLoadMessages(); });
    };

    function initials(name){
        return (name||'?').split(' ').slice(0,2).map(function(w){ return w[0]||''; }).join('').toUpperCase();
    }
    function esc(s){
        return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    setTimeout(sfchatLoadConvs, 3000);
})();
</script>
@endauth

<!-- ChartJS -->

