@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h4>Please Follow The Steps To Add New Student</h4>
        </div>
    </div>
    
    <!-- Progress Steps -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="d-flex align-items-center justify-content-center">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #28a745;"></div>
                <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <span>2</span>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #ddd;"></div>
                <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 1px solid #ddd;">
                    <span>3</span>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #ddd;"></div>
                <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 1px solid #ddd;">
                    <span>4</span>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #ddd;"></div>
                <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 1px solid #ddd;">
                    <span>5</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student's Information</h3>
                </div>
                <div class="card-body">
                    <h5 class="mb-4">Passport information</h5>
                    
                    <form action="{{ route('admin.students.store.step2') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Date of Birth -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                                    <div class="input-group date" id="birthdate" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input @error('date_of_birth') is-invalid @enderror" 
                                               data-target="#birthdate" 
                                               name="date_of_birth" 
                                               value="{{ old('date_of_birth', session('student_data.passport.date_of_birth')) }}"
                                               placeholder="mm/dd/yyyy"
                                               required>
                                        <div class="input-group-append" data-target="#birthdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    @error('date_of_birth')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Passport ID -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="passport_id">Passport ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('passport_id') is-invalid @enderror" 
                                           id="passport_id" 
                                           name="passport_id" 
                                           value="{{ old('passport_id', session('student_data.passport.passport_id')) }}"
                                           placeholder="Provide student passport ID, ONLY letters and numbers allowed"
                                           required>
                                    @error('passport_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Issue Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="passport_issue_date">Issue Date <span class="text-danger">*</span></label>
                                    <div class="input-group date" id="issuedate" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input @error('passport_issue_date') is-invalid @enderror" 
                                               data-target="#issuedate" 
                                               name="passport_issue_date" 
                                               value="{{ old('passport_issue_date', session('student_data.passport.passport_issue_date')) }}"
                                               placeholder="mm/dd/yyyy"
                                               required>
                                        <div class="input-group-append" data-target="#issuedate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    @error('passport_issue_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Expiration Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="passport_expiry_date">Expiration Date <span class="text-danger">*</span></label>
                                    <div class="input-group date" id="expirydate" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input @error('passport_expiry_date') is-invalid @enderror" 
                                               data-target="#expirydate" 
                                               name="passport_expiry_date" 
                                               value="{{ old('passport_expiry_date', session('student_data.passport.passport_expiry_date')) }}"
                                               placeholder="mm/dd/yyyy"
                                               required>
                                        <div class="input-group-append" data-target="#expirydate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    @error('passport_expiry_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Visa Support -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="needs_visa_support">Does student need visa support letter? <span class="text-danger">*</span></label>
                                    <select class="form-control @error('needs_visa_support') is-invalid @enderror" id="needs_visa_support" name="needs_visa_support">
                                        <option value="0" {{ old('needs_visa_support', session('student_data.passport.needs_visa_support')) == '0' ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('needs_visa_support', session('student_data.passport.needs_visa_support')) == '1' ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    @error('needs_visa_support')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Navigation Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.students.create.step1') }}" class="btn btn-secondary">Previous</a>
                                <button type="submit" class="btn btn-primary">Next</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body.dark-mode .rounded-circle.bg-light {
    background-color: #1a3050 !important;
    color: #90a4c8 !important;
    border-color: rgba(255,255,255,.12) !important;
}
body.dark-mode .input-group-text {
    background-color: #0d1e38 !important;
    border-color: rgba(255,255,255,.1) !important;
    color: #90a4c8 !important;
}
</style>

<!-- Date Picker Assets -->
<link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

<!-- Initialize Date Pickers -->
<script>
$(function() {
    $('#birthdate').datetimepicker({ format: 'L', icons: { time: 'far fa-clock' } });
    $('#issuedate').datetimepicker({ format: 'L', icons: { time: 'far fa-clock' } });
    $('#expirydate').datetimepicker({ format: 'L', icons: { time: 'far fa-clock' } });
});
</script>
@endsection