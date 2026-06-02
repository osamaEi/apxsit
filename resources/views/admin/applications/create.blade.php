@extends('admin.index')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Create New Application</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Application Details</h6>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('admin.applications.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="student_id">Student *</label>
                            <select class="form-control select2" id="student_id" name="student_id" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->first_name }}  {{ $student->last_name }}
                                </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="university_id">University *</label>
                            <select class="form-control select2" id="university_id" name="university_id" required>
                                <option value="">Select University</option>
                                @foreach($universities as $university)
                                <option value="{{ $university->id }}" {{ old('university_id') == $university->id ? 'selected' : '' }}>
                                    {{ $university->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('university_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="department">Department *</label>
                            <select class="form-control" id="department" name="department" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->name }}" {{ old('department') == $department ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('department')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="code">Application Code</label>
                            <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" placeholder="Enter application code">
                            @error('code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="degree">Degree *</label>
                            <select class="form-control" id="degree" name="degree" required>
                                <option value="">Select Degree</option>
                                @foreach($degrees as $degree)
                                <option value="{{ $degree->name }}" {{ old('degree') == $degree ? 'selected' : '' }}>
                                    {{ $degree->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('degree')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="language">Language *</label>
                            <select class="form-control" id="language" name="language" required>
                                <option value="">Select Language</option>
                                @foreach($languages as $language)
                                <option value="{{ $language }}" {{ old('language') == $language ? 'selected' : '' }}>
                                    {{ $language }}
                                </option>
                                @endforeach
                            </select>
                            @error('language')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="semester">Semester *</label>
                            <select class="form-control" id="semester" name="semester" required>
                                <option value="">Select Semester</option>
                                @foreach($semesters as $semester)
                                <option value="{{ $semester }}" {{ old('semester') == $semester ? 'selected' : '' }}>
                                    {{ $semester }}
                                </option>
                                @endforeach
                            </select>
                            @error('semester')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>
                
                <input type="hidden" name="status" value="Pending Review">
                <input type="hidden" name="created_by" value="{{ auth()->id() }}">
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Submit Application
                    </button>
                    <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem + 2px);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });
        
        // University-department relationship
        $('#university_id').change(function() {
            var universityId = $(this).val();
            $('#department_id').empty().append('<option value="">Select Department</option>');
            
            if (universityId) {
                $.get('/api/universities/' + universityId + '/departments', function(data) {
                    $.each(data, function(key, department) {
                        $('#department_id').append('<option value="'+ department.id +'">'+ department.name +'</option>');
                    });
                });
            }
        });
    });
</script>
@endsection