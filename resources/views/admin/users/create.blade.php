@extends('admin.index')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h2 class="m-0 font-weight-bold">
                        <i class="fas fa-user-plus me-2"></i>{{ __('Create New User') }}
                    </h2>
                </div>
                
                <div class="card-body p-4">
                    <div class="steps-container mb-4">
                        <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between">
                            <div class="step active">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fas fa-user"></i></div>
                                </div>
                                <h4 class="step-title">{{ __('Basic Info') }}</h4>
                            </div>
                            <div class="step">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fas fa-user-shield"></i></div>
                                </div>
                                <h4 class="step-title">{{ __('Credentials') }}</h4>
                            </div>
                            <div class="step">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fas fa-check-circle"></i></div>
                                </div>
                                <h4 class="step-title">{{ __('Confirmation') }}</h4>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('users.store') }}" id="createUserForm">
                        @csrf
                        
                        <div class="form-section" id="section1">
                            <h3 class="section-title border-bottom pb-2 mb-4">{{ __('Personal Information') }}</h3>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">{{ __('Enter the user\'s full name as it will appear in the system.') }}</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">{{ __('This email will be used for login and notifications.') }}</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label">{{ __('User Role') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                    <select id="role" class="form-select @error('role') is-invalid @enderror" name="role" required>
                                        <option value="" disabled selected>{{ __('Select a role') }}</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <span class="badge bg-danger me-2">Admin</span> {{ __('Full system access') }}<br>
                                    <span class="badge bg-success me-2">Sales</span> {{ __('Sales management access') }}<br>
                                    <span class="badge bg-warning me-2">Register</span> {{ __('Registration access') }}<br>
                                    <span class="badge bg-info me-2">Employee</span> {{ __('Basic access') }}
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-primary next-section-btn" data-target="section2">
                                    {{ __('Next') }} <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-section d-none" id="section2">
                            <h3 class="section-title border-bottom pb-2 mb-4">{{ __('Security Information') }}</h3>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">{{ __('Password must be at least 8 characters long.') }}</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="send-credentials" name="send_credentials">
                                    <label class="form-check-label" for="send-credentials">
                                        {{ __('Send login credentials to user email') }}
                                    </label>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary prev-section-btn" data-target="section1">
                                    <i class="fas fa-arrow-left me-1"></i> {{ __('Previous') }}
                                </button>
                                <button type="button" class="btn btn-primary next-section-btn" data-target="section3">
                                    {{ __('Next') }} <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-section d-none" id="section3">
                            <h3 class="section-title border-bottom pb-2 mb-4">{{ __('Review Information') }}</h3>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ __('Please review the information below before creating the user.') }}
                            </div>
                            
                            <div class="review-info p-3 border rounded bg-light mb-4">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <th width="140">{{ __('Name') }}:</th>
                                        <td class="review-name">-</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Email') }}:</th>
                                        <td class="review-email">-</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Role') }}:</th>
                                        <td class="review-role">-</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Password') }}:</th>
                                        <td>
                                            <span class="text-muted"><i class="fas fa-circle me-1"></i>{{ __('Hidden for security') }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="confirm-info" required>
                                <label class="form-check-label" for="confirm-info">
                                    {{ __('I confirm that all the information provided is correct') }}
                                </label>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary prev-section-btn" data-target="section2">
                                    <i class="fas fa-arrow-left me-1"></i> {{ __('Previous') }}
                                </button>
                                <button type="submit" class="btn btn-success" id="submit-btn" disabled>
                                    <i class="fas fa-user-plus me-1"></i> {{ __('Create User') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('users.index') }}" class="btn btn-link text-muted">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('Back to User List') }}
                        </a>
                        <span class="text-muted small">
                            <i class="fas fa-shield-alt me-1"></i> {{ __('All fields marked with') }} <span class="text-danger">*</span> {{ __('are required') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Step navigation
        const nextButtons = document.querySelectorAll('.next-section-btn');
        const prevButtons = document.querySelectorAll('.prev-section-btn');
        const steps = document.querySelectorAll('.step');
        
        nextButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetSection = this.getAttribute('data-target');
                const currentSection = document.querySelector('.form-section:not(.d-none)');
                
                // Validate current section before proceeding
                if (validateSection(currentSection.id)) {
                    currentSection.classList.add('d-none');
                    document.getElementById(targetSection).classList.remove('d-none');
                    
                    // Update steps indicator
                    updateSteps(targetSection);
                    
                    // If moving to review section, update review info
                    if (targetSection === 'section3') {
                        updateReviewInfo();
                    }
                }
            });
        });
        
        prevButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetSection = this.getAttribute('data-target');
                const currentSection = document.querySelector('.form-section:not(.d-none)');
                
                currentSection.classList.add('d-none');
                document.getElementById(targetSection).classList.remove('d-none');
                
                // Update steps indicator
                updateSteps(targetSection);
            });
        });
        
        function updateSteps(sectionId) {
            const stepIndex = parseInt(sectionId.replace('section', '')) - 1;
            
            steps.forEach((step, index) => {
                if (index < stepIndex) {
                    step.classList.add('completed');
                    step.classList.remove('active');
                } else if (index === stepIndex) {
                    step.classList.add('active');
                    step.classList.remove('completed');
                } else {
                    step.classList.remove('active', 'completed');
                }
            });
        }
        
        function validateSection(sectionId) {
            // Add validation logic here if needed
            return true;
        }
        
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
        
        // Update review info
        function updateReviewInfo() {
            document.querySelector('.review-name').textContent = document.getElementById('name').value;
            document.querySelector('.review-email').textContent = document.getElementById('email').value;
            
            const roleSelect = document.getElementById('role');
            const selectedRole = roleSelect.options[roleSelect.selectedIndex].text;
            document.querySelector('.review-role').textContent = selectedRole;
        }
        
        // Enable/disable submit button based on confirmation checkbox
        const confirmCheckbox = document.getElementById('confirm-info');
        const submitButton = document.getElementById('submit-btn');
        
        confirmCheckbox.addEventListener('change', function() {
            submitButton.disabled = !this.checked;
        });
    });
</script>

<style>
/* Steps styling */
.steps-container {
    padding: 0 0 2rem 0;
}

.steps {
    margin-bottom: 1rem;
}

.step {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    padding: 0 0.5rem;
}

.step:not(:first-child):before {
    content: "";
    position: absolute;
    top: 21px;
    left: -50%;
    width: 100%;
    height: 2px;
    background-color: #e9ecef;
    z-index: 0;
}

.step.active:not(:first-child):before,
.step.completed:not(:first-child):before {
    background-color: #28a745;
}

.step-icon-wrap {
    position: relative;
    z-index: 1;
    background-color: #fff;
    border-radius: 50%;
    margin-bottom: 0.75rem;
}

.step-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    font-size: 1.2rem;
}

.step.active .step-icon {
    background-color: #28a745;
    color: #fff;
}

.step.completed .step-icon {
    background-color: #218838;
    color: #fff;
}

.step-title {
    font-size: 0.875rem;
    color: #6c757d;
    text-align: center;
}

.step.active .step-title {
    color: #28a745;
    font-weight: bold;
}

.step.completed .step-title {
    color: #218838;
}

/* Form section transitions */
.form-section {
    transition: all 0.3s ease;
}
</style>
@endsection