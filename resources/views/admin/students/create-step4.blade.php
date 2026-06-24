@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h4>Please Follow The Steps To Add New Student</h4>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="d-flex align-items-center justify-content-center">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #28a745;"></div>
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #28a745;"></div>
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #28a745;"></div>
                <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <span>4</span>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #ddd;"></div>
                <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 1px solid #ddd;">
                    <span>5</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card"> 
                <div class="card-header">
                    <h3 class="card-title">Education information</h3>
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Education Information</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.students.store.step4') }}" method="POST">
                                @csrf
                                
                                <!-- Applying Degree Section -->
                                <div class="form-section mb-4">
                                    <h6 class="section-title border-bottom pb-2 mb-3">Degree Information</h6>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="applying_degree_id">
                                                    <strong>What is the degree you are applying to?</strong>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control select2 @error('applying_degree_id') is-invalid @enderror" 
                                                        id="applying_degree_id" 
                                                        name="applying_degree_id"
                                                        required>
                                                    <option value="">Please Select</option>
                                                    @foreach($degrees as $degree)
                                                    <option value="{{$degree->id}}">
                                                        {{$degree->name}}
                                                    </option>
                                                  
                                                    @endforeach
                                                    </option>
                                                </select>
                                                @error('applying_degree_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- High School Information Section -->
                                <div class="form-section mb-4">
                                    <h6 class="section-title border-bottom pb-2 mb-3">High School Information</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="high_school_name">
                                                    <strong>High School Name</strong>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       class="form-control @error('high_school_name') is-invalid @enderror" 
                                                       id="high_school_name" 
                                                       name="high_school_name" 
                                                       value="{{ old('high_school_name', session('student_data.education.high_school_name')) }}"
                                                       placeholder="Enter high school name"
                                                       required>
                                                @error('high_school_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="high_school_country_id">
                                                    <strong>High School Country</strong>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control select2 @error('high_school_country_id') is-invalid @enderror" 
                                                        id="high_school_country_id" 
                                                        name="high_school_country_id"
                                                        required>
                                                    <option value="">Please Select</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" 
                                                                {{ old('high_school_country_id', session('student_data.education.high_school_country_id')) == $country->id ? 'selected' : '' }}>
                                                            {{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('high_school_country_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gpa">
                                                    <strong>Grade (GPA)</strong>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="text" 
                                                           class="form-control @error('gpa') is-invalid @enderror" 
                                                           id="gpa" 
                                                           name="gpa" 
                                                           value="{{ old('gpa', session('student_data.education.gpa')) }}"
                                                           placeholder="Enter the student GPA"
                                                           required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-calculator"></i>
                                                        </span>
                                                    </div>
                                                    @error('gpa')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <small class="form-text text-muted">
                                                    Examples: 80%, 2.6, 12/20
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Form Navigation -->
                                <div class="form-navigation mt-4">
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-between">
                                            <a href="{{ route('admin.students.create.step3') }}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left mr-1"></i> Previous
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                Next <i class="fas fa-arrow-right ml-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Custom CSS -->
                    <style>
                        .section-title {
                            color: #495057;
                            font-weight: 600;
                        }
                        
                        .form-section {
                            background-color: #f8f9fa;
                            padding: 15px;
                            border-radius: 5px;
                        }
                        
                        .form-navigation {
                            border-top: 1px solid #dee2e6;
                            padding-top: 20px;
                        }
                        
                        /* Fix for select2 validation styling */
                        .select2-container--default .select2-selection--single {
                            height: calc(1.5em + 0.75rem + 2px);
                            padding: 0.375rem 0.75rem;
                            border: 1px solid #ced4da;
                        }

                        .is-invalid + .select2-container--default .select2-selection--single {
                            border-color: #dc3545;
                        }

                        /* ── dark mode ── */
                        body.dark-mode .form-section {
                            background-color: #0d1e38 !important;
                        }
                        body.dark-mode .section-title {
                            color: #90a4c8 !important;
                            border-color: rgba(255,255,255,.08) !important;
                        }
                        body.dark-mode .form-navigation {
                            border-color: rgba(255,255,255,.08) !important;
                        }
                        /* inactive step bubbles (bg-light text-dark) */
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
                    
                    <!-- Initialize Select2 -->
                    <script>
                        $(document).ready(function() {
                            $('.select2').select2({
                                theme: 'bootstrap4',
                                width: '100%'
                            });
                            
                            // Re-validate on select2 change
                            $('.select2').on('change', function() {
                                $(this).removeClass('is-invalid');
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(function() {
    // Initialize Select2 Elements
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
});
</script>
@endsection