@extends('admin.index')

@section('content')
<!-- Student Profile Wrapper -->
<div class="student-profile-wrapper">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="container-fluid">
            <div class="header-content">
                <div class="student-intro">
                    <div class="student-avatar">
                        @if($student->photo_path)
                            <img src="{{ Storage::url($student->photo_path) }}" alt="{{ $student->first_name }}" class="avatar-img">
                        @else
                            <div class="avatar-placeholder">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </div>
                        @endif
                        <div class="status-indicator 
                            @if($student->status == 'New') status-primary
                            @elseif($student->status == 'In Review') status-info
                            @elseif($student->status == 'Pending Documents') status-warning
                            @elseif($student->status == 'Accepted') status-success
                            @elseif($student->status == 'Rejected') status-danger
                            @elseif($student->status == 'Enrolled') status-success
                            @elseif($student->status == 'Cancelled') status-secondary
                            @endif">
                        </div>
                    </div>
                    <div class="student-name-container">
                        <h1 class="student-name">{{ $student->first_name }} {{ $student->last_name }}</h1>
                        <div class="student-status">
                            <span class="status-label 
                                @if($student->status == 'New') status-badge-primary
                                @elseif($student->status == 'In Review') status-badge-info
                                @elseif($student->status == 'Pending Documents') status-badge-warning
                                @elseif($student->status == 'Accepted') status-badge-success
                                @elseif($student->status == 'Rejected') status-badge-danger
                                @elseif($student->status == 'Enrolled') status-badge-success
                                @elseif($student->status == 'Cancelled') status-badge-secondary
                                @endif">
                                @if($student->status == 'New')
                                    <i class="fas fa-plus-circle"></i>
                                @elseif($student->status == 'In Review')
                                    <i class="fas fa-search"></i>
                                @elseif($student->status == 'Pending Documents')
                                    <i class="fas fa-exclamation-circle"></i>
                                @elseif($student->status == 'Accepted')
                                    <i class="fas fa-check-circle"></i>
                                @elseif($student->status == 'Rejected')
                                    <i class="fas fa-times-circle"></i>
                                @elseif($student->status == 'Enrolled')
                                    <i class="fas fa-user-graduate"></i>
                                @elseif($student->status == 'Cancelled')
                                    <i class="fas fa-ban"></i>
                                @endif
                                {{ $student->status }}
                            </span>
                            
                            @if($student->is_transfer)
                                <span class="status-badge-info-soft ml-2">
                                    <i class="fas fa-exchange-alt"></i> Transfer
                                </span>
                            @endif
                            
                            @if($student->needs_visa_support)
                                <span class="status-badge-warning-soft ml-2">
                                    <i class="fas fa-passport"></i> Visa Support
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-white">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-white">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-white dropdown-toggle" type="button" id="moreActions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="moreActions">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-envelope"></i> Send Email
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-print"></i> Print Details
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-download"></i> Export Data
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash-alt"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="key-details">
                <div class="key-detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="detail-info">
                        <span class="detail-label">Student ID</span>
                        <span class="detail-value">{{ $student->id }}</span>
                    </div>
                </div>
                
                <div class="key-detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-passport"></i>
                    </div>
                    <div class="detail-info">
                        <span class="detail-label">Passport</span>
                        <span class="detail-value">{{ $student->passport_id }}</span>
                    </div>
                </div>
                
                <div class="key-detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="detail-info">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">{{ $student->email }}</span>
                    </div>
                </div>
                
                <div class="key-detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="detail-info">
                        <span class="detail-label">Phone</span>
                        <span class="detail-value">{{ $student->phone_number }}</span>
                    </div>
                </div>
                
                <div class="key-detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="detail-info">
                        <span class="detail-label">Program</span>
                        <span class="detail-value">{{ $student->applyingDegree->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status Update Panel -->
    <div class="container-fluid">
        <div class="status-update-panel">
            @if(session('success'))
                <div class="success-alert">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="close-alert" onclick="this.parentElement.style.display='none';">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            
            <div class="update-form-container">
                <h3 class="section-title">
                    <i class="fas fa-exchange-alt"></i> Update Status
                </h3>
                
                <form action="{{ route('admin.students.change-status', $student) }}" method="POST" class="status-form">
                    @csrf
                    @method('PATCH')
                    
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control status-select">
                                @foreach($student::getStatuses() as $key => $value)
                                    <option value="{{ $key }}" {{ $student->status == $key ? 'selected' : '' }}
                                        data-icon="
                                        @if($key == 'New') fa-plus-circle
                                
                                        @elseif($key == 'Accepted') fa-check-circle
                                        @elseif($key == 'Rejected') fa-times-circle
                                     
                                        @elseif($key == 'Cancelled') fa-ban
                                        @endif">
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                
                        
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="container-fluid">
        <div class="content-tabs">
            <ul class="nav nav-tabs" id="studentTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab">
                        <i class="fas fa-user"></i> Personal Info
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="education-tab" data-toggle="tab" href="#education" role="tab">
                        <i class="fas fa-graduation-cap"></i> Education
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab">
                        <i class="fas fa-file-alt"></i> Documents
                        @if(!$student->photo_path || !$student->passport_path || !$student->transcript_path)
                            <span class="badge badge-warning ml-1">!</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="timeline-tab" data-toggle="tab" href="#timeline" role="tab">
                        <i class="fas fa-history"></i> Timeline
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="timeline-tab" data-toggle="tab" href="#application" role="tab">
                        <i class="fas fa-history"></i> Applications
                    </a>
                </li>
            </ul>
            
            <div class="tab-content">
                <!-- Personal Information Tab -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    <div class="info-cards-container">
                        <div class="row">
                            <!-- Basic Info Card -->
                            <div class="col-lg-6 mb-4">
                                <div class="info-card">
                                    <div class="card-header">
                                        <h3><i class="fas fa-address-card"></i> Basic Information</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <span class="info-label">Full Name</span>
                                                    <span class="info-value">{{ $student->first_name }} {{ $student->last_name }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Gender</span>
                                                    <span class="info-value">{{ $student->gender }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Date of Birth</span>
                                                    <span class="info-value">{{ $student->date_of_birth->format('d/m/Y') }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Marital Status</span>
                                                    <span class="info-value">{{ $student->marital_status }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <span class="info-label">Email</span>
                                                    <span class="info-value">{{ $student->email }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Phone Number</span>
                                                    <span class="info-value">{{ $student->phone_number }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Nationality</span>
                                                    <span class="info-value">{{ $student->nationality->name ?? 'N/A' }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Country of Residence</span>
                                                    <span class="info-value">{{ $student->countryOfResidence->name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Passport Card -->
                            <div class="col-lg-6 mb-4">
                                <div class="info-card">
                                    <div class="card-header">
                                        <h3><i class="fas fa-passport"></i> Passport Information</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <span class="info-label">Passport ID</span>
                                                    <span class="info-value highlight">{{ $student->passport_id }}</span>
                                                </div>
                                                
                                                {{-- <div class="info-item">
                                                    <span class="info-label">Issue Date</span>
                                                    <span class="info-value">{{ $student->passport_issue_date->format('d/m/Y') }}</span>
                                                </div> --}}
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Expiry Date</span>
                                                    <span class="info-value 
                                                        @if($student->passport_expiry_date->lt(now()->addMonths(6))) text-warning @endif">
                                                        {{ $student->passport_expiry_date->format('d/m/Y') }}
                                                        @if($student->passport_expiry_date->lt(now()->addMonths(6)))
                                                            <i class="fas fa-exclamation-triangle text-warning ml-1" title="Expires in less than 6 months"></i>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <span class="info-label">Needs Visa Support</span>
                                                    <span class="info-value">
                                                        @if($student->needs_visa_support)
                                                            <span class="badge badge-warning"><i class="fas fa-check"></i> Yes</span>
                                                        @else
                                                            <span class="badge badge-success"><i class="fas fa-times"></i> No</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Is Transfer Student</span>
                                                    <span class="info-value">
                                                        @if($student->is_transfer)
                                                            <span class="badge badge-info"><i class="fas fa-check"></i> Yes</span>
                                                        @else
                                                            <span class="badge badge-secondary"><i class="fas fa-times"></i> No</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                
                                                @if($student->passport_path)
                                                <div class="info-item mt-3">
                                                    <a href="{{ Storage::url($student->passport_path) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                                        <i class="fas fa-eye"></i> View Passport Document
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Family Info Card -->
                            <div class="col-lg-6 mb-4">
                                <div class="info-card">
                                    <div class="card-header">
                                        <h3><i class="fas fa-users"></i> Family Information</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <span class="info-label">Father's Name</span>
                                                    <span class="info-value">{{ $student->father_name }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Mother's Name</span>
                                                    <span class="info-value">{{ $student->mother_name }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <span class="info-label">Emergency Email</span>
                                                    <span class="info-value">{{ $student->emergency_email ?? 'N/A' }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Emergency Phone</span>
                                                    <span class="info-value">{{ $student->emergency_phone ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Application Info Card -->
                            <div class="col-lg-6 mb-4">
                                <div class="info-card">
                                    <div class="card-header">
                                        <h3><i class="fas fa-clipboard-list"></i> Application Details</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <span class="info-label">Application Date</span>
                                                    <span class="info-value">{{ $student->application_date->format('d/m/Y') }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Academic Year</span>
                                                    <span class="info-value">{{ $student->academic_year }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Study Country</span>
                                                    <span class="info-value">{{ $student->studyCountry->name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <span class="info-label">Responsible Employee</span>
                                                    <span class="info-value">{{ $student->responsibleEmployee->name ?? 'N/A' }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Processed By</span>
                                                    <span class="info-value">{{ $student->processedBy->name ?? 'N/A' }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Notes</span>
                                                    <span class="info-value">{{ $student->notes ?? 'No notes available' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Education Tab -->
                <div class="tab-pane fade" id="education" role="tabpanel">
                    <div class="info-cards-container">
                        <div class="row">
                            <!-- High School Info -->
                            <div class="col-lg-6 mb-4">
                                <div class="info-card">
                                    <div class="card-header">
                                        <h3><i class="fas fa-school"></i> High School Information</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <span class="info-label">High School Name</span>
                                                    <span class="info-value">{{ $student->high_school_name }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">High School Country</span>
                                                    <span class="info-value">{{ $student->highSchoolCountry->name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <span class="info-label">GPA</span>
                                                    <span class="info-value highlight">{{ $student->gpa }}</span>
                                                </div>
                                                
                                                @if($student->transcript_path)
                                                <div class="info-item mt-3">
                                                    <a href="{{ Storage::url($student->transcript_path) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                                        <i class="fas fa-eye"></i> View Transcript
                                                    </a>
                                                </div>
                                                @endif
                                                
                                                @if($student->diploma_path)
                                                <div class="info-item mt-2">
                                                    <a href="{{ Storage::url($student->diploma_path) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                                        <i class="fas fa-eye"></i> View Diploma
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Applied Program -->
                            <div class="col-lg-6 mb-4">
                                <div class="info-card">
                                    <div class="card-header">
                                        <h3><i class="fas fa-graduation-cap"></i> Applied Program</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <span class="info-label">Department</span>
                                                    <span class="info-value">{{ $student->applyingDegree->name ?? 'N/A' }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Degree</span>
                                                    <span class="info-value">{{ $student->applyingDegree->name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <span class="info-label">Academic Year</span>
                                                    <span class="info-value">{{ $student->academic_year }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Study Country</span>
                                                    <span class="info-value">{{ $student->studyCountry->name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($student->denklik_path || $student->certificate_path)
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="additional-documents">
                                                    <h5>Additional Documents</h5>
                                                    <div class="d-flex flex-wrap">
                                                        @if($student->denklik_path)
                                                        <a href="{{ Storage::url($student->denklik_path) }}" class="btn btn-sm btn-outline-info mr-2 mb-2" target="_blank">
                                                            <i class="fas fa-eye"></i> View Denklik
                                                        </a>
                                                        @endif
                                                        
                                                        @if($student->certificate_path)
                                                        <a href="{{ Storage::url($student->certificate_path) }}" class="btn btn-sm btn-outline-info mr-2 mb-2" target="_blank">
                                                            <i class="fas fa-eye"></i> View Certificate
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- <!-- Documents Tab -->
                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="documents-grid">
                        <!-- Photo Document -->
                        <div class="document-card {{ !$student->photo_path ? 'missing' : '' }}">
                            <div class="document-icon">
                                <i class="far fa-user"></i>
                            </div>
                            <div class="document-details">
                                <h4>Photo</h4>
                                <p class="document-description">Student identification photo</p>
                                @if($student->photo_path)
                    <div class="btn-group" role="group">
                        <a href="{{ Storage::url($student->photo_path) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ Storage::url($student->photo_path) }}" class="btn btn-outline-success" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                @else
                    <div class="missing-document">
                        <span class="missing-label"><i class="fas fa-exclamation-triangle"></i> Missing</span>
                        <a href="{{ route('admin.students.edit', $student) }}#documents" class="btn btn-sm btn-warning">
                            <i class="fas fa-upload"></i> Upload
                        </a>
                    </div>
                @endif
                            </div>
                        </div>
                        
                        <!-- Passport Document -->
                        <div class="document-card {{ !$student->passport_path ? 'missing' : '' }}">
                            <div class="document-icon">
                                <i class="fas fa-passport"></i>
                            </div>
                            <div class="document-details">
                                <h4>Passport</h4>
                                <p class="document-description">Copy of passport identification page</p>
                                @if($student->photo_path)
                    <div class="btn-group" role="group">
                        <a href="{{ Storage::url($student->photo_path) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ Storage::url($student->photo_path) }}" class="btn btn-outline-success" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                @else
                    <div class="missing-document">
                        <span class="missing-label"><i class="fas fa-exclamation-triangle"></i> Missing</span>
                        <a href="{{ route('admin.students.edit', $student) }}#documents" class="btn btn-sm btn-warning">
                            <i class="fas fa-upload"></i> Upload
                        </a>
                    </div>
                @endif
                            </div>
                        </div>
                        
                        <!-- Transcript Document -->
                        <div class="document-card {{ !$student->transcript_path ? 'missing' : '' }}">
                            <div class="document-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="document-details">
                                <h4>Transcript</h4>
                                <p class="document-description">Official high school transcript</p>
                                @if($student->transcript_path)
                                <div class="btn-group" role="group">

                                <a href="{{ Storage::url($student->transcript_path) }}" class="btn btn-outline-info" target="_blank">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ Storage::url($student->transcript_path) }}" class="btn btn-outline-success" download>
                                    <i class="fas fa-download"></i> Download
                                </a>
                                </div>
                            @else
                                <div class="missing-document">
                                    <span class="missing-label"><i class="fas fa-exclamation-triangle"></i> Missing</span>
                                    <a href="{{ route('admin.students.edit', $student) }}#documents" class="btn btn-sm btn-warning">
                                        <i class="fas fa-upload"></i> Upload
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Diploma Document -->
                    <div class="document-card">
                        <div class="document-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="document-details">
                            <h4>Diploma</h4>
                            <p class="document-description">High school diploma</p>
                            @if($student->diploma_path)
                            <div class="btn-group" role="group">
                                <a href="{{ Storage::url($student->diploma_path) }}" class="btn btn-outline-info" target="_blank">
                                    <i class="fas fa-eye"></i> View
                                </a>
  <a href="{{ Storage::url($student->diploma_path) }}" class="btn btn-outline-success" download>
                                    <i class="fas fa-download"></i> Download
                                </a>

</div>
                            @else
                                <div class="optional-document">
                                    <span class="optional-label"><i class="fas fa-info-circle"></i> Optional</span>
                                    <a href="{{ route('admin.students.edit', $student) }}#documents" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-upload"></i> Upload
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Denklik Document -->
                    <div class="document-card">
                        <div class="document-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="document-details">
                            <h4>Denklik</h4>
                            <p class="document-description">Certificate of equivalence</p>

                            @if($student->denklik_path)
                            <div class="btn-group" role="group">

                                <a href="{{ Storage::url($student->denklik_path) }}" class="btn btn-outline-info" target="_blank">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ Storage::url($student->denklik_path) }}" class="btn btn-outline-success" download>
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                            @else
                                <div class="optional-document">
                                    <span class="optional-label"><i class="fas fa-info-circle"></i> Optional</span>
                                    <a href="{{ route('admin.students.edit', $student) }}#documents" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-upload"></i> Upload
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Certificate Document -->
                    <div class="document-card">
                        <div class="document-icon">
                            <i class="fas fa-file-certificate"></i>
                        </div>
                        <div class="document-details">
                            <h4>Certificate</h4>
                            <p class="document-description">Additional certificate</p>
                            @if($student->certificate_path)
                            <div class="btn-group" role="group">

                                <a href="{{ Storage::url($student->certificate_path) }}" class="btn btn-outline-info" target="_blank">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ Storage::url($student->certificate_path) }}" class="btn btn-outline-success" download>
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                            @else
                                <div class="optional-document">
                                    <span class="optional-label"><i class="fas fa-info-circle"></i> Optional</span>
                                    <a href="{{ route('admin.students.edit', $student) }}#documents" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-upload"></i> Upload
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Other Documents -->
                    <div class="document-card">
                        <div class="document-icon">
                            <i class="fas fa-file-archive"></i>
                        </div>
                        <div class="document-details">
                            <h4>Other Documents</h4>
                            <p class="document-description">Additional supporting documents</p>
                            @if($student->other_documents_path)
                            <div class="btn-group" role="group">

                                <a href="{{ Storage::url($student->other_documents_path) }}" class="btn btn-outline-info" target="_blank">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ Storage::url($student->other_documents_path) }}" class="btn btn-outline-success" download>
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                            @else
                                <div class="optional-document">
                                    <span class="optional-label"><i class="fas fa-info-circle"></i> Optional</span>
                                    <a href="{{ route('admin.students.edit', $student) }}#documents" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-upload"></i> Upload
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
              
            </div> --}}
            
<!-- Documents Tab -->
<div class="tab-pane fade" id="documents" role="tabpanel">
    <div class="documents-grid">
        <!-- Photo Document -->
        <div class="document-card {{ !$student->photo_path ? 'missing' : '' }}">
            <div class="document-icon">
                <i class="far fa-user"></i>
            </div>
            <div class="document-details">
                <h4>Photo</h4>
                <p class="document-description">Student identification photo</p>
                @if($student->photo_path)
                    <div class="btn-group" role="group">
                        <a href="{{ Storage::url($student->photo_path) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ Storage::url($student->photo_path) }}" class="btn btn-outline-success" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-outline-danger delete-document" 
                                data-toggle="modal" 
                                data-target="#deleteDocumentModal" 
                                data-document="photo_path"
                                data-document-name="Photo">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                @else
                    <div class="missing-document">
                        <span class="missing-label"><i class="fas fa-exclamation-triangle"></i> Missing</span>
                        <button type="button" class="btn btn-sm btn-warning upload-document-btn" 
                                data-document="photo_path" 
                                data-document-name="Photo">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Passport Document -->
        <div class="document-card {{ !$student->passport_path ? 'missing' : '' }}">
            <div class="document-icon">
                <i class="fas fa-passport"></i>
            </div>
            <div class="document-details">
                <h4>Passport</h4>
                <p class="document-description">Copy of passport identification page</p>
                @if($student->passport_path)
                    <div class="btn-group" role="group">
                        <a href="{{ Storage::url($student->passport_path) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ Storage::url($student->passport_path) }}" class="btn btn-outline-success" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-outline-danger delete-document" 
                                data-toggle="modal" 
                                data-target="#deleteDocumentModal" 
                                data-document="passport_path"
                                data-document-name="Passport">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                @else
                    <div class="missing-document">
                        <span class="missing-label"><i class="fas fa-exclamation-triangle"></i> Missing</span>
                        <button type="button" class="btn btn-sm btn-warning upload-document-btn" 
                                data-document="passport_path" 
                                data-document-name="Passport">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Transcript Document -->
        <div class="document-card {{ !$student->transcript_path ? 'missing' : '' }}">
            <div class="document-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="document-details">
                <h4>Transcript</h4>
                <p class="document-description">Official high school transcript</p>
                @if($student->transcript_path)
                    <div class="btn-group" role="group">
                        <a href="{{ Storage::url($student->transcript_path) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ Storage::url($student->transcript_path) }}" class="btn btn-outline-success" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-outline-danger delete-document" 
                                data-toggle="modal" 
                                data-target="#deleteDocumentModal" 
                                data-document="transcript_path"
                                data-document-name="Transcript">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                @else
                    <div class="missing-document">
                        <span class="missing-label"><i class="fas fa-exclamation-triangle"></i> Missing</span>
                        <button type="button" class="btn btn-sm btn-warning upload-document-btn" 
                                data-document="transcript_path" 
                                data-document-name="Transcript">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Diploma Document -->
        <div class="document-card {{ !$student->diploma_path ? 'missing' : '' }}">
            <div class="document-icon">
                <i class="fas fa-award"></i>
            </div>
            <div class="document-details">
                <h4>Diploma</h4>
                <p class="document-description">High school diploma</p>
                @if($student->diploma_path)
                    <div class="btn-group" role="group">
                        <a href="{{ Storage::url($student->diploma_path) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ Storage::url($student->diploma_path) }}" class="btn btn-outline-success" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-outline-danger delete-document" 
                                data-toggle="modal" 
                                data-target="#deleteDocumentModal" 
                                data-document="diploma_path"
                                data-document-name="Diploma">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                @else
                    <div class="optional-document">
                        <span class="optional-label"><i class="fas fa-info-circle"></i> Optional</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary upload-document-btn" 
                                data-document="diploma_path" 
                                data-document-name="Diploma">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Denklik Document -->
        <div class="document-card {{ !$student->denklik_path ? 'missing' : '' }}">
            <div class="document-icon">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="document-details">
                <h4>Denklik</h4>
                <p class="document-description">Certificate of equivalence</p>
                @if($student->denklik_path)
                    <div class="btn-group" role="group">
                        <a href="{{ Storage::url($student->denklik_path) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ Storage::url($student->denklik_path) }}" class="btn btn-outline-success" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-outline-danger delete-document" 
                                data-toggle="modal" 
                                data-target="#deleteDocumentModal" 
                                data-document="denklik_path"
                                data-document-name="Denklik">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                @else
                    <div class="optional-document">
                        <span class="optional-label"><i class="fas fa-info-circle"></i> Optional</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary upload-document-btn" 
                                data-document="denklik_path" 
                                data-document-name="Denklik">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Certificate Document -->
        <div class="document-card {{ !$student->certificate_path ? 'missing' : '' }}">
            <div class="document-icon">
                <i class="fas fa-file-certificate"></i>
            </div>
            <div class="document-details">
                <h4>Certificate</h4>
                <p class="document-description">Additional certificate</p>
                @if($student->certificate_path)
                    <div class="btn-group" role="group">
                        <a href="{{ Storage::url($student->certificate_path) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ Storage::url($student->certificate_path) }}" class="btn btn-outline-success" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-outline-danger delete-document" 
                                data-toggle="modal" 
                                data-target="#deleteDocumentModal" 
                                data-document="certificate_path"
                                data-document-name="Certificate">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                @else
                    <div class="optional-document">
                        <span class="optional-label"><i class="fas fa-info-circle"></i> Optional</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary upload-document-btn" 
                                data-document="certificate_path" 
                                data-document-name="Certificate">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Other Documents -->
        <div class="document-card {{ !$student->other_documents_path ? 'missing' : '' }}">
            <div class="document-icon">
                <i class="fas fa-file-archive"></i>
            </div>
            <div class="document-details">
                <h4>Other Documents</h4>
                <p class="document-description">Additional supporting documents</p>
                @if($student->other_documents_path)
                    <div class="btn-group" role="group">
                        <a href="{{ Storage::url($student->other_documents_path) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ Storage::url($student->other_documents_path) }}" class="btn btn-outline-success" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-outline-danger delete-document" 
                                data-toggle="modal" 
                                data-target="#deleteDocumentModal" 
                                data-document="other_documents_path"
                                data-document-name="Other Documents">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                @else
                    <div class="optional-document">
                        <span class="optional-label"><i class="fas fa-info-circle"></i> Optional</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary upload-document-btn" 
                                data-document="other_documents_path" 
                                data-document-name="Other Documents">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" role="dialog" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="uploadDocumentModalLabel">Upload Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uploadDocumentForm" method="POST" action="{{ route('admin.students.upload-document', $student) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="document_file">Select File</label>
                        <input type="file" class="form-control-file" id="document_file" name="document_file" required>
                        <small class="text-muted">Accepted file types: PDF, JPG, JPEG, PNG (Max: 5MB)</small>
                    </div>
                    <input type="hidden" name="document_type" id="document_type">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Document Modal -->
<div class="modal fade" id="deleteDocumentModal" tabindex="-1" role="dialog" aria-labelledby="deleteDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteDocumentModalLabel">Delete Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this document? This action cannot be undone.</p>
                <p class="font-weight-bold" id="deleteDocumentName"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteDocumentForm" method="POST" action="{{ route('admin.students.delete-document', $student) }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="document_type" id="delete_document_type">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Upload document button handler
        $('.upload-document-btn').on('click', function() {
            var documentType = $(this).data('document');
            var documentName = $(this).data('document-name');
            
            $('#document_type').val(documentType);
            $('#uploadDocumentModalLabel').text('Upload ' + documentName);
            $('#uploadDocumentModal').modal('show');
        });
        
        // Delete document button handler
        $('.delete-document').on('click', function() {
            var documentType = $(this).data('document');
            var documentName = $(this).data('document-name');
            
            $('#delete_document_type').val(documentType);
            $('#deleteDocumentName').text('Document: ' + documentName);
        });
        
        // Form validation
        $('#uploadDocumentForm').on('submit', function(e) {
            var fileInput = $('#document_file')[0];
            var maxSize = 5 * 1024 * 1024; // 5MB
            
            if (fileInput.files.length > 0) {
                var fileSize = fileInput.files[0].size;
                
                if (fileSize > maxSize) {
                    e.preventDefault();
                    alert('File size exceeds the maximum limit of 5MB.');
                    return false;
                }
                
                var fileType = fileInput.files[0].type;
                var validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                
                if (!validTypes.includes(fileType)) {
                    e.preventDefault();
                    alert('Invalid file type. Please upload a PDF, JPG, JPEG, or PNG file.');
                    return false;
                }
            }
        });
    });
    </script>


            <!-- Timeline Tab -->
            <div class="tab-pane fade" id="timeline" role="tabpanel">
                <div class="timeline-container">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-point-container">
                                <div class="timeline-point bg-success">
                                    <i class="fas fa-plus"></i>
                                </div>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $student->created_at->format('M d, Y - H:i') }}</div>
                                <h4 class="timeline-title">Student Record Created</h4>
                                <div class="timeline-body">
                                    Student application was initially registered in the system.
                                </div>
                                <div class="timeline-meta">
                                    <span class="timeline-tag">System</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-point-container">
                                <div class="timeline-point bg-primary">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $student->created_at->addMinutes(15)->format('M d, Y - H:i') }}</div>
                                <h4 class="timeline-title">Assigned to Employee</h4>
                                <div class="timeline-body">
                                    Student was assigned to {{ $student->responsibleEmployee->name ?? 'an employee' }}.
                                </div>
                                <div class="timeline-meta">
                                    <span class="timeline-tag">Assignment</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-point-container">
                                <div class="timeline-point bg-info">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $student->created_at->addHours(2)->format('M d, Y - H:i') }}</div>
                                <h4 class="timeline-title">Application Review Started</h4>
                                <div class="timeline-body">
                                    The application has been moved to "In Review" status.
                                </div>
                                <div class="timeline-meta">
                                    <span class="timeline-tag">Status Change</span>
                                </div>
                            </div>
                        </div>
                        
                        @if($student->status == 'Pending Documents' || in_array($student->status, ['Accepted', 'Rejected', 'Enrolled']))
                        <div class="timeline-item">
                            <div class="timeline-point-container">
                                <div class="timeline-point bg-warning">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $student->created_at->addDays(1)->format('M d, Y - H:i') }}</div>
                                <h4 class="timeline-title">Documents Requested</h4>
                                <div class="timeline-body">
                                    Additional documents were requested from the student.
                                </div>
                                <div class="timeline-meta">
                                    <span class="timeline-tag">Document Request</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($student->status == 'Accepted' || $student->status == 'Enrolled')
                        <div class="timeline-item">
                            <div class="timeline-point-container">
                                <div class="timeline-point bg-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $student->created_at->addDays(5)->format('M d, Y - H:i') }}</div>
                                <h4 class="timeline-title">Application Accepted</h4>
                                <div class="timeline-body">
                                    Student's application has been accepted.
                                </div>
                                <div class="timeline-meta">
                                    <span class="timeline-tag">Status Change</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($student->status == 'Enrolled')
                        <div class="timeline-item">
                            <div class="timeline-point-container">
                                <div class="timeline-point bg-success-dark">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $student->created_at->addDays(15)->format('M d, Y - H:i') }}</div>
                                <h4 class="timeline-title">Student Enrolled</h4>
                                <div class="timeline-body">
                                    Student has been officially enrolled.
                                </div>
                                <div class="timeline-meta">
                                    <span class="timeline-tag">Status Change</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($student->status == 'Rejected')
                        <div class="timeline-item">
                            <div class="timeline-point-container">
                                <div class="timeline-point bg-danger">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $student->created_at->addDays(5)->format('M d, Y - H:i') }}</div>
                                <h4 class="timeline-title">Application Rejected</h4>
                                <div class="timeline-body">
                                    Student's application has been rejected.
                                </div>
                                <div class="timeline-meta">
                                    <span class="timeline-tag">Status Change</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="timeline-item">
                            <div class="timeline-point-container">
                                <div class="timeline-point bg-secondary">
                                    <i class="fas fa-sync"></i>
                                </div>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $student->updated_at->format('M d, Y - H:i') }}</div>
                                <h4 class="timeline-title">Last Updated</h4>
                                <div class="timeline-body">
                                    Last modification to student record.
                                </div>
                                <div class="timeline-meta">
                                    <span class="timeline-tag">System</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-footer">
                        <button class="btn btn-outline-primary" type="button">
                            <i class="fas fa-history"></i> View Full Audit Log
                        </button>
                    </div>
                </div>
            </div>
                <div class="tab-pane fade" id="application" role="tabpanel">
                    <div class="container-fluid">
                        <h1 class="h3 mb-2 text-gray-800">Student Details: {{ $student->first_name }} {{ $student->last_name }}</h1>
                        
                        <div class="card shadow mb-4">
                            <div id="statusMessage" class="alert" style="display: none;"></div>
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Applications</h6>
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addApplicationModal">
                                    <i class="fas fa-plus"></i> Add Application
                                </button>
                            </div>
                            
                            <div class="card-body">
                                @if($student->applications->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Code</th>
                                                    <th>University</th>
                                                    <th>Department</th>
                                                    <th>Degree</th>
                                                    <th>Semester</th>
                                                    <th>Language</th>
                                                    <th>Created</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($student->applications as $application)
                                                <tr>
                                                    <td>{{ $application->code }}</td>
                                                    <td>{{ $application->university->name }}</td>
                                                    <td>{{ $application->department }}</td>
                                                    <td>{{ $application->degree }}</td>
                                                    <td>{{ $application->semester }}</td>
                                                    <td>{{ $application->language }}</td>
                                                  
                                                    <td>{{ $application->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        <a class="btn btn-info" href="{{ route('admin.applications.show',$application->id)}}">see</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">No applications found for this student.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add Application Modal (keep your existing modal) -->
                    
                    <!-- Notes Modal (keep your existing modal) -->
                    
                    <script>
                $(document).ready(function() {
    $('.status-select').change(function() {
        const applicationId = $(this).data('application-id');
        const newStatus = $(this).val();
        const statusMessage = $('#statusMessage');
        
        $.ajax({
            url: '/admin/applications/' + applicationId + '/status',
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            beforeSend: function() {
                statusMessage.removeClass('alert-success alert-danger')
                           .html('Updating status...')
                           .addClass('alert-info')
                           .show();
            },
            success: function(response) {
                statusMessage.removeClass('alert-info')
                           .addClass('alert-success')
                           .html('<i class="fas fa-check-circle"></i> ' + response.message)
                           .show();
                
                // Hide message after 3 seconds
                setTimeout(function() {
                    statusMessage.fadeOut();
                }, 3000);
            },
            error: function(xhr) {
                statusMessage.removeClass('alert-info')
                           .addClass('alert-danger')
                           .html('<i class="fas fa-exclamation-circle"></i> ' + 
                                (xhr.responseJSON?.message || 'Error updating status'))
                           .show();
            }
        });
    });
});
</script>
                    </script>
                </div>
         



        </div>
    </div>
</div>
<!-- Add Application Modal -->
<div class="modal fade" id="addApplicationModal" tabindex="-1" role="dialog" aria-labelledby="addApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addApplicationModalLabel">
                    <i class="fas fa-plus-circle text-primary"></i> Add New Application
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.applications.store') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="university_id">University <span class="text-danger">*</span></label>
                                <select name="university_id" id="university_id" class="form-control" required>
                                    <option value="">Select University</option>
                                    @foreach($universities as $university)
                                        <option value="{{ $university->id }}">{{ $university->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department">Department <span class="text-danger">*</span></label>
                                <select name="department" id="department" class="form-control" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                    <option value="{{$department->name}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="degree">Degree <span class="text-danger">*</span></label>
                                <select name="degree" id="degree" class="form-control" required>
                                    <option value="">Select Degree</option>
                                    @foreach($degrees as $degree)
                                    <option value="{{$degree->name}}">{{$degree->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="semester">Semester <span class="text-danger">*</span></label>
                                <select name="semester" id="semester" class="form-control" required>
                                    <option value="">Select Semester</option>
                                    <option value="Fall">Fall</option>
                                    <option value="Spring">Spring</option>
                                    <option value="Summer">Summer</option>
                                    <option value="Winter">Winter</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="language">Language <span class="text-danger">*</span></label>
                                <select name="language" id="language" class="form-control" required>
                                    <option value="">Select Language</option>
                                    <option value="English">English</option>
                                    <option value="Turkish">Turkish</option>
                                    <option value="Arabic">Arabic</option>
                                    <option value="French">French</option>
                                    <option value="German">German</option>
                                    <option value="Russian">Russian</option>
                                    <option value="Chinese">Chinese</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code">Application Code</label>
                                <input type="text" class="form-control" name="code" id="code" placeholder="Enter application code" value="{{ old('code') }}">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Footer Actions -->
<div class="profile-footer">
    <div class="container-fluid">
        <div class="footer-actions">
            <div class="action-group">
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>

            <div class="action-group">
                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
                
                @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register')

                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>

                @endif

            </div>

        </div>
    </div>
</div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">
                <i class="fas fa-exclamation-triangle text-danger"></i> Confirm Deletion
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body text-center">
            <div class="delete-icon">
                <i class="fas fa-user-slash"></i>
            </div>
            <h4 class="mt-3">Are you sure?</h4>
            <p>You are about to delete the student record for:</p>
            <div class="student-to-delete">
                <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                <span class="d-block text-muted">Passport: {{ $student->passport_id }}</span>
            </div>
            <div class="alert alert-warning mt-3">
                <i class="fas fa-info-circle"></i> This action cannot be undone
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-times"></i> Cancel
            </button>
            <form action="{{ route('admin.students.destroy', $student) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> Delete Permanently
                </button>
            </form>
        </div>
    </div>
</div>
</div>

<style>
/* Main Profile Wrapper */
.student-profile-wrapper {
background-color: #f8f9fa;
min-height: calc(100vh - 150px);
}

/* Header Styles */
.profile-header {
background: linear-gradient(135deg, #3a8dff 0%, #1e56c0 100%);
color: white;
padding: 2rem 0;
margin-bottom: 1.5rem;
position: relative;
box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.profile-header::before {
content: '';
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
opacity: 0.5;
}

.header-content {
display: flex;
justify-content: space-between;
align-items: center;
position: relative;
z-index: 1;
}

.student-intro {
display: flex;
align-items: center;
}

.student-avatar {
position: relative;
width: 100px;
height: 100px;
margin-right: 1.5rem;
}

.avatar-img {
width: 100%;
height: 100%;
object-fit: cover;
border-radius: 50%;
border: 3px solid rgba(255, 255, 255, 0.5);
}

.avatar-placeholder {
width: 100%;
height: 100%;
border-radius: 50%;
background-color: rgba(255, 255, 255, 0.2);
display: flex;
align-items: center;
justify-content: center;
font-size: 2.5rem;
font-weight: 700;
color: white;
border: 3px solid rgba(255, 255, 255, 0.5);
}

.status-indicator {
position: absolute;
bottom: 0;
right: 0;
width: 25px;
height: 25px;
border-radius: 50%;
border: 3px solid white;
}

.status-primary {
background-color: #3a8dff;
}

.status-info {
background-color: #17a2b8;
}

.status-warning {
background-color: #ffc107;
}

.status-success {
background-color: #28a745;
}

.status-danger {
background-color: #dc3545;
}

.status-secondary {
background-color: #6c757d;
}

.student-name-container {
display: flex;
flex-direction: column;
}

.student-name {
font-size: 2rem;
font-weight: 700;
margin-bottom: 0.25rem;
}

.student-status {
display: flex;
align-items: center;
flex-wrap: wrap;
}

.status-label {
display: inline-flex;
align-items: center;
padding: 0.35rem 0.75rem;
border-radius: 50px;
font-weight: 500;
font-size: 0.85rem;
}

.status-label i {
margin-right: 0.35rem;
}

.status-badge-primary {
background-color: rgba(58, 141, 255, 0.15);
color: #3a8dff;
}

.status-badge-info {
background-color: rgba(23, 162, 184, 0.15);
color: #17a2b8;
}

.status-badge-warning {
background-color: rgba(255, 193, 7, 0.15);
color: #ffc107;
}

.status-badge-success {
background-color: rgba(40, 167, 69, 0.15);
color: #28a745;
}

.status-badge-danger {
background-color: rgba(220, 53, 69, 0.15);
color: #dc3545;
}

.status-badge-secondary {
background-color: rgba(108, 117, 125, 0.15);
color: #6c757d;
}

.status-badge-info-soft {
background-color: rgba(23, 162, 184, 0.15);
color: white;
padding: 0.25rem 0.5rem;
border-radius: 4px;
font-size: 0.75rem;
display: inline-flex;
align-items: center;
}

.status-badge-warning-soft {
background-color: rgba(255, 193, 7, 0.15);
color: white;
padding: 0.25rem 0.5rem;
border-radius: 4px;
font-size: 0.75rem;
display: inline-flex;
align-items: center;
}

.status-badge-info-soft i,
.status-badge-warning-soft i {
margin-right: 0.25rem;
}

.action-buttons {
display: flex;
align-items: center;
gap: 0.5rem;
}

.btn-outline-white {
color: white;
border-color: rgba(255, 255, 255, 0.5);
background-color: transparent;
}

.btn-outline-white:hover {
background-color: rgba(255, 255, 255, 0.1);
border-color: white;
color: white;}

.btn-white {
    background-color: white;
    color: #1e56c0;
}

.btn-white:hover {
    background-color: #f8f9fa;
    color: #1e56c0;
}

.key-details {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1.5rem;
    position: relative;
    z-index: 1;
}

.key-detail-item {
    display: flex;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.detail-icon {
    margin-right: 0.75rem;
    opacity: 0.7;
}

.detail-info {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 0.7rem;
    opacity: 0.7;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.detail-value {
    font-weight: 500;
}

/* Status Update Panel */
.status-update-panel {
    background-color: white;
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.success-alert {
    display: flex;
    align-items: center;
    background-color: rgba(40, 167, 69, 0.1);
    border-left: 4px solid #28a745;
    border-radius: 4px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.success-icon {
    color: #28a745;
    font-size: 1.25rem;
    margin-right: 1rem;
}

.success-message {
    flex: 1;
    color: #155724;
}

.close-alert {
    background: none;
    border: none;
    color: #155724;
    cursor: pointer;
}

.update-form-container {
    padding-top: 0.5rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #495057;
}

.section-title i {
    margin-right: 0.5rem;
    color: #1e56c0;
}

.status-form {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1.25rem;
    border: 1px solid #e9ecef;
}

.status-select {
    font-weight: 500;
}

/* Main Content Area */
.content-tabs {
    margin-bottom: 2rem;
}

.nav-tabs {
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 1.5rem;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    position: relative;
    padding: 0.75rem 1rem;
}

.nav-tabs .nav-link i {
    margin-right: 0.35rem;
}

.nav-tabs .nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background-color: transparent;
    transition: all 0.2s ease;
}

.nav-tabs .nav-link.active {
    color: #1e56c0;
    background-color: transparent;
    border: none;
}

.nav-tabs .nav-link.active::after {
    background-color: #1e56c0;
}

.nav-tabs .nav-link:hover {
    border: none;
    color: #1e56c0;
}

.nav-tabs .nav-link:hover::after {
    background-color: rgba(30, 86, 192, 0.3);
}

/* Info Cards */
.info-cards-container {
    margin-bottom: 1.5rem;
}

.info-card {
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    height: 100%;
}

.info-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.25rem;
}

.info-card .card-header h3 {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 600;
    color: #343a40;
}

.info-card .card-header h3 i {
    margin-right: 0.5rem;
    color: #1e56c0;
}

.info-card .card-body {
    padding: 1.25rem;
}

.info-item {
    margin-bottom: 1rem;
}

.info-label {
    display: block;
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.info-value {
    font-weight: 500;
    color: #212529;
}

.info-value.highlight {
    color: #1e56c0;
    font-weight: 600;
}

.text-warning {
    color: #ffc107 !important;
}

/* Documents Tab */
.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.document-card {
    display: flex;
    align-items: center;
    background-color: white;
    border-radius: 10px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
}

.document-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.document-card.missing {
    border-left: 4px solid #ffc107;
}

.document-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #6c757d;
    margin-right: 1rem;
}

.document-details {
    flex: 1;
}

.document-details h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.document-description {
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 0.75rem;
}

.missing-document {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.missing-label {
    color: #ffc107;
    font-weight: 500;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
}

.missing-label i {
    margin-right: 0.35rem;
}

.optional-document {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.optional-label {
    color: #6c757d;
    font-weight: 500;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
}

.optional-label i {
    margin-right: 0.35rem;
}

.document-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.5rem;
}

/* Document Checklist */
.document-checklist {
    background-color: white;
    border-radius: 10px;
    padding: 1.25rem;
    margin-top: 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.checklist-title {
    font-size: 1.15rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e9ecef;
}

.checklist-items {
    margin-bottom: 1.25rem;
}

.checklist-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.check-icon {
    width: 24px;
    margin-right: 1rem;
    text-align: center;
}

.checklist-item.completed .check-icon {
    color: #28a745;
}

.checklist-item:not(.completed):not(.optional) .check-icon {
    color: #dc3545;
}

.checklist-item.optional .check-icon {
    color: #6c757d;
}

.check-label {
    flex: 1;
    font-weight: 500;
}

.check-status {
    font-size: 0.85rem;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
}

.checklist-item.completed .check-status {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.checklist-item:not(.completed):not(.optional) .check-status {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.checklist-item.optional .check-status {
    background-color: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.checklist-summary {
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.progress {
    height: 8px;
    border-radius: 4px;
    background-color: #e9ecef;
    margin-bottom: 0.75rem;
}

.checklist-status {
    display: flex;
    justify-content: center;
}

.status-text {
    font-size: 0.9rem;
    font-weight: 500;
}

/* Timeline Tab */
.timeline-container {
    padding: 1rem 0;
}

.timeline {
    position: relative;
    padding-left: 2rem;
    margin-bottom: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 9px;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-point-container {
    position: absolute;
    left: -2rem;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timeline-point {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.5rem;
}

.bg-success-dark {
    background-color: #1d923e;
}

.timeline-content {
    background-color: white;
    border-radius: 10px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.timeline-time {
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.timeline-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #343a40;
}

.timeline-body {
    color: #495057;
    margin-bottom: 0.75rem;
}

.timeline-meta {
    display: flex;
    align-items: center;
}

.timeline-tag {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    background-color: #f8f9fa;
    border-radius: 4px;
    font-size: 0.75rem;
    color: #6c757d;
}

.timeline-footer {
    text-align: center;
}

/* Footer Actions */
.profile-footer {
    background-color: white;
    border-top: 1px solid #dee2e6;
    padding: 1rem 0;
    margin-top: 2rem;
}

.footer-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.action-group {
    display: flex;
    gap: 0.5rem;
}

/* Delete Modal */
.delete-icon {
    width: 80px;
    height: 80px;
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 1rem auto;
}

.student-to-delete {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin: 1rem auto;
    max-width: 80%;
}

/* Responsive Styles */
@media (max-width: 991.98px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .action-buttons {
        margin-top: 1rem;
        align-self: flex-end;
    }
    
    .key-details {
        flex-direction: column;
    }
    
    .key-detail-item {
        width: 100%;
    }
    
    .documents-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767.98px) {
    .student-intro {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .student-avatar {
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .student-status {
        justify-content: center;
    }
    
    .action-buttons {
        align-self: center;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .footer-actions {
        flex-direction: column-reverse;
        gap: 1rem;
    }
    
    .action-group {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
$(function() {
    // Document Checklist Toggle
    $('#checklistBtn').on('click', function() {
        $('#documentChecklist').slideToggle(300);
    });
    
    // Status Select Customization
    $('.status-select option').each(function() {
        const icon = $(this).data('icon');
        $(this).data('content', `<i class="fas ${icon} mr-2"></i> ${$(this).text()}`);
    });
    
    // Tab initialization with deep linking
    const hash = window.location.hash;
    if (hash) {
        $(`#studentTabs a[href="${hash}"]`).tab('show');
    }
    
    // Update URL on tab change
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        history.replaceState(null, null, $(this).attr('href'));
    });
    
    // Enable tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-hide success alert
    setTimeout(function() {
        $('.success-alert').fadeOut(500);
    }, 5000);
});
</script>
@endsection