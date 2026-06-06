@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-graduation-cap mr-1"></i>
                        Programs Management
                    </h3>
             <!-- Updated Export Buttons with All Filter Parameters -->
<div class="card-tools">
    <div class="btn-group">
        <a href="{{ route('admin.programs.export-excel', [
            'search' => request('search'),
            'university_id' => request('university_id'),
            'department' => request('department'),
            'degree' => request('degree'),
            'language' => request('language'),
            'shift_type' => request('shift_type'),
            'status' => request('status'),
            'min_price' => request('min_price'),
            'max_price' => request('max_price'),
            'min_discount' => request('min_discount'),
            'max_discount' => request('max_discount')
        ]) }}" class="btn btn-success btn-sm mr-1">
            <i class="fas fa-file-excel mr-1"></i> Export Excel
        </a>
        <a href="{{ route('admin.programs.export-pdf', [
            'search' => request('search'),
            'university_id' => request('university_id'),
            'department' => request('department'),
            'degree' => request('degree'),
            'language' => request('language'),
            'shift_type' => request('shift_type'),
            'status' => request('status'),
            'min_price' => request('min_price'),
            'max_price' => request('max_price'),
            'min_discount' => request('min_discount'),
            'max_discount' => request('max_discount')
        ]) }}" class="btn btn-danger btn-sm mr-1">
            <i class="fas fa-file-pdf mr-1"></i> Export PDF
        </a>
        @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register' )
        <button type="button" class="btn btn-warning btn-sm mr-1" data-toggle="modal" data-target="#importModal">
            <i class="fas fa-file-import mr-1"></i> Import Excel
        </button>
        <a href="{{ route('admin.programs.create') }}" class="btn btn-primary btn-sm mr-1">
            <i class="fas fa-plus mr-1"></i> Add Program
        </a>
        @endif
        @if(auth()->user()->role == 'Admin')
        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteAllModal">
            <i class="fas fa-trash mr-1"></i> Delete All
        </button>
        @endif
    </div>
</div>

<!-- Import Modal -->
@if(auth()->user()->role == 'Admin')
<div class="modal fade" id="deleteAllModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-1"></i> Delete All Programs</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to <strong>delete all {{ $totalPrograms }} programs</strong>?</p>
                <p class="text-danger"><strong>This action cannot be undone.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.programs.destroy-all') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i> Yes, Delete All
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register' )
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fas fa-file-import mr-1"></i> Import Programs from Excel
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.programs.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Required columns (first row = headers):</strong><br>
                        University, Department, Degree, Language, Price Before Discount, Price After Discount,
                        Cash Discount, Deposit Payment, Siblings Discount, Shift Type, Status
                        <br><br>
                        <strong>⚠️ University name must match exactly:</strong><br>
                        <small>{{ $universities->pluck('name')->implode(', ') }}</small>
                        <br><br>
                        <a href="{{ route('admin.programs.import-template') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-download mr-1"></i> Download Template
                        </a>
                    </div>
                    <div class="form-group">
                        <label for="importFile">Excel File <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="importFile" class="form-control-file @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Accepted formats: .xlsx, .xls, .csv — max 10 MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-upload mr-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalPrograms ?? $programs->total() }}</h3>
                                    <p>Total Programs</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $activePrograms ?? $programs->where('status', 'Active')->count() }}</h3>
                                    <p>Active Programs</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $totalUniversities ?? $universities->count() }}</h3>
                                    <p>Universities</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-university"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $totalDepartments ?? $departments->count() }}</h3>
                                    <p>Departments</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-building"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $hasAnyFilter = !empty($universityFilter) || !empty($departmentFilter) || !empty($degreeFilter)
                            || $statusFilter || $languageFilter || $shiftTypeFilter
                            || request('min_price') || request('max_price') || request('min_discount') || request('max_discount')
                            || request('search');
                        $hasRangeFilter = request('min_price') || request('max_price') || request('min_discount') || request('max_discount');
                    @endphp

                    <style>
                    /* ── Filter Bar ─────────────────────────────── */
                    .filter-bar { background:#fff; border:1px solid #dee2e6; border-radius:6px; padding:12px 16px; margin-bottom:16px; }
                    .filter-bar .filter-row { display:flex; flex-wrap:wrap; gap:8px; align-items:flex-end; }

                    /* Custom dropdown */
                    .fd-wrap { position:relative; min-width:0; }
                    .fd-wrap label { display:block; font-size:11px; font-weight:600; color:#555; margin-bottom:3px; white-space:nowrap; }
                    .fd-btn {
                        display:flex; align-items:center; justify-content:space-between;
                        height:31px; padding:0 10px; border:1px solid #ced4da; border-radius:4px;
                        background:#fff; cursor:pointer; font-size:13px; color:#495057;
                        white-space:nowrap; overflow:hidden; user-select:none; gap:6px;
                    }
                    .fd-btn:hover { border-color:#adb5bd; background:#f8f9fa; }
                    .fd-btn .fd-label { overflow:hidden; text-overflow:ellipsis; flex:1; }
                    .fd-btn .fd-caret { font-size:10px; color:#888; flex-shrink:0; }
                    .fd-btn.has-value { border-color:#7a0066cb; color:#7a0066cb; background:#fdf5fc; }

                    .fd-panel {
                        display:none; position:absolute; top:calc(100% + 4px); left:0;
                        min-width:220px; background:#fff; border:1px solid #ced4da;
                        border-radius:6px; box-shadow:0 4px 16px rgba(0,0,0,.12);
                        z-index:1050; padding:8px 0;
                    }
                    .fd-panel.open { display:block; }
                    .fd-search {
                        margin:0 8px 6px; padding:5px 8px; border:1px solid #dee2e6;
                        border-radius:4px; font-size:12px; width:calc(100% - 16px); box-sizing:border-box;
                    }
                    .fd-list { max-height:200px; overflow-y:auto; }
                    .fd-item { display:flex; align-items:center; padding:5px 12px; cursor:pointer; font-size:13px; }
                    .fd-item:hover { background:#f8f9fa; }
                    .fd-item input[type=checkbox] { margin-right:8px; flex-shrink:0; accent-color:#7a0066cb; }
                    .fd-item.active-item { background:#fdf5fc; font-weight:500; }
                    .fd-none { padding:8px 12px; color:#aaa; font-size:12px; font-style:italic; }

                    /* Active filter tags */
                    .active-tags { display:flex; flex-wrap:wrap; gap:4px; align-items:center; margin-top:8px; }
                    .active-tags .tag {
                        display:inline-flex; align-items:center; gap:4px;
                        background:#f0e6f0; color:#7a0066cb; border-radius:20px;
                        padding:2px 10px; font-size:11px; font-weight:500;
                    }
                    .active-tags .tag .tag-x { cursor:pointer; opacity:.6; font-size:10px; }
                    .active-tags .tag .tag-x:hover { opacity:1; }
                    </style>

                    <form action="{{ route('admin.programs.index') }}" method="GET" id="filterForm">
                        <div class="filter-bar">
                            <div class="filter-row">

                                {{-- Search --}}
                                <div class="fd-wrap" style="flex:1.5; min-width:160px;">
                                    <label>Search</label>
                                    <input type="text" name="search" class="form-control form-control-sm"
                                           placeholder="Program or university..." value="{{ request('search') }}"
                                           style="height:31px; font-size:13px;">
                                </div>

                                {{-- University multi-select --}}
                                <div class="fd-wrap" style="flex:1.5; min-width:150px;" id="wrap-university">
                                    <label>University</label>
                                    <div class="fd-btn {{ !empty($universityFilter) ? 'has-value' : '' }}" onclick="togglePanel('panel-university')">
                                        <span class="fd-label" id="lbl-university">
                                            {{ !empty($universityFilter) ? count($universityFilter).' selected' : 'All universities' }}
                                        </span>
                                        <i class="fas fa-chevron-down fd-caret"></i>
                                    </div>
                                    <div class="fd-panel" id="panel-university">
                                        <input class="fd-search" placeholder="Search..." oninput="filterItems(this,'list-university')">
                                        <div class="fd-list" id="list-university">
                                            @foreach($universities as $university)
                                            <label class="fd-item {{ in_array($university->id, $universityFilter ?? []) ? 'active-item' : '' }}">
                                                <input type="checkbox" name="university_id[]" value="{{ $university->id }}"
                                                    {{ in_array($university->id, $universityFilter ?? []) ? 'checked' : '' }}
                                                    onchange="updateLabel('panel-university','lbl-university','All universities')">
                                                {{ $university->name }}
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Department multi-select --}}
                                <div class="fd-wrap" style="flex:1.5; min-width:150px;" id="wrap-department">
                                    <label>Department</label>
                                    <div class="fd-btn {{ !empty($departmentFilter) ? 'has-value' : '' }}" onclick="togglePanel('panel-department')">
                                        <span class="fd-label" id="lbl-department">
                                            {{ !empty($departmentFilter) ? count($departmentFilter).' selected' : 'All departments' }}
                                        </span>
                                        <i class="fas fa-chevron-down fd-caret"></i>
                                    </div>
                                    <div class="fd-panel" id="panel-department">
                                        <input class="fd-search" placeholder="Search..." oninput="filterItems(this,'list-department')">
                                        <div class="fd-list" id="list-department">
                                            @foreach($departments as $dept)
                                            <label class="fd-item {{ in_array($dept, $departmentFilter ?? []) ? 'active-item' : '' }}">
                                                <input type="checkbox" name="department[]" value="{{ $dept }}"
                                                    {{ in_array($dept, $departmentFilter ?? []) ? 'checked' : '' }}
                                                    onchange="updateLabel('panel-department','lbl-department','All departments')">
                                                {{ $dept }}
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Degree multi-select --}}
                                <div class="fd-wrap" style="flex:1; min-width:120px;" id="wrap-degree">
                                    <label>Degree</label>
                                    <div class="fd-btn {{ !empty($degreeFilter) ? 'has-value' : '' }}" onclick="togglePanel('panel-degree')">
                                        <span class="fd-label" id="lbl-degree">
                                            {{ !empty($degreeFilter) ? count($degreeFilter).' selected' : 'All degrees' }}
                                        </span>
                                        <i class="fas fa-chevron-down fd-caret"></i>
                                    </div>
                                    <div class="fd-panel" id="panel-degree">
                                        <div class="fd-list" id="list-degree">
                                            @foreach($degrees as $deg)
                                            <label class="fd-item {{ in_array($deg->name, $degreeFilter ?? []) ? 'active-item' : '' }}">
                                                <input type="checkbox" name="degree[]" value="{{ $deg->name }}"
                                                    {{ in_array($deg->name, $degreeFilter ?? []) ? 'checked' : '' }}
                                                    onchange="updateLabel('panel-degree','lbl-degree','All degrees')">
                                                {{ $deg->name }}
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Language --}}
                                <div class="fd-wrap" style="flex:0.8; min-width:100px;">
                                    <label>Language</label>
                                    <select name="language" class="form-control form-control-sm" style="height:31px; font-size:13px;">
                                        <option value="">All</option>
                                        @foreach(\App\Models\Program::select('language')->distinct()->orderBy('language')->pluck('language') as $lang)
                                            <option value="{{ $lang }}" {{ $languageFilter == $lang ? 'selected' : '' }}>{{ $lang }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Shift --}}
                                <div class="fd-wrap" style="flex:0.8; min-width:90px;">
                                    <label>Shift</label>
                                    <select name="shift_type" class="form-control form-control-sm" style="height:31px; font-size:13px;">
                                        <option value="">All</option>
                                        @foreach(\App\Models\Program::select('shift_type')->distinct()->orderBy('shift_type')->pluck('shift_type') as $shift)
                                            <option value="{{ $shift }}" {{ $shiftTypeFilter == $shift ? 'selected' : '' }}>{{ $shift }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Status --}}
                                <div class="fd-wrap" style="flex:0.8; min-width:90px;">
                                    <label>Status</label>
                                    <select name="status" class="form-control form-control-sm" style="height:31px; font-size:13px;">
                                        <option value="">All</option>
                                        @foreach($statuses as $sKey => $sVal)
                                            <option value="{{ $sKey }}" {{ $statusFilter == $sKey ? 'selected' : '' }}>{{ $sVal }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            {{-- Action buttons row --}}
                            <div class="d-flex align-items-center mt-2" style="gap:6px;">
                                <button type="submit" class="btn btn-primary btn-sm" style="height:31px; white-space:nowrap;">
                                    <i class="fas fa-search mr-1"></i> Search
                                </button>
                                <a href="{{ route('admin.programs.index') }}" class="btn btn-secondary btn-sm" style="height:31px;">
                                    <i class="fas fa-redo mr-1"></i> Reset
                                </a>
                                <button type="button" class="btn btn-outline-secondary btn-sm" style="height:31px;" onclick="toggleRangePanel()" title="Price / Discount range">
                                    <i class="fas fa-sliders-h mr-1"></i> Price / Discount range
                                </button>
                            </div>

                            {{-- Range row --}}
                            <div id="rangePanel" style="{{ $hasRangeFilter ? '' : 'display:none;' }} margin-top:10px; padding-top:10px; border-top:1px solid #f0f0f0;">
                                <div class="row" style="margin-top:10px;">
                                    <div class="col-md-5 col-sm-6 mb-1">
                                        <label class="small font-weight-bold">Price After Discount ($)</label>
                                        <div class="d-flex" style="gap:6px;">
                                            <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                                            <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-6 mb-1">
                                        <label class="small font-weight-bold">Discount (%)</label>
                                        <div class="d-flex" style="gap:6px;">
                                            <input type="number" name="min_discount" class="form-control form-control-sm" placeholder="Min %" value="{{ request('min_discount') }}">
                                            <input type="number" name="max_discount" class="form-control form-control-sm" placeholder="Max %" value="{{ request('max_discount') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Active tags --}}
                            @if($hasAnyFilter)
                            <div class="active-tags">
                                <small class="text-muted mr-1" style="font-size:11px;">Active:</small>
                                @if(request('search'))
                                    <span class="tag"><i class="fas fa-search" style="font-size:9px;"></i> {{ request('search') }}</span>
                                @endif
                                @foreach($universities->whereIn('id', $universityFilter ?? []) as $u)
                                    <span class="tag"><i class="fas fa-university" style="font-size:9px;"></i> {{ $u->name }}</span>
                                @endforeach
                                @foreach($departmentFilter ?? [] as $d)
                                    <span class="tag"><i class="fas fa-building" style="font-size:9px;"></i> {{ $d }}</span>
                                @endforeach
                                @foreach($degreeFilter ?? [] as $d)
                                    <span class="tag"><i class="fas fa-graduation-cap" style="font-size:9px;"></i> {{ $d }}</span>
                                @endforeach
                                @if($languageFilter) <span class="tag">{{ $languageFilter }}</span> @endif
                                @if($shiftTypeFilter) <span class="tag">{{ $shiftTypeFilter }}</span> @endif
                                @if($statusFilter) <span class="tag">{{ $statusFilter }}</span> @endif
                                @if(request('min_price') || request('max_price'))
                                    <span class="tag">${{ request('min_price',0) }}–{{ request('max_price','∞') }}</span>
                                @endif
                                @if(request('min_discount') || request('max_discount'))
                                    <span class="tag">{{ request('min_discount',0) }}–{{ request('max_discount',100) }}%</span>
                                @endif
                                <a href="{{ route('admin.programs.index') }}" style="font-size:11px; color:#dc3545; margin-left:4px;">
                                    <i class="fas fa-times-circle"></i> Clear all
                                </a>
                            </div>
                            @endif
                        </div>
                    </form>

                    <script>
                    // Close all panels when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!e.target.closest('.fd-wrap')) {
                            document.querySelectorAll('.fd-panel.open').forEach(p => p.classList.remove('open'));
                        }
                    });

                    function togglePanel(id) {
                        const panel = document.getElementById(id);
                        const wasOpen = panel.classList.contains('open');
                        document.querySelectorAll('.fd-panel.open').forEach(p => p.classList.remove('open'));
                        if (!wasOpen) panel.classList.add('open');
                    }

                    function filterItems(input, listId) {
                        const q = input.value.toLowerCase();
                        document.querySelectorAll('#' + listId + ' .fd-item').forEach(item => {
                            item.style.display = item.textContent.toLowerCase().includes(q) ? '' : 'none';
                        });
                    }

                    function updateLabel(panelId, lblId, placeholder) {
                        const checked = document.querySelectorAll('#' + panelId + ' input[type=checkbox]:checked');
                        const lbl = document.getElementById(lblId);
                        const btn = document.querySelector('#' + panelId).previousElementSibling;
                        if (checked.length === 0) {
                            lbl.textContent = placeholder;
                            btn.classList.remove('has-value');
                        } else {
                            lbl.textContent = checked.length + ' selected';
                            btn.classList.add('has-value');
                        }
                        // Update active-item class
                        document.querySelectorAll('#' + panelId + ' .fd-item').forEach(item => {
                            item.classList.toggle('active-item', item.querySelector('input').checked);
                        });
                    }

                    function toggleRangePanel() {
                        const p = document.getElementById('rangePanel');
                        p.style.display = p.style.display === 'none' ? '' : 'none';
                    }
                    </script>

                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('warning') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Programs Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>University</th>
                                    <th>Department</th>
                                    <th>Degree</th>
                                    <th>Language</th>
                                    <th>Shift</th>
                                    <th>
                                        <i class="fas fa-money-bill-wave mr-1"></i> Pricing
                                    </th>
                                    <th>
                                        <i class="fas fa-percent mr-1"></i> Discounts
                                    </th>
                                    <th>Status</th>
                                    <th width="140">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($programs as $key => $program)
                                    <tr>
                                        <td class="text-center">{{ $key+1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                         
                                                {{ $program->university->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>{{ $program->department }}</td>
                                        <td>
                                            @if($program->degree == 'Bachelor')
                                                <span class="badge badge-info">{{ $program->degree }}</span>
                                            @elseif($program->degree == 'Master')
                                                <span class="badge badge-success">{{ $program->degree }}</span>
                                            @elseif($program->degree == 'PhD')
                                                <span class="badge badge-warning">{{ $program->degree }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $program->degree }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $program->language }}</td>
                                        <td>
                                            <span class="badge {{ $program->shift_type === 'Day' ? 'badge-light' : 'badge-dark' }}">
                                                <i class="fas {{ $program->shift_type === 'Day' ? 'fa-sun' : 'fa-moon' }} mr-1"></i>
                                                {{ $program->shift_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-nowrap">
                                                <div class="text-muted small">Before: <span class="text-danger">${{ number_format($program->before_discount, 2) }}</span></div>
                                                <div class="font-weight-bold">After: <span class="text-success">${{ number_format($program->after_discount, 2) }}</span></div>
                                                <div class="text-muted small">Deposit: ${{ number_format($program->deposit_payment, 2) }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-nowrap">
                                                <div>
                                                    <span class="badge badge-info">
                                                        {{ $program->discount_percentage }}% Off
                                                    </span>
                                                </div>
                                                @if($program->cash_discount > 0)
                                                    <div class="mt-1">
                                                        <span class="badge badge-success">
                                                            Cash: ${{ number_format($program->cash_discount, 2) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if($program->siblings_discount > 0 || $program->brothers_discount > 0)
                                                    <div class="mt-1">
                                                        @if($program->siblings_discount > 0)
                                                            <span class="badge badge-warning mr-1">
                                                                Siblings: {{ $program->siblings_discount }}%
                                                            </span>
                                                        @endif
                                                        @if($program->brothers_discount > 0)
                                                            <span class="badge badge-warning">
                                                                Brothers: {{ $program->brothers_discount }}%
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $program->status === 'Active' ? 'success' : ($program->status === 'Inactive' ? 'danger' : 'warning') }} badge-pill px-3 py-2">
                                                <i class="fas fa-{{ $program->status === 'Active' ? 'check-circle' : ($program->status === 'Inactive' ? 'times-circle' : 'exclamation-circle') }} mr-1"></i>
                                                {{ $program->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register' )

                                            <div class="btn-group">
                                         
                                                <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-sm btn-primary" title="Edit Program">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.programs.toggle-status', $program) }}" class="btn btn-sm btn-{{ $program->status === 'Active' ? 'warning' : 'success' }}" title="{{ $program->status === 'Active' ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $program->status === 'Active' ? 'times' : 'check' }}"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal{{ $program->id }}" title="Delete Program">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            @endif

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $program->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $program->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $program->id }}">
                                                                <i class="fas fa-exclamation-triangle mr-1"></i> Confirm Delete
                                                            </h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete the program:</p>
                                                            <div class="alert alert-warning">
                                                                <strong>{{ $program->department }} ({{ $program->degree }})</strong><br>
                                                                from <strong>{{ $program->university->name ?? 'Unknown University' }}</strong>
                                                            </div>
                                                            <p class="text-danger"><small><i class="fas fa-info-circle mr-1"></i> This action cannot be undone.</small></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                <i class="fas fa-times mr-1"></i> Cancel
                                                            </button>
                                                            <form action="{{ route('admin.programs.destroy', $program) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-trash mr-1"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-database fa-3x text-muted mb-3"></i>
                                                <h5>No programs found</h5>
                                                <p class="text-muted">Try adjusting your search criteria or add a new program.</p>
                                                @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register' )

                                                <a href="{{ route('admin.programs.create') }}" class="btn btn-primary mt-2">
                                                    <i class="fas fa-plus mr-1"></i> Add Program
                                                </a>
@endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted">
                                @if($programs->total() > 0)
                                    Showing {{ $programs->firstItem() }} to {{ $programs->lastItem() }} of {{ $programs->total() }} entries
                                @else
                                    Showing 0 to 0 of 0 entries
                                @endif
                            </span>
                        </div>
                        <div>
                            {{ $programs->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS Styles -->
<style>
    .small-box {
        border-radius: 0.5rem;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    .small-box:hover {
        transform: translateY(-5px);
    }
    .empty-state {
        padding: 2rem;
        text-align: center;
    }
    .badge-pill {
        font-size: 0.85rem;
    }
    .table td {
        vertical-align: middle;
    }
</style>

<script>
$(function() {
    setTimeout(function() { $('.alert-dismissible').fadeOut('slow'); }, 5000);
});
</script>
@endsection