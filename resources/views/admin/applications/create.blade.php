@extends('admin.index')

@section('additional_css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
/* ── Section header ── */
.form-section {
    border-left: 3px solid var(--primary, #1a6bff);
    padding-left: 12px;
    margin-bottom: 1.5rem;
}
.form-section h6 {
    font-size: 0.8125rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--primary, #1a6bff);
    margin: 0;
}

/* ── Field labels ── */
.field-label {
    font-size: 0.8125rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #344050;
}
.field-label .req { color: #ef4444; margin-left: 2px; }

/* ── Cascading dependent fields ── */
.dep-field { position: relative; }
.dep-badge {
    position: absolute;
    top: 0; right: 0;
    font-size: 10px;
    font-weight: 600;
    color: #6c757d;
    background: #f1f5f9;
    border-radius: 4px;
    padding: 1px 6px;
    line-height: 20px;
}
select:disabled {
    cursor: not-allowed;
    background-image: none;
}

/* ── Loading spinner overlay on dep fields ── */
.dep-loading::after {
    content: '';
    position: absolute;
    right: 12px; top: 50%;
    transform: translateY(-50%);
    width: 14px; height: 14px;
    border: 2px solid #dee2e6;
    border-top-color: #1a6bff;
    border-radius: 50%;
    animation: spin .6s linear infinite;
}
@keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }

/* ── Notes ── */
textarea.form-control { resize: vertical; min-height: 90px; }

/* ── Divider ── */
.form-divider {
    border: none;
    border-top: 1px solid #e9ecef;
    margin: 1.75rem 0;
}

/* ── Dark mode overrides for this page ── */
body.dark-mode .field-label { color: #a8b8d0; }
body.dark-mode .dep-badge   { background: #0d1e38; color: #6a86aa; }
body.dark-mode .form-divider { border-top-color: rgba(255,255,255,0.08); }
body.dark-mode .form-section h6 { color: #4d8eff; }
body.dark-mode .form-section { border-left-color: #4d8eff; }

/* Select2 dark-mode fixes */
body.dark-mode .select2-container--bootstrap4 .select2-selection--single,
body.dark-mode .select2-container--bootstrap4 .select2-selection--multiple {
    background-color: #0a1628 !important;
    border-color: rgba(255,255,255,0.15) !important;
    color: #d0d8e8 !important;
}
body.dark-mode .select2-container--bootstrap4 .select2-selection__rendered,
body.dark-mode .select2-container--bootstrap4 .select2-selection__placeholder {
    color: #d0d8e8 !important;
}
body.dark-mode .select2-container--bootstrap4 .select2-selection__arrow b {
    border-top-color: #6a86aa !important;
}
</style>
@endsection

@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0 font-weight-bold">
                <i class="fas fa-file-alt mr-2 text-primary"></i>New Application
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 bg-transparent" style="font-size:12px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.applications.index') }}">Applications</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.applications.store') }}" enctype="multipart/form-data" id="appForm">
        @csrf
        <input type="hidden" name="status" value="Pending Review">
        <input type="hidden" name="created_by" value="{{ auth()->id() }}">

        <div class="row">

            {{-- ── Left column ── --}}
            <div class="col-lg-8">

                {{-- Student & University card --}}
                <div class="card card-primary card-outline mb-3">
                    <div class="card-body pb-2">

                        <div class="form-section"><h6><i class="fas fa-user mr-1"></i> Applicant</h6></div>

                        <div class="form-group">
                            <label class="field-label" for="student_id">Student <span class="req">*</span></label>
                            <select class="form-control select2bs4 @error('student_id') is-invalid @enderror"
                                    id="student_id" name="student_id" required>
                                <option value="">— Select student —</option>
                                @foreach($students as $s)
                                <option value="{{ $s->id }}" {{ old('student_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->first_name }} {{ $s->last_name }}
                                </option>
                                @endforeach
                            </select>
                            @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <hr class="form-divider">
                        <div class="form-section"><h6><i class="fas fa-university mr-1"></i> Program</h6></div>

                        {{-- University --}}
                        <div class="form-group">
                            <label class="field-label" for="university_id">University <span class="req">*</span></label>
                            <select class="form-control select2bs4 @error('university_id') is-invalid @enderror"
                                    id="university_id" name="university_id" required>
                                <option value="">— Select university —</option>
                                @foreach($universities as $u)
                                <option value="{{ $u->id }}" {{ old('university_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('university_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Dependent row: Language / Department / Degree --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group dep-field" id="wrap-language">
                                    <label class="field-label" for="language">
                                        Language <span class="req">*</span>
                                    </label>
                                    <select class="form-control @error('language') is-invalid @enderror"
                                            id="language" name="language" required disabled>
                                        <option value="">Select university first</option>
                                    </select>
                                    @error('language')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group dep-field" id="wrap-department">
                                    <label class="field-label" for="department">
                                        Department <span class="req">*</span>
                                    </label>
                                    <select class="form-control @error('department') is-invalid @enderror"
                                            id="department" name="department" required disabled>
                                        <option value="">Select language first</option>
                                    </select>
                                    @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group dep-field" id="wrap-degree">
                                    <label class="field-label" for="degree">
                                        Degree <span class="req">*</span>
                                    </label>
                                    <select class="form-control @error('degree') is-invalid @enderror"
                                            id="degree" name="degree" required disabled>
                                        <option value="">Select university first</option>
                                    </select>
                                    @error('degree')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- Semester --}}
                        <div class="form-group">
                            <label class="field-label" for="semester">Semester <span class="req">*</span></label>
                            <select class="form-control @error('semester') is-invalid @enderror"
                                    id="semester" name="semester" required>
                                <option value="">— Select semester —</option>
                                @foreach($semesters as $sem)
                                <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                                @endforeach
                            </select>
                            @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>

                {{-- Notes card --}}
                <div class="card card-primary card-outline mb-3">
                    <div class="card-body">
                        <div class="form-section"><h6><i class="fas fa-sticky-note mr-1"></i> Notes</h6></div>
                        <div class="form-group mb-0">
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="4"
                                      placeholder="Any additional notes about this application...">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Right column ── --}}
            <div class="col-lg-4">

                {{-- Summary / Submit card --}}
                <div class="card card-primary card-outline mb-3">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-paper-plane mr-1"></i> Submit</h6>
                    </div>
                    <div class="card-body">


                        <div class="alert alert-info py-2 px-3" style="font-size:12px;">
                            <i class="fas fa-info-circle mr-1"></i>
                            Status will be set to <strong>Pending Review</strong> automatically.
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Submit Application
                        </button>
                        <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary btn-block mt-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                </div>

                {{-- Help card --}}
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-muted"><i class="fas fa-question-circle mr-1"></i> How it works</h6>
                    </div>
                    <div class="card-body p-3" style="font-size:12px; line-height:1.8;">
                        <div class="d-flex align-items-start mb-2">
                            <span class="badge badge-primary mr-2 mt-1" style="min-width:18px;">1</span>
                            Select the <strong>student</strong> applying
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <span class="badge badge-primary mr-2 mt-1" style="min-width:18px;">2</span>
                            Choose a <strong>university</strong> — language, department &amp; degree will load automatically
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <span class="badge badge-primary mr-2 mt-1" style="min-width:18px;">3</span>
                            Pick <strong>language</strong> first, then department will filter to match
                        </div>
                        <div class="d-flex align-items-start">
                            <span class="badge badge-primary mr-2 mt-1" style="min-width:18px;">4</span>
                            Select <strong>semester</strong> &amp; submit
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@section('additional_js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(function () {

    // Init Select2 on student & university
    $('#student_id, #university_id').select2({
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%'
    });

    var BASE     = '{{ url("admin/programs/by-university") }}';
    var BASE_DEP = '{{ url("admin/programs/departments-by-language") }}';
    var oldUni   = '{{ old("university_id") }}';
    var oldLang  = '{{ old("language") }}';
    var oldDept  = '{{ old("department") }}';
    var oldDeg   = '{{ old("degree") }}';

    // ── helpers ────────────────────────────────────────────────────
    function disableDep(selector, msg) {
        $(selector).html('<option value="">' + msg + '</option>').prop('disabled', true);
    }

    function buildOptions(arr, placeholder, selected) {
        var html = '<option value="">' + placeholder + '</option>';
        $.each(arr, function (i, v) {
            html += '<option value="' + v + '"' + (v === selected ? ' selected' : '') + '>' + v + '</option>';
        });
        return html;
    }

    function setLoading(wrap) { $('#' + wrap).addClass('dep-loading'); }
    function clearLoading(wrap) { $('#' + wrap).removeClass('dep-loading'); }

    // ── Step 1: university → languages + degrees ───────────────────
    function loadByUniversity(uniId, selLang, selDept, selDeg) {
        disableDep('#language', 'Loading…');
        disableDep('#department', 'Select language first');
        disableDep('#degree', 'Loading…');
        setLoading('wrap-language');
        setLoading('wrap-degree');

        $.getJSON(BASE + '/' + uniId)
            .done(function (data) {
                clearLoading('wrap-language');
                clearLoading('wrap-degree');

                $('#language')
                    .html(buildOptions(data.languages, '— Select language —', selLang))
                    .prop('disabled', data.languages.length === 0);

                $('#degree')
                    .html(buildOptions(data.degrees, '— Select degree —', selDeg))
                    .prop('disabled', data.degrees.length === 0);

                // if restoring old value, also load departments+degrees by language
                if (selLang) loadByLanguage(uniId, selLang, selDept, selDeg);
            })
            .fail(function () {
                clearLoading('wrap-language');
                clearLoading('wrap-degree');
                disableDep('#language', 'Error — try again');
                disableDep('#degree', 'Error — try again');
            });
    }

    // ── Step 2: language → departments + degrees ──────────────────
    function loadByLanguage(uniId, lang, selDept, selDeg) {
        disableDep('#department', 'Loading…');
        disableDep('#degree',     'Loading…');
        setLoading('wrap-department');
        setLoading('wrap-degree');

        // load departments filtered by university+language
        var deptReq = $.getJSON(BASE_DEP + '/' + uniId + '/' + encodeURIComponent(lang));
        // load degrees filtered by university+language
        var degReq  = $.getJSON(BASE + '/' + uniId + '?language=' + encodeURIComponent(lang));

        $.when(deptReq, degReq)
            .done(function (deptData, degData) {
                clearLoading('wrap-department');
                clearLoading('wrap-degree');

                var depts   = deptData[0].departments  || [];
                var degrees = degData[0].degrees        || [];

                if (depts.length === 0) {
                    $('#department').html('<option value="">No departments for this language</option>').prop('disabled', true);
                } else {
                    $('#department').html(buildOptions(depts, '— Select department —', selDept)).prop('disabled', false);
                }

                if (degrees.length === 0) {
                    $('#degree').html('<option value="">No degrees for this language</option>').prop('disabled', true);
                } else {
                    $('#degree').html(buildOptions(degrees, '— Select degree —', selDeg)).prop('disabled', false);
                }
            })
            .fail(function () {
                clearLoading('wrap-department');
                clearLoading('wrap-degree');
                disableDep('#department', 'Error — try again');
                disableDep('#degree',     'Error — try again');
            });
    }

    // ── Events ─────────────────────────────────────────────────────
    $('#university_id').on('select2:select select2:unselect change', function () {
        var id = $(this).val();
        if (id) {
            loadByUniversity(id, '', '', '');
        } else {
            disableDep('#language',   'Select university first');
            disableDep('#department', 'Select language first');
            disableDep('#degree',     'Select university first');
        }
    });

    $('#language').on('change', function () {
        var uniId = $('#university_id').val();
        var lang  = $(this).val();
        if (uniId && lang) {
            loadByLanguage(uniId, lang, '', '');
        } else {
            disableDep('#department', 'Select language first');
            disableDep('#degree',     'Select university first');
        }
    });

    // Re-enable disabled selects before submit so values are posted
    $('#appForm').on('submit', function () {
        $('#language, #department, #degree').prop('disabled', false);
    });

    // ── Restore old values on validation fail ──────────────────────
    if (oldUni) {
        loadByUniversity(oldUni, oldLang, oldDept, oldDeg);
    } else {
        disableDep('#language',   'Select university first');
        disableDep('#department', 'Select language first');
        disableDep('#degree',     'Select university first');
    }
});
</script>
@endsection
