<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ABX SITE - Student Registration</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --info-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --body-bg: #f5f7fa;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--body-bg);
            color: #333;
            min-height: 100vh;
            position: relative;
            padding-bottom: 60px;
        }

        .container {
            position: relative;
            z-index: 1;
        }
        
        /* Main layout */
        .registration-container {
            display: flex;
            min-height: 100vh;
        }
        
        .info-sidebar {
            width: 30%;
            padding: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
        }
        
        .sidebar-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .sidebar-header {
            margin-bottom: 40px;
            text-align: center;
        }
        
        .sidebar-header img {
            max-width: 180px;
            margin-bottom: 20px;
        }
        
        .sidebar-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .sidebar-header p {
            opacity: 0.8;
            font-size: 0.95rem;
        }
        
        .sidebar-features {
            margin-bottom: 40px;
        }
        
        .sidebar-feature {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .feature-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .feature-text {
            font-size: 0.95rem;
        }
        
        .sidebar-footer {
            text-align: center;
            font-size: 0.85rem;
            opacity: 0.6;
            margin-top: auto;
            padding-top: 40px;
        }
        
        .main-content {
            width: 70%;
            margin-left: 30%;
            padding: 40px;
            min-height: 100vh;
        }
        
        /* Background decor */
        .bg-shape {
            position: fixed;
            border-radius: 50%;
            opacity: 0.05;
            z-index: 0;
        }
        
        .bg-shape-1 {
            background: var(--primary-color);
            width: 300px;
            height: 300px;
            bottom: -100px;
            right: 10%;
        }
        
        .bg-shape-2 {
            background: var(--warning-color);
            width: 200px;
            height: 200px;
            top: 30%;
            right: 5%;
        }
        
        /* Main card */
        .main-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 40px;
            background: white;
        }
        
        .card-header {
            background: var(--primary-color);
            padding: 20px 30px;
            border-bottom: none;
            color: white;
        }
        
        .card-body {
            padding: 30px;
        }
        
        /* Stepper */
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            margin: 0 auto 40px;
            position: relative;
        }
        
        .stepper-wrapper::after {
            content: "";
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e9ecef;
            z-index: 1;
        }
        
        .stepper-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            z-index: 2;
        }
        
        .step-counter {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 2px solid #e9ecef;
            margin-bottom: 12px;
            color: #6c757d;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .stepper-item.completed .step-counter {
            background: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }
        
        .stepper-item.active .step-counter {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 3px 15px rgba(67, 97, 238, 0.3);
        }
        
        .step-name {
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
            margin-top: 5px;
            transition: all 0.3s ease;
        }
        
        .stepper-item.completed .step-name,
        .stepper-item.active .step-name {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        /* Form controls */
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            padding: 12px 15px;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: var(--warning-color);
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
        }
        
        /* Section headers */
        .section-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
        }
        
        .section-header i {
            font-size: 24px;
            margin-right: 15px;
            color: var(--primary-color);
            width: 40px;
            height: 40px;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .section-header h5 {
            margin: 0;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        /* Required field indicator */
        .required-mark {
            color: var(--warning-color);
            margin-left: 3px;
        }
        
        /* Custom file input */
        .custom-file-label {
            padding: 12px 15px;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            background-color: white;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .custom-file-label:hover {
            background-color: #f8f9fa;
        }
        
        .custom-file-label span {
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .custom-file-label .browse-btn {
            background-color: #e9ecef;
            padding: 6px 12px;
            border-radius: 4px;
            color: #495057;
            font-weight: 500;
            margin-left: 10px;
        }
        
        .custom-file-input {
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        
        .custom-file-container {
            position: relative;
            overflow: hidden;
        }
        
        /* Buttons */
        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn i.right {
            margin-right: 0;
            margin-left: 8px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
        }
        
        .btn-secondary {
            background-color: #e9ecef;
            border-color: #e9ecef;
            color: #495057;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        
        .btn-secondary:hover {
            background-color: #dee2e6;
            border-color: #dee2e6;
            color: #212529;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
            box-shadow: 0 4px 10px rgba(76, 201, 240, 0.3);
        }
        
        .btn-success:hover {
            background-color: #3db8dc;
            border-color: #3db8dc;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(76, 201, 240, 0.4);
        }
        
        /* Review cards */
        .review-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        
        .review-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transform: translateY(-3px);
        }
        
        .review-card .card-header {
            background-color: rgba(67, 97, 238, 0.05);
            border-bottom: 1px solid rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            font-weight: 600;
            padding: 15px 20px;
        }
        
        /* Terms checkbox */
        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 0.15em;
        }
        
        .form-check-label {
            padding-left: 8px;
            font-size: 14px;
        }
        
        /* Password strength indicator */
        .password-strength {
            height: 5px;
            margin-top: 8px;
            border-radius: 3px;
            background-color: #e9ecef;
            overflow: hidden;
        }
        
        .password-strength-meter {
            height: 100%;
            width: 0;
            transition: width 0.3s ease;
        }
        
        .strength-weak {
            width: 33.3%;
            background-color: #f72585;
        }
        
        .strength-medium {
            width: 66.6%;
            background-color: #ffbe0b;
        }
        
        .strength-strong {
            width: 100%;
            background-color: #4cc9f0;
        }
        
        .strength-text {
            font-size: 12px;
            margin-top: 5px;
            font-weight: 500;
        }
        
        .strength-weak-text {
            color: #f72585;
        }
        
        .strength-medium-text {
            color: #ffbe0b;
        }
        
        .strength-strong-text {
            color: #4cc9f0;
        }
        
        /* Documents list in review */
        .documents-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .documents-list li {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
        }
        
        .documents-list li:last-child {
            border-bottom: none;
        }
        
        .documents-list li i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .registration-container {
                flex-direction: column;
            }
            
            .info-sidebar {
                width: 100%;
                position: relative;
                padding: 20px;
            }
            
            .main-content {
                width: 100%;
                margin-left: 0;
                padding: 20px;
            }
            
            .sidebar-header {
                margin-bottom: 20px;
            }
            
            .feature-icon {
                width: 30px;
                height: 30px;
                font-size: 0.85rem;
            }
            
            .feature-text {
                font-size: 0.85rem;
            }
            
            .sidebar-footer {
                padding-top: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Background shapes -->
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>

    <div class="registration-container">
        <!-- Information Sidebar -->
        <div class="info-sidebar">
            <div class="sidebar-content">
                <div class="sidebar-header">
                    <img src="{{asset('Apx.jpeg')}}" alt="ABX SITE" class="img-fluid">
                    <h1>ABX SITE</h1>
                    <p>Student Registration Portal</p>
                </div>
                
                <div class="sidebar-features">
                    <div class="sidebar-feature">
                        <div class="feature-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="feature-text">
                            Access to world-class education and academic resources
                        </div>
                    </div>
                    
                    <div class="sidebar-feature">
                        <div class="feature-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="feature-text">
                            Join our diverse international community of learners
                        </div>
                    </div>
                    
                    <div class="sidebar-feature">
                        <div class="feature-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="feature-text">
                            Earn globally recognized qualifications and certifications
                        </div>
                    </div>
                    
                    <div class="sidebar-feature">
                        <div class="feature-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="feature-text">
                            Career support and industry connections after graduation
                        </div>
                    </div>
                    
                    <div class="sidebar-feature">
                        <div class="feature-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <div class="feature-text">
                            State-of-the-art facilities and technology for learning
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-footer">
                    <p>© {{ date('Y') }} ABX SITE. All rights reserved.</p>
                    <p>Need help? Contact us at <strong>info@abxsite.com</strong></p>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="main-card">
                <div class="card-header">
                    <h4 class="mb-0">Student Application Portal</h4>
                </div>
                
                <div class="card-body">
                    <!-- Stepper -->
                    <div class="stepper-wrapper">
                        <div class="stepper-item active">
                            <div class="step-counter">1</div>
                            <div class="step-name">Basic Info</div>
                        </div>
                        <div class="stepper-item">
                            <div class="step-counter">2</div>
                            <div class="step-name">Passport</div>
                        </div>
                        <div class="stepper-item">
                            <div class="step-counter">3</div>
                            <div class="step-name">Personal</div>
                        </div>
                        <div class="stepper-item">
                            <div class="step-counter">4</div>
                            <div class="step-name">Education</div>
                        </div>
                        <div class="stepper-item">
                            <div class="step-counter">5</div>
                            <div class="step-name">Documents</div>
                        </div>
                    </div>

                    <form id="registrationForm" method="POST" action="{{ route('student.register') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Step 1: Basic Information -->
                        <div class="step step-1 fade-in">
                            <div class="section-header">
                                <i class="fas fa-info-circle"></i>
                                <h5>Basic Information</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="academic_year">Academic Year <span class="required-mark">*</span></label>
                                        <select class="form-select @error('academic_year') is-invalid @enderror" id="academic_year" name="academic_year" required>
                                            <option value="">Please Select</option>
                                            @php
                                                $currentYear = date('Y');
                                                $nextYear = $currentYear + 1;
                                            @endphp
                                            <option value="{{ $currentYear }}-{{ $nextYear }}" {{ old('academic_year') == "$currentYear-$nextYear" ? 'selected' : '' }}>
                                                {{ $currentYear }}-{{ $nextYear }}
                                            </option>
                                            <option value="{{ $nextYear }}-{{ $nextYear + 1 }}" {{ old('academic_year') == "$nextYear-" . ($nextYear + 1) ? 'selected' : '' }}>
                                                {{ $nextYear }}-{{ $nextYear + 1 }}
                                            </option>
                                        </select>
                                        @error('academic_year')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="study_country_id">Study Country <span class="required-mark">*</span></label>
                                        <select class="form-select @error('study_country_id') is-invalid @enderror" id="study_country_id" name="study_country_id" required>
                                            <option value="">Please Select</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('study_country_id') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('study_country_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_transfer">Are you a Transfer Student? <span class="required-mark">*</span></label>
                                        <select class="form-select @error('is_transfer') is-invalid @enderror" id="is_transfer" name="is_transfer" required>
                                            <option value="0" {{ old('is_transfer') == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('is_transfer') == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                        @error('is_transfer')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-primary next-step">
                                    Continue <i class="fas fa-arrow-right right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Passport Information -->
                        <div class="step step-2 d-none fade-in">
                            <div class="section-header">
                                <i class="fas fa-passport"></i>
                                <h5>Passport Information</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_of_birth">Date of Birth <span class="required-mark">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                                  id="date_of_birth" 
                                                  name="date_of_birth" 
                                                  value="{{ old('date_of_birth') }}"
                                                  required>
                                        </div>
                                        @error('date_of_birth')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="passport_id">Passport ID <span class="required-mark">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <input type="text" class="form-control @error('passport_id') is-invalid @enderror" 
                                                  id="passport_id" 
                                                  name="passport_id" 
                                                  value="{{ old('passport_id') }}"
                                                  placeholder="Enter your passport ID number"
                                                  required>
                                        </div>
                                        @error('passport_id')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="passport_issue_date">Passport Issue Date <span class="required-mark">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                            <input type="date" class="form-control @error('passport_issue_date') is-invalid @enderror" 
                                                  id="passport_issue_date" 
                                                  name="passport_issue_date" 
                                                  value="{{ old('passport_issue_date') }}"
                                                  required>
                                        </div>
                                        @error('passport_issue_date')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="passport_expiry_date">Passport Expiry Date <span class="required-mark">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-times"></i></span>
                                            <input type="date" class="form-control @error('passport_expiry_date') is-invalid @enderror" 
                                                  id="passport_expiry_date" 
                                                  name="passport_expiry_date" 
                                                  value="{{ old('passport_expiry_date') }}"
                                                  required>
                                        </div>
                                        @error('passport_expiry_date')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="needs_visa_support">Do you need visa support? <span class="required-mark">*</span></label>
                                        <select class="form-select @error('needs_visa_support') is-invalid @enderror" 
                                               id="needs_visa_support" 
                                               name="needs_visa_support"
                                               required>
                                            <option value="0" {{ old('needs_visa_support') == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('needs_visa_support') == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                        @error('needs_visa_support')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step">
                                    <i class="fas fa-arrow-left"></i> Back
                                </button>
                                <button type="button" class="btn btn-primary next-step">
                                    Continue <i class="fas fa-arrow-right right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Personal Information -->
                        <div class="step step-3 d-none fade-in">
                            <div class="section-header">
                                <i class="fas fa-user"></i>
                                <h5>Personal Information</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name <span class="required-mark">*</span></label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                              id="first_name" 
                                              name="first_name" 
                                              value="{{ old('first_name') }}"
                                              placeholder="As shown in your passport"
                                              required>
                                        @error('first_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name <span class="required-mark">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                              id="last_name" 
                                              name="last_name" 
                                              value="{{ old('last_name') }}"
                                              placeholder="As shown in your passport"
                                              required>
                                        @error('last_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address <span class="required-mark">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                  id="email" 
                                                  name="email" 
                                                  value="{{ old('email') }}"
                                                  placeholder="Your valid email address"
                                                  required>
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number">Phone Number <span class="required-mark">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                                  id="phone_number" 
                                                  name="phone_number" 
                                                  value="{{ old('phone_number') }}"
                                                  placeholder="Include country code"
                                                  required>
                                        </div>
                                        @error('phone_number')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender <span class="required-mark">*</span></label>
                                        <select class="form-select @error('gender') is-invalid @enderror" 
                                              id="gender" 
                                              name="gender"
                                              required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="marital_status">Marital Status <span class="required-mark">*</span></label>
                                        <select class="form-select @error('marital_status') is-invalid @enderror" 
                                              id="marital_status" 
                                              name="marital_status"
                                              required>
                                            <option value="">Select Marital Status</option>
                                            <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        </select>
                                        @error('marital_status')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nationality_id">Nationality <span class="required-mark">*</span></label>
                                        <select class="form-select @error('nationality_id') is-invalid @enderror" 
                                              id="nationality_id" 
                                              name="nationality_id"
                                              required>
                                            <option value="">Select Nationality</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('nationality_id') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('nationality_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country_of_residence_id">Country of Residence <span class="required-mark">*</span></label>
                                        <select class="form-select @error('country_of_residence_id') is-invalid @enderror" 
                                              id="country_of_residence_id" 
                                              name="country_of_residence_id"
                                              required>
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('country_of_residence_id') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('country_of_residence_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password <span class="required-mark">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                  id="password" 
                                                  name="password" 
                                                  required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength">
                                            <div class="password-strength-meter"></div>
                                        </div>
                                        <div class="strength-text"></div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password <span class="required-mark">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" 
                                                  id="password_confirmation" 
                                                  name="password_confirmation" 
                                                  required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            <h6 class="mb-3">Family Information</h6>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="father_name">Father's Name <span class="required-mark">*</span></label>
                                        <input type="text" class="form-control @error('father_name') is-invalid @enderror" 
                                              id="father_name" 
                                              name="father_name" 
                                              value="{{ old('father_name') }}"
                                              required>
                                        @error('father_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mother_name">Mother's Name <span class="required-mark">*</span></label>
                                        <input type="text" class="form-control @error('mother_name') is-invalid @enderror" 
                                              id="mother_name" 
                                              name="mother_name" 
                                              value="{{ old('mother_name') }}"
                                              required>
                                        @error('mother_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emergency_email">Emergency Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control @error('emergency_email') is-invalid @enderror" 
                                                  id="emergency_email" 
                                                  name="emergency_email" 
                                                  value="{{ old('emergency_email') }}"
                                                  placeholder="Alternative contact email">
                                        </div>
                                        @error('emergency_email')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emergency_phone">Emergency Phone</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="tel" class="form-control @error('emergency_phone') is-invalid @enderror" 
                                                  id="emergency_phone" 
                                                  name="emergency_phone" 
                                                  value="{{ old('emergency_phone') }}"
                                                  placeholder="Include country code">
                                        </div>
                                        @error('emergency_phone')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step">
                                    <i class="fas fa-arrow-left"></i> Back
                                </button>
                                <button type="button" class="btn btn-primary next-step">
                                    Continue <i class="fas fa-arrow-right right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 4: Education Information -->
                        <div class="step step-4 d-none fade-in">
                            <div class="section-header">
                                <i class="fas fa-graduation-cap"></i>
                                <h5>Education Information</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="high_school_name">High School Name <span class="required-mark">*</span></label>
                                        <input type="text" class="form-control @error('high_school_name') is-invalid @enderror" 
                                              id="high_school_name" 
                                              name="high_school_name" 
                                              value="{{ old('high_school_name') }}"
                                              placeholder="Enter your high school name"
                                              required>
                                        @error('high_school_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="high_school_country_id">High School Country <span class="required-mark">*</span></label>
                                        <select class="form-select @error('high_school_country_id') is-invalid @enderror" 
                                              id="high_school_country_id" 
                                              name="high_school_country_id"
                                              required>
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('high_school_country_id') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('high_school_country_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gpa">GPA/Grade <span class="required-mark">*</span></label>
                                        <input type="text" class="form-control @error('gpa') is-invalid @enderror" 
                                              id="gpa" 
                                              name="gpa" 
                                              value="{{ old('gpa') }}"
                                              placeholder="e.g. 3.5/4.0 or 85%"
                                              required>
                                        <small class="form-text text-muted">Enter your GPA or overall grade percentage</small>
                                        @error('gpa')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="applying_degree_id">Program Applying For <span class="required-mark">*</span></label>
                                        <select class="form-select @error('applying_degree_id') is-invalid @enderror" 
                                              id="applying_degree_id" 
                                              name="applying_degree_id"
                                              required>
                                            <option value="">Select Degree</option>
                                            @foreach($degrees as $degree)
                                                <option value="{{ $degree->id }}">
                                                    {{ $degree->name }} 
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('applying_degree_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step">
                                    <i class="fas fa-arrow-left"></i> Back
                                </button>
                                <button type="button" class="btn btn-primary next-step">
                                    Continue <i class="fas fa-arrow-right right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 5: Documents -->
                        <div class="step step-5 d-none fade-in">
                            <div class="section-header">
                                <i class="fas fa-file-alt"></i>
                                <h5>Required Documents</h5>
                            </div>
                            
                            <p class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> Please upload clear scans of the following documents. Accepted formats: PDF, JPG, PNG.
                            </p>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">Passport Photo <span class="required-mark">*</span></label>
                                        <div class="custom-file-container">
                                            <div class="custom-file-label">
                                                <span id="photo-filename">No file selected</span>
                                                <div class="browse-btn">Browse</div>
                                            </div>
                                            <input type="file" class="custom-file-input" 
                                                  id="photo" 
                                                  name="photo" 
                                                  accept="image/*" 
                                                  required>
                                        </div>
                                        <small class="form-text text-muted">Recent passport-style photo</small>
                                        @error('photo')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="passport">Passport Copy <span class="required-mark">*</span></label>
                                        <div class="custom-file-container">
                                            <div class="custom-file-label">
                                                <span id="passport-filename">No file selected</span>
                                                <div class="browse-btn">Browse</div>
                                            </div>
                                            <input type="file" class="custom-file-input" 
                                                  id="passport" 
                                                  name="passport" 
                                                  accept=".pdf,.jpg,.jpeg,.png" 
                                                  required>
                                        </div>
                                        <small class="form-text text-muted">Clear copy of passport bio page</small>
                                        @error('passport')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="transcript">Academic Transcript <span class="required-mark">*</span></label>
                                        <div class="custom-file-container">
                                            <div class="custom-file-label">
                                                <span id="transcript-filename">No file selected</span>
                                                <div class="browse-btn">Browse</div>
                                            </div>
                                            <input type="file" class="custom-file-input" 
                                                  id="transcript" 
                                                  name="transcript" 
                                                  accept=".pdf,.jpg,.jpeg,.png" 
                                                  required>
                                        </div>
                                        <small class="form-text text-muted">Official high school transcript</small>
                                        @error('transcript')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="diploma">High School Diploma</label>
                                        <div class="custom-file-container">
                                            <div class="custom-file-label">
                                                <span id="diploma-filename">No file selected</span>
                                                <div class="browse-btn">Browse</div>
                                            </div>
                                            <input type="file" class="custom-file-input" 
                                                  id="diploma" 
                                                  name="diploma" 
                                                  accept=".pdf,.jpg,.jpeg,.png">
                                        </div>
                                        <small class="form-text text-muted">If already graduated</small>
                                        @error('diploma')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="denklik">Denklik (Equivalence Certificate)</label>
                                        <div class="custom-file-container">
                                            <div class="custom-file-label">
                                                <span id="denklik-filename">No file selected</span>
                                                <div class="browse-btn">Browse</div>
                                            </div>
                                            <input type="file" class="custom-file-input" 
                                                  id="denklik" 
                                                  name="denklik" 
                                                  accept=".pdf,.jpg,.jpeg,.png">
                                        </div>
                                        <small class="form-text text-muted">If applicable</small>
                                        @error('denklik')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="certificate">Other Certificates (SAT, ACT, etc.)</label>
                                        <div class="custom-file-container">
                                            <div class="custom-file-label">
                                                <span id="certificate-filename">No file selected</span>
                                                <div class="browse-btn">Browse</div>
                                            </div>
                                            <input type="file" class="custom-file-input" 
                                                  id="certificate" 
                                                  name="certificate" 
                                                  accept=".pdf,.jpg,.jpeg,.png">
                                        </div>
                                        <small class="form-text text-muted">Any additional qualification certificates</small>
                                        @error('certificate')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mt-4">
                                <label for="other_documents">Other Supporting Documents</label>
                                <div class="custom-file-container">
                                    <div class="custom-file-label">
                                        <span id="other-documents-filename">No file selected</span>
                                        <div class="browse-btn">Browse</div>
                                    </div>
                                    <input type="file" class="custom-file-input" 
                                          id="other_documents" 
                                          name="other_documents" 
                                          accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                <small class="form-text text-muted">Any additional documents that may support your application</small>
                                @error('other_documents')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group mt-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" 
                                          type="checkbox" 
                                          id="terms" 
                                          name="terms" 
                                          required>
                                    <label class="form-check-label" for="terms">
                                        I confirm that all information provided is accurate and I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a>.
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                       <!-- Fixed Submit Button - Add to the end of the form before closing </form> tag -->
<div class="d-flex justify-content-between mt-4">
    <button type="button" class="btn btn-secondary prev-step">
        <i class="fas fa-arrow-left"></i> Back
    </button>
    <button type="submit" class="btn btn-success" id="submitBtn" onclick="document.getElementById('registrationForm').submit();">
        <i class="fas fa-paper-plane"></i> Submit Application
    </button>
</div>

<!-- Add this script at the bottom of your page, before the closing </body> tag -->
<script>
// Form submission fix
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        // Ensure the form submits when the button is clicked
        submitBtn.addEventListener('click', function(event) {
            event.preventDefault();
            
            // Validate all fields before submission
            const allSteps = document.querySelectorAll('.step');
            let isValid = true;
            
            // Validate all steps
            allSteps.forEach(step => {
                const requiredFields = step.querySelectorAll('input[required], select[required]');
                requiredFields.forEach(field => {
                    if (!field.value) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
            });
            
            if (isValid) {
                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                submitBtn.disabled = true;
                
                // Submit the form
                form.submit();
            } else {
                // Show error message
                alert('Please complete all required fields before submitting.');
                
                // Find the first step with invalid fields and show it
                for (let i = 0; i < allSteps.length; i++) {
                    const invalidFields = allSteps[i].querySelectorAll('.is-invalid');
                    if (invalidFields.length > 0) {
                        // Show this step
                        allSteps.forEach(s => s.classList.add('d-none'));
                        allSteps[i].classList.remove('d-none');
                        updateStepper(i);
                        
                        // Scroll to first invalid field
                        invalidFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                        break;
                    }
                }
            }
        });
    }
});
</script>

<!-- Add this in the <style> section to update the color scheme -->
<style>
:root {
    --primary-color: #ff4757;
    --secondary-color: #ff6b81;
    --success-color: #2ed573;
    --warning-color: #ffa502;
    --info-color: #70a1ff;
    --light-color: #f8f9fa;
    --dark-color: #333333;
    --body-bg: #f5f7fa;
}

/* Update existing component styles with new colors */

.info-sidebar {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
}

.bg-shape-1 {
    background: var(--primary-color);
}

.bg-shape-2 {
    background: var(--warning-color);
}

.card-header {
    background: var(--primary-color);
}

.section-header i {
    color: var(--primary-color);
    background: rgba(255, 71, 87, 0.1);
}

.stepper-item.active .step-counter {
    background: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 3px 15px rgba(255, 71, 87, 0.3);
}

.stepper-item.completed .step-counter {
    background: var(--success-color);
    border-color: var(--success-color);
}

.stepper-item.completed .step-name,
.stepper-item.active .step-name {
    color: var(--primary-color);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(255, 71, 87, 0.25);
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    box-shadow: 0 4px 10px rgba(255, 71, 87, 0.3);
}

.btn-primary:hover {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
    box-shadow: 0 6px 15px rgba(255, 71, 87, 0.4);
}

.btn-success {
    background-color: var(--success-color);
    border-color: var(--success-color);
    box-shadow: 0 4px 10px rgba(46, 213, 115, 0.3);
}

.btn-success:hover {
    background-color: #26ae60;
    border-color: #26ae60;
    box-shadow: 0 6px 15px rgba(46, 213, 115, 0.4);
}

.review-card .card-header {
    background-color: rgba(255, 71, 87, 0.05);
    border-bottom: 1px solid rgba(255, 71, 87, 0.1);
    color: var(--primary-color);
}

.documents-list li i {
    color: var(--primary-color);
}

.password-strength-meter.strength-strong {
    background-color: var(--success-color);
}

.strength-strong-text {
    color: var(--success-color);
}

.strength-medium {
    background-color: var(--warning-color);
}

.strength-medium-text {
    color: var(--warning-color);
}

/* Custom Loader Animation */
@keyframes submitPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(46, 213, 115, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(46, 213, 115, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(46, 213, 115, 0);
    }
}

#submitBtn {
    position: relative;
    overflow: hidden;
}

#submitBtn:active {
    animation: submitPulse 1.5s infinite;
}
</style>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>ABX SITE Student Registration Terms</h5>
                    <p>By submitting this application form, you agree to the following terms and conditions:</p>
                    
                    <h6>1. Accuracy of Information</h6>
                    <p>You certify that all information provided in this application is complete and accurate. Providing false information may result in the rejection of your application or subsequent dismissal if admitted.</p>
                    
                    <h6>2. Documentation</h6>
                    <p>You agree to provide authentic documents. The university reserves the right to verify all submitted documents with issuing authorities.</p>
                    
                    <h6>3. Privacy Policy</h6>
                    <p>You consent to the collection and processing of your personal data for admission-related purposes in accordance with our privacy policy.</p>
                    
                    <h6>4. Communication</h6>
                    <p>You authorize the university to contact you via email, phone, or mail regarding your application status and other relevant information.</p>
                    
                    <h6>5. Application Fee</h6>
                    <p>You understand that application fees, if applicable, are non-refundable regardless of the admission decision.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle file input labels
        const fileInputs = document.querySelectorAll('.custom-file-input');
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const fileId = this.id;
                const filenameElement = document.getElementById(`${fileId}-filename`);
                
                if (this.files.length > 0) {
                    if (this.multiple) {
                        filenameElement.textContent = `${this.files.length} files selected`;
                    } else {
                        filenameElement.textContent = this.files[0].name;
                    }
                } else {
                    filenameElement.textContent = 'No file selected';
                }
            });
        });

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

        // Password strength meter
        const passwordInput = document.getElementById('password');
        const strengthMeter = document.querySelector('.password-strength-meter');
        const strengthText = document.querySelector('.strength-text');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 8) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^A-Za-z0-9]/)) strength += 1;
            
            strengthMeter.className = 'password-strength-meter';
            strengthText.className = 'strength-text';
            
            if (password.length === 0) {
                strengthMeter.style.width = '0';
                strengthText.textContent = '';
            } else if (strength < 2) {
                strengthMeter.classList.add('strength-weak');
                strengthText.classList.add('strength-weak-text');
                strengthText.textContent = 'Weak password';
            } else if (strength < 4) {
                strengthMeter.classList.add('strength-medium');
                strengthText.classList.add('strength-medium-text');
                strengthText.textContent = 'Medium password';
            } else {
                strengthMeter.classList.add('strength-strong');
                strengthText.classList.add('strength-strong-text');
                strengthText.textContent = 'Strong password';
            }
        });

        // Stepper navigation
        const nextButtons = document.querySelectorAll('.next-step');
        const prevButtons = document.querySelectorAll('.prev-step');
        const stepperItems = document.querySelectorAll('.stepper-item');
        const steps = document.querySelectorAll('.step');
        
        nextButtons.forEach(button => {
            button.addEventListener('click', function() {
                const currentStep = this.closest('.step');
                const currentIndex = Array.from(steps).indexOf(currentStep);
                const nextStep = steps[currentIndex + 1];
                
                if (validateStep(currentStep)) {
                    currentStep.classList.add('d-none');
                    nextStep.classList.remove('d-none');
                    updateStepper(currentIndex + 1);
                    
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
        
        prevButtons.forEach(button => {
            button.addEventListener('click', function() {
                const currentStep = this.closest('.step');
                const currentIndex = Array.from(steps).indexOf(currentStep);
                const prevStep = steps[currentIndex - 1];
                
                currentStep.classList.add('d-none');
                prevStep.classList.remove('d-none');
                updateStepper(currentIndex - 1);
                
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
        
        // Update stepper indicators
        function updateStepper(activeIndex) {
            stepperItems.forEach((item, index) => {
                item.classList.remove('active', 'completed');
                
                if (index < activeIndex) {
                    item.classList.add('completed');
                } else if (index === activeIndex) {
                    item.classList.add('active');
                }
            });
        }
        
        // Basic form validation
        function validateStep(step) {
            let isValid = true;
            
            step.querySelectorAll('input[required], select[required]').forEach(field => {
                if (!field.value) {
                    field.classList.add('is-invalid');
                    isValid = false;
                    
                    // Scroll to first invalid field
                    if (isValid === false) {
                        field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Password confirmation validation
            if (step.contains(document.getElementById('password')) && step.contains(document.getElementById('password_confirmation'))) {
                const password = document.getElementById('password').value;
                const confirmation = document.getElementById('password_confirmation').value;
                
                if (password !== confirmation) {
                    document.getElementById('password_confirmation').classList.add('is-invalid');
                    
                    // Add error message if not exists
                    let errorElement = document.getElementById('password-match-error');
                    if (!errorElement) {
                        errorElement = document.createElement('div');
                        errorElement.id = 'password-match-error';
                        errorElement.className = 'invalid-feedback d-block';
                        errorElement.textContent = 'Passwords do not match';
                        document.getElementById('password_confirmation').parentNode.appendChild(errorElement);
                    }
                    
                    isValid = false;
                } else {
                    document.getElementById('password_confirmation').classList.remove('is-invalid');
                    
                    // Remove error message if exists
                    const errorElement = document.getElementById('password-match-error');
                    if (errorElement) {
                        errorElement.remove();
                    }
                }
            }
            
            return isValid;
        }
        
        // Smooth scroll to form on page load
        setTimeout(() => {
            document.querySelector('.main-card').scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        }, 500);
        
        // Add animation classes when switching steps
        document.querySelectorAll('.next-step, .prev-step').forEach(button => {
            button.addEventListener('click', function() {
                setTimeout(() => {
                    document.querySelectorAll('.step:not(.d-none)').forEach(step => {
                        step.classList.add('animate__animated', 'animate__fadeIn');
                        setTimeout(() => {
                            step.classList.remove('animate__animated', 'animate__fadeIn');
                        }, 500);
                    });
                }, 10);
            });
        });

        // Date of birth validation
        const dateOfBirthInput = document.getElementById('date_of_birth');
        if (dateOfBirthInput) {
            dateOfBirthInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                const minAge = 16; // Minimum age requirement
                
                let maxDate = new Date();
                maxDate.setFullYear(today.getFullYear() - minAge);
                
                if (selectedDate > maxDate) {
                    this.classList.add('is-invalid');
                    
                    // Add error message if not exists
                    let errorElement = document.getElementById('dob-error');
                    if (!errorElement) {
                        errorElement = document.createElement('div');
                        errorElement.id = 'dob-error';
                        errorElement.className = 'invalid-feedback d-block';
                        errorElement.textContent = `You must be at least ${minAge} years old to apply.`;
                        this.parentNode.appendChild(errorElement);
                    }
                } else {
                    this.classList.remove('is-invalid');
                    
                    // Remove error message if exists
                    const errorElement = document.getElementById('dob-error');
                    if (errorElement) {
                        errorElement.remove();
                    }
                }
            });
        }

        // Passport expiry date validation
        const passportExpiryInput = document.getElementById('passport_expiry_date');
        if (passportExpiryInput) {
            passportExpiryInput.addEventListener('change', function() {
                const expiryDate = new Date(this.value);
                const today = new Date();
                const sixMonthsLater = new Date();
                sixMonthsLater.setMonth(today.getMonth() + 6);
                
                if (expiryDate < sixMonthsLater) {
                    this.classList.add('is-invalid');
                    
                    // Add error message if not exists
                    let errorElement = document.getElementById('expiry-error');
                    if (!errorElement) {
                        errorElement = document.createElement('div');
                        errorElement.id = 'expiry-error';
                        errorElement.className = 'invalid-feedback d-block';
                        errorElement.textContent = 'Passport should be valid for at least 6 months from today.';
                        this.parentNode.appendChild(errorElement);
                    }
                } else {
                    this.classList.remove('is-invalid');
                    
                    // Remove error message if exists
                    const errorElement = document.getElementById('expiry-error');
                    if (errorElement) {
                        errorElement.remove();
                    }
                }
            });
        }

        // Form submission handling
        const form = document.getElementById('registrationForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                const allSteps = document.querySelectorAll('.step');
                let isValid = true;
                
                // Validate all steps
                allSteps.forEach(step => {
                    if (!validateStep(step)) {
                        isValid = false;
                    }
                });
                
                if (!isValid) {
                    event.preventDefault();
                    
                    // Show error message
                    alert('Please check all fields and correct any errors before submitting.');
                    
                    // Find the first step with invalid fields and show it
                    for (let i = 0; i < allSteps.length; i++) {
                        const invalidFields = allSteps[i].querySelectorAll('.is-invalid');
                        if (invalidFields.length > 0) {
                            // Show this step
                            allSteps.forEach(s => s.classList.add('d-none'));
                            allSteps[i].classList.remove('d-none');
                            updateStepper(i);
                            
                            // Scroll to first invalid field
                            invalidFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                            break;
                        }
                    }
                }
            });
        }

        // Initialize select2 for countries if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('.form-select').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }

        // Initialize stepper
        updateStepper(0);
    });
    </script>
</body>
</html>