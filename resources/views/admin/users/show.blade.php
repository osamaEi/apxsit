@extends('admin.index')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                    <h2 class="m-0 font-weight-bold">
                        <i class="fas fa-user-circle me-2"></i>{{ __('User Profile') }}
                    </h2>
                    <a href="{{ route('users.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i>{{ __('Back to List') }}
                    </a>
                </div>
                
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Left side - User Info -->
                        <div class="col-md-4 border-end">
                            <div class="d-flex flex-column align-items-center text-center p-5">
                                <div class="avatar bg-{{ getRoleColor($user->role) }} mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 150px; height: 150px;">
                                    <span class="text-white font-weight-bold" style="font-size: 4rem;">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <h3 class="font-weight-bold">{{ $user->name }}</h3>
                                <span class="badge bg-{{ getRoleColor($user->role) }} px-3 py-2 fs-6 my-2">
                                    {{ $user->role }}
                                </span>
                                <p class="text-muted">{{ $user->email }}</p>
                                
                                <div class="mt-4 d-flex gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-1"></i>{{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                            <i class="fas fa-trash-alt me-1"></i>{{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right side - Detailed Info -->
                        <div class="col-md-8">
                            <div class="p-4">
                                <h3 class="border-bottom pb-3 mb-4">{{ __('User Details') }}</h3>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="info-card mb-3 p-3 border rounded bg-light">
                                            <small class="text-muted d-block">{{ __('User ID') }}</small>
                                            <div class="fs-5 fw-bold">#{{ $user->id }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-card mb-3 p-3 border rounded bg-light">
                                            <small class="text-muted d-block">{{ __('Date Joined') }}</small>
                                            <div class="fs-5 fw-bold">{{ $user->created_at->format('F d, Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="info-card mb-3 p-3 border rounded bg-light">
                                            <small class="text-muted d-block">{{ __('Email Verified') }}</small>
                                            <div class="fs-5 fw-bold">
                                                @if($user->email_verified_at)
                                                    <span class="text-success">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        {{ $user->email_verified_at->format('F d, Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-danger">
                                                        <i class="fas fa-times-circle me-1"></i>
                                                        {{ __('Not Verified') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-card mb-3 p-3 border rounded bg-light">
                                            <small class="text-muted d-block">{{ __('Last Updated') }}</small>
                                            <div class="fs-5 fw-bold">{{ $user->updated_at->format('F d, Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <h3 class="border-bottom pb-3 mb-4 mt-5">{{ __('Activity') }}</h3>
                                
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h4 class="timeline-title">{{ __('Account Created') }}</h4>
                                            <p class="timeline-subtitle">{{ $user->created_at->format('F d, Y - h:i A') }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($user->email_verified_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <h4 class="timeline-title">{{ __('Email Verified') }}</h4>
                                            <p class="timeline-subtitle">{{ $user->email_verified_at->format('F d, Y - h:i A') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning"></div>
                                        <div class="timeline-content">
                                            <h4 class="timeline-title">{{ __('Last Profile Update') }}</h4>
                                            <p class="timeline-subtitle">{{ $user->updated_at->format('F d, Y - h:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 30px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    left: -30px;
    top: 6px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -23px;
    top: 21px;
    height: 100%;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 1.1rem;
    font-weight: bold;
}

.timeline-subtitle {
    color: #6c757d;
    font-size: 0.9rem;
}
</style>

@php
function getRoleColor($role) {
    switch($role) {
        case 'Admin': return 'danger';
        case 'Sales': return 'success';
        case 'Register': return 'warning';
        default: return 'info';
    }
}
@endphp
@endsection