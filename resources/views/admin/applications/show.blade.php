@extends('admin.index')

@section('content')

<div class="container-fluid">
    <!-- Application Header with Quick Actions -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                Application #{{ $application->id }}
            </h1>
            <p class="mb-0">
                @php
                    $statusClass = '';
                    if(in_array($application->status, ['Completed', 'Paid', 'Final Acceptance', 'Free Scholarship', '100% Scholarship'])) {
                        $statusClass = 'success';
                    } elseif(in_array($application->status, ['Pending Review', 'Awaiting App Fees Payment', 'Awaiting Student', 'Awaiting Payment'])) {
                        $statusClass = 'warning';
                    } elseif(in_array($application->status, ['Refused', 'Cancelled', 'Quota Full', 'Refund Request (Visa Rejected)', 'Student Duplicated'])) {
                        $statusClass = 'danger';
                    } else {
                        $statusClass = 'info';
                    }
                @endphp
                <span class="badge badge-{{ $statusClass }} px-3 py-2">
                    <i class="fas fa-certificate mr-1"></i> {{ $application->status }}
                </span>
                <span class="text-muted ml-2">Created {{ $application->created_at->diffForHumans() }}</span>
            </p>
        </div>
        <div class="d-flex">
            <a href="{{ route('admin.applications.edit', $application) }}" class="btn btn-primary btn-icon-split mr-2">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Edit</span>
            </a>
            <div class="dropdown mr-2">
          
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="statusDropdown">
                    <h6 class="dropdown-header">Select New Status:</h6>
                    @foreach(App\Models\Application::STATUSES as $statusKey => $statusValue)
                        @if($statusValue != $application->status)
                            <a class="dropdown-item status-change" href="javascript:void(0);" data-status="{{ $statusValue }}">
                                <span class="badge badge-{{ ($statusValue) }} mr-2">
                                    <i class="fas fa-circle mr-1 fa-sm"></i>
                                </span>
                                {{ $statusValue }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-secondary btn-icon-split dropdown-toggle" type="button" id="actionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="icon text-white-50">
                        <i class="fas fa-cog"></i>
                    </span>
                    <span class="text">Actions</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="actionsDropdown">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#historyModal">
                        <i class="fas fa-history fa-sm fa-fw mr-2 text-gray-400"></i>
                        View Status History
                    </a>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#emailModal">
                        <i class="fas fa-envelope fa-sm fa-fw mr-2 text-gray-400"></i>
                        Email Student
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.applications.export-pdf', $application) }}" target="_blank">
                        <i class="fas fa-file-pdf fa-sm fa-fw mr-2 text-gray-400"></i>
                        Download as PDF
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash fa-sm fa-fw mr-2 text-danger"></i>
                        Delete Application
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(count($missingFiles) > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <div class="mr-3">
                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
            </div>
            <div>
                <h4 class="alert-heading mb-1">Required files missing!</h4>
                <p class="mb-0">This application is missing {{ count($missingFiles) }} required document(s). You can upload them below or by using the "Upload Missing Files" button.</p>
            </div>
        </div>
        <button class="btn btn-warning btn-sm mt-2" type="button" data-toggle="modal" data-target="#uploadMissingFilesModal">
            <i class="fas fa-upload"></i> Upload Missing Files
        </button>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Main Content Row -->
    <div class="row">
        <!-- Application Details Column -->
        <div class="col-xl-8 col-lg-7">
            <!-- Application Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-1"></i> Application Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="small font-weight-bold text-uppercase text-muted">Student</h5>
                                <div class="d-flex align-items-center">
                                    @if($application->student && $application->student->photo_path)
                                        <img src="{{ asset('storage/'.$application->student->photo_path) }}" alt="{{ $application->student->first_name }}" class="img-profile rounded-circle mr-2" width="60">
                                    @else
                                        <div class="bg-primary text-white rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            {{ strtoupper(substr($application->student->first_name ?? '', 0, 1) . substr($application->student->last_name ?? '', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="mb-0">{{ $application->student->first_name ?? 'N/A' }} {{ $application->student->last_name ?? '' }}</h4>
                                        <p class="mb-0">
                                            <i class="fas fa-envelope fa-sm mr-1 text-gray-400"></i> {{ $application->student->email ?? 'N/A' }}<br>
                                            <i class="fas fa-phone fa-sm mr-1 text-gray-400"></i> {{ $application->student->phone ?? 'Not provided' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="small font-weight-bold text-uppercase text-muted">University</h5>
                                <div class="d-flex align-items-center">
                                    @if($application->university && $application->university->logo)
                                        <img src="{{ asset('storage/'.$application->university->logo) }}" alt="{{ $application->university->name }}" class="mr-2" width="60">
                                    @else
                                        <div class="bg-info text-white rounded mr-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-university fa-2x"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="mb-0">{{ $application->university->name ?? 'N/A' }}</h4>
                                        <p class="mb-0">
                                            <i class="fas fa-map-marker-alt fa-sm mr-1 text-gray-400"></i> 
                                            {{ $application->university->city->name ?? 'N/A' }}, {{ $application->university->country->name ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <h5 class="small font-weight-bold text-uppercase text-muted">Program Details</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="bg-light" width="40%">Department</th>
                                            <td>{{ $application->department }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Degree</th>
                                            <td>{{ $application->degree }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Language</th>
                                            <td>{{ $application->language }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Semester</th>
                                            <td>{{ $application->semester }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5 class="small font-weight-bold text-uppercase text-muted">Notes</h5>
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($application->notes)) ?: '<em class="text-muted">No notes provided</em>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Official Documents Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-contract mr-1"></i> Required Official Documents
                    </h6>
               
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- First Acceptance Letter -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 {{ $firstAcceptance ? 'border-success' : 'border-warning' }}">
                                <div class="card-header py-2 d-flex justify-content-between align-items-center {{ $firstAcceptance ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas {{ $firstAcceptance ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-1"></i>
                                        First Acceptance Letter
                                    </h6>
                                    @if($firstAcceptance)
                                    <span class="badge badge-light">Uploaded by 
                                         {{ $firstAcceptance->uploader ? $firstAcceptance->uploader->name : 'Unknown' }}</span>
                                    @else
                                    <span class="badge badge-light text-warning">Missing</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    @if($firstAcceptance)
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <i class="fas {{  ($firstAcceptance->mime_type ?? 'application/pdf') }} fa-2x mr-2 text-primary"></i>
                                            </div>
                                            <div class="text-right">
                                                <small class="text-muted d-block">Uploaded {{ $firstAcceptance->created_at->format('M d, Y') }}</small>
                                                <small class="text-muted d-block">{{   ($firstAcceptance->file_size ?? 0) }}</small>
                                            </div>
                                        </div>
                                        <h5 class="mb-1 text-truncate">{{ $firstAcceptance->original_filename }}</h5>
                                        <div class="btn-group btn-block mt-3">
                                            <a href="{{ route('admin.applications.files.download', ['application' => $application->id, 'file' => $firstAcceptance->id]) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                       
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="fas fa-file-upload fa-3x text-warning mb-3"></i>
                                            <p class="mb-2">No first acceptance letter uploaded yet.</p>
            
                                            @if(auth()->user()->role =="Register")
                                            <form action="{{ route('admin.applications.upload-file', $application) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                                @csrf
                                                <input type="hidden" name="file_type" value="first_acceptance">
                                                <div class="custom-file mb-2">
                                                    <input type="file" class="custom-file-input" id="first_acceptance" name="file">
                                                    <label class="custom-file-label text-left" for="first_acceptance">Choose file</label>
                                                </div>
                                                <button type="submit" class="btn btn-warning btn-block">
                                                    <i class="fas fa-upload mr-1"></i> Upload Now
                                                </button>
                                            </form>
                                            @else 
                                            <b>Only Register Upload this</b>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment File -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 {{ $paymentFile ? 'border-success' : ($firstAcceptance ? 'border-warning' : 'border-secondary') }}">
                                <div class="card-header py-2 d-flex justify-content-between align-items-center {{ $paymentFile ? 'bg-success text-white' : ($firstAcceptance ? 'bg-warning text-dark' : 'bg-secondary text-white') }}">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas {{ $paymentFile ? 'fa-check-circle' : ($firstAcceptance ? 'fa-exclamation-circle' : 'fa-lock') }} mr-1"></i>
                                        Payment Confirmation
                                    </h6>
                                    @if($paymentFile)
                                    <span class="badge badge-light">Uploaded by 
                                         {{ $paymentFile->uploader ? $paymentFile->uploader->name : 'Unknown' }}</span>
                                    @else
                                    <span class="badge badge-light {{ $firstAcceptance ? 'text-warning' : 'text-secondary' }}">
                                        {{ $firstAcceptance ? 'Missing' : 'Locked' }}
                                    </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    @if($paymentFile)
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <i class="fas {{  ($paymentFile->mime_type ?? 'application/pdf') }} fa-2x mr-2 text-primary"></i>
                                            </div>
                                            <div class="text-right">
                                                <small class="text-muted d-block">Uploaded {{ $paymentFile->created_at->format('M d, Y') }}</small>
                                                <small class="text-muted d-block">{{   ($paymentFile->file_size ?? 0) }}</small>
                                            </div>
                                        </div>
                                        <h5 class="mb-1 text-truncate">{{ $paymentFile->original_filename }}</h5>
                                        <div class="btn-group btn-block mt-3">
                                            <a href="" class="btn btn-sm btn-primary">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                     
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="fas fa-file-invoice-dollar fa-3x {{ $firstAcceptance ? 'text-warning' : 'text-secondary' }} mb-3"></i>
                                            <p class="mb-2">
                                                @if($firstAcceptance)
                                                    No payment confirmation uploaded yet.
                                                @else
                                                    Upload First Acceptance Letter to unlock
                                                @endif
                                            </p>
                                            @if($firstAcceptance)
                                            <form action="{{ route('admin.applications.upload-file', $application) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                                @csrf
                                                <input type="hidden" name="file_type" value="payment_file">
                                                <div class="custom-file mb-2">
                                                    <input type="file" class="custom-file-input" id="payment_file" name="file">
                                                    <label class="custom-file-label text-left" for="payment_file">Choose file</label>
                                                </div>
                                                <button type="submit" class="btn btn-warning btn-block">
                                                    <i class="fas fa-upload mr-1"></i> Upload Now
                                                </button>
                                            </form>
                                            @else
                                            <button class="btn btn-secondary btn-block" disabled>
                                                <i class="fas fa-lock mr-1"></i> Locked
                                            </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Final Acceptance Letter -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 {{ $finalAcceptance ? 'border-success' : ($paymentFile ? 'border-warning' : 'border-secondary') }}">
                                <div class="card-header py-2 d-flex justify-content-between align-items-center {{ $finalAcceptance ? 'bg-success text-white' : ($paymentFile ? 'bg-warning text-dark' : 'bg-secondary text-white') }}">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas {{ $finalAcceptance ? 'fa-check-circle' : ($paymentFile ? 'fa-exclamation-circle' : 'fa-lock') }} mr-1"></i>
                                        Final Acceptance Letter
                                    </h6>
                                    @if($finalAcceptance)
                                    <span class="badge badge-light">Uploaded by 
                                         {{ $finalAcceptance->uploader ? $finalAcceptance->uploader->name : 'Unknown' }}</span>
                                    @else
                                    <span class="badge badge-light {{ $paymentFile ? 'text-warning' : 'text-secondary' }}">
                                        {{ $paymentFile ? 'Missing' : 'Locked' }}
                                    </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    @if($finalAcceptance)
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <i class="fas {{  ($finalAcceptance->mime_type ?? 'application/pdf') }} fa-2x mr-2 text-primary"></i>
                                            </div>
                                            <div class="text-right">
                                                <small class="text-muted d-block">Uploaded {{ $finalAcceptance->created_at->format('M d, Y') }}</small>
                                                <small class="text-muted d-block">{{   ($finalAcceptance->file_size ?? 0) }}</small>
                                            </div>
                                        </div>
                                        <h5 class="mb-1 text-truncate">{{ $finalAcceptance->original_filename }}</h5>
                                        <div class="btn-group btn-block mt-3">
                                            <a href="{{ route('admin.applications.files.download', [$application, $finalAcceptance]) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                   
                                        </div>
                                    @else
                                        <div class="text-center py-3"> 
                                            <i class="fas fa-file-contract fa-3x {{ $paymentFile ? 'text-warning' : 'text-secondary' }} mb-3"></i>
                                            <p class="mb-2">
                                                @if($paymentFile)
                                                    No final acceptance letter uploaded yet.
                                                @else
                                                    Upload Payment Confirmation to unlock
                                                @endif
                                            </p>
            
                                            @if($paymentFile && auth()->user()->role =="Register")
                                            <form action="{{ route('admin.applications.upload-file', $application) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                                @csrf
                                                <input type="hidden" name="file_type" value="final_acceptance">
                                                <div class="custom-file mb-2">
                                                    <input type="file" class="custom-file-input" id="final_acceptance" name="file">
                                                    <label class="custom-file-label text-left" for="final_acceptance">Choose file</label>
                                                </div>
                                                <button type="submit" class="btn btn-warning btn-block">
                                                    <i class="fas fa-upload mr-1"></i> Upload Now
                                                </button>
                                            </form>
                                            @elseif($paymentFile)
                                            <b>Only Register Upload this</b>
                                            @else
                                            <button class="btn btn-secondary btn-block" disabled>
                                                <i class="fas fa-lock mr-1"></i> Locked
                                            </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Awaiting Student Card -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 {{ $awaitingStudentCard ? 'border-success' : ($finalAcceptance ? 'border-warning' : 'border-secondary') }}">
                                <div class="card-header py-2 d-flex justify-content-between align-items-center {{ $awaitingStudentCard ? 'bg-success text-white' : ($finalAcceptance ? 'bg-warning text-dark' : 'bg-secondary text-white') }}">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas {{ $awaitingStudentCard ? 'fa-check-circle' : ($finalAcceptance ? 'fa-exclamation-circle' : 'fa-lock') }} mr-1"></i>
                                        Awaiting Student Card
                                    </h6>
                                    @if($awaitingStudentCard)
                                    <span class="badge badge-light">Uploaded by 
                                         {{ $awaitingStudentCard->uploader ? $awaitingStudentCard->uploader->name : 'Unknown' }}</span>
                                    @else
                                    <span class="badge badge-light {{ $finalAcceptance ? 'text-warning' : 'text-secondary' }}">
                                        {{ $finalAcceptance ? 'Missing' : 'Locked' }}
                                    </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    @if($awaitingStudentCard)
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <i class="fas {{  ($awaitingStudentCard->mime_type ?? 'application/pdf') }} fa-2x mr-2 text-primary"></i>
                                            </div>
                                            <div class="text-right">
                                                <small class="text-muted d-block">Uploaded {{ $awaitingStudentCard->created_at->format('M d, Y') }}</small>
                                                <small class="text-muted d-block">{{   ($awaitingStudentCard->file_size ?? 0) }}</small>
                                            </div>
                                        </div>
                                        <h5 class="mb-1 text-truncate">{{ $awaitingStudentCard->original_filename }}</h5>
                                        <div class="btn-group btn-block mt-3">
                                            <a href="{{ route('admin.applications.files.download', [$application, $awaitingStudentCard]) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                            
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="fas fa-id-card fa-3x {{ $finalAcceptance ? 'text-warning' : 'text-secondary' }} mb-3"></i>
                                            <p class="mb-2">
                                                @if($finalAcceptance)
                                                    No student card uploaded yet.
                                                @else
                                                    Upload Final Acceptance Letter to unlock
                                                @endif
                                            </p>
                                            @if($finalAcceptance)
                                            <form action="{{ route('admin.applications.upload-file', $application) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                                @csrf
                                                <input type="hidden" name="file_type" value="awaiting_student_card">
                                                <div class="custom-file mb-2">
                                                    <input type="file" class="custom-file-input" id="awaiting_student_card" name="file">
                                                    <label class="custom-file-label text-left" for="awaiting_student_card">Choose file</label>
                                                </div>
                                                <button type="submit" class="btn btn-warning btn-block">
                                                    <i class="fas fa-upload mr-1"></i> Upload Now
                                                </button>
                                            </form>
                                            @else
                                            <button class="btn btn-secondary btn-block" disabled>
                                                <i class="fas fa-lock mr-1"></i> Locked
                                            </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-xl-4 col-lg-5">
            <!-- Application Progress Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tasks mr-1"></i> Application Progress
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        // Calculate progress percentage based on status and documents
                        $progress = 0;
                        $totalSteps = 6; // Maximum steps in application process
                        
                        // Add progress for each completed document
                        if($firstAcceptance) $progress++;
                        if($paymentFile) $progress++;
                        if($finalAcceptance) $progress++;
                        if($awaitingStudentCard) $progress++;
                        
                        // Add progress based on status
                        if(in_array($application->status, ['Completed', 'Final Acceptance'])) {
                            $progress = $totalSteps; // Maximum progress
                        } elseif(in_array($application->status, ['Paid', 'Awaiting Final Acceptance'])) {
                            $progress = max($progress, 4);
                        } elseif(in_array($application->status, ['Conditional Acceptance', 'Awaiting Payment'])) {
                            $progress = max($progress, 3);
                        } elseif(in_array($application->status, ['Awaiting Conditional Acceptance'])) {
                            $progress = max($progress, 2);
                        } elseif(in_array($application->status, ['Awaiting App Fees Payment'])) {
                            $progress = max($progress, 1);
                        }
                        
                        $progressPercent = min(100, round(($progress / $totalSteps) * 100));
                        
                        // Progress color
                        $progressColor = "danger";
                        if($progressPercent >= 100) {
                            $progressColor = "success";
                        } elseif($progressPercent >= 70) {
                            $progressColor = "info";
                        } elseif($progressPercent >= 40) {
                            $progressColor = "primary";
                        } elseif($progressPercent >= 20) {
                            $progressColor = "warning";
                        }
                    @endphp
                    
<!-- Progress bar -->
<h4 class="small font-weight-bold">Overall Progress <span class="float-right">{{ $progressPercent }}%</span></h4>
<div class="progress mb-4">
    <div class="progress-bar bg-{{ $progressColor }}" role="progressbar" style="width: {{ $progressPercent }}%" aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
</div>

<!-- Progress steps -->
<div class="application-progress-steps">
    <div class="step {{ $progress >= 1 ? 'completed' : ($progress == 0 ? 'active' : '') }}">
        <div class="step-icon {{ $progress >= 1 ? 'bg-success' : ($progress == 0 ? 'bg-primary' : 'bg-light') }}">
            <i class="fas fa-file-alt {{ $progress >= 1 || $progress == 0 ? 'text-white' : 'text-gray-500' }}"></i>
        </div>
        <div class="step-content">
            <h6 class="mb-1 {{ $progress >= 1 ? 'text-success' : ($progress == 0 ? 'text-primary' : 'text-muted') }}">Application Submitted</h6>
            <p class="small text-muted mb-0">Initial application received</p>
        </div>
    </div>
    
    <div class="step {{ $progress >= 2 ? 'completed' : ($progress == 1 ? 'active' : '') }}">
        <div class="step-icon {{ $progress >= 2 ? 'bg-success' : ($progress == 1 ? 'bg-primary' : 'bg-light') }}">
            <i class="fas fa-check-square {{ $progress >= 2 || $progress == 1 ? 'text-white' : 'text-gray-500' }}"></i>
        </div>
        <div class="step-content">
            <h6 class="mb-1 {{ $progress >= 2 ? 'text-success' : ($progress == 1 ? 'text-primary' : 'text-muted') }}">First Acceptance</h6>
            <p class="small text-muted mb-0">Initial university approval</p>
        </div>
    </div>
    
    <div class="step {{ $progress >= 3 ? 'completed' : ($progress == 2 ? 'active' : '') }}">
        <div class="step-icon {{ $progress >= 3 ? 'bg-success' : ($progress == 2 ? 'bg-primary' : 'bg-light') }}">
            <i class="fas fa-money-bill-wave {{ $progress >= 3 || $progress == 2 ? 'text-white' : 'text-gray-500' }}"></i>
        </div>
        <div class="step-content">
            <h6 class="mb-1 {{ $progress >= 3 ? 'text-success' : ($progress == 2 ? 'text-primary' : 'text-muted') }}">Payment Confirmed</h6>
            <p class="small text-muted mb-0">Tuition/fees payment received</p>
        </div>
    </div>
    
    <div class="step {{ $progress >= 4 ? 'completed' : ($progress == 3 ? 'active' : '') }}">
        <div class="step-icon {{ $progress >= 4 ? 'bg-success' : ($progress == 3 ? 'bg-primary' : 'bg-light') }}">
            <i class="fas fa-certificate {{ $progress >= 4 || $progress == 3 ? 'text-white' : 'text-gray-500' }}"></i>
        </div>
        <div class="step-content">
            <h6 class="mb-1 {{ $progress >= 4 ? 'text-success' : ($progress == 3 ? 'text-primary' : 'text-muted') }}">Final Acceptance</h6>
            <p class="small text-muted mb-0">Final university approval</p>
        </div>
    </div>
    
    <div class="step {{ $progress >= 5 ? 'completed' : ($progress == 4 ? 'active' : '') }}">
        <div class="step-icon {{ $progress >= 5 ? 'bg-success' : ($progress == 4 ? 'bg-primary' : 'bg-light') }}">
            <i class="fas fa-id-card {{ $progress >= 5 || $progress == 4 ? 'text-white' : 'text-gray-500' }}"></i>
        </div>
        <div class="step-content">
            <h6 class="mb-1 {{ $progress >= 5 ? 'text-success' : ($progress == 4 ? 'text-primary' : 'text-muted') }}">Student Card</h6>
            <p class="small text-muted mb-0">Student ID/credentials</p>
        </div>
    </div>
    
    <div class="step {{ $progress >= 6 ? 'completed' : ($progress == 5 ? 'active' : '') }}">
        <div class="step-icon {{ $progress >= 6 ? 'bg-success' : ($progress == 5 ? 'bg-primary' : 'bg-light') }}">
            <i class="fas fa-graduation-cap {{ $progress >= 6 || $progress == 5 ? 'text-white' : 'text-gray-500' }}"></i>
        </div>
        <div class="step-content">
            <h6 class="mb-1 {{ $progress >= 6 ? 'text-success' : ($progress == 5 ? 'text-primary' : 'text-muted') }}">Completed</h6>
            <p class="small text-muted mb-0">Application process finished</p>
        </div>
    </div>
</div>
            
            @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register' )

            <td class="status-cell">
                @include('partials.status-badge', ['status' => $application->status])
                
                <div class="mt-2">
                    <form id="status-update-form-{{ $application->id }}"
                        action="{{ route('admin.applications.update-status', $application) }}"
                        method="POST">
                      @csrf
                      @method('PUT')
                      <select name="status" class="form-control form-control-sm" 
                              onchange="this.form.submit()"
                              data-application-id="{{ $application->id }}">
                          @foreach(App\Models\AdmissionStage::all() as $stage)
                              <option value="{{ $stage->id }}" {{ $application->status == $stage->name ? 'selected' : '' }}>
                                  {{ $stage->name }}
                              </option>
                          @endforeach
                      </select>
                  </form>
                </div>
            </td>

            @endif
       
        </div>
    </div>
</div>

<!-- Upload Missing Files Modal -->
<div class="modal fade" id="uploadMissingFilesModal" tabindex="-1" role="dialog" aria-labelledby="uploadMissingFilesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="uploadMissingFilesModalLabel">
                    <i class="fas fa-upload mr-1"></i> Upload Missing Files
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i> Upload the missing required files for this application. You can upload multiple files at once.
                </div>
                
                <form action="{{ route('admin.applications.upload-missing-files', $application) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        @if(!$firstAcceptance)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-warning">
                                <div class="card-header bg-warning text-dark py-2">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas fa-file-alt mr-1"></i> First Acceptance Letter
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="first_acceptance_modal" name="first_acceptance">
                                        <label class="custom-file-label" for="first_acceptance_modal">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(!$paymentFile)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-warning">
                                <div class="card-header bg-warning text-dark py-2">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas fa-file-invoice-dollar mr-1"></i> Payment Confirmation
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="payment_file_modal" name="payment_file">
                                        <label class="custom-file-label" for="payment_file_modal">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(!$finalAcceptance)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-warning">
                                <div class="card-header bg-warning text-dark py-2">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas fa-file-contract mr-1"></i> Final Acceptance Letter
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="final_acceptance_modal" name="final_acceptance">
                                        <label class="custom-file-label" for="final_acceptance_modal">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(!$awaitingStudentCard)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-warning">
                                <div class="card-header bg-warning text-dark py-2">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas fa-id-card mr-1"></i> Awaiting Student Card
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="awaiting_student_card_modal" name="awaiting_student_card">
                                        <label class="custom-file-label" for="awaiting_student_card_modal">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    @if(count($missingFiles) > 0)
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload mr-1"></i> Upload Files
                        </button>
                    </div>
                    @else
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle mr-1"></i> All required files have been uploaded.
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Status History Modal -->


<!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeStatusModalLabel">Change Application Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="statusChangeForm" action="{{ route('admin.applications.update-status', $application) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <input type="hidden" name="status" id="newStatus">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i> You are changing the status from 
                        <strong>{{ $application->status }}</strong> to <strong id="statusDisplay"></strong>
                    </div>
                    
                    <div class="form-group">
                        <label for="statusNotes">Notes (Optional)</label>
                        <textarea class="form-control" id="statusNotes" name="notes" rows="3" placeholder="Add notes about this status change..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete File Modal -->
<div class="modal fade" id="deleteFileModal" tabindex="-1" role="dialog" aria-labelledby="deleteFileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFileModalLabel">Delete File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this file?')">
                    <i class="fas fa-trash mr-1"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Timeline styling */
.status-timeline {
    position: relative;
    padding-left: 30px;
}

.status-timeline:before {
    content: '';
    position: absolute;
    top: 0;
    left: 15px;
    height: 100%;
    width: 2px;
    background: #e3e6f0;
}

.status-item {
    position: relative;
}

.icon-circle {
    height: 30px;
    width: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Application progress steps */
.application-progress-steps {
    position: relative;
}

.application-progress-steps .step {
    position: relative;
    padding-left: 50px;
    margin-bottom: 20px;
}

.application-progress-steps .step:last-child {
    margin-bottom: 0;
}

.application-progress-steps .step:before {
    content: '';
    position: absolute;
    top: 15px;
    left: 15px;
    height: 100%;
    width: 2px;
    background: #e3e6f0;
}

.application-progress-steps .step:last-child:before {
    display: none;
}

.application-progress-steps .step .step-icon {
    position: absolute;
    top: 0;
    left: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}

.application-progress-steps .step.completed .step-icon {
    background-color: #1cc88a;
    color: #fff;
}

.application-progress-steps .step.active .step-icon {
    background-color: #4e73df;
    color: #fff;
}



.custom-file-input:lang(en)~.custom-file-label::after {
    content: "Browse";
}
</style>

<script>
  // Status update handler for dropdown select
$('.update-status-select').on('change', function() {
    const form = $(this).closest('form');
    const applicationId = $(this).data('application-id');
    const statusCell = $(this).closest('.status-cell');
    
    $.ajax({
        url: form.attr('action'),
        type: 'PUT',
        data: form.serialize(),
        success: function(response) {
            if (response.success) {
                // Replace the status badge
                statusCell.find('.badge').replaceWith($(response.status_badge));
                
                // Update the progress section
                $('#application-progress-container').html(response.progress_html);
                
                // Show success message
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        },
        error: function(xhr) {
            // Show error message
            Swal.fire({
                title: 'Error!',
                text: xhr.responseJSON?.message || 'Failed to update status',
                icon: 'error'
            });
        }
    });
});

// Status change handling for dropdown menu
$('.status-change').on('click', function() {
    const newStatus = $(this).data('status');
    $('#newStatus').val(newStatus);
    $('#statusDisplay').text(newStatus);
    $('#changeStatusModal').modal('show');
});

// Form submission from the modal
$('#statusChangeForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: $(this).attr('action'),
        type: 'PATCH',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                // Close the modal
                $('#changeStatusModal').modal('hide');
                
                // Update the status badge in the header
                $('.badge.badge-' + response.old_status_class).replaceWith($(response.status_badge));
                
                // Update the progress section
                $('#application-progress-container').html(response.progress_html);
                
                // Show success message
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        },
        error: function(xhr) {
            // Show error message
            Swal.fire({
                title: 'Error!',
                text: xhr.responseJSON?.message || 'Failed to update status',
                icon: 'error'
            });
        }
    });
});
$(document).ready(function() {
    // Status change handling
    $('.status-change').on('click', function() {
        const newStatus = $(this).data('status');
        $('#newStatus').val(newStatus);
        $('#statusDisplay').text(newStatus);
        $('#changeStatusModal').modal('show');
    });
    
    // Delete file modal setup
    $('#deleteFileModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const fileId = button.data('file-id');
        const fileName = button.data('file-name');
       
        const modal = $(this);
        modal.find('#fileNameDisplay').text(fileName);
        
        // Use the correct route format
        const deleteUrl = "".replace(':fileId', fileId);
        modal.find('#deleteFileForm').attr('action', deleteUrl);
    });
    
    // Bootstrap custom file input
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName || "Choose file");
    });
});

    // Helper function for file icons
    function  (mimeType) {
        if (mimeType && mimeType.includes('pdf')) {
            return 'fa-file-pdf';
        } else if (mimeType && (mimeType.includes('word') || mimeType.includes('doc'))) {
            return 'fa-file-word';
        } else if (mimeType && (mimeType.includes('excel') || mimeType.includes('spreadsheet') || mimeType.includes('csv'))) {
            return 'fa-file-excel';
        } else if (mimeType && (mimeType.includes('powerpoint') || mimeType.includes('presentation'))) {
            return 'fa-file-powerpoint';
        } else if (mimeType && mimeType.includes('image')) {
            return 'fa-file-image';
        } else if (mimeType && (mimeType.includes('zip') || mimeType.includes('rar') || mimeType.includes('archive'))) {
            return 'fa-file-archive';
        } else if (mimeType && mimeType.includes('text')) {
            return 'fa-file-alt';
        } else {
            return 'fa-file';
        }
    }

    // Helper function for file size formatting
    function   (sizeInKB) {
        if (!sizeInKB) return '0 KB';
        
        sizeInKB = parseFloat(sizeInKB);
        if (sizeInKB < 1024) {
            return sizeInKB.toFixed(2) + ' KB';
        } else {
            return (sizeInKB / 1024).toFixed(2) + ' MB';
        }
    }
</script>
@endsection