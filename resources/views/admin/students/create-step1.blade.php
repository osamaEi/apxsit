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
                <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <span>1</span>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #ffc107;"></div>
                <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 1px solid #ddd;">
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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Basic Information</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.students.store.step1') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Academic Year -->
                                <div class="form-group">
                                    <label for="academic_year">Academic Year <span class="text-danger">*</span></label>
                                    <select class="form-control @error('academic_year') is-invalid @enderror" id="academic_year" name="academic_year" required>
                                        <option value="">Please Select</option>
                                        @php
                                            $currentYear = date('Y');
                                            $nextYear = $currentYear + 1;
                                        @endphp
                                        <option value="{{ $currentYear }}-{{ $nextYear }}" {{ old('academic_year', session('student_data.basic.academic_year')) == "$currentYear-$nextYear" ? 'selected' : '' }}>
                                            {{ $currentYear }}-{{ $nextYear }}
                                        </option>
                                        <option value="{{ $nextYear }}-{{ $nextYear + 1 }}" {{ old('academic_year', session('student_data.basic.academic_year')) == "$nextYear-" . ($nextYear + 1) ? 'selected' : '' }}>
                                            {{ $nextYear }}-{{ $nextYear + 1 }}
                                        </option>
                                    </select>
                                    @error('academic_year')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">

                            


                                <div class="form-group">
                                    <label for="study_country_id">Study Country <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('study_country_id') is-invalid @enderror" id="study_country_id" name="study_country_id" required>
                                        <option value="">Please Select</option>
                                        @foreach($studycountries as $country)
                                            <option value="{{ $country->id }}" {{ old('study_country_id', session('student_data.basic.study_country_id')) == $country->id ? 'selected' : '' }}>
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
                                <!-- Is Transfer Student -->
                                <div class="form-group">
                                    <label for="is_transfer">Is This a Transfer Student? <span class="text-danger">*</span></label>
                                    <select class="form-control @error('is_transfer') is-invalid @enderror" id="is_transfer" name="is_transfer">
                                        <option value="0" {{ old('is_transfer', session('student_data.basic.is_transfer')) == '0' ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('is_transfer', session('student_data.basic.is_transfer')) == '1' ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    @error('is_transfer')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Previous</a>
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
</style>
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