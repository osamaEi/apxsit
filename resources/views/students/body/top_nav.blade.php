<style>
.main-header.navbar{
    background: linear-gradient(90deg,#060f1e,#0a1e3d) !important;
    border-bottom: 1px solid rgba(26,107,255,.2) !important;
    padding: 0 16px !important;
    min-height: 50px !important;
}
.main-header .nav-link{
    color: rgba(255,255,255,.65) !important;
    padding: 8px 10px !important;
    font-size: 13px !important;
    border-radius: 8px !important;
    transition: all .15s !important;
}
.main-header .nav-link:hover{ background:rgba(255,255,255,.07) !important; color:#fff !important; }
.main-header [data-widget="pushmenu"]{ color:rgba(255,255,255,.7) !important; }
.sp-topnav-name{
    font-size:13px; font-weight:600; color:rgba(255,255,255,.75);
    padding: 4px 10px;
}
.sp-topnav-badge{
    display:inline-flex;align-items:center;gap:5px;
    background:rgba(26,107,255,.2);border:1px solid rgba(26,107,255,.3);
    border-radius:20px;padding:3px 10px;font-size:11px;color:#60a5fa;font-weight:500;
}
#sp-theme-toggle{
    background: rgba(255,255,255,.07) !important;
    border: 1px solid rgba(255,255,255,.1) !important;
    border-radius: 8px !important;
    color: rgba(255,255,255,.65) !important;
    padding: 5px 12px !important;
    font-size: 12px !important;
    cursor: pointer !important;
    display:flex;align-items:center;gap:6px;
    transition: all .2s !important;
}
#sp-theme-toggle:hover{ background:rgba(26,107,255,.2) !important; color:#fff !important; border-color:rgba(26,107,255,.4) !important; }
.sp-topnav-av{
    width:30px;height:30px;border-radius:50%;
    background:linear-gradient(135deg,#1a6bff,#0a3d99);
    display:flex;align-items:center;justify-content:center;
    font-size:11px;font-weight:700;color:#fff;
    box-shadow:0 0 0 2px rgba(26,107,255,.3);overflow:hidden;
}
.sp-topnav-av img{width:100%;height:100%;object-fit:cover}
</style>

@php $student = Auth::guard('student')->user(); @endphp

<nav class="main-header navbar navbar-expand navbar-dark" style="margin-left:210px">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto" style="gap:6px;align-items:center">

    <li class="nav-item">
        <span class="sp-topnav-badge">
            <i class="fas fa-graduation-cap"></i>
            {{ $student->status ?? 'Student' }}
        </span>
    </li>

    <li class="nav-item">
        <span class="sp-topnav-name">{{ $student->first_name }} {{ $student->last_name }}</span>
    </li>

    <li class="nav-item">
        <button id="sp-theme-toggle" onclick="spToggleTopTheme()">
            <i class="fas fa-moon" id="sp-theme-icon"></i>
            <span id="sp-theme-lbl">Dark</span>
        </button>
    </li>

    <li class="nav-item" style="padding:0 4px">
        <div class="sp-topnav-av">
            @if($student->photo_path)
                <img src="{{ Storage::url($student->photo_path) }}" alt="">
            @else
                {{ strtoupper(substr($student->first_name,0,1)) }}{{ strtoupper(substr($student->last_name,0,1)) }}
            @endif
        </div>
    </li>

    <li class="nav-item">
        <form action="{{ route('student.logout') }}" method="POST" style="margin:0">
            @csrf
            <button type="submit" class="nav-link" style="background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:5px;color:rgba(239,68,68,.7)!important;font-size:13px">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form>
    </li>
  </ul>
</nav>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<script>
function spToggleTopTheme(){
    var isDark = document.body.classList.toggle('sp-light-mode');
    var icon   = document.getElementById('sp-theme-icon');
    var lbl    = document.getElementById('sp-theme-lbl');
    if(isDark){ icon.className='fas fa-sun'; lbl.textContent='Light'; }
    else       { icon.className='fas fa-moon'; lbl.textContent='Dark'; }
    localStorage.setItem('sp-layout-theme', isDark ? 'light' : 'dark');
}
(function(){
    if(localStorage.getItem('sp-layout-theme')==='light'){
        document.body.classList.add('sp-light-mode');
        var ic=document.getElementById('sp-theme-icon'); if(ic) ic.className='fas fa-sun';
        var lb=document.getElementById('sp-theme-lbl'); if(lb) lb.textContent='Light';
    }
})();
</script>
