@extends('admin.index') 

@section('title', 'Admission Applications')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admission Applications</h1>
        <div>
            <a href="{{ route('admin.applications.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create New
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Applications List</h6>
                </div>

                <div class="card-body">

                    @php
                        $fUniversity = request('university_id', []);
                        $fDepartment = request('department', []);
                        $fDegree     = request('degree', []);
                        $fStudent    = request('student_id');
                        $fStatus     = request('status');
                        $hasFilter   = $fStudent || !empty($fUniversity) || !empty($fDepartment) || !empty($fDegree) || $fStatus;
                    @endphp

                    <style>
                    .fd-wrap { position:relative; }
                    .fd-btn {
                        display:flex; align-items:center; justify-content:space-between;
                        height:38px; padding:0 12px; border:1px solid #ced4da; border-radius:4px;
                        background:#fff; cursor:pointer; font-size:.875rem; color:#495057;
                        white-space:nowrap; overflow:hidden; user-select:none; gap:6px; width:100%;
                    }
                    .fd-btn:hover { border-color:#adb5bd; background:#f8f9fa; }
                    .fd-btn .fd-label { overflow:hidden; text-overflow:ellipsis; flex:1; text-align:left; }
                    .fd-btn .fd-caret { font-size:10px; color:#888; flex-shrink:0; }
                    .fd-btn.has-value { border-color:#4e73df; color:#4e73df; background:#f0f3ff; }
                    .fd-panel {
                        display:none; position:absolute; top:calc(100% + 4px); left:0;
                        min-width:100%; background:#fff; border:1px solid #ced4da;
                        border-radius:6px; box-shadow:0 4px 16px rgba(0,0,0,.12);
                        z-index:9999; padding:8px 0;
                    }
                    .fd-panel.open { display:block; }
                    .fd-search {
                        margin:0 8px 6px; padding:5px 8px; border:1px solid #dee2e6;
                        border-radius:4px; font-size:.8rem; width:calc(100% - 16px); box-sizing:border-box;
                    }
                    .fd-list { max-height:200px; overflow-y:auto; }
                    .fd-item { display:flex; align-items:center; padding:5px 12px; cursor:pointer; font-size:.85rem; gap:8px; }
                    .fd-item:hover { background:#f8f9fa; }
                    .fd-item input { accent-color:#4e73df; flex-shrink:0; cursor:pointer; }
                    .fd-item.checked { background:#f0f3ff; }
                    .fd-divider { border:none; border-top:1px solid #f0f0f0; margin:4px 0; }
                    </style>

                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.applications.index') }}" id="filterForm" class="mb-4">
                        <div class="row align-items-end">

                            {{-- Student --}}
                            <div class="col-lg-3 col-md-6 mb-2">
                                <label class="small font-weight-bold mb-1">Student</label>
                                <select name="student_id" id="studentSelect" class="form-control" style="height:38px;">
                                    <option value="">All Students</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ $fStudent == $student->id ? 'selected' : '' }}>
                                            {{ $student->first_name }} {{ $student->last_name }} (#{{ $student->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- University --}}
                            <div class="col-lg-3 col-md-6 mb-2 fd-wrap" id="wrap-university">
                                <label class="small font-weight-bold mb-1">University</label>
                                <div class="fd-btn {{ !empty($fUniversity) ? 'has-value' : '' }}" onclick="toggleFd('fd-university')">
                                    <span class="fd-label" id="lbl-university">
                                        {{ !empty($fUniversity) ? count($fUniversity).' selected' : 'All Universities' }}
                                    </span>
                                    <i class="fas fa-chevron-down fd-caret"></i>
                                </div>
                                <div class="fd-panel" id="fd-university">
                                    <input class="fd-search" placeholder="Search..." oninput="fdSearch(this,'fd-university')">
                                    <label class="fd-item" style="font-weight:600; font-size:.8rem; color:#888;">
                                        <input type="checkbox" onchange="fdSelectAll(this,'university_id[]','lbl-university','All Universities')"> Select All
                                    </label>
                                    <hr class="fd-divider">
                                    <div class="fd-list">
                                        @foreach($universities as $uni)
                                        <label class="fd-item {{ in_array($uni->id, $fUniversity) ? 'checked' : '' }}">
                                            <input type="checkbox" name="university_id[]" value="{{ $uni->id }}"
                                                {{ in_array($uni->id, $fUniversity) ? 'checked' : '' }}
                                                onchange="fdUpdate('fd-university','lbl-university','All Universities')">
                                            {{ $uni->name }}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Department --}}
                            <div class="col-lg-2 col-md-4 mb-2 fd-wrap" id="wrap-department">
                                <label class="small font-weight-bold mb-1">Department</label>
                                <div class="fd-btn {{ !empty($fDepartment) ? 'has-value' : '' }}" onclick="toggleFd('fd-department')">
                                    <span class="fd-label" id="lbl-department">
                                        {{ !empty($fDepartment) ? count($fDepartment).' selected' : 'All Departments' }}
                                    </span>
                                    <i class="fas fa-chevron-down fd-caret"></i>
                                </div>
                                <div class="fd-panel" id="fd-department">
                                    <input class="fd-search" placeholder="Search..." oninput="fdSearch(this,'fd-department')">
                                    <label class="fd-item" style="font-weight:600; font-size:.8rem; color:#888;">
                                        <input type="checkbox" onchange="fdSelectAll(this,'department[]','lbl-department','All Departments')"> Select All
                                    </label>
                                    <hr class="fd-divider">
                                    <div class="fd-list">
                                        @foreach($departments as $dept)
                                        <label class="fd-item {{ in_array($dept->name, $fDepartment) ? 'checked' : '' }}">
                                            <input type="checkbox" name="department[]" value="{{ $dept->name }}"
                                                {{ in_array($dept->name, $fDepartment) ? 'checked' : '' }}
                                                onchange="fdUpdate('fd-department','lbl-department','All Departments')">
                                            {{ $dept->name }}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Degree --}}
                            <div class="col-lg-1 col-md-4 mb-2 fd-wrap" id="wrap-degree">
                                <label class="small font-weight-bold mb-1">Degree</label>
                                <div class="fd-btn {{ !empty($fDegree) ? 'has-value' : '' }}" onclick="toggleFd('fd-degree')">
                                    <span class="fd-label" id="lbl-degree">
                                        {{ !empty($fDegree) ? count($fDegree).' selected' : 'All Degrees' }}
                                    </span>
                                    <i class="fas fa-chevron-down fd-caret"></i>
                                </div>
                                <div class="fd-panel" id="fd-degree">
                                    <div class="fd-list">
                                        @foreach($degrees as $deg)
                                        <label class="fd-item {{ in_array($deg->name, $fDegree) ? 'checked' : '' }}">
                                            <input type="checkbox" name="degree[]" value="{{ $deg->name }}"
                                                {{ in_array($deg->name, $fDegree) ? 'checked' : '' }}
                                                onchange="fdUpdate('fd-degree','lbl-degree','All Degrees')">
                                            {{ $deg->name }}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-lg-1 col-md-4 mb-2">
                                <label class="small font-weight-bold mb-1">Status</label>
                                <select name="status" class="form-control" style="height:38px; font-size:.875rem;">
                                    <option value="">All</option>
                                    @foreach(App\Models\Application::STATUSES as $sk => $sv)
                                        <option value="{{ $sk }}" {{ $fStatus == $sk ? 'selected' : '' }}>{{ $sv }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="col-lg-2 col-md-12 mb-2">
                                <label class="small font-weight-bold mb-1 d-block">&nbsp;</label>
                                <div class="d-flex" style="gap:6px; flex-wrap:wrap;">
                                    <button type="submit" class="btn btn-primary btn-sm" style="height:38px;">
                                        <i class="fas fa-search mr-1"></i> Search
                                    </button>
                                    <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary btn-sm" style="height:38px;">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                    <a href="{{ route('admin.applications.export-excel', request()->all()) }}" class="btn btn-success btn-sm" style="height:38px;" title="Export Excel">
                                        <i class="fas fa-file-excel"></i>
                                    </a>
                                    <a href="{{ route('admin.applications.export-pdf', request()->all()) }}" class="btn btn-danger btn-sm" style="height:38px;" title="Export PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Active filter tags --}}
                        @if($hasFilter)
                        <div class="d-flex flex-wrap align-items-center mt-2" style="gap:6px;">
                            <small class="text-muted">Active:</small>
                            @if($fStudent)
                                @php $st = $students->firstWhere('id', $fStudent); @endphp
                                @if($st) <span class="badge badge-primary">{{ $st->first_name }} {{ $st->last_name }}</span> @endif
                            @endif
                            @foreach($universities->whereIn('id', $fUniversity) as $u)
                                <span class="badge badge-info">{{ $u->name }}</span>
                            @endforeach
                            @foreach($fDepartment as $d)
                                <span class="badge badge-secondary">{{ $d }}</span>
                            @endforeach
                            @foreach($fDegree as $d)
                                <span class="badge badge-success">{{ $d }}</span>
                            @endforeach
                            @if($fStatus)
                                <span class="badge badge-warning">{{ App\Models\Application::STATUSES[$fStatus] ?? $fStatus }}</span>
                            @endif
                            <a href="{{ route('admin.applications.index') }}" class="text-danger" style="font-size:.8rem;">
                                <i class="fas fa-times-circle"></i> Clear all
                            </a>
                        </div>
                        @endif
                    </form>

                    <script>
                    // Close panels on outside click
                    document.addEventListener('click', function(e) {
                        if (!e.target.closest('.fd-wrap')) {
                            document.querySelectorAll('.fd-panel.open').forEach(p => p.classList.remove('open'));
                        }
                    });
                    function toggleFd(id) {
                        const p = document.getElementById(id);
                        const was = p.classList.contains('open');
                        document.querySelectorAll('.fd-panel.open').forEach(x => x.classList.remove('open'));
                        if (!was) p.classList.add('open');
                    }
                    function fdSearch(input, panelId) {
                        const q = input.value.toLowerCase();
                        document.querySelectorAll('#' + panelId + ' .fd-list .fd-item').forEach(item => {
                            item.style.display = item.textContent.toLowerCase().includes(q) ? '' : 'none';
                        });
                    }
                    function fdUpdate(panelId, lblId, placeholder) {
                        const checked = document.querySelectorAll('#' + panelId + ' input[type=checkbox]:checked:not([onchange*="SelectAll"])');
                        const lbl = document.getElementById(lblId);
                        const btn = document.getElementById(panelId).previousElementSibling;
                        if (checked.length === 0) {
                            lbl.textContent = placeholder;
                            btn.classList.remove('has-value');
                        } else {
                            lbl.textContent = checked.length + ' selected';
                            btn.classList.add('has-value');
                        }
                        document.querySelectorAll('#' + panelId + ' .fd-list .fd-item').forEach(item => {
                            item.classList.toggle('checked', item.querySelector('input') && item.querySelector('input').checked);
                        });
                    }
                    function fdSelectAll(cb, name, lblId, placeholder) {
                        const panel = cb.closest('.fd-panel');
                        panel.querySelectorAll('input[name="' + name + '"]').forEach(c => c.checked = cb.checked);
                        fdUpdate(panel.id, lblId, placeholder);
                    }
                    </script>
                    <style>
                    .app-table-wrap { border-radius:12px; overflow:hidden; box-shadow:0 2px 16px rgba(0,0,0,.08); margin-top:12px; }
                    .app-table { width:100%; border-collapse:collapse; font-size:13px; }
                    .app-table thead tr th {
                        background: linear-gradient(135deg,#1a6bff 0%,#0a3d99 100%);
                        color:#fff;
                        font-weight:700;
                        font-size:11px;
                        text-transform:uppercase;
                        letter-spacing:.08em;
                        padding:14px 16px;
                        white-space:nowrap;
                        border:none;
                        border-right:1px solid rgba(255,255,255,.1);
                        position:relative;
                        vertical-align:middle;
                    }
                    .app-table thead tr th:last-child { border-right:none; }
                    .app-table thead tr th i {
                        display:block;
                        font-size:15px;
                        margin-bottom:4px;
                        opacity:.75;
                    }
                    .app-table thead tr { box-shadow:0 3px 8px rgba(10,61,153,.25); }
                    .app-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background .12s; }
                    .app-table tbody tr:hover { background:#f0f5ff; }
                    .app-table tbody tr:last-child { border-bottom:none; }
                    .app-table td { padding:10px 12px; vertical-align:middle; color:#374151; border:none; }
                    .app-table td.actions-td { white-space:nowrap; }
                    .app-av {
                        width:38px;height:38px;border-radius:50%;object-fit:cover;
                        border:2px solid #e0e7ff;
                    }
                    .app-av-init {
                        width:38px;height:38px;border-radius:50%;
                        background:linear-gradient(135deg,#1a6bff,#0a3d99);
                        display:inline-flex;align-items:center;justify-content:center;
                        color:#fff;font-size:13px;font-weight:700;
                        border:2px solid #e0e7ff;
                    }
                    .app-name { font-weight:600; color:#1a6bff; text-decoration:none; }
                    .app-name:hover { text-decoration:underline; }
                    .app-muted { color:#9ca3af; font-size:12px; }
                    .app-pill {
                        display:inline-block; padding:3px 10px; border-radius:20px;
                        font-size:11px; font-weight:600; white-space:nowrap;
                    }
                    .app-pill-blue  { background:#eff6ff; color:#1d4ed8; }
                    .app-pill-gray  { background:#f3f4f6; color:#6b7280; }
                    .app-pill-green { background:#ecfdf5; color:#059669; }
                    .app-btn { width:28px;height:28px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;font-size:12px;border:none;cursor:pointer;transition:opacity .15s; }
                    .app-btn:hover { opacity:.8; }
                    .app-btn-view   { background:#e0f2fe; color:#0284c7; }
                    .app-btn-edit   { background:#e0e7ff; color:#4f46e5; }
                    .app-btn-del    { background:#fee2e2; color:#dc2626; }
                    .app-tbl-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:4px; }
                    .app-count-badge { background:#1a6bff; color:#fff; font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px; }
                    </style>

                    <div class="app-tbl-header">
                        <span style="font-size:13px;font-weight:700;color:#374151;">
                            <i class="fas fa-list-alt mr-1" style="color:#1a6bff"></i> Applications List
                        </span>
                        <span class="app-count-badge">{{ $applications->total() }} results</span>
                    </div>

                    <div class="app-table-wrap">
                    <div class="table-responsive" style="margin:0">
                        <table class="app-table" id="applications-table">
                            <thead>
                                <tr>
                                    <th style="width:90px;text-align:center"><i class="fas fa-cogs"></i>Actions</th>
                                    <th style="text-align:center"><i class="fas fa-user-circle"></i>Photo</th>
                                    <th><i class="fas fa-id-badge"></i>Name</th>
                                    <th><i class="fas fa-envelope"></i>E-Mail</th>
                                    <th><i class="fas fa-phone"></i>Phone</th>
                                    <th><i class="fas fa-passport"></i>Passport ID</th>
                                    <th><i class="fas fa-birthday-cake"></i>Date Of Birth</th>
                                    <th><i class="fas fa-globe"></i>Nationality</th>
                                    <th><i class="fas fa-university"></i>University</th>
                                    <th><i class="fas fa-sitemap"></i>Department</th>
                                    <th><i class="fas fa-graduation-cap"></i>Degree</th>
                                    <th><i class="fas fa-language"></i>Language</th>
                                    <th><i class="fas fa-traffic-light"></i>Status</th>
                                    <th><i class="fas fa-calendar-alt"></i>Created On</th>
                                    <th><i class="fas fa-user-edit"></i>Created By</th>
                                    <th><i class="fas fa-shield-alt"></i>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                @php $student = $application->student; @endphp
                                <tr>
                                    <td class="actions-td">
                                        <a href="{{ route('admin.applications.show', $application) }}" class="app-btn app-btn-view" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.applications.edit', $application) }}" class="app-btn app-btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                        @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register')
                                        <form method="POST" action="{{ route('admin.applications.destroy', $application->id) }}" style="display:inline" onsubmit="return confirm('Delete this application?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="app-btn app-btn-del" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                        @endif
                                    </td>
                                    <td>
                                        @if($student && $student->photo_path)
                                            <img src="{{ Storage::url($student->photo_path) }}" class="app-av" alt="">
                                        @else
                                            <span class="app-av-init">{{ strtoupper(substr($student->first_name ?? '?', 0, 1)) }}</span>
                                        @endif
                                    </td>
                                    <td style="white-space:nowrap">
                                        <a href="{{ route('admin.students.show', $application->student_id) }}" class="app-name">
                                            {{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}
                                        </a>
                                    </td>
                                    <td class="app-muted">{{ $student->email ?? '—' }}</td>
                                    <td style="white-space:nowrap">{{ $student->phone ?? '—' }}</td>
                                    <td><span class="app-pill app-pill-gray">{{ $student->passport_id ?? '—' }}</span></td>
                                    <td style="white-space:nowrap">{{ $student->date_of_birth ?? '—' }}</td>
                                    <td><span class="app-pill app-pill-blue">{{ $student->nationality->name ?? '—' }}</span></td>
                                    <td style="white-space:nowrap;max-width:160px;overflow:hidden;text-overflow:ellipsis">{{ $application->university->name ?? '—' }}</td>
                                    <td style="white-space:nowrap">{{ $application->department }}</td>
                                    <td><span class="app-pill app-pill-green">{{ $application->degree }}</span></td>
                                    <td>{{ $application->language }}</td>
                                    <td>@include('partials.status-badge', ['status' => $application->status])</td>
                                    <td style="white-space:nowrap">{{ $application->created_at->format('Y-m-d') }}</td>
                                    <td style="white-space:nowrap">{{ $application->creator->name ?? '—' }}</td>
                                    <td>
                                        @if($application->creator)
                                        <span class="app-pill app-pill-gray">{{ $application->creator->role }}</span>
                                        @else —
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="16" class="text-center py-4 app-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity:.3"></i>
                                        No applications found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $applications->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this application? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .status-cell {
        min-width: 160px;
    }
    
    #applications-table .badge {
        font-size: 90%;
    }
    
    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: #4e73df;
        color: white;
        border: none;
        padding: 3px 8px;
        margin: 2px;
    }
    
    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
    }
    
    .select2-container--bootstrap4 .select2-selection--multiple {
        min-height: 38px;
    }
</style>
<script>
$(function() {
    // Student select2
    $('#studentSelect').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'All Students',
        allowClear: true
    });
});
</script>

@endsection