<div class="position-sticky pt-3">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.world.dashboard') ? 'active' : '' }}" href="{{ route('admin.world.dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i>
                World Data Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.countries.index') ? 'active' : '' }}" href="{{ route('admin.countries.index') }}">
                <i class="fas fa-globe me-2"></i>
                Countries
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Admin
            </a>
        </li>
    </ul>
    
    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Saved Reports</span>
    </h6>
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-file-alt me-2"></i>
                Countries Report
            </a>
        </li>
    </ul>
</div>