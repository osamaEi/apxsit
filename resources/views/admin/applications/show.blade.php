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
                <div class="card-body p-0">
                <style>
                .doc-timeline { padding: 8px 0; }
                .doc-step {
                    display: flex; align-items: stretch;
                    position: relative; padding: 0 24px 0 0;
                }
                .doc-step:not(:last-child)::after {
                    content: ''; position: absolute;
                    left: 38px; top: 72px; bottom: 0;
                    width: 2px; background: #e5e9f2; z-index: 0;
                }
                /* left column: number bubble */
                .doc-step-num {
                    width: 76px; flex-shrink: 0;
                    display: flex; flex-direction: column; align-items: center;
                    padding-top: 20px; position: relative; z-index: 1;
                }
                .doc-bubble {
                    width: 40px; height: 40px; border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 15px; font-weight: 700; flex-shrink: 0;
                    box-shadow: 0 2px 8px rgba(0,0,0,.12);
                }
                .doc-bubble.done  { background: #10b981; color: #fff; }
                .doc-bubble.next  { background: #f59e0b; color: #fff; }
                .doc-bubble.lock  { background: #e5e9f2; color: #94a3b8; }

                /* right column: the card */
                .doc-step-body {
                    flex: 1; margin: 12px 0 12px 0;
                    border-radius: 14px; overflow: hidden;
                    border: 1.5px solid #e5e9f2;
                    background: #fff;
                    box-shadow: 0 1px 6px rgba(0,0,0,.05);
                    transition: box-shadow .2s;
                }
                .doc-step-body:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); }
                .doc-step-body.done  { border-color: #10b981; }
                .doc-step-body.next  { border-color: #f59e0b; }
                .doc-step-body.lock  { border-color: #e5e9f2; background: #f9fafb; }

                .doc-step-hd {
                    display: flex; align-items: center; justify-content: space-between;
                    padding: 13px 18px 12px;
                    border-bottom: 1px solid #f1f5f9;
                }
                .doc-step-hd.done { background: linear-gradient(90deg,#ecfdf5,#f0fdf8); }
                .doc-step-hd.next { background: linear-gradient(90deg,#fffbeb,#fff8e0); }
                .doc-step-hd.lock { background: #f9fafb; }

                .doc-step-title {
                    font-size: 13.5px; font-weight: 700;
                    display: flex; align-items: center; gap: 8px; margin: 0;
                }
                .doc-step-title.done { color: #059669; }
                .doc-step-title.next { color: #b45309; }
                .doc-step-title.lock { color: #94a3b8; }

                .doc-badge {
                    font-size: 11px; font-weight: 600; padding: 3px 10px;
                    border-radius: 20px; white-space: nowrap;
                }
                .doc-badge.done  { background: #d1fae5; color: #059669; }
                .doc-badge.next  { background: #fef3c7; color: #b45309; }
                .doc-badge.lock  { background: #f1f5f9; color: #94a3b8; }

                .doc-step-bd { padding: 16px 18px; }

                /* uploaded file row */
                .doc-file-row {
                    display: flex; align-items: center; gap: 14px;
                }
                .doc-file-ico {
                    width: 44px; height: 44px; border-radius: 10px;
                    background: #eff6ff; color: #1a6bff;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 20px; flex-shrink: 0;
                }
                .doc-file-meta { flex: 1; min-width: 0; }
                .doc-file-name { font-weight: 600; font-size: 13px; color: #1e293b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
                .doc-file-date { font-size: 11px; color: #94a3b8; margin-top: 2px; }
                .doc-file-by   { font-size: 11px; color: #64748b; margin-top: 1px; }
                .doc-dl-btn {
                    padding: 7px 16px; border-radius: 9px; font-size: 12px; font-weight: 600;
                    background: linear-gradient(135deg,#1a6bff,#0a3d99); color: #fff;
                    border: none; white-space: nowrap; cursor: pointer; text-decoration: none;
                    display: inline-flex; align-items: center; gap: 5px; flex-shrink: 0;
                }
                .doc-dl-btn:hover { opacity: .88; color: #fff; text-decoration: none; }

                /* upload form */
                .doc-upload-row {
                    display: flex; align-items: center; gap: 12px;
                }
                .doc-who {
                    font-size: 12px; color: #64748b;
                    display: flex; align-items: center; gap: 5px; margin-bottom: 10px;
                }
                .doc-who strong { color: #1e293b; }
                .doc-upload-row .custom-file { flex: 1; }
                .doc-upload-row .custom-file-label { font-size: 12px; height: 36px; line-height: 24px; border-radius: 8px; }
                .doc-upload-row .custom-file-input { height: 36px; }
                .doc-upload-btn {
                    padding: 7px 16px; border-radius: 9px; font-size: 12px; font-weight: 600;
                    background: linear-gradient(135deg,#f59e0b,#d97706); color: #fff;
                    border: none; cursor: pointer; white-space: nowrap;
                    display: inline-flex; align-items: center; gap: 5px; flex-shrink: 0;
                }
                .doc-upload-btn:hover { opacity: .9; }
                .doc-locked-msg {
                    font-size: 12px; color: #94a3b8;
                    display: flex; align-items: center; gap: 6px;
                }
                .doc-role-only {
                    font-size: 12px; background: #f0f9ff; color: #0369a1;
                    border: 1px solid #bae6fd; border-radius: 8px;
                    padding: 7px 12px; display: flex; align-items: center; gap: 6px;
                }
                /* file picker button */
                .doc-file-pick-btn {
                    display: inline-flex; align-items: center; gap: 7px;
                    padding: 7px 16px; border-radius: 9px; font-size: 12px; font-weight: 600;
                    background: #f1f5f9; color: #334155; border: 1.5px dashed #cbd5e1;
                    cursor: pointer; transition: background .15s, border-color .15s;
                    white-space: nowrap; flex-shrink: 0;
                }
                .doc-file-pick-btn:hover { background: #e2e8f0; border-color: #94a3b8; }
                .doc-file-pick-btn.selected { background: #eff6ff; border-color: #3b82f6; color: #1d4ed8; }
                .doc-uploading-msg {
                    font-size: 12px; color: #64748b;
                    display: flex; align-items: center; gap: 4px;
                }
                /* delete button */
                .doc-del-btn {
                    padding: 7px 14px; border-radius: 9px; font-size: 12px; font-weight: 600;
                    background: linear-gradient(135deg,#ef4444,#b91c1c); color: #fff;
                    border: none; white-space: nowrap; cursor: pointer; text-decoration: none;
                    display: inline-flex; align-items: center; gap: 5px; flex-shrink: 0;
                }
                .doc-del-btn:hover { opacity: .88; color: #fff; text-decoration: none; }
                </style>

                @php
                $steps = [
                    [
                        'key'     => 'first_acceptance',
                        'label'   => 'First Acceptance Letter',
                        'icon'    => 'fa-file-check',
                        'file'    => $firstAcceptance,
                        'locked'  => false,
                        'who'     => 'Register',
                        'roles'   => ['Register','Admin'],
                        'unlock'  => null,
                        'ftype'   => 'fa-file-alt',
                    ],
                    [
                        'key'     => 'payment_file',
                        'label'   => 'Payment Confirmation',
                        'icon'    => 'fa-money-bill-wave',
                        'file'    => $paymentFile,
                        'locked'  => !$firstAcceptance,
                        'who'     => 'Sales',
                        'roles'   => ['Sales','Admin'],
                        'unlock'  => 'Upload First Acceptance Letter to unlock',
                        'ftype'   => 'fa-file-invoice-dollar',
                    ],
                    [
                        'key'     => 'final_acceptance',
                        'label'   => 'Final Acceptance Letter',
                        'icon'    => 'fa-file-contract',
                        'file'    => $finalAcceptance,
                        'locked'  => !$paymentFile,
                        'who'     => 'Register',
                        'roles'   => ['Register','Admin'],
                        'unlock'  => 'Upload Payment Confirmation to unlock',
                        'ftype'   => 'fa-file-contract',
                    ],
                    [
                        'key'     => 'awaiting_student_card',
                        'label'   => 'Student Card',
                        'icon'    => 'fa-id-card',
                        'file'    => $awaitingStudentCard,
                        'locked'  => !$finalAcceptance,
                        'who'     => 'Sales',
                        'roles'   => ['Sales','Admin'],
                        'unlock'  => 'Upload Final Acceptance Letter to unlock',
                        'ftype'   => 'fa-id-card',
                    ],
                ];
                @endphp

                <div class="doc-timeline px-3 py-2">
                @foreach($steps as $i => $step)
                    @php
                        $state = $step['file'] ? 'done' : ($step['locked'] ? 'lock' : 'next');
                        $canUpload = in_array(auth()->user()->role, $step['roles']);
                        $num = $i + 1;
                    @endphp
                    <div class="doc-step">
                        {{-- Step number bubble --}}
                        <div class="doc-step-num">
                            <div class="doc-bubble {{ $state }}">
                                @if($state === 'done')
                                    <i class="fas fa-check"></i>
                                @elseif($state === 'lock')
                                    <i class="fas fa-lock"></i>
                                @else
                                    {{ $num }}
                                @endif
                            </div>
                        </div>

                        {{-- Step card --}}
                        <div class="doc-step-body {{ $state }}">
                            {{-- Header --}}
                            <div class="doc-step-hd {{ $state }}">
                                <h6 class="doc-step-title {{ $state }}">
                                    <i class="fas {{ $step['icon'] }}"></i>
                                    {{ $step['label'] }}
                                </h6>
                                @if($state === 'done')
                                    <span class="doc-badge done"><i class="fas fa-check-circle mr-1"></i>Uploaded</span>
                                @elseif($state === 'next')
                                    <span class="doc-badge next"><i class="fas fa-exclamation-circle mr-1"></i>Pending</span>
                                @else
                                    <span class="doc-badge lock"><i class="fas fa-lock mr-1"></i>Locked</span>
                                @endif
                            </div>

                            {{-- Body --}}
                            <div class="doc-step-bd">
                                @if($state === 'done')
                                    <div class="doc-file-row">
                                        <div class="doc-file-ico"><i class="fas {{ $step['ftype'] }}"></i></div>
                                        <div class="doc-file-meta">
                                            <div class="doc-file-name">{{ $step['file']->original_filename }}</div>
                                            <div class="doc-file-date"><i class="fas fa-calendar-alt mr-1"></i>{{ $step['file']->created_at->format('d M Y') }}</div>
                                            <div class="doc-file-by"><i class="fas fa-user mr-1"></i>{{ $step['file']->uploader->name ?? 'Unknown' }}</div>
                                        </div>
                                        @php
                                            $dlKey = $step['key'] === 'first_acceptance' ? ['application' => $application->id, 'file' => $step['file']->id] : [$application, $step['file']];
                                        @endphp
                                        <a href="{{ route('admin.applications.files.download', $step['key'] === 'first_acceptance' ? ['application' => $application->id, 'file' => $step['file']->id] : [$application, $step['file']]) }}"
                                           class="doc-dl-btn">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.applications.files.delete', ['application' => $application->id, 'file' => $step['file']->id]) }}"
                                              onsubmit="return confirm('Delete this file? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="doc-del-btn">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>

                                @elseif($state === 'lock')
                                    <div class="doc-locked-msg">
                                        <i class="fas fa-lock"></i>
                                        {{ $step['unlock'] }}
                                    </div>

                                @else
                                    <div class="doc-who">
                                        <i class="fas fa-user-tag"></i>
                                        Waiting for <strong>{{ $step['who'] }}</strong> to upload
                                    </div>
                                    @if($canUpload)
                                    <form id="upload-form-{{ $step['key'] }}"
                                          action="{{ route('admin.applications.upload-file', $application) }}"
                                          method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="file_type" value="{{ $step['key'] }}">
                                        <div class="doc-upload-row">
                                            <label class="doc-file-pick-btn" for="file_{{ $step['key'] }}" id="label_{{ $step['key'] }}">
                                                <i class="fas fa-paperclip"></i>
                                                <span id="fname_{{ $step['key'] }}">اختر ملف…</span>
                                            </label>
                                            <input type="file"
                                                   id="file_{{ $step['key'] }}"
                                                   name="file"
                                                   style="display:none"
                                                   onchange="docFileSelected(this, '{{ $step['key'] }}')">
                                            <button type="submit"
                                                    id="submit_{{ $step['key'] }}"
                                                    class="doc-upload-btn"
                                                    style="display:none">
                                                <i class="fas fa-upload"></i> رفع
                                            </button>
                                            <div id="uploading_{{ $step['key'] }}" style="display:none" class="doc-uploading-msg">
                                                <span class="spinner-border spinner-border-sm mr-1"></span> جاري الرفع…
                                            </div>
                                        </div>
                                    </form>
                                    @else
                                    <div class="doc-role-only">
                                        <i class="fas fa-info-circle"></i>
                                        Only <strong>&nbsp;{{ $step['who'] }}&nbsp;</strong> can upload this step
                                    </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
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
                        // Progress is driven ONLY by document uploads
                        // Step 1 = Application Submitted (always done once application exists)
                        // Step 2 = First Acceptance uploaded
                        // Step 3 = Payment Confirmation uploaded
                        // Step 4 = Final Acceptance uploaded
                        // Step 5 = Student Card uploaded → Completed
                        $docProgress = 1; // application submitted is always step 1
                        if($firstAcceptance)      $docProgress = 2;
                        if($paymentFile)          $docProgress = 3;
                        if($finalAcceptance)      $docProgress = 4;
                        if($awaitingStudentCard)  $docProgress = 5;

                        $totalSteps     = 5;
                        $progressPercent = min(100, round(($docProgress / $totalSteps) * 100));

                        $progressColor = match(true) {
                            $progressPercent >= 100 => 'success',
                            $progressPercent >= 80  => 'info',
                            $progressPercent >= 60  => 'primary',
                            $progressPercent >= 40  => 'warning',
                            default                 => 'danger',
                        };

                        // Derive current status label from documents only
                        $pipelineStatus = match($docProgress) {
                            5 => 'Completed',
                            4 => 'Final Acceptance',
                            3 => 'Payment Confirmed',
                            2 => 'First Acceptance',
                            default => 'Application Submitted',
                        };

                        $pipelineSteps = [
                            ['label' => 'Application Submitted', 'desc' => 'Initial application received',   'icon' => 'fa-file-alt'],
                            ['label' => 'First Acceptance',      'desc' => 'First acceptance letter uploaded','icon' => 'fa-check-square'],
                            ['label' => 'Payment Confirmed',     'desc' => 'Payment confirmation uploaded',   'icon' => 'fa-money-bill-wave'],
                            ['label' => 'Final Acceptance',      'desc' => 'Final acceptance letter uploaded','icon' => 'fa-certificate'],
                            ['label' => 'Completed',             'desc' => 'Student card uploaded',          'icon' => 'fa-graduation-cap'],
                        ];
                    @endphp

                    {{-- Current status derived from documents --}}
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="small font-weight-bold text-muted text-uppercase">Pipeline Status</span>
                        @php
                            $psColor = match($pipelineStatus) {
                                'Completed'           => 'success',
                                'Final Acceptance'    => 'info',
                                'Payment Confirmed'   => 'primary',
                                'First Acceptance'    => 'warning',
                                default               => 'secondary',
                            };
                        @endphp
                        <span class="badge badge-{{ $psColor }} px-2 py-1" style="font-size:11px">
                            <i class="fas fa-circle mr-1" style="font-size:7px"></i>{{ $pipelineStatus }}
                        </span>
                    </div>

                    {{-- Progress bar --}}
                    <h4 class="small font-weight-bold">Overall Progress <span class="float-right">{{ $progressPercent }}%</span></h4>
                    <div class="progress mb-4" style="height:8px; border-radius:6px">
                        <div class="progress-bar bg-{{ $progressColor }}" role="progressbar"
                             style="width:{{ $progressPercent }}%; border-radius:6px"
                             aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    {{-- Pipeline steps --}}
                    <div class="application-progress-steps">
                    @foreach($pipelineSteps as $idx => $ps)
                        @php
                            $stepNum  = $idx + 1;
                            $isDone   = $docProgress > $stepNum;
                            $isActive = $docProgress === $stepNum;
                        @endphp
                        <div class="step {{ $isDone ? 'completed' : ($isActive ? 'active' : '') }}">
                            <div class="step-icon {{ $isDone ? 'bg-success' : ($isActive ? 'bg-primary' : 'bg-light') }}">
                                <i class="fas {{ $ps['icon'] }} {{ ($isDone || $isActive) ? 'text-white' : 'text-gray-500' }}"></i>
                            </div>
                            <div class="step-content">
                                <h6 class="mb-1 {{ $isDone ? 'text-success' : ($isActive ? 'text-primary' : 'text-muted') }}">
                                    {{ $ps['label'] }}
                                    @if($isDone)<i class="fas fa-check-circle ml-1 text-success" style="font-size:11px"></i>@endif
                                </h6>
                                <p class="small text-muted mb-0">{{ $ps['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                    </div>

                </div>
            </div>
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
    function getFileIcon(mimeType) {
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
    function formatFileSize(sizeInKB) {
        if (!sizeInKB) return '0 KB';

        sizeInKB = parseFloat(sizeInKB);
        if (sizeInKB < 1024) {
            return sizeInKB.toFixed(2) + ' KB';
        } else {
            return (sizeInKB / 1024).toFixed(2) + ' MB';
        }
    }

    // ── Document upload: select → show name + submit button ──────────
    function docFileSelected(input, key) {

        var file = input.files[0];
        if (!file) return;

        // Show filename on the label
        var label = document.getElementById('label_' + key);
        var fname = document.getElementById('fname_' + key);
        if (fname) fname.textContent = file.name;
        if (label) label.classList.add('selected');

        // Show the submit button
        var btn = document.getElementById('submit_' + key);
        if (btn) btn.style.display = 'inline-flex';
    }

    // Auto-submit when Upload button is clicked: show spinner
    document.addEventListener('DOMContentLoaded', function () {
        ['first_acceptance','payment_file','final_acceptance','awaiting_student_card'].forEach(function(key) {
            var form = document.getElementById('upload-form-' + key);
            if (!form) return;
            form.addEventListener('submit', function () {
                var btn = document.getElementById('submit_' + key);
                var spinner = document.getElementById('uploading_' + key);
                if (btn) btn.style.display = 'none';
                if (spinner) spinner.style.display = 'flex';
            });
        });
    });
</script>

<style>
/* ══════════════════════════════════════════
   DARK MODE — applications/show
   ══════════════════════════════════════════ */

/* page bg */
body.dark-mode .container-fluid { background-color: #0a1628; }

/* cards */
body.dark-mode .card { background-color: #0f2040 !important; border-color: rgba(255,255,255,.06) !important; }
body.dark-mode .card-header { background-color: #0d1e38 !important; border-color: rgba(255,255,255,.06) !important; }

/* table inside program details */
body.dark-mode .table { background-color: transparent; color: #c8d2e6 !important; }
body.dark-mode .table th.bg-light { background-color: #0d1e38 !important; color: #90a4c8 !important; border-color: rgba(255,255,255,.06) !important; }
body.dark-mode .table td { border-color: rgba(255,255,255,.06) !important; color: #c8d2e6 !important; }
body.dark-mode .table-bordered { border-color: rgba(255,255,255,.06) !important; }

/* notes box */
body.dark-mode .border.rounded.p-3.bg-light { background-color: #0d1e38 !important; border-color: rgba(255,255,255,.08) !important; color: #c8d2e6 !important; }

/* doc timeline steps */
body.dark-mode .doc-step:not(:last-child)::after { background: rgba(255,255,255,.08); }
body.dark-mode .doc-bubble.lock { background: #1a3050; color: #4a6080; }

body.dark-mode .doc-step-body { background: #0f2040 !important; border-color: rgba(255,255,255,.08) !important; }
body.dark-mode .doc-step-body.done { border-color: #059669 !important; }
body.dark-mode .doc-step-body.next { border-color: #d97706 !important; }
body.dark-mode .doc-step-body.lock { background: #0d1e38 !important; border-color: rgba(255,255,255,.06) !important; }

body.dark-mode .doc-step-hd { border-color: rgba(255,255,255,.06) !important; }
body.dark-mode .doc-step-hd.done { background: rgba(16,185,129,.08) !important; }
body.dark-mode .doc-step-hd.next { background: rgba(245,158,11,.08) !important; }
body.dark-mode .doc-step-hd.lock { background: #0d1e38 !important; }

body.dark-mode .doc-step-title.lock { color: #3a5070 !important; }
body.dark-mode .doc-badge.lock { background: #1a3050 !important; color: #3a5070 !important; }
body.dark-mode .doc-badge.next { background: rgba(245,158,11,.15) !important; color: #fbbf24 !important; }
body.dark-mode .doc-badge.done { background: rgba(16,185,129,.15) !important; color: #34d399 !important; }

/* uploaded file row */
body.dark-mode .doc-file-ico { background: #0d2a4a !important; color: #6ea8fe !important; }
body.dark-mode .doc-file-name { color: #c8d2e6 !important; }
body.dark-mode .doc-file-date,
body.dark-mode .doc-file-by   { color: #4a6080 !important; }

/* locked + role-only messages */
body.dark-mode .doc-locked-msg { color: #3a5070 !important; }
body.dark-mode .doc-role-only  { background: #0d2a4a !important; color: #6ea8fe !important; border-color: #1a4a7a !important; }
body.dark-mode .doc-who        { color: #4a6080 !important; }
body.dark-mode .doc-who strong { color: #c8d2e6 !important; }

/* upload file picker button */
body.dark-mode .doc-file-pick-btn { background: #0d1e38 !important; border-color: rgba(255,255,255,.15) !important; color: #a8b8d0 !important; }
body.dark-mode .doc-file-pick-btn:hover { background: #1a3050 !important; border-color: rgba(255,255,255,.25) !important; }
body.dark-mode .doc-file-pick-btn.selected { background: #0d2a4a !important; border-color: #3b82f6 !important; color: #6ea8fe !important; }
body.dark-mode .doc-uploading-msg { color: #4a6080 !important; }

/* application progress sidebar */
body.dark-mode .application-progress-steps .step:before { background: rgba(255,255,255,.08); }
body.dark-mode .application-progress-steps .step .step-icon.bg-light { background-color: #1a3050 !important; }
body.dark-mode .application-progress-steps .step .step-content h6.text-muted { color: #4a6080 !important; }
body.dark-mode .application-progress-steps .step .step-content p { color: #4a6080 !important; }
body.dark-mode .progress { background-color: #0d1e38 !important; }
body.dark-mode .text-muted { color: #4a6080 !important; }

/* status timeline (history) */
body.dark-mode .status-timeline:before { background: rgba(255,255,255,.08); }
body.dark-mode .icon-circle { border: 1px solid rgba(255,255,255,.08); }

/* modals */
body.dark-mode .modal-content { background-color: #0f2040 !important; border-color: rgba(255,255,255,.08) !important; }
body.dark-mode .modal-header { border-color: rgba(255,255,255,.08) !important; }
body.dark-mode .modal-footer { border-color: rgba(255,255,255,.08) !important; }
body.dark-mode .modal-body .alert-info { background: #0d2a4a !important; color: #90c8f0 !important; border-color: #1a4a7a !important; }
body.dark-mode .modal-body label { color: #a8b8d0 !important; }
body.dark-mode .modal-body .card { background: #0d1e38 !important; }
body.dark-mode .modal-body .card-header.bg-warning { background-color: #92400e !important; }
body.dark-mode .custom-file-label { background-color: #0d1e38 !important; border-color: rgba(255,255,255,.12) !important; color: #a8b8d0 !important; }
body.dark-mode .custom-file-label::after { background-color: #1a3050 !important; color: #c8d2e6 !important; border-color: rgba(255,255,255,.12) !important; }
body.dark-mode #statusNotes { background-color: #0d1e38 !important; border-color: rgba(255,255,255,.12) !important; color: #c8d2e6 !important; }
</style>

@endsection