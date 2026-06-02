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
                    <!-- Applications Table -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Applications List</h6>
                        <span class="badge badge-info">{{ $applications->total() }} results found</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="applications-table" width="100%" cellspacing="0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Student</th>
                                    <th>University</th>
                                    <th>Department</th>
                                    <th>Degree</th>
                                    <th>Language</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Creator</th>
                                    <th>Register</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                <tr>
                                    <td>{{ $application->id }}</td>
                                    <td>{{ $application->code }}</td>
                                    <td>
                                        <a href="{{ route('admin.students.show', $application->student_id) }}" class="font-weight-bold text-primary">
                                            {{ $application->student->first_name }} {{ $application->student->last_name }}
                                        </a>
                                    </td>
                                    <td>{{ $application->university->name }}</td>
                                    <td>{{ $application->department }}</td>
                                    <td>{{ $application->degree }}</td>
                                    <td>{{ $application->language }}</td>
                                    <td class="status-cell">
                                        @include('partials.status-badge', ['status' => $application->status])
                                    </td>
                                    <td>{{ $application->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($application->creator)
                                        {{ $application->creator->name }}
                                        @else
                                        SELF Student

                                        @endif
                                    
                                    </td>
                                    <td>
                                        @if($application->files->count() > 0)
                                            {{ $application->files->first()->uploader->name }}
                                        @else
                                            Not Yet
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.applications.edit', $application) }}" class="btn btn-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register')

<!-- Individual delete form for each application -->
<form method="POST" action="{{ route('admin.applications.destroy', $application->id) }}" 
    style="display: inline-block;"
    onsubmit="return confirm('Are you sure you want to delete this application? This action cannot be undone.');">
  @csrf
  @method('DELETE')
  <button type="submit" class="btn btn-danger btn-sm" title="Delete">
      <i class="fas fa-trash"></i>
  </button>
</form>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No applications found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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