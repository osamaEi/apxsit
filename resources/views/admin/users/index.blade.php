@extends('admin.index')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section with Glass Morphism -->
    <div class="header-section position-relative mb-5">
        <div class="glass-card position-relative overflow-hidden p-4 rounded-xl">
            <div class="bg-patterns"></div>
            <div class="row align-items-center position-relative z-index-1">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-gradient mb-2">
                        <span>{{ __('User') }}</span>
                        <span class="gradient-text">{{ __('Management') }}</span>
                    </h1>
                    <p class="lead text-muted mb-4">{{ __('Manage your team members and their access levels') }}</p>
                    <div class="d-flex stats-summary">
                        <div class="stat-item me-4">
                            <span class="stat-value">{{ $users->total() }}</span>
                            <span class="stat-label">{{ __('Total Users') }}</span>
                        </div>
                        <div class="stat-item me-4">
                            <span class="stat-value">{{ $users->where('role', 'Admin')->count() }}</span>
                            <span class="stat-label">{{ __('Admins') }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</span>
                            <span class="stat-label">{{ __('New (30d)') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-flex justify-content-end">
                    <div class="illustration-container">
                        <div class="illustration-circle"></div>
                        <div class="illustration-icon">
                            <i class="fas fa-users-cog fa-4x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="action-bar d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div class="search-filter-container d-flex flex-wrap">
            <div class="search-box me-3 mb-2">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0" id="userSearch" placeholder="{{ __('Search users...') }}">
                </div>
            </div>
            <div class="filter-box mb-2">
                <select class="form-select" id="roleFilter">
                    <option value="">{{ __('All Roles') }}</option>
                    <option value="Admin">{{ __('Admin') }}</option>
                    <option value="Sales">{{ __('Sales') }}</option>
                    <option value="Register">{{ __('Register') }}</option>
                    <option value="Employee">{{ __('Employee') }}</option>
                </select>
            </div>
        </div>
        <div class="action-buttons mb-2">
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-elevated">
                <i class="fas fa-user-plus me-2"></i>{{ __('New User') }}
            </a>
            <div class="btn-group ms-2">
                <button type="button" class="btn btn-outline-secondary">
                    <i class="fas fa-download me-1"></i>{{ __('Export') }}
                </button>
                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="far fa-file-excel me-2"></i>Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="far fa-file-pdf me-2"></i>PDF</a></li>
                    <li><a class="dropdown-item" href="#"><i class="far fa-file-csv me-2"></i>CSV</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    @if(session('success'))
        <div class="notification-toast show" id="successToast">
            <div class="toast-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="toast-content">
                <h6 class="toast-title">{{ __('Success!') }}</h6>
                <p class="toast-message">{{ session('success') }}</p>
            </div>
            <button type="button" class="toast-close" onclick="document.getElementById('successToast').classList.remove('show')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- User Cards Grid -->
    <div class="row g-4 user-card-container">
        <!-- Quick Add Card -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 quick-add-card">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <div class="icon-placeholder mb-3">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h5 class="card-title text-center mb-3">{{ __('Add New User') }}</h5>
                    <p class="card-text text-center text-muted mb-4">{{ __('Create a new user account and assign roles') }}</p>
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-plus me-1"></i>{{ __('Quick Add') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- User Cards -->
        @forelse($users as $user)
            <div class="col-xl-3 col-lg-4 col-md-6 user-card" data-role="{{ $user->role }}">
                <div class="card h-100 user-profile-card">
                    <div class="card-status-bar bg-{{ getRoleBadgeClass($user->role) }}"></div>
                    <div class="card-body p-4">
                        <div class="dropdown card-actions">
                            <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
                                    <i class="fas fa-eye me-2"></i>{{ __('View Profile') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                    <i class="fas fa-edit me-2"></i>{{ __('Edit User') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="dropdown-item-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger" 
                                            onclick="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                        <i class="fas fa-trash-alt me-2"></i>{{ __('Delete User') }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="user-avatar-container mb-3">
                            <div class="user-avatar bg-light">
                                <span class="initial text-{{ getRoleBadgeClass($user->role) }}">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="user-status {{ $user->email_verified_at ? 'verified' : 'pending' }}">
                                <i class="fas {{ $user->email_verified_at ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            </div>
                        </div>

                        <h5 class="card-title text-center mb-1">{{ $user->name }}</h5>
                        <p class="text-muted text-center small mb-2">{{ $user->email }}</p>
                        
                        <div class="user-role-badge text-center mb-3">
                            <span class="badge rounded-pill bg-{{ getRoleBadgeClass($user->role) }}-soft text-{{ getRoleBadgeClass($user->role) }}">
                                <i class="fas fa-{{ getRoleIcon($user->role) }} me-1"></i>{{ $user->role }}
                            </span>
                        </div>

                        <div class="user-meta">
                            <div class="meta-item">
                                <span class="meta-icon"><i class="fas fa-id-card"></i></span>
                                <span class="meta-text">ID: #{{ $user->id }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-icon"><i class="fas fa-calendar-alt"></i></span>
                                <span class="meta-text">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <div class="user-actions mt-3">
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-light-hover w-100">
                                {{ __('View Profile') }} <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State Card -->
            <div class="col-12">
                <div class="empty-state-card text-center p-5">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <h3 class="empty-state-title mb-3">{{ __('No Users Found') }}</h3>
                    <p class="empty-state-text mb-4">{{ __('Your team is empty. Start by adding your first team member.') }}</p>
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>{{ __('Add First User') }}
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination with Custom Design -->
    <div class="custom-pagination d-flex justify-content-center mt-5">
        {{ $users->onEachSide(1)->links('vendor.pagination.custom') }}
    </div>
</div>

<style>
    /* Glass Morphism Header */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .bg-patterns {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.05;
        background-image: 
            radial-gradient(circle at 20% 20%, var(--bs-primary) 0%, transparent 80%),
            radial-gradient(circle at 80% 50%, var(--bs-info) 0%, transparent 80%);
    }

    .text-gradient {
        background: linear-gradient(90deg, var(--bs-dark) 0%, var(--bs-primary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .gradient-text {
        background: linear-gradient(90deg, var(--bs-primary) 0%, var(--bs-info) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .z-index-1 {
        z-index: 1;
    }

    /* Stats Summary */
    .stats-summary {
        margin-top: 1rem;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--bs-dark);
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--bs-secondary);
    }

    /* Illustration */
    .illustration-container {
        position: relative;
        width: 200px;
        height: 200px;
    }

    .illustration-circle {
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(var(--bs-info-rgb), 0.1) 100%);
        border: 2px dashed rgba(var(--bs-primary-rgb), 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse 3s infinite;
    }

    .illustration-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: var(--bs-primary);
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* Action Bar */
    .action-bar {
        background-color: #fff;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .search-box .form-control, .search-box .input-group-text {
        border-color: #e9ecef;
    }

    .search-box .form-control:focus {
        box-shadow: none;
    }

    .btn-elevated {
        box-shadow: 0 4px 10px rgba(var(--bs-primary-rgb), 0.2);
    }

    /* Toast Notification */
    .notification-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        display: flex;
        align-items: center;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        width: 350px;
        overflow: hidden;
        z-index: 1050;
        transform: translateX(400px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .notification-toast.show {
        transform: translateX(0);
        opacity: 1;
    }

    .toast-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 100%;
        color: white;
        padding: 15px;
    }

    .toast-content {
        flex: 1;
        padding: 15px;
    }

    .toast-title {
        margin-bottom: 5px;
    }

    .toast-message {
        margin-bottom: 0;
        color: #6c757d;
        font-size: 0.875rem;
    }

    .toast-close {
        background: none;
        border: none;
        color: #6c757d;
        padding: 15px;
        cursor: pointer;
    }

    /* User Cards */
    .user-card-container {
        margin-bottom: 2rem;
    }

    .quick-add-card {
        border: 2px dashed #e9ecef;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .quick-add-card:hover {
        border-color: var(--bs-primary);
        background-color: rgba(var(--bs-primary-rgb), 0.02);
    }

    .icon-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .user-profile-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .user-profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .card-status-bar {
        height: 4px;
        width: 100%;
    }

    .card-actions {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .btn-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.05);
        color: #6c757d;
    }

    .dropdown-item-form {
        margin: 0;
        padding: 0;
    }

    .user-avatar-container {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto;
    }

    .user-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .initial {
        font-size: 2rem;
        font-weight: bold;
    }

    .user-status {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .user-status.verified {
        background-color: var(--bs-success);
        color: white;
    }

    .user-status.pending {
        background-color: var(--bs-warning);
        color: white;
    }

    .bg-danger-soft {
        background-color: rgba(var(--bs-danger-rgb), 0.1);
    }

    .bg-success-soft {
        background-color: rgba(var(--bs-success-rgb), 0.1);
    }

    .bg-warning-soft {
        background-color: rgba(var(--bs-warning-rgb), 0.1);
    }

    .bg-info-soft {
        background-color: rgba(var(--bs-info-rgb), 0.1);
    }

    .user-meta {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #f0f0f0;
    }

    .meta-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        color: #6c757d;
        font-size: 0.8rem;
    }

    .meta-icon {
        margin-right: 0.5rem;
        width: 15px;
        text-align: center;
    }

    .btn-light-hover {
        background-color: transparent;
        border-color: #e9ecef;
        transition: all 0.3s ease;
    }

    .btn-light-hover:hover {
        background-color: var(--bs-light);
    }

    /* Empty State */
    .empty-state-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        border-radius: 50%;
        background-color: rgba(var(--bs-secondary-rgb), 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--bs-secondary);
    }

    /* Custom Pagination */
    .custom-pagination {
        margin-top: 2rem;
    }

    .custom-pagination .pagination {
        gap: 5px;
    }

    .custom-pagination .page-item .page-link {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        color: #6c757d;
    }

    .custom-pagination .page-item.active .page-link {
        background-color: var(--bs-primary);
        color: white;
    }

    .custom-pagination .page-item.disabled .page-link {
        background-color: #f8f9fa;
        color: #6c757d;
        opacity: 0.5;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hide notification toast after 5 seconds
        setTimeout(() => {
            const toast = document.getElementById('successToast');
            if (toast) toast.classList.remove('show');
        }, 5000);

        // Search functionality
        const userSearch = document.getElementById('userSearch');
        const roleFilter = document.getElementById('roleFilter');
        const userCards = document.querySelectorAll('.user-card');

        userSearch.addEventListener('keyup', filterUsers);
        roleFilter.addEventListener('change', filterUsers);

        function filterUsers() {
            const searchTerm = userSearch.value.toLowerCase();
            const roleValue = roleFilter.value;

            userCards.forEach(card => {
                const name = card.querySelector('.card-title').textContent.toLowerCase();
                const email = card.querySelector('.text-muted').textContent.toLowerCase();
                const cardRole = card.dataset.role;
                
                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesRole = roleValue === '' || cardRole === roleValue;
                
                if (matchesSearch && matchesRole) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    });
</script>

@php
function getAvatarColor($role) {
    switch($role) {
        case 'Admin': return 'danger';
        case 'Sales': return 'success';
        case 'Register': return 'warning';
        default: return 'info';
    }
}

function getRoleBadgeClass($role) {
    switch($role) {
        case 'Admin': return 'danger';
        case 'Sales': return 'success';
        case 'Register': return 'warning';
        default: return 'info';
    }
}

function getRoleIcon($role) {
    switch($role) {
        case 'Admin': return 'crown';
        case 'Sales': return 'chart-line';
        case 'Register': return 'clipboard-list';
        default: return 'user-tie';
    }
}
@endphp
@endsection