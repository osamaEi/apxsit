@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Student</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Student
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.students.update', $student) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <!-- Flash Messages -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs" id="studentEditTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true">
                                    <i class="fas fa-info-circle"></i> Basic Info
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="passport-tab" data-toggle="tab" href="#passport" role="tab" aria-controls="passport" aria-selected="false">
                                    <i class="fas fa-passport"></i> Passport
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="personal-tab" data-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="false">
                                    <i class="fas fa-user"></i> Personal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="education-tab" data-toggle="tab" href="#education" role="tab" aria-controls="education" aria-selected="false">
                                    <i class="fas fa-graduation-cap"></i> Education
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="false">
                                    <i class="fas fa-file-alt"></i> Documents
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="status-tab" data-toggle="tab" href="#status" role="tab" aria-controls="status" aria-selected="false">
                                    <i class="fas fa-clipboard-check"></i> Status
                                </a>
                            </li>
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content p-3 mt-3" id="studentEditTabsContent">
                            <!-- Basic Information Tab -->
                            <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="academic_year">Academic Year <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('academic_year') is-invalid @enderror" id="academic_year" name="academic_year" value="{{ old('academic_year', $student->academic_year ?? '') }}" required>
                                            @error('academic_year')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="study_country_id">Study Country <span class="text-danger">*</span></label>
                                            <select class="form-control select2bs4 @error('study_country_id') is-invalid @enderror" id="study_country_id" name="study_country_id" required>
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" {{ old('study_country_id', $student->study_country_id ?? '') == $country->id ? 'selected' : '' }}>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('study_country_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="employee_id">Responsible Employee <span class="text-danger">*</span></label>
                                            <select class="form-control select2bs4 @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                                                <option value="">Select Employee</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}" {{ old('employee_id', $student->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox mt-4">
                                                <input type="hidden" name="is_transfer" value="0">
                                                <input type="checkbox" class="custom-control-input" id="is_transfer" name="is_transfer" value="1" {{ old('is_transfer', $student->is_transfer ?? 0) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_transfer">Is Transfer Student</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Passport Information Tab -->
                            <div class="tab-pane fade" id="passport" role="tabpanel" aria-labelledby="passport-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}" required>
                                            @error('date_of_birth')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="passport_id">Passport ID <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('passport_id') is-invalid @enderror" id="passport_id" name="passport_id" value="{{ old('passport_id', $student->passport_id ?? '') }}" required>
                                            @error('passport_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="passport_issue_date">Passport Issue Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('passport_issue_date') is-invalid @enderror" id="passport_issue_date" name="passport_issue_date" value="{{ old('passport_issue_date', $student->passport_issue_date ? $student->passport_issue_date->format('Y-m-d') : '') }}" required>
                                            @error('passport_issue_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="passport_expiry_date">Passport Expiry Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('passport_expiry_date') is-invalid @enderror" id="passport_expiry_date" name="passport_expiry_date" value="{{ old('passport_expiry_date', $student->passport_expiry_date ? $student->passport_expiry_date->format('Y-m-d') : '') }}" required>
                                            @error('passport_expiry_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="needs_visa_support" value="0">
                                                <input type="checkbox" class="custom-control-input" id="needs_visa_support" name="needs_visa_support" value="1" {{ old('needs_visa_support', $student->needs_visa_support ?? 0) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="needs_visa_support">Needs Visa Support</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Information Tab -->
                            <div class="tab-pane fade" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $student->first_name ?? '') }}" required>
                                            @error('first_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $student->last_name ?? '') }}" required>
                                            @error('last_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $student->email ?? '') }}" required>
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $student->phone_number ?? '') }}" required>
                                            @error('phone_number')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country_of_residence_id">Country of Residence <span class="text-danger">*</span></label>
                                            <select class="form-control select2bs4 @error('country_of_residence_id') is-invalid @enderror" id="country_of_residence_id" name="country_of_residence_id" required>
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" {{ old('country_of_residence_id', $student->country_of_residence_id ?? '') == $country->id ? 'selected' : '' }}>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('country_of_residence_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nationality_id">Nationality <span class="text-danger">*</span></label>
                                            <select class="form-control select2bs4 @error('nationality_id') is-invalid @enderror" id="nationality_id" name="nationality_id" required>
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" {{ old('nationality_id', $student->nationality_id ?? '') == $country->id ? 'selected' : '' }}>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('nationality_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender">Gender <span class="text-danger">*</span></label>
                                            <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                                <option value="">Select Gender</option>
                                                @foreach($genders as $key => $value)
                                                    <option value="{{ $key }}" {{ old('gender', $student->gender ?? '') == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('gender')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="marital_status">Marital Status <span class="text-danger">*</span></label>
                                            <select class="form-control @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status" required>
                                                <option value="">Select Marital Status</option>
                                                @foreach($maritalStatuses as $key => $value)
                                                    <option value="{{ $key }}" {{ old('marital_status', $student->marital_status ?? '') == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('marital_status')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="father_name">Father's Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{ old('father_name', $student->father_name ?? '') }}" required>
                                            @error('father_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mother_name">Mother's Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('mother_name') is-invalid @enderror" id="mother_name" name="mother_name" value="{{ old('mother_name', $student->mother_name ?? '') }}" required>
                                            @error('mother_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="emergency_email">Emergency Email</label>
                                            <input type="email" class="form-control @error('emergency_email') is-invalid @enderror" id="emergency_email" name="emergency_email" value="{{ old('emergency_email', $student->emergency_email ?? '') }}">
                                            @error('emergency_email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="emergency_phone">Emergency Phone</label>
                                            <input type="text" class="form-control @error('emergency_phone') is-invalid @enderror" id="emergency_phone" name="emergency_phone" value="{{ old('emergency_phone', $student->emergency_phone ?? '') }}">
                                            @error('emergency_phone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Education Information Tab -->
                            <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="education-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="applying_degree_id">Applying Degree <span class="text-danger">*</span></label>
                                            <select class="form-control select2bs4 @error('applying_degree_id') is-invalid @enderror" id="applying_degree_id" name="applying_degree_id" required>
                                                <option value="">Select Program</option>
                                                @foreach($degrees as $degree)
                                                    <option value="{{ $degree->id }}" {{ old('applying_degree_id', $student->applying_degree_id ?? '') == $degree->id ? 'selected' : '' }}>
                                                        {{ $degree->name }} 
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('applying_degree_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="high_school_name">High School Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('high_school_name') is-invalid @enderror" id="high_school_name" name="high_school_name" value="{{ old('high_school_name', $student->high_school_name ?? '') }}" required>
                                            @error('high_school_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="high_school_country_id">High School Country <span class="text-danger">*</span></label>
                                            <select class="form-control select2bs4 @error('high_school_country_id') is-invalid @enderror" id="high_school_country_id" name="high_school_country_id" required>
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" {{ old('high_school_country_id', $student->high_school_country_id ?? '') == $country->id ? 'selected' : '' }}>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('high_school_country_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gpa">GPA <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('gpa') is-invalid @enderror" id="gpa" name="gpa" value="{{ old('gpa', $student->gpa ?? '') }}" required>
                                            @error('gpa')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Documents Tab -->
                            <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="photo">Profile Photo</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input @error('photo') is-invalid @enderror" id="photo" name="photo">
                                                    <label class="custom-file-label" for="photo">Choose file</label>
                                                </div>
                                            </div>
                                            @error('photo')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @if($student->photo_path)
                                                <div class="mt-2">
                                                    <small class="text-muted">Current photo:</small>
                                                    <a href="{{ Storage::url($student->photo_path) }}" target="_blank" class="d-block mt-1">
                                                        <img src="{{ Storage::url($student->photo_path) }}" alt="Profile Photo" class="img-thumbnail" style="max-height: 100px;">
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="passport">Passport</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input @error('passport') is-invalid @enderror" id="passport" name="passport">
                                                    <label class="custom-file-label" for="passport">Choose file</label>
                                                </div>
                                            </div>
                                            @error('passport')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @if($student->passport_path)
                                                <div class="mt-2">
                                                    <small class="text-muted">Current file:</small>
                                                    <a href="{{ Storage::url($student->passport_path) }}" target="_blank" class="d-block mt-1">
                                                        <i class="fas fa-file-pdf mr-1"></i> View passport
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="transcript">Transcript</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input @error('transcript') is-invalid @enderror" id="transcript" name="transcript">
                                                    <label class="custom-file-label" for="transcript">Choose file</label>
                                                </div>
                                            </div>
                                            @error('transcript')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @if($student->transcript_path)
                                                <div class="mt-2">
                                                    <small class="text-muted">Current file:</small>
                                                    <a href="{{ Storage::url($student->transcript_path) }}" target="_blank" class="d-block mt-1">
                                                        <i class="fas fa-file-pdf mr-1"></i> View transcript
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="diploma">Diploma (Optional)</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input @error('diploma') is-invalid @enderror" id="diploma" name="diploma">
                                                    <label class="custom-file-label" for="diploma">Choose file</label>
                                                </div>
                                            </div>
                                            @error('diploma')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @if($student->diploma_path)
                                                <div class="mt-2">
                                                    <small class="text-muted">Current file:</small>
                                                    <a href="{{ Storage::url($student->diploma_path) }}" target="_blank" class="d-block mt-1">
                                                        <i class="fas fa-file-pdf mr-1"></i> View diploma
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="denklik">Denklik (Optional)</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input @error('denklik') is-invalid @enderror" id="denklik" name="denklik">
                                                    <label class="custom-file-label" for="denklik">Choose file</label>
                                                </div>
                                            </div>
                                            @error('denklik')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @if($student->denklik_path)
                                                <div class="mt-2">
                                                    <small class="text-muted">Current file:</small>
                                                    <a href="{{ Storage::url($student->denklik_path) }}" target="_blank" class="d-block mt-1">
                                                        <i class="fas fa-file-pdf mr-1"></i> View denklik
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="certificate">Certificate (Optional)</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input @error('certificate') is-invalid @enderror" id="certificate" name="certificate">
                                                    <label class="custom-file-label" for="certificate">Choose file</label>
                                                </div>
                                            </div>
                                            @error('certificate')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @if($student->certificate_path)
                                                <div class="mt-2">
                                                    <small class="text-muted">Current file:</small>
                                                    <a href="{{ Storage::url($student->certificate_path) }}" target="_blank" class="d-block mt-1">
                                                        <i class="fas fa-file-pdf mr-1"></i> View certificate
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="other_documents">Other Documents (Optional)</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input @error('other_documents') is-invalid @enderror" id="other_documents" name="other_documents">
                                                    <label class="custom-file-label" for="other_documents">Choose file</label>
                                                </div>
                                            </div>
                                            @error('other_documents')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @if($student->other_documents_path)
                                                <div class="mt-2">
                                                    <small class="text-muted">Current file:</small>
                                                    <a href="{{ Storage::url($student->other_documents_path) }}" target="_blank" class="d-block mt-1">
                                                        <i class="fas fa-file-pdf mr-1"></i> View document
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Tab -->
                            <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Application Status <span class="text-danger">*</span></label>
                                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                                @foreach($statuses as $key => $value)
                                                    <option value="{{ $key }}" {{ old('status', $student->status ?? '') == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes">Notes</label>
                                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="5">{{ old('notes', $student->notes ?? '') }}</textarea>
                                            @error('notes')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Student
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
$(function() {
    // Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
    
    // Initialize custom file input
    bsCustomFileInput.init();
    
    // Remember the active tab
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        localStorage.setItem('activeStudentEditTab', $(e.target).attr('href'));
    });
    
    // Go to the last active tab
    var activeTab = localStorage.getItem('activeStudentEditTab');
    if(activeTab){
        $('#studentEditTabs a[href="' + activeTab + '"]').tab('show');
    }
});
</script>
@endsection