@extends('students.index')

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
                        <h1 class="student-name">Welcome, {{ $student->first_name }} {{ $student->last_name }}</h1>
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
                                Application Status: {{ $student->status }}
                            </span>
                            
                            @if($student->is_transfer)
                                <span class="status-badge-info-soft ml-2">
                                    <i class="fas fa-exchange-alt"></i> Transfer Student
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
           
                    <form action="{{ route('student.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-white">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
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
                        <span class="detail-value">{{ $student->applyingDegree->department ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(session('success'))
    <div class="container-fluid mt-3">
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
    </div>
    @endif
    
    <!-- Main Content Area -->
    <div class="container-fluid mt-4">
        <div class="content-tabs">
            <ul class="nav nav-tabs" id="studentTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="personal-tab" data-toggle="tab" href="#personal" role="tab">
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
                    <a class="nav-link" id="applications-tab" data-toggle="tab" href="#applications" role="tab">
                        <i class="fas fa-university"></i> Applications
                        <span class="badge badge-primary ml-1">{{ $student->applications->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="timeline-tab" data-toggle="tab" href="#timeline" role="tab">
                        <i class="fas fa-history"></i> Timeline
                    </a>
                </li>
            </ul>
            <style>

/* General text contrast fixes */
.info-card {
  background-color: var(--card-bg);
  color: var(--text-color);
  border-color: var(--border-color);
}

.card-header h3 {
  color: var(--text-color);
}

.table {
  color: var(--text-color);
}

/* Fix for status indicators in dark mode */
.status-indicator-text {
  /* Base styles for dark mode compatibility */
  font-weight: 600;
}

/* Ensure strong contrast for status colors */
.text-success {
  color: #2ecc71 !important; /* Brighter green for dark mode */
}

.text-warning {
  color: #f39c12 !important; /* Brighter yellow for dark mode */
}

.text-danger {
  color: #e74c3c !important; /* Brighter red for dark mode */
}

/* Progress bar and checklist visibility */
.progress {
  background-color: rgba(255, 255, 255, 0.1); /* Subtle background in dark mode */
}

.checklist-item {
  color: var(--text-color);
}

/* Fix for icons in dark mode */
.fas {
  color: inherit;
}

            </style>

            <script>
                // Add this to your scripts.js file
document.addEventListener('DOMContentLoaded', function() {
  // Check for saved theme preference or respect OS preference
  const savedTheme = localStorage.getItem('theme') || 
                     (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  
  // Apply saved theme
  document.documentElement.setAttribute('data-theme', savedTheme);
  
  // Setup theme toggle if you have a dark mode switch
  const themeToggle = document.getElementById('theme-toggle');
  if (themeToggle) {
    themeToggle.addEventListener('click', function() {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      
      document.documentElement.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
    });
  }
});
            </script>
            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="info-card mb-4">
                                <div class="card-header">
                                    <h3><i class="fas fa-bell"></i> Notifications & Reminders</h3>
                                </div>
                                <div class="card-body">
                                    <div class="notifications-list">
                                        @if(!$student->photo_path || !$student->passport_path || !$student->transcript_path)
                                            <div class="notification-item warning">
                                                <div class="notification-icon">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </div>
                                                <div class="notification-content">
                                                    <h4>Missing Documents</h4>
                                                    <p>Please upload all required documents to complete your application.</p>
                                                    <a href="#documents" class="btn btn-sm btn-warning" data-toggle="tab" role="tab" aria-controls="documents" aria-selected="false">
                                                        <i class="fas fa-upload"></i> Upload Documents
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($student->passport_expiry_date && $student->passport_expiry_date->lt(now()->addMonths(6)))
                                            <div class="notification-item warning">
                                                <div class="notification-icon">
                                                    <i class="fas fa-passport"></i>
                                                </div>
                                                <div class="notification-content">
                                                    <h4>Passport Expiring Soon</h4>
                                                    <p>Your passport will expire on {{ $student->passport_expiry_date }}. Please renew it as soon as possible.</p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($student->status == 'Pending Documents')
                                            <div class="notification-item info">
                                                <div class="notification-icon">
                                                    <i class="fas fa-info-circle"></i>
                                                </div>
                                                <div class="notification-content">
                                                    <h4>Documents Requested</h4>
                                                    <p>Please upload the requested documents to proceed with your application.</p>
                                                    <a href="#documents" class="btn btn-sm btn-info" data-toggle="tab" role="tab" aria-controls="documents" aria-selected="false">
                                                        <i class="fas fa-upload"></i> View Requirements
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($student->status == 'Accepted')
                                            <div class="notification-item success">
                                                <div class="notification-icon">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                                <div class="notification-content">
                                                    <h4>Application Accepted</h4>
                                                    <p>Congratulations! Your application has been accepted. Please check your email for further instructions.</p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($student->applications->where('status', 'Awaiting Payment')->count() > 0)
                                            <div class="notification-item primary">
                                                <div class="notification-icon">
                                                    <i class="fas fa-credit-card"></i>
                                                </div>
                                                <div class="notification-content">
                                                    <h4>Payment Required</h4>
                                                    <p>One or more of your applications require payment to proceed.</p>
                                                    <a href="#applications" class="btn btn-sm btn-primary" data-toggle="tab" role="tab" aria-controls="applications" aria-selected="false">
                                                        <i class="fas fa-university"></i> View Applications
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($student->applications->count() == 0)
                                            <div class="notification-item info">
                                                <div class="notification-icon">
                                                    <i class="fas fa-university"></i>
                                                </div>
                                                <div class="notification-content">
                                                    <h4>Apply to Universities</h4>
                                                    <p>You haven't applied to any universities yet. Start your application process now.</p>
                                                    <a href="#applications" class="btn btn-sm btn-info" data-toggle="tab" role="tab" aria-controls="applications" aria-selected="false">
                                                        <i class="fas fa-plus-circle"></i> Add Application
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Application Status Timeline -->
                            <div class="info-card mb-4">
                                <div class="card-header">
                                    <h3><i class="fas fa-chart-line"></i> Application Progress</h3>
                                </div>
                                <div class="card-body">
                                    <div class="application-progress">
                                        <ul class="progress-steps">
                                            <li class="progress-step {{ in_array($student->status, ['New', 'In Review', 'Pending Documents', 'Accepted', 'Enrolled']) ? 'completed' : '' }}">
                                                <div class="step-icon">
                                                    <i class="fas fa-plus-circle"></i>
                                                </div>
                                                <div class="step-label">Application Submitted</div>
                                            </li>
                                            <li class="progress-step {{ in_array($student->status, ['In Review', 'Pending Documents', 'Accepted', 'Enrolled']) ? 'completed' : '' }}">
                                                <div class="step-icon">
                                                    <i class="fas fa-search"></i>
                                                </div>
                                                <div class="step-label">Under Review</div>
                                            </li>
                                            <li class="progress-step {{ in_array($student->status, ['Pending Documents', 'Accepted', 'Enrolled']) ? 'completed' : '' }}">
                                                <div class="step-icon">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <div class="step-label">Documents Verified</div>
                                            </li>
                                            <li class="progress-step {{ in_array($student->status, ['Accepted', 'Enrolled']) ? 'completed' : '' }}">
                                                <div class="step-icon">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                                <div class="step-label">Application Accepted</div>
                                            </li>
                                            <li class="progress-step {{ in_array($student->status, ['Enrolled']) ? 'completed' : '' }}">
                                                <div class="step-icon">
                                                    <i class="fas fa-user-graduate"></i>
                                                </div>
                                                <div class="step-label">Enrolled</div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Latest Applications -->
                            @if($student->applications->count() > 0)
                            <div class="info-card">
                                <div class="card-header">
                                    <h3><i class="fas fa-university"></i> Recent Applications</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>University</th>
                                                    <th>Program</th>
                                                    <th>Status</th>
                                                    <th>Updated</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($student->applications->take(3) as $application)
                                                <tr>
                                                    <td>{{ $application->university->name }}</td>
                                                    <td>{{ $application->department }} ({{ $application->degree }})</td>
                                                    <td>
                                                        <span class="status-indicator-text
                                                            {{ in_array($application->status, ['Paid', 'Final Acceptance', 'Completed']) ? 'text-success' : '' }}
                                                            {{ in_array($application->status, ['Pending Review', 'Awaiting App Fees Payment', 'Awaiting Payment']) ? 'text-warning' : '' }}
                                                            {{ in_array($application->status, ['Refused', 'Cancelled', 'Invalid']) ? 'text-danger' : '' }}">
                                                            {{ $application->status }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $application->updated_at->diffForHumans() }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="#applications" class="btn btn-outline-primary" data-toggle="tab" role="tab" aria-controls="applications" aria-selected="false">
                                            <i class="fas fa-list"></i> View All Applications
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-lg-4">
                            <!-- Document Checklist Card -->
                            <div class="info-card mb-4">
                                <div class="card-header">
                                    <h3><i class="fas fa-tasks"></i> Document Checklist</h3>
                                </div>
                                <div class="card-body">
                                    <div class="checklist-items">
                                        <div class="checklist-item {{ $student->photo_path ? 'completed' : '' }}">
                                            <span class="check-icon">
                                                @if($student->photo_path)
                                                    <i class="fas fa-check-circle"></i>
                                                @else
                                                    <i class="fas fa-times-circle"></i>
                                                @endif
                                            </span>
                                            <span class="check-label">Photo</span>
                                            <span class="check-status">{{ $student->photo_path ? 'Submitted' : 'Missing' }}</span>
                                        </div>
                                        
                                        <div class="checklist-item {{ $student->passport_path ? 'completed' : '' }}">
                                            <span class="check-icon">
                                                @if($student->passport_path)
                                                    <i class="fas fa-check-circle"></i>
                                                @else
                                                    <i class="fas fa-times-circle"></i>
                                                @endif
                                            </span>
                                            <span class="check-label">Passport</span>
                                            <span class="check-status">{{ $student->passport_path ? 'Submitted' : 'Missing' }}</span>
                                        </div>
                                        
                                        <div class="checklist-item {{ $student->transcript_path ? 'completed' : '' }}">
                                            <span class="check-icon">
                                                @if($student->transcript_path)
                                                    <i class="fas fa-check-circle"></i>
                                                @else
                                                    <i class="fas fa-times-circle"></i>
                                                @endif
                                            </span>
                                            <span class="check-label">Transcript</span>
                                            <span class="check-status">{{ $student->transcript_path ? 'Submitted' : 'Missing' }}</span>
                                        </div>
                                        
                                        <div class="checklist-item {{ $student->diploma_path ? 'completed' : 'optional' }}">
                                            <span class="check-icon">
                                                @if($student->diploma_path)
                                                    <i class="fas fa-check-circle"></i>
                                                @else
                                                    <i class="fas fa-circle-notch"></i>
                                                @endif
                                            </span>
                                            <span class="check-label">Diploma</span>
                                            <span class="check-status">{{ $student->diploma_path ? 'Submitted' : 'Optional' }}</span>
                                        </div>
                                        
                                        <div class="checklist-item {{ $student->denklik_path ? 'completed' : 'optional' }}">
                                            <span class="check-icon">
                                                @if($student->denklik_path)
                                                    <i class="fas fa-check-circle"></i>
                                                @else
                                                    <i class="fas fa-circle-notch"></i>
                                                @endif
                                            </span>
                                            <span class="check-label">Denklik</span>
                                            <span class="check-status">{{ $student->denklik_path ? 'Submitted' : 'Optional' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="checklist-summary">
                                        <div class="progress">
                                            @php
                                                $requiredDocs = 3; // Photo, Passport, Transcript
                                                $submittedRequired = 0;
                                                if($student->photo_path) $submittedRequired++;
                                                if($student->passport_path)$submittedRequired++;
                                                if($student->transcript_path) $submittedRequired++;
                                                
                                                $percentage = $requiredDocs > 0 ? ($submittedRequired / $requiredDocs) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar {{ $percentage == 100 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                                role="progressbar" style="width: {{ $percentage }}%" 
                                                aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($percentage) }}%
                                            </div>
                                        </div>
                                        <div class="checklist-status">
                                            <span class="status-text">
                                                @if($percentage == 100)
                                                    <i class="fas fa-check-circle text-success"></i> All required documents submitted
                                                @else
                                                    <i class="fas fa-exclamation-circle text-warning"></i> Missing {{ $requiredDocs - $submittedRequired }} of {{ $requiredDocs }} required documents
                                                @endif
                                            </span>
                                        </div>
                                        
                                        @if($percentage < 100)
                                        <div class="text-center mt-3">
                                            <a href="#documents" class="btn btn-sm btn-warning" data-toggle="tab" role="tab" aria-controls="documents" aria-selected="false">
                                                <i class="fas fa-upload"></i> Upload Missing Documents
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Information Card -->
                            <div class="info-card mb-4">
                                <div class="card-header">
                                    <h3><i class="fas fa-phone-alt"></i> Need Help?</h3>
                                </div>
                                <div class="card-body">
                                    <div class="contact-info">
                                        <div class="contact-item">
                                            <div class="contact-icon">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div class="contact-details">
                                                <h4>Your Advisor</h4>
                                                <p>{{ $student->responsibleEmployee->name ?? 'Not assigned yet' }}</p>
                                                @if($student->responsibleEmployee)
                                                <a href="mailto:{{ $student->responsibleEmployee->email }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-envelope"></i> Email
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="contact-item">
                                            <div class="contact-icon">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <div class="contact-details">
                                                <h4>Support Email</h4>
                                                <p>support@example.com</p>
                                            </div>
                                        </div>
                                        
                                        <div class="contact-item">
                                            <div class="contact-icon">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <div class="contact-details">
                                                <h4>Phone Support</h4>
                                                <p>+1 (555) 123-4567</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Next Steps Card -->
                            <div class="info-card">
                                <div class="card-header">
                                    <h3><i class="fas fa-tasks"></i> Next Steps</h3>
                                </div>
                                <div class="card-body">
                                    <ul class="next-steps-list">
                                        @if(!$student->photo_path || !$student->passport_path || !$student->transcript_path)
                                        <li class="next-step-item">
                                            <div class="step-number">1</div>
                                            <div class="step-content">
                                                <h4>Complete Your Documents</h4>
                                                <p>Upload all required documents for your application.</p>
                                            </div>
                                        </li>
                                        @endif
                                        
                                        @if($student->applications->count() == 0)
                                        <li class="next-step-item">
                                            <div class="step-number">{{ (!$student->photo_path || !$student->passport_path || !$student->transcript_path) ? '2' : '1' }}</div>
                                            <div class="step-content">
                                                <h4>Apply to Universities</h4>
                                                <p>Start your application to your preferred universities.</p>
                                            </div>
                                        </li>
                                        @endif
                                        
                                        @if($student->applications->where('status', 'Awaiting Payment')->count() > 0)
                                        <li class="next-step-item">
                                            <div class="step-number">{{ (!$student->photo_path || !$student->passport_path || !$student->transcript_path) ? '2' : '1' }}</div>
                                            <div class="step-content">
                                                <h4>Complete Payment</h4>
                                                <p>Pay the required fees to proceed with your application.</p>
                                            </div>
                                        </li>
                                        @endif
                                        
                                        @if($student->status == 'Accepted')
                                        <li class="next-step-item">
                                            <div class="step-number">1</div>
                                            <div class="step-content">
                                                <h4>Enrollment Process</h4>
                                                <p>Complete the enrollment process to finalize your admission.</p>
                                            </div>
                                        </li>
                                        @endif
                                        
                                        @if($student->status == 'Enrolled')
                                        <li class="next-step-item">
                                            <div class="step-number">1</div>
                                            <div class="step-content">
                                                <h4>Ready to Start</h4>
                                                <p>You're all set! Prepare for your academic journey.</p>
                                            </div>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Personal Information Tab -->
                <div class="tab-pane fade" id="personal" role="tabpanel">
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
                                                    <span class="info-value">{{ $student->date_of_birth }}</span>
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
                                        
                                        <div class="edit-button-container text-right mt-3">
                                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editContactInfoModal">
                                                <i class="fas fa-edit"></i> Edit Contact Info
                                            </button>
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
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Issue Date</span>
                                                    <span class="info-value">{{ $student->passport_issue_date }}</span>
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
                                        
                                        <div class="edit-button-container text-right mt-3">
                                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editEmergencyContactModal">
                                                <i class="fas fa-edit"></i> Edit Emergency Contacts
                                            </button>
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
                                                    <span class="info-value">{{ $student->application_date }}</span>
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
                                                    <span class="info-label">Advisor</span>
                                                    <span class="info-value">{{ $student->responsibleEmployee->name ?? 'Not assigned yet' }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Current Status</span>
                                                    <span class="info-value">
                                                        <span class="badge 
                                                            @if($student->status == 'New') badge-primary
                                                            @elseif($student->status == 'In Review') badge-info
                                                            @elseif($student->status == 'Pending Documents') badge-warning
                                                            @elseif($student->status == 'Accepted') badge-success
                                                            @elseif($student->status == 'Rejected') badge-danger
                                                            @elseif($student->status == 'Enrolled') badge-success
                                                            @elseif($student->status == 'Cancelled') badge-secondary
                                                            @endif">
                                                            {{ $student->status }}
                                                        </span>
                                                    </span>
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
                    
                    <!-- Edit Contact Information Modal -->
                    <div class="modal fade" id="editContactInfoModal" tabindex="-1" role="dialog" aria-labelledby="editContactInfoModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editContactInfoModalLabel">Edit Contact Information</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('student.update.profile') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="email">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $student->email }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone_number">Phone Number</label>
                                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $student->phone_number }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Edit Emergency Contact Modal -->
                    <div class="modal fade" id="editEmergencyContactModal" tabindex="-1" role="dialog" aria-labelledby="editEmergencyContactModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editEmergencyContactModalLabel">Edit Emergency Contacts</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('student.update.profile') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="emergency_email">Emergency Email</label>
                                            <input type="email" class="form-control" id="emergency_email" name="emergency_email" value="{{ $student->emergency_email }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="emergency_phone">Emergency Phone</label>
                                            <input type="text" class="form-control" id="emergency_phone" name="emergency_phone" value="{{ $student->emergency_phone }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
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
                                                    <span class="info-value">{{ $student->applyingDegree->department ?? 'N/A' }}</span>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <span class="info-label">Degree</span>
                                                    <span class="info-value">{{ $student->applyingDegree->degree ?? 'N/A' }}</span>
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
                
                <!-- Documents Tab -->
                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="info-card">
                               
                            </div>
                        </div>
                    </div>
                    
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
                                    <a href="{{ Storage::url($student->photo_path) }}" class="btn btn-outline-info" target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                @else
                                    <div class="missing-document">
                                        <span class="missing-label"><i class="fas fa-exclamation-triangle"></i> Missing</span>
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
                                    <a href="{{ Storage::url($student->passport_path) }}" class="btn btn-outline-info" target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                @else
                                    <div class="missing-document">
                                        <span class="missing-label"><i class="fas fa-exclamation-triangle"></i> Missing</span>
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
                                    <a href="{{ Storage::url($student->transcript_path) }}" class="btn btn-outline-info" target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                @else
                                    <div class="missing-document">
                                        <span class="missing-label"><i class="fas fa-exclamation-triangle"></i> Missing</span>
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
                                    <a href="{{ Storage::url($student->diploma_path) }}" class="btn btn-outline-info" target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                @else
                                    <div class="optional-document">
                                        <span class="optional-label"><i class="fas fa-info-circle"></i> Optional</span>
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
                                    <a href="{{ Storage::url($student->denklik_path) }}" class="btn btn-outline-info" target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                @else
                                    <div class="optional-document">
                                        <span class="optional-label"><i class="fas fa-info-circle"></i> Optional</span>
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
                                    <a href="{{ Storage::url($student->certificate_path) }}" class="btn btn-outline-info" target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                @else
                                    <div class="optional-document">
                                        <span class="optional-label"><i class="fas fa-info-circle"></i> Optional</span>
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
                                    <a href="{{ Storage::url($student->other_documents_path) }}" class="btn btn-outline-info" target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                @else
                                    <div class="optional-document">
                                        <span class="optional-label"><i class="fas fa-info-circle"></i> Optional</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="document-checklist">
                        <h3 class="checklist-title">Document Checklist</h3>
                        <div class="checklist-items">
                            <div class="checklist-item {{ $student->photo_path ? 'completed' : '' }}">
                                <span class="check-icon">
                                    @if($student->photo_path)
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="fas fa-times-circle"></i>
                                    @endif
                                </span>
                                <span class="check-label">Photo</span>
                                <span class="check-status">{{ $student->photo_path ? 'Submitted' : 'Missing' }}</span>
                            </div>
                            
                            <div class="checklist-item {{ $student->passport_path ? 'completed' : '' }}">
                                <span class="check-icon">
                                    @if($student->passport_path)
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="fas fa-times-circle"></i>
                                    @endif
                                </span>
                                <span class="check-label">Passport</span>
                                <span class="check-status">{{ $student->passport_path ? 'Submitted' : 'Missing' }}</span>
                            </div>
                            
                            <div class="checklist-item {{ $student->transcript_path ? 'completed' : '' }}">
                                <span class="check-icon">
                                    @if($student->transcript_path)
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="fas fa-times-circle"></i>
                                    @endif
                                </span>
                                <span class="check-label">Transcript</span>
                                <span class="check-status">{{ $student->transcript_path ? 'Submitted' : 'Missing' }}</span>
                            </div>
                            
                            <div class="checklist-item {{ $student->diploma_path ? 'completed' : 'optional' }}">
                                <span class="check-icon">
                                    @if($student->diploma_path)
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="fas fa-circle-notch"></i>
                                    @endif
                                </span>
                                <span class="check-label">Diploma</span>
                                <span class="check-status">{{ $student->diploma_path ? 'Submitted' : 'Optional' }}</span>
                            </div>
                            
                            <div class="checklist-item {{ $student->denklik_path ? 'completed' : 'optional' }}">
                                <span class="check-icon">
                                    @if($student->denklik_path)
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="fas fa-circle-notch"></i>
                                    @endif
                                </span>
                                <span class="check-label">Denklik</span>
                                <span class="check-status">{{ $student->denklik_path ? 'Submitted' : 'Optional' }}</span>
                            </div>
                        </div>
                        
                        <div class="checklist-summary">
                            <div class="progress">
                                @php
                                    $requiredDocs = 3; // Photo, Passport, Transcript
                                    $submittedRequired = 0;
                                    if($student->photo_path) $submittedRequired++;
                                    if($student->passport_path) $submittedRequired++;
                                    if($student->transcript_path) $submittedRequired++;
                                    
                                    $percentage = $requiredDocs > 0 ? ($submittedRequired / $requiredDocs) * 100 : 0;
                                @endphp
                                <div class="progress-bar {{ $percentage == 100 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                     role="progressbar" style="width: {{ $percentage }}%" 
                                     aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ round($percentage) }}%
                                </div>
                            </div>
                            <div class="checklist-status">
                                <span class="status-text">
                                    @if($percentage == 100)
                                        <i class="fas fa-check-circle text-success"></i> All required documents submitted
                                    @else
                                        <i class="fas fa-exclamation-circle text-warning"></i> Missing {{ $requiredDocs - $submittedRequired }} of {{ $requiredDocs }} required documents
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Applications Tab -->
                <div class="tab-pane fade" id="applications" role="tabpanel">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="info-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3><i class="fas fa-university"></i> My Applications</h3>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addApplicationModal">
                                        <i class="fas fa-plus-circle"></i> Add New Application
                                    </button>
                                </div>
                                <div class="card-body">
                                    @if($student->applications->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>University</th>
                                                        <th>Department</th>
                                                        <th>Degree</th>
                                                        <th>Semester</th>
                                                        <th>Language</th>
                                                        <th>Status</th>
                                                        <th>Created</th>
                                                        <th>Notes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($student->applications as $application)
                                                    <tr>
                                                        <td>{{ $application->university->name }}</td>
                                                        <td>{{ $application->department }}</td>
                                                        <td>{{ $application->degree }}</td>
                                                        <td>{{ $application->semester }}</td>
                                                        <td>{{ $application->language }}</td>
                                                        <td>
                                                            <span class="badge 
                                                                @if(in_array($application->status, ['Final Acceptance', 'Completed', 'Paid'])) badge-success
                                                                @elseif(in_array($application->status, ['Rejected', 'Cancelled', 'Refused'])) badge-danger
                                                                @elseif(in_array($application->status, ['Pending Review', 'Awaiting Payment'])) badge-warning
                                                                @else badge-info
                                                                @endif px-2 py-1">
                                                                {{ $application->status }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $application->created_at }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info view-notes" 
                                                                    data-notes="{{ $application->notes }}"
                                                                    data-toggle="modal" 
                                                                    data-target="#notesModal">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> You haven't applied to any universities yet. Start your application process by clicking the "Add New Application" button.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="info-card">
                                <div class="card-header">
                                    <h3><i class="fas fa-lightbulb"></i> Application Tips</h3>
                                </div>
                                <div class="card-body">
                                    <div class="application-tips">
                                        <div class="tip-item">
                                            <div class="tip-icon">
                                                <i class="fas fa-check-double"></i>
                                            </div>
                                            <div class="tip-content">
                                                <h4>Complete Your Documents</h4>
                                                <p>Make sure all your required documents are uploaded before applying to universities. This will speed up the processing of your applications.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="tip-item">
                                            <div class="tip-icon">
                                                <i class="fas fa-search"></i>
                                            </div>
                                            <div class="tip-content">
                                                <h4>Research Universities</h4>
                                                <p>Take time to research universities and programs that match your academic goals and preferences.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="tip-item">
                                            <div class="tip-icon">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <div class="tip-content">
                                                <h4>Consider Application Deadlines</h4>
                                                <p>Be mindful of application deadlines for different universities and programs to ensure your application is considered.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="tip-item">
                                            <div class="tip-icon">
                                                <i class="fas fa-user-graduate"></i>
                                            </div>
                                            <div class="tip-content">
                                                <h4>Consult Your Advisor</h4>
                                                <p>Don't hesitate to contact your advisor for guidance on choosing the right universities and programs.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add Application Modal -->
                    <div class="modal fade" id="addApplicationModal" tabindex="-1" role="dialog" aria-labelledby="addApplicationModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addApplicationModalLabel">Add New Application</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('student.applications.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="university_id">University</label>
                                                    <select class="form-control" id="university_id" name="university_id" required>
                                                        <option value="">Select University</option>
                                                        @foreach($universities as $university)
                                                            <option value="{{ $university->id }}">{{ $university->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="department">Department</label>
                                                    <select class="form-control" id="department" name="department" required>
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department }}">{{ $department }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="degree">Degree</label>
                                                    <select class="form-control" id="degree" name="degree" required>
                                                        <option value="">Select Degree</option>
                                                        @foreach($degrees as $degree)
                                                            <option value="{{ $degree }}">{{ $degree }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="semester">Semester</label>
                                                    <select class="form-control" id="semester" name="semester" required>
                                                        <option value="">Select Semester</option>
                                                        @foreach($semesters as $semester)
                                                            <option value="{{ $semester }}">{{ $semester }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="language">Language</label>
                                                    <select class="form-control" id="language" name="language" required>
                                                        <option value="">Select Language</option>
                                                        @foreach($languages as $language)
                                                            <option value="{{ $language }}">{{ $language }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="notes">Notes</label>
                                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional information you'd like to include with your application"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Submit Application</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes Modal -->
                    <div class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="notesModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="notesModalLabel">Application Notes</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="notesContent" class="p-3"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
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
                                    <div class="timeline-time">{{ $student->created_at }}</div>
                                    <h4 class="timeline-title">Application Created</h4>
                                    <div class="timeline-body">
                                        Your application was registered in our system.
                                    </div>
                                    <div class="timeline-meta">
                                        <span class="timeline-tag">Registration</span>
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
                                    <div class="timeline-time">{{ $student->created_at->addMinutes(15) }}</div>
                                    <h4 class="timeline-title">Advisor Assigned</h4>
                                    <div class="timeline-body">
                                        @if($student->responsibleEmployee)
                                            {{ $student->responsibleEmployee->name }} was assigned as your advisor.
                                        @else
                                            An advisor was assigned to your application.
                                        @endif
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
                                    <div class="timeline-time">{{ $student->created_at->addHours(2) }}</div>
                                    <h4 class="timeline-title">Application Review Started</h4>
                                    <div class="timeline-body">
                                        Your application status was updated to "In Review".
                                    </div>
                                    <div class="timeline-meta"><span class="timeline-tag">Status Change</span>
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
                                    <div class="timeline-time">{{ $student->created_at->addDays(1) }}</div>
                                    <h4 class="timeline-title">Documents Requested</h4>
                                    <div class="timeline-body">
                                        Additional documents were requested for your application.
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
                                    <div class="timeline-time">{{ $student->created_at->addDays(5) }}</div>
                                    <h4 class="timeline-title">Application Accepted</h4>
                                    <div class="timeline-body">
                                        Congratulations! Your application has been accepted.
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
                                    <div class="timeline-time">{{ $student->created_at->addDays(15) }}</div>
                                    <h4 class="timeline-title">Enrolled</h4>
                                    <div class="timeline-body">
                                        You have been officially enrolled in your program.
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
                                    <div class="timeline-time">{{ $student->created_at->addDays(5)}}</div>
                                    <h4 class="timeline-title">Application Rejected</h4>
                                    <div class="timeline-body">
                                        Unfortunately, your application has been rejected.
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
                                    <div class="timeline-time">{{ $student->updated_at }}</div>
                                    <h4 class="timeline-title">Last Updated</h4>
                                    <div class="timeline-body">
                                        Last update to your application.
                                    </div>
                                    <div class="timeline-meta">
                                        <span class="timeline-tag">System</span>
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
