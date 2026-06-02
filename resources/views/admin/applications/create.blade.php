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

@endsection

@section('additional_js')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(function() {
    $('#student_id, #university_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Select an option',
        allowClear: true,
        width: '100%'
    });

    var BASE    = '{{ url("admin/programs/by-university") }}';
    var oldDept = '{{ old("department") }}';
    var oldLang = '{{ old("language") }}';
    var oldDeg  = '{{ old("degree") }}';

    function setMsg(msg) {
        $('#department, #language, #degree')
            .html('<option value="">' + msg + '</option>')
            .prop('disabled', true);
    }

    function load(uniId, dept, lang, deg) {
        setMsg('Loading...');
        $.getJSON(BASE + '/' + uniId)
            .done(function(data) {
                var d = '<option value="">Select Department</option>';
                $.each(data.departments, function(i, v) {
                    d += '<option value="' + v + '"' + (v === dept ? ' selected' : '') + '>' + v + '</option>';
                });
                var l = '<option value="">Select Language</option>';
                $.each(data.languages, function(i, v) {
                    l += '<option value="' + v + '"' + (v === lang ? ' selected' : '') + '>' + v + '</option>';
                });
                var g = '<option value="">Select Degree</option>';
                $.each(data.degrees, function(i, v) {
                    g += '<option value="' + v + '"' + (v === deg ? ' selected' : '') + '>' + v + '</option>';
                });
                $('#department').html(d).prop('disabled', false);
                $('#language').html(l).prop('disabled', false);
                $('#degree').html(g).prop('disabled', false);
            })
            .fail(function(xhr) {
                setMsg('Error — try again');
                console.error(xhr.status, xhr.responseText);
            });
    }

    $('#university_id').on('change', function() {
        var id = $(this).val();
        id ? load(id, '', '', '') : setMsg('Select University first');
    });

    var oldUni = '{{ old("university_id") }}';
    if (oldUni) {
        load(oldUni, oldDept, oldLang, oldDeg);
    } else {
        setMsg('Select University first');
    }
});
</script>
@endsection