@extends('admin.index')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h2 class="m-0 font-weight-bold">
                        <i class="fas fa-user-edit me-2"></i>{{ __('Edit User') }}
                    </h2>
                    <div>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm me-2">
                            <i class="fas fa-eye me-1"></i>{{ __('View Profile') }}
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>{{ __('Back') }}
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="user-avatar text-center mb-4">
                        <div class="avatar bg-{{ getRoleColor($user->role) }} mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 100px; height: 100px;">
                            <span class="text-white font-weight-bold" style="font-size: 2.5rem;">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <h3 class="user-name">{{ $user->name }}</h3>
                        <p class="user-id text-muted"><span class="badge bg-secondary">ID: {{ $user->id }}</span></p>
                    </div>
                    
                    <form method="POST" action="{{ route('users.update', $user->id) }}" class="user-edit-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">{{ __('User Role') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        <select id="role" class="form-select @error('role') is-invalid @enderror" name="role" required>
                                            @foreach($roles as $role)
                                                <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>{{ $role }}</option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="created-at" class="form-label">{{ __('Member Since') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" class="form-control" id="created-at" value="{{ $user->created_at->format('Y-m-d H:i') }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="password-container border rounded p-3 my-4">
                            <div class="password-header d-flex align-items-center justify-content-between mb-3">
                                <h4 class="m-0"><i class="fas fa-key me-2"></i>{{ __('Change Password') }}</h4>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="change-password-toggle">
                                    <label class="form-check-label" for="change-password-toggle">{{ __('Update Password') }}</label>
                                </div>
                            </div>
                            
                            <div id="password-fields" class="password-fields d-none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">{{ __('New Password') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">{{ __('Leave blank to keep the current password.') }}</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password-confirm" class="form-label">{{ __('Confirm New Password') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning mt-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    {{ __('Changing the password will log the user out of all devices.') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="additional-options border rounded p-3 my-4">
                            <h4 class="mb-3"><i class="fas fa-cog me-2"></i>{{ __('Additional Options') }}</h4>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="send-notification" name="send_notification">
                                        <label class="form-check-label" for="send-notification">
                                            {{ __('Notify user about changes') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="force-logout" name="force_logout">
                                        <label class="form-check-label" for="force-logout">
                                            {{ __('Force logout from all devices') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-warning me-2">
                                    <i class="fas fa-undo me-1"></i>{{ __('Reset') }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>{{ __('Update User') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-center">
                        <span class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ __('Last updated:') }} {{ $user->updated_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Toggle password fields visibility
        const changePasswordToggle = document.getElementById('change-password-toggle');
        const passwordFields = document.getElementById('password-fields');
        
        changePasswordToggle.addEventListener('change', function() {
            if (this.checked) {
                passwordFields.classList.remove('d-none');
            } else {
                passwordFields.classList.add('d-none');
                document.getElementById('password').value = '';
                document.getElementById('password-confirm').value = '';
            }
        });
    });
</script>

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