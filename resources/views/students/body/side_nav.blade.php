<aside class="main-sidebar elevation-4" style="
    background: linear-gradient(180deg,#060f1e 0%,#0a1e3d 60%,#060f1e 100%) !important;
    width: 210px;
    min-height: 100vh;
    border-right: 1px solid rgba(26,107,255,.15);
    box-shadow: 4px 0 24px rgba(0,0,0,.35);
">
<style>
/* ── slim sidebar overrides ── */
.main-sidebar{ width:210px !important; }
.main-sidebar .brand-link{
    padding: 10px 14px !important;
    background: rgba(255,255,255,.03) !important;
    border-bottom: 1px solid rgba(255,255,255,.07) !important;
    display:flex; align-items:center; gap:9px; min-height:50px;
    text-decoration:none;
}
.main-sidebar .brand-image{
    width:32px !important; height:32px !important;
    border-radius:8px !important; margin:0 !important;
    object-fit:cover; flex-shrink:0;
}
.main-sidebar .brand-text{
    font-size:13px !important; font-weight:700 !important;
    color:#fff !important; line-height:1.2 !important;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.main-sidebar .brand-text span{
    display:block; font-size:10px !important; color:rgba(255,255,255,.4) !important;
    font-weight:400 !important;
}
.main-sidebar .sidebar{ padding:6px 10px !important; }
.main-sidebar .nav-sidebar .nav-item{ margin:2px 0 !important; }
.main-sidebar .nav-sidebar .nav-link{
    padding: 9px 12px !important;
    border-radius: 10px !important;
    font-size: 12.5px !important;
    color: rgba(255,255,255,.55) !important;
    display:flex; align-items:center; gap:9px;
    transition: background .15s, color .15s !important;
    white-space: nowrap;
}
.main-sidebar .nav-sidebar .nav-link:hover{
    background: rgba(26,107,255,.18) !important;
    color: rgba(255,255,255,.9) !important;
}
.main-sidebar .nav-sidebar .nav-link.active{
    background: linear-gradient(135deg,#1a6bff,#0a3d99) !important;
    color: #fff !important;
    box-shadow: 0 3px 12px rgba(26,107,255,.35) !important;
}
.main-sidebar .nav-icon{
    width:16px !important; font-size:13px !important;
    text-align:center; flex-shrink:0; margin:0 !important;
}
.main-sidebar .nav-treeview{
    padding-left: 10px !important; margin-top:2px !important;
}
.main-sidebar .nav-treeview .nav-link{
    padding: 7px 10px !important;
    font-size: 12px !important;
    border-radius:8px !important;
    color: rgba(255,255,255,.4) !important;
}
.main-sidebar .nav-treeview .nav-link .nav-icon{
    font-size:6px !important; color:rgba(255,255,255,.3);
}
.main-sidebar .nav-treeview .nav-link.active{
    background: rgba(26,107,255,.2) !important;
    color: #60a5fa !important;
    box-shadow: none !important;
}
.main-sidebar .nav-treeview .nav-link.active .nav-icon{ color:#60a5fa !important; }
/* section label */
.sb-label{
    font-size:9.5px; font-weight:700; text-transform:uppercase; letter-spacing:.09em;
    color:rgba(255,255,255,.25); padding:12px 12px 4px; display:block;
}
/* sidebar mini tweak */
.sidebar-mini.sidebar-collapse .main-sidebar{ width:60px !important; }
.sidebar-mini.sidebar-collapse .main-sidebar .brand-text,
.sidebar-mini.sidebar-collapse .main-sidebar .nav-link p,
.sidebar-mini.sidebar-collapse .sb-label{ display:none !important; }
.sidebar-mini.sidebar-collapse .main-sidebar .nav-link{ justify-content:center; padding:10px !important; }
.sidebar-mini.sidebar-collapse .main-sidebar .brand-link{ justify-content:center; padding:10px !important; }

/* adjust content-wrapper margin */
.content-wrapper, .main-header{ margin-left:210px !important; }
.sidebar-mini.sidebar-collapse .content-wrapper,
.sidebar-mini.sidebar-collapse .main-header{ margin-left:60px !important; }
</style>

    <!-- Brand -->
    <a href="{{ route('student.dashboard') }}" class="brand-link">
        <img src="{{ asset('Apx.jpeg') }}" alt="APX" class="brand-image">
        <span class="brand-text">
            DEVA Education
            <span>Student Portal</span>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <span class="sb-label">Main</span>

                <li class="nav-item">
                    <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <span class="sb-label">Communication</span>

                <li class="nav-item {{ request()->is('chat*') || request()->routeIs('studentIndex') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('chat*') || request()->routeIs('studentIndex') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comment-dots"></i>
                        <p>
                            Messages
                            <i class="right fas fa-angle-left" style="font-size:10px"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('studentIndex') }}" class="nav-link {{ request()->routeIs('studentIndex') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Messages</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <span class="sb-label">Account</span>

                <li class="nav-item">
                    <form action="{{ route('student.logout') }}" method="POST" style="margin:0">
                        @csrf
                        <button type="submit" class="nav-link" style="width:100%;background:none;border:none;cursor:pointer;text-align:left;">
                            <i class="nav-icon fas fa-sign-out-alt" style="color:rgba(239,68,68,.7)"></i>
                            <p style="color:rgba(239,68,68,.7)">Logout</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>
