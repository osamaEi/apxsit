@extends('admin.index')

@section('content')
<div class="student-dashboard">
    <!-- Hero Header with Stats -->
    <div class="hero-header">
        <div class="stats-overview">
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon bg-primary-soft">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-details">
                        <h3>{{ $totalStudents ?? $students->total() }}</h3>
                        <p>Total Students</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon bg-success-soft">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-details">
                        <h3>{{ $acceptedCount ?? $students->where('status', 'Accepted')->count() }}</h3>
                        <p>Accepted</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon bg-warning-soft">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-details">
                        <h3>{{ $pendingCount ?? $students->where('status', 'Pending Documents')->count() }}</h3>
                        <p>Pending Documents</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon bg-info-soft">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <div class="stat-details">
                        <h3>{{ $countriesCount ?? $students->pluck('studyCountry.name')->unique()->count() }}</h3>
                        <p>Destination Countries</p>
                    </div>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('admin.students.create.step1') }}" class="btn btn-primary btn-rounded">
                    <i class="fas fa-plus-circle"></i> Add Student
                </a>
                <a href="{{ route('admin.students.export-excel', [
                    'search' => request('search'),
                    'status' => request('status'),
                    'country_id' => request('country_id'),
                    'employee_id' => request('employee_id'),
                    'degree_id' => request('degree_id')
                ]) }}" class="btn btn-success btn-rounded">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('admin.students.export-pdf', [
                    'search' => request('search'),
                    'status' => request('status'),
                    'country_id' => request('country_id'),
                    'employee_id' => request('employee_id'),
                    'degree_id' => request('degree_id')
                ]) }}" class="btn btn-danger btn-rounded">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('admin.students.pending-documents') }}" class="btn btn-warning btn-rounded">
                    <i class="fas fa-exclamation-triangle"></i> Pending Documents 
                    <span class="badge bg-danger">{{ $pendingCount ?? $students->where('status', 'Pending Documents')->count() }}</span>
                </a>
            </div>
        </div>
        
        <div class="filters-toggle">
            <button class="btn btn-outline-secondary btn-rounded" id="toggleFilters">
                <i class="fas fa-filter"></i> Advanced Filters
            </button>
        </div>
    </div>
    
    <!-- Advanced Filters Panel (Hidden by default) -->
    <div class="filters-panel" id="filtersPanel" style="display: none;">
        <form action="{{ route('admin.students.index') }}" method="GET">
            <div class="filters-header">
                <h5><i class="fas fa-sliders-h"></i> Student Filters</h5>
                <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-sync-alt"></i> Reset All
                </a>
            </div>
            
            <div class="filters-body">
                <div class="filter-group">
                    <div class="filter-item">
                        <label for="search">
                            <i class="fas fa-search"></i> Search
                        </label>
                        <input type="text" id="search" name="search" class="form-control" 
                               placeholder="Name, email, passport, phone..." 
                               value="{{ $search }}">
                    </div>
                    
                    <div class="filter-item">
                        <label for="status">
                            <i class="fas fa-tag"></i> Status
                        </label>
                        <select id="status" name="status" class="form-control select2-minimal">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $key => $value)
                                <option value="{{ $key }}" {{ $statusFilter == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-item">
                        <label for="country_id">
                            <i class="fas fa-globe"></i> Study Country
                        </label>
                        <select id="country_id" name="country_id" class="form-control select2-minimal">
                            <option value="">All Countries</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $countryFilter == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-item">
                        <label for="employee_id">
                            <i class="fas fa-user-tie"></i> Responsible
                        </label>
                        <select id="employee_id" name="employee_id" class="form-control select2-minimal">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ $employeeFilter == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-item">
                        <label for="degree_id">
                            <i class="fas fa-graduation-cap"></i> Program
                        </label>
                        <select id="degree_id" name="degree_id" class="form-control select2-minimal">
                            <option value="">All Programs</option>
                            @foreach($degrees as $degree)
                                <option value="{{ $degree->id }}" {{ $degreeFilter == $degree->id ? 'selected' : '' }}>
                                    {{ $degree->department }} ({{ $degree->degree }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="filters-footer">
                <button type="submit" class="btn btn-primary btn-rounded">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>
    
    <!-- Active Filters Display -->
    @if($search || $statusFilter || $countryFilter || $employeeFilter || $degreeFilter)
    <div class="active-filters">
        <div class="active-filters-header">
            <h6>Active Filters:</h6>
            <a href="{{ route('admin.students.index') }}" class="clear-all">Clear All</a>
        </div>
        <div class="filter-tags">
            @if($search)
            <div class="filter-tag">
                <span><i class="fas fa-search"></i> "{{ $search }}"</span>
                <a href="{{ route('admin.students.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="remove-tag">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            @endif
            
            @if($statusFilter)
            <div class="filter-tag">
                <span><i class="fas fa-tag"></i> Status: {{ $statuses[$statusFilter] }}</span>
                <a href="{{ route('admin.students.index', array_merge(request()->except('status'), ['page' => 1])) }}" class="remove-tag">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            @endif
            
            @if($countryFilter)
            <div class="filter-tag">
                <span><i class="fas fa-globe"></i> Country: {{ $countries->where('id', $countryFilter)->first()->name }}</span>
                <a href="{{ route('admin.students.index', array_merge(request()->except('country_id'), ['page' => 1])) }}" class="remove-tag">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            @endif
            
            @if($employeeFilter)
            <div class="filter-tag">
                <span><i class="fas fa-user-tie"></i> Employee: {{ $employees->where('id', $employeeFilter)->first()->name }}</span>
                <a href="{{ route('admin.students.index', array_merge(request()->except('employee_id'), ['page' => 1])) }}" class="remove-tag">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            @endif
            
            @if($degreeFilter)
            <div class="filter-tag">
                <span><i class="fas fa-graduation-cap"></i> Program: {{ $degrees->where('id', $degreeFilter)->first()->department }}</span>
                <a href="{{ route('admin.students.index', array_merge(request()->except('degree_id'), ['page' => 1])) }}" class="remove-tag">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Success Notification -->
    @if(session('success'))
    <div class="success-notification" id="successNotification">
        <div class="notification-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notification-content">
            <h6>Success!</h6>
            <p>{{ session('success') }}</p>
        </div>
        <button type="button" class="notification-close" onclick="closeNotification()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif
    
    <!-- Quick Summary Bar -->
  
    <div class="filtration-section mb-4">
        <div class="card card-outline card-primary">
            <div class="card-header">
              
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.students.index') }}" method="GET" id="student-filter-form">
                    <div class="row">
                        <!-- Search Field -->
                        <div class="col-md-6 col-lg-3 mb-3">
                            <label for="search">Search</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="search" name="search" 
                                    placeholder="Name, email, passport..." value="{{ request('search') }}">
                            </div>
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="col-md-6 col-lg-3 mb-3">
                            <label for="status">Status</label>
                            <select class="form-control select2" id="status" name="status">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $key => $value)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Study Country Filter -->
                        <div class="col-md-6 col-lg-3 mb-3">
                            <label for="country_id">Study Country</label>
                            <select class="form-control select2" id="country_id" name="country_id">
                                <option value="">All Countries</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Employee Filter -->
                        <div class="col-md-6 col-lg-3 mb-3">
                            <label for="employee_id">Responsible Employee</label>
                            <select class="form-control select2" id="employee_id" name="employee_id">
                                <option value="">All Employees</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Degree Program Filter -->
                        <div class="col-md-6 col-lg-3 mb-3">
                            <label for="degree_id">Program</label>
                            <select class="form-control select2" id="degree_id" name="degree_id">
                                <option value="">All Programs</option>
                                @foreach($degrees as $degree)
                                    <option value="{{ $degree->id }}" {{ request('degree_id') == $degree->id ? 'selected' : '' }}>
                                        {{ $degree->department }} ({{ $degree->degree }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
    
                        <!-- Nationality Filter -->
                 
                        
                        <!-- Date Range Filter -->
                        <div class="col-md-6 col-lg-3 mb-3">
                            <label for="date_range">Created Date Range</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control date-range-picker" id="date_range" name="date_range"
                                    value="{{ request('date_range') }}">
                            </div>
                        </div>
                        
                        <!-- Filter Actions -->
                        <div class="col-md-6 col-lg-3 mb-3 d-flex align-items-end">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter mr-1"></i> Apply Filters
                                </button>
                                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo mr-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Active Filter Tags -->
    @if(request('search') || request('status') || request('country_id') || request('employee_id') || request('degree_id') || request('nationality_id') || request('date_range'))
    <div class="active-filters mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0"><i class="fas fa-tag mr-2"></i> Active Filters</h6>
            <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-times mr-1"></i> Clear All
            </a>
        </div>
        
        <div class="filter-tags">
            @if(request('search'))
            <div class="filter-badge">
                <span class="badge badge-info">
                    <i class="fas fa-search mr-1"></i> 
                    Search: {{ request('search') }}
                    <a href="{{ route('admin.students.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="text-white ml-1">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            </div>
            @endif
            
            @if(request('status'))
            <div class="filter-badge">
                <span class="badge badge-info">
                    <i class="fas fa-tag mr-1"></i> 
                    Status: {{ $statuses[request('status')] ?? request('status') }}
                    <a href="{{ route('admin.students.index', array_merge(request()->except('status'), ['page' => 1])) }}" class="text-white ml-1">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            </div>
            @endif
            
            @if(request('country_id'))
            <div class="filter-badge">
                <span class="badge badge-info">
                    <i class="fas fa-globe mr-1"></i> 
                    Country: {{ $countries->where('id', request('country_id'))->first()->name ?? request('country_id') }}
                    <a href="{{ route('admin.students.index', array_merge(request()->except('country_id'), ['page' => 1])) }}" class="text-white ml-1">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            </div>
            @endif
            
            @if(request('employee_id'))
            <div class="filter-badge">
                <span class="badge badge-info">
                    <i class="fas fa-user-tie mr-1"></i> 
                    Employee: {{ $employees->where('id', request('employee_id'))->first()->name ?? request('employee_id') }}
                    <a href="{{ route('admin.students.index', array_merge(request()->except('employee_id'), ['page' => 1])) }}" class="text-white ml-1">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            </div>
            @endif
            
            @if(request('degree_id'))
            <div class="filter-badge">
                <span class="badge badge-info">
                    <i class="fas fa-graduation-cap mr-1"></i> 
                    Program: {{ $degrees->where('id', request('degree_id'))->first()->department ?? request('degree_id') }}
                    <a href="{{ route('admin.students.index', array_merge(request()->except('degree_id'), ['page' => 1])) }}" class="text-white ml-1">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            </div>
            @endif
            
            @if(request('nationality_id'))
            <div class="filter-badge">
                <span class="badge badge-info">
                    <i class="fas fa-flag mr-1"></i> 
                    Nationality: {{ $nationalities->where('id', request('nationality_id'))->first()->name ?? request('nationality_id') }}
                    <a href="{{ route('admin.students.index', array_merge(request()->except('nationality_id'), ['page' => 1])) }}" class="text-white ml-1">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            </div>
            @endif
            
            @if(request('date_range'))
            <div class="filter-badge">
                <span class="badge badge-info">
                    <i class="fas fa-calendar mr-1"></i> 
                    Date Range: {{ request('date_range') }}
                    <a href="{{ route('admin.students.index', array_merge(request()->except('date_range'), ['page' => 1])) }}" class="text-white ml-1">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Required CSS -->
    <style>
        .filtration-section .card-outline {
            border-top: 3px solid #4e73df;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }
        
        .filter-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .filter-badge .badge {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 500;
        }
    </style>
    
    <!-- Required JavaScript -->
    <script>
    $(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
        
        // Initialize DateRangePicker
        $('.date-range-picker').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            }
        });
        
        $('.date-range-picker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
        });
        
        $('.date-range-picker').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
    </script>
    <!-- Students Table View (Default) -->
    <div class="students-view table-view">
        <div class="students-table">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">ID</span>
                                    <div class="sort-icons">
                                        <i class="fas fa-sort-up"></i>
                                        <i class="fas fa-sort-down"></i>
                                    </div>
                                </div>
                            </th>
                            <th>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">Student</span>
                                    <div class="sort-icons">
                                        <i class="fas fa-sort-up"></i>
                                        <i class="fas fa-sort-down"></i>
                                    </div>
                                </div>
                            </th>
                            <th>Agent or Sales</th>
                            <th>Registers</th>
                            <th>Nationality</th>
                            <th>Study Details</th>
                            <th>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">Status</span>
                                    <div class="sort-icons">
                                        <i class="fas fa-sort-up"></i>
                                        <i class="fas fa-sort-down"></i>
                                    </div>
                                </div>
                            </th>
                            <th>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">Created</span>
                                    <div class="sort-icons">
                                        <i class="fas fa-sort-up"></i>
                                        <i class="fas fa-sort-down"></i>
                                    </div>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr class="student-row" data-status="{{ $student->status }}">
                            <td class="student-id">
                                <span class="id-badge">#{{ $student->id }}</span>
                                @if($student->photo_path)
                                    <img src="{{ Storage::url($student->photo_path) }}" alt="{{ $student->first_name }}" class="avatar-img" width="50">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td class="student-info">
                                <div class="d-flex align-items-center">
                                    <div class="student-avatar 
                                        @if($student->status == 'Accepted' || $student->status == 'Enrolled') avatar-success
                                        @elseif($student->status == 'Pending Documents') avatar-warning
                                        @elseif($student->status == 'Rejected' || $student->status == 'Cancelled') avatar-danger
                                        @else avatar-primary @endif">
                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                    </div>
                                    <div class="student-details">
                                        <h6 class="mb-0">
                                            <a href="{{ route('admin.students.show', $student) }}" class="student-name">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </a>
                                        </h6>
                                        <span class="passport-id">Passport: {{ $student->passport_id }}</span>
                                        @if($student->is_transfer)
                                            <span class="badge badge-soft-info">Transfer</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="student-contact">
                                @if($student->processedBy)
                                    {{ $student->processedBy->name }}
                                @else
                                    SELF REGISTER
                                @endif
                            </td>

                            <td>

                                

                            </td>

                            <td class="student-nationality">
                                <div class="d-flex align-items-center">
                                    @if($student->nationality)
                                        <span class="nationality">{{ $student->nationality->name }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </div>
                            </td>
                            <td class="study-details">
                                <div class="study-info">
                                    <div class="study-item">
                                        <i class="fas fa-globe-americas"></i>
                                        <span class="country">{{ $student->studyCountry->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="study-item">
                                        <i class="fas fa-graduation-cap"></i>
                                        <span class="program">{{ $student->applyingDegree->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="study-item">
                                        <i class="fas fa-user-tie"></i>
                                        <span class="employee">{{ $student->responsibleEmployee->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="student-status">
                                <div class="status-badge 
                                    @if($student->status == 'New') badge-primary
                                    @elseif($student->status == 'In Review') badge-info
                                    @elseif($student->status == 'Pending Documents') badge-warning
                                    @elseif($student->status == 'Accepted') badge-success
                                    @elseif($student->status == 'Rejected') badge-danger
                                    @elseif($student->status == 'Enrolled') badge-success-dark
                                    @elseif($student->status == 'Cancelled') badge-secondary
                                    @endif">
                                    @if($student->status == 'New')
                                        <i class="fas fa-plus-circle"></i>
                                    @elseif($student->status == 'In Review')
                                        <i class="fas fa-search"></i>
                                    @elseif($student->status == 'Pending Documents')
                                        <i class="fas fa-exclamation-circle"></i>
                                    @elseif($student->status == 'Accepted')
                                        <i class="fas fa-check-circle"></i>
                                    @elseif($student->status == 'Rejected')
                                        <i class="fas fa-times-circle"></i>
                                    @elseif($student->status == 'Enrolled')
                                        <i class="fas fa-user-graduate"></i>
                                    @elseif($student->status == 'Cancelled')
                                        <i class="fas fa-ban"></i>
                                    @endif
                                    {{ $student->status }}
                                </div>
                                <div class="last-updated">
                                    <span>Updated: {{ $student->updated_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="student-created-at">
                                <div class="created-date">
                                    {{ $student->created_at->format('M d, Y') }}
                                </div>
                                <div class="created-time text-muted">
                                    {{ $student->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td class="student-actions">
                                <div class="actions-menu">
                                    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-icon btn-light" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register')
                                        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-icon btn-light" title="Edit Student">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-icon btn-light" data-toggle="modal" data-target="#deleteModal{{ $student->id }}" title="Delete Student">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-icon btn-light dropdown-toggle" data-toggle="dropdown" title="More Actions">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-file-alt"></i> View Documents
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-envelope"></i> Send Email
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-history"></i> View History
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#deleteModal{{ $student->id }}">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade delete-modal" id="deleteModal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $student->id }}">
                                                <i class="fas fa-exclamation-triangle text-danger"></i> Confirm Deletion
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <div class="delete-icon">
                                                <i class="fas fa-user-slash"></i>
                                            </div>
                                            <h4 class="mt-3">Are you sure?</h4>
                                            <p>You are about to delete the student record for:</p>
                                            <div class="student-to-delete">
                                                <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                                <span class="d-block text-muted">Passport: {{ $student->passport_id }}</span>
                                            </div>
                                            <div class="alert alert-warning mt-3">
                                                <i class="fas fa-info-circle"></i> This action cannot be undone
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                            <form action="{{ route('admin.students.destroy', $student) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Delete Permanently
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <img src="{{ asset('images/empty-students.svg') }}" alt="No students" class="empty-state-image">
                                    <h4>No Students Found</h4>
                                    <p class="text-muted">There are no students matching your criteria.</p>
                                    <div class="empty-state-actions">
                                        <a href="{{ route('admin.students.create.step1') }}" class="btn btn-primary btn-rounded">
                                            <i class="fas fa-plus-circle"></i> Add First Student
                                        </a>
                                        @if($search || $statusFilter || $countryFilter || $employeeFilter || $degreeFilter)
                                        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-rounded mt-2">
                                            <i class="fas fa-filter"></i> Clear Filters
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <style>
    /* Additional styles for new columns */
    .student-nationality {
        font-size: 0.9rem;
    }
    
    .student-created-at {
        font-size: 0.85rem;
    }
    
    .created-date {
        font-weight: 500;
    }
    
    .created-time {
        font-size: 0.75rem;
    }
    </style>
    
    <!-- Students Card View (Hidden by default) -->
    <div class="students-view cards-view" style="display: none;">
        <div class="student-cards">
            <div class="row">
                @forelse($students as $student)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="student-card" data-status="{{ $student->status }}">
                        <div class="card-status-indicator 
                            @if($student->status == 'New') bg-primary
                            @elseif($student->status == 'In Review') bg-info
                            @elseif($student->status == 'Pending Documents') bg-warning
                            @elseif($student->status == 'Accepted') bg-success
                            @elseif($student->status == 'Rejected') bg-danger
                            @elseif($student->status == 'Enrolled') bg-success
                            @elseif($student->status == 'Cancelled') bg-secondary
                            @endif"></div>
                        
                        <div class="card-header">
                            <div class="student-avatar-wrapper">
                                <div class="student-avatar 
                                    @if($student->status == 'Accepted' || $student->status == 'Enrolled') avatar-success
                                    @elseif($student->status == 'Pending Documents') avatar-warning
                                    @elseif($student->status == 'Rejected' || $student->status == 'Cancelled') avatar-danger
                                    @else avatar-primary @endif">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                            </div>
                            <div class="student-info">
                                <h5 class="student-name">
                                    <a href="{{ route('admin.students.show', $student) }}">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </a>
                                </h5>
                                <div class="student-meta">
                                    <span class="id-badge">#{{ $student->id }}</span>
                                    @if($student->is_transfer)
                                        <span class="badge badge-soft-info">Transfer</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-passport"></i> Passport
                                </div>
                                <div class="info-value">{{ $student->passport_id }}</div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-envelope"></i> Email
                                </div>
                                <div class="info-value text-truncate">{{ $student->email }}</div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-globe-americas"></i> Country
                                </div>
                                <div class="info-value">{{ $student->studyCountry->name ?? 'N/A' }}</div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-graduation-cap"></i> Program
                                </div>
                                <div class="info-value">{{ $student->applyingDegree->department ?? 'N/A' }}</div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-user-tie"></i> Responsible
                                </div>
                                <div class="info-value">{{ $student->responsibleEmployee->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="status-badge 
                                @if($student->status == 'New') badge-primary
                                @elseif($student->status == 'In Review') badge-info
                                @elseif($student->status == 'Pending Documents') badge-warning
                                @elseif($student->status == 'Accepted') badge-success
                                @elseif($student->status == 'Rejected') badge-danger
                                @elseif($student->status == 'Enrolled') badge-success-dark
                                @elseif($student->status == 'Cancelled') badge-secondary
                                @endif">
                                @if($student->status == 'New')
                                    <i class="fas fa-plus-circle"></i>
                                @elseif($student->status == 'In Review')
                                    <i class="fas fa-search"></i>
                                @elseif($student->status == 'Pending Documents')
                                    <i class="fas fa-exclamation-circle"></i>
                                @elseif($student->status == 'Accepted')
                                    <i class="fas fa-check-circle"></i>
                                @elseif($student->status == 'Rejected')
                                    <i class="fas fa-times-circle"></i>
                                @elseif($student->status == 'Enrolled')
                                    <i class="fas fa-user-graduate"></i>
                                @elseif($student->status == 'Cancelled')
                                    <i class="fas fa-ban"></i>
                                @endif
                                {{ $student->status }}
                            </div>
                            
                            <div class="action-buttons">
                                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-icon btn-light" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-icon btn-light" title="Edit Student">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-icon btn-light" data-toggle="modal" data-target="#deleteCardModal{{ $student->id }}" title="Delete Student">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <div class="dropdown d-inline">
                                    <button class="btn btn-sm btn-icon btn-light dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-file-alt"></i> View Documents
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-envelope"></i> Send Email
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-history"></i> View History
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delete Modal for Card View -->
                    <div class="modal fade delete-modal" id="deleteCardModal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteCardModalLabel{{ $student->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteCardModalLabel{{ $student->id }}">
                                        <i class="fas fa-exclamation-triangle text-danger"></i> Confirm Deletion
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <div class="delete-icon">
                                        <i class="fas fa-user-slash"></i>
                                    </div>
                                    <h4 class="mt-3">Are you sure?</h4>
                                    <p>You are about to delete the student record for:</p>
                                    <div class="student-to-delete">
                                        <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                        <span class="d-block text-muted">Passport: {{ $student->passport_id }}</span>
                                    </div>
                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-info-circle"></i> This action cannot be undone
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash-alt"></i> Delete Permanently
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="empty-state">
                        <img src="{{ asset('images/empty-students.svg') }}" alt="No students" class="empty-state-image">
                        <h4>No Students Found</h4>
                        <p class="text-muted">There are no students matching your criteria.</p>
                        <div class="empty-state-actions">
                            <a href="{{ route('admin.students.create.step1') }}" class="btn btn-primary btn-rounded">
                                <i class="fas fa-plus-circle"></i> Add First Student
                            </a>
                            @if($search || $statusFilter || $countryFilter || $employeeFilter || $degreeFilter)
                            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-rounded mt-2">
                                <i class="fas fa-filter"></i> Clear Filters
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="custom-pagination">
        {{ $students->appends(request()->query())->links() }}
    </div>
</div>

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    /* Main Dashboard Styles */
    .student-dashboard {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
    }
    
    /* Hero Header */
    .hero-header {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .hero-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.5;
    }
    
    .stats-overview {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }
    
    .stats-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .stat-card {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
        padding: 15px;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        min-width: 180px;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-3px);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 15px;
    }
    
    .bg-primary-soft {
        background-color: rgba(78, 115, 223, 0.2);
        color: #4e73df;
    }
    
    .bg-success-soft {
        background-color: rgba(40, 167, 69, 0.2);
        color: #28a745;
    }
    
    .bg-warning-soft {
        background-color: rgba(255, 193, 7, 0.2);
        color: #ffc107;
    }
    
    .bg-info-soft {
        background-color: rgba(23, 162, 184, 0.2);
        color: #17a2b8;
    }
    
    .stat-details h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
    }
    
    .stat-details p {
        margin: 0;
        font-size: 0.85rem;
        opacity: 0.8;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .btn-rounded {
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .btn-rounded:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }
    
    .filters-toggle {
        position: relative;
        z-index: 1;
        margin-top: 15px;
        text-align: right;
    }
    
    /* Filters Panel */
    .filters-panel {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .filters-header {
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .filters-header h5 {
        margin: 0;
        font-weight: 600;
        color: #495057;
    }
    
    .filters-body {
        padding: 20px;
    }
    
    .filters-footer {
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        text-align: right;
    }
    
    .filter-group {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }
    
    .filter-item label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #495057;
        font-size: 0.875rem;
    }
    
    /* Active Filters */
    .active-filters {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 30px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }
    
    .active-filters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .active-filters-header h6 {
        margin: 0;
        color: #6c757d;
        font-weight: 500;
    }
    
    .clear-all {
        color: #6c757d;
        font-size: 0.875rem;
        text-decoration: none;
    }
    
    .clear-all:hover {
        text-decoration: underline;
    }
    
    .filter-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .filter-tag {
        display: inline-flex;
        align-items: center;
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 50px;
        padding: 5px 15px;
        font-size: 0.875rem;
        color: #495057;
    }
    
    .filter-tag span {
        margin-right: 8px;
    }
    
    .remove-tag {
        color: #6c757d;
        font-size: 0.75rem;
    }
    
    .remove-tag:hover {
        color: #dc3545;
    }
    
    /* Success Notification */
    .success-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        display: flex;
        width: 350px;
        overflow: hidden;
        z-index: 1050;
        animation: slideIn 0.5s ease forwards;
    }
    
    @keyframes slideIn {
        0% { transform: translateX(100%); opacity: 0; }
        100% { transform: translateX(0); opacity: 1; }
    }
    
    .notification-icon {
        width: 60px;
        background-color: #28a745;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .notification-content {
        padding: 15px;
        flex: 1;
    }
    
    .notification-content h6 {
        margin: 0 0 5px 0;
        font-weight: 600;
    }
    
    .notification-content p {
        margin: 0;
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: #6c757d;
        padding: 15px;
        cursor: pointer;
    }
    
    /* Quick Summary Bar */
    .quick-summary-bar {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }
    
    .summary-info {
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .view-controls {
        display: flex;
        gap: 5px;
    }
    
    .view-control {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .view-control.active {
        background-color: #4e73df;
        color: white;
        border-color: #4e73df;
    }
    
    /* Table View */
    .students-view {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }
    
    .students-table {
        padding: 0;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        border-top: none;
        color: #495057;
        font-weight: 600;
        padding: 15px;
        vertical-align: middle;
    }
    
    .table td {
        padding: 15px;
        vertical-align: middle;
    }
    
    .sort-icons {
        display: flex;
        flex-direction: column;
        font-size: 0.6rem;
        line-height: 0.5;
        opacity: 0.5;
    }
    
    .student-row {
        transition: all 0.2s ease;
    }
    
    .student-row:hover {
        background-color: #f8f9fa;
    }
    
    /* Student ID */
    .id-badge {
        font-family: 'Courier New', monospace;
        background-color: #f0f0f0;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #495057;
    }
    
    /* Student Info */
    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        color: white;
        margin-right: 15px;
    }
    
    .avatar-primary {
        background-color: #4e73df;
    }
    
    .avatar-success {
        background-color: #28a745;
    }
    
    .avatar-warning {
        background-color: #ffc107;
        color: #212529;
    }
    
    .avatar-danger {
        background-color: #dc3545;
    }
    
    .student-name {
        color: #343a40;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .student-name:hover {
        color: #4e73df;
        text-decoration: none;
    }
    
    .passport-id {
        font-size: 0.75rem;
        color: #6c757d;
        display: block;
    }
    
    .badge-soft-info {
        background-color: rgba(23, 162, 184, 0.1);
        color: #17a2b8;
        font-weight: 500;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 4px;
        margin-left: 5px;
    }
    
    /* Contact Info */
    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .contact-item i {
        width: 18px;
        margin-right: 8px;
    }
    
    /* Study Info */
    .study-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .study-item {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .study-item i {
        width: 18px;
        margin-right: 8px;
    }
    
    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .status-badge i {
        margin-right: 5px;
    }
    
    .badge-primary {
        background-color: rgba(78, 115, 223, 0.15);
        color: #4e73df;
    }
    
    .badge-info {
        background-color: rgba(23, 162, 184, 0.15);
        color: #17a2b8;
    }
    
    .badge-warning {
        background-color: rgba(255, 193, 7, 0.15);
        color: #f6c23e;
    }
    
    .badge-success {
        background-color: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }
    
    .badge-danger {
        background-color: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }
    
    .badge-secondary {
        background-color: rgba(108, 117, 125, 0.15);
        color: #6c757d;
    }
    
    .badge-success-dark {
        background-color: rgba(40, 167, 69, 0.25);
        color: #1e7e34;
    }
    
    .last-updated {
        font-size: 0.7rem;
        color: #adb5bd;
        margin-top: 5px;
    }
    
    /* Action Buttons */
    .actions-menu {
        display: flex;
        gap: 5px;
    }
    
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #495057;
        transition: all 0.2s ease;
    }
    
    .btn-icon:hover {
        background-color: #e9ecef;
        color: #212529;
    }
    
    /* Delete Modal */
    .delete-modal .modal-content {
        border-radius: 15px;
        border: none;
        overflow: hidden;
    }
    
    .delete-icon {
        width: 80px;
        height: 80px;
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 15px auto;
    }
    
    .student-to-delete {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin: 15px auto;
        max-width: 80%;
    }
    
    /* Empty State */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 50px 20px;
        text-align: center;
    }
    
    .empty-state-image {
        width: 150px;
        height: auto;
        margin-bottom: 20px;
        opacity: 0.7;
    }
    
    .empty-state h4 {
        margin-bottom: 10px;
        color: #343a40;
    }
    
    .empty-state p {
        margin-bottom: 20px;
        max-width: 500px;
    }
    
    .empty-state-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    /* Card View */
    .student-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
        height: 100%;
    }
    
    .student-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    .card-status-indicator {
        height: 4px;
        width: 100%;
    }
    
    .card-header {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .student-avatar-wrapper {
        margin-bottom: 15px;
    }
    
    .card-header .student-avatar {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
        margin-right: 0;
    }
    
    .card-header .student-info {
        text-align: center;
    }
    
    .card-header .student-name {
        font-size: 1.15rem;
        margin-bottom: 5px;
        display: block;
    }
    
    .card-header .student-meta {
        display: flex;
        justify-content: center;
        gap: 5px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        font-size: 0.875rem;
    }
    
    .info-label {
        color: #6c757d;
    }
    
    .info-value {
        font-weight: 500;
        color: #495057;
    }
    
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #f0f0f0;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    /* Custom Pagination */
    .custom-pagination {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }
    
    .pagination {
        margin-bottom: 0;
    }
    
    .page-item .page-link {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 5px;
        border: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        color: #495057;
        font-weight: 500;
    }
    
    .page-item.active .page-link {
        background-color: #4e73df;
        color: white;
    }
    
    .page-item.disabled .page-link {
        background-color: #f8f9fa;
        color: #adb5bd;
    }
    
    /* Select2 Customization */
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
    }
    
    .select2-container--bootstrap4 .select2-selection__rendered {
        line-height: calc(1.5em + 0.75rem);
    }
    
    .select2-container--bootstrap4 .select2-selection__arrow {
        height: calc(1.5em + 0.75rem);
    }
    
    /* Custom styles for Select2 Minimal theme */
    .select2-minimal + .select2-container .select2-selection {
        border-radius: 8px;
        box-shadow: none;
    }
    
    .select2-minimal + .select2-container .select2-selection__rendered {
        padding-left: 12px;
        color: #495057;
    }
    
    /* Responsive Styles */
    @media (max-width: 1199.98px) {
        .filter-group {
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        }
    }
    
    @media (max-width: 991.98px) {
        .hero-header {
            padding: 20px;
        }
        
        .stats-container {
            margin-bottom: 15px;
            justify-content: flex-start;
        }
        
        .stat-card {
            min-width: 150px;
        }
        
        .student-row .study-details {
            display: none;
        }
        
        .table thead th:nth-child(4),
        .table tbody td:nth-child(4) {
            display: none;
        }
    }
    
    @media (max-width: 767.98px) {
        .filters-toggle {
            margin-top: 15px;
        }
        
        .student-row .student-contact {
            display: none;
        }
        
        .table thead th:nth-child(3),
        .table tbody td:nth-child(3) {
            display: none;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 10px;
        }
        
        .btn-rounded {
            width: 100%;
        }
    }
    
    @media (max-width: 575.98px) {
        .hero-header {
            padding: 15px;
        }
        
        .stats-container {.stats-container {
            flex-direction: column;
            width: 100%;
        }
        
        .stat-card {
            width: 100%;
        }
        
        .student-actions .dropdown-menu {
            left: auto;
            right: 0;
        }
    }
</style>

<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(function() {
    // Initialize Select2 Elements
    $('.select2-minimal').select2({
        theme: 'bootstrap4',
        minimumResultsForSearch: 6,
        width: '100%'
    });
    
    // Toggle Filters Panel
    $('#toggleFilters').click(function() {
        $('#filtersPanel').slideToggle(300);
        $(this).find('i').toggleClass('fa-filter fa-times');
        
        if ($(this).find('i').hasClass('fa-times')) {
            $(this).html('<i class="fas fa-times"></i> Close Filters');
        } else {
            $(this).html('<i class="fas fa-filter"></i> Advanced Filters');
        }
    });
    
    // Toggle View
    $('.view-control').click(function() {
        $('.view-control').removeClass('active');
        $(this).addClass('active');
        
        const viewType = $(this).data('view');
        if (viewType === 'table') {
            $('.table-view').show();
            $('.cards-view').hide();
        } else {
            $('.table-view').hide();
            $('.cards-view').show();
        }
        
        // Save view preference in localStorage
        localStorage.setItem('studentViewPreference', viewType);
    });
    
    // Check for saved view preference
    const savedView = localStorage.getItem('studentViewPreference');
    if (savedView) {
        $('.view-control').removeClass('active');
        $(`.view-control[data-view="${savedView}"]`).addClass('active');
        
        if (savedView === 'cards') {
            $('.table-view').hide();
            $('.cards-view').show();
        }
    }
    
    // Auto close success notification after 5 seconds
    setTimeout(function() {
        $('#successNotification').fadeOut(500);
    }, 5000);
    
    // Highlight table rows based on status for better visibility
    $('.student-row').each(function() {
        const status = $(this).data('status');
        if (status === 'Pending Documents') {
            $(this).addClass('bg-warning-ultra-light');
        } else if (status === 'Accepted' || status === 'Enrolled') {
            $(this).addClass('bg-success-ultra-light');
        } else if (status === 'Rejected' || status === 'Cancelled') {
            $(this).addClass('bg-danger-ultra-light');
        }
    });
    
    // Add tooltip to each button with a title attribute
    $('[title]').tooltip({
        placement: 'top',
        trigger: 'hover'
    });
    
    // Add sorting functionality to sortable columns
    $('.sort-icons').parent().css('cursor', 'pointer').click(function() {
        const column = $(this).index();
        const table = $(this).closest('table');
        const rows = table.find('tbody > tr').toArray();
        const ascending = $(this).hasClass('asc');
        
        // Toggle sorting direction
        $(this).toggleClass('asc');
        
        // Reset sort indicators on all columns
        table.find('th .sort-icons i').css('opacity', '0.3');
        
        // Highlight active sort direction
        if (ascending) {
            $(this).find('.sort-icons .fa-sort-down').css('opacity', '1');
        } else {
            $(this).find('.sort-icons .fa-sort-up').css('opacity', '1');
        }
        
        // Sort the rows
        rows.sort(function(a, b) {
            const A = $(a).children('td').eq(column).text().trim();
            const B = $(b).children('td').eq(column).text().trim();
            
            if (column === 0) { // ID column (numeric)
                return ascending ? 
                    parseInt(A.replace('#', '')) - parseInt(B.replace('#', '')) : 
                    parseInt(B.replace('#', '')) - parseInt(A.replace('#', ''));
            } else { // Text columns
                return ascending ? 
                    A.localeCompare(B) : 
                    B.localeCompare(A);
            }
        });
        
        // Re-append rows in the new order
        $.each(rows, function(index, row) {
            table.children('tbody').append(row);
        });
    });
});

// Function to close notification
function closeNotification() {
    document.getElementById('successNotification').style.display = 'none';
}
</script>

<!-- Dynamic Background Colors -->
<style>
    /* Ultra light background colors for status highlighting */
    .bg-warning-ultra-light { background-color: rgba(255, 193, 7, 0.05); }
    .bg-success-ultra-light { background-color: rgba(40, 167, 69, 0.05); }
    .bg-danger-ultra-light  { background-color: rgba(220, 53, 69, 0.05); }
</style>

<!-- ══════════════════════════════════════════
     DARK MODE OVERRIDES — students/index
     ══════════════════════════════════════════ -->
<style>
/* ── page wrapper ── */
body.dark-mode .student-dashboard {
    background-color: #0a1628 !important;
}

/* ── filter card (card-outline) ── */
body.dark-mode .filtration-section .card-outline {
    background: #0f2040 !important;
    border-color: rgba(255,255,255,.07) !important;
    box-shadow: 0 0 15px rgba(0,0,0,.3) !important;
}
body.dark-mode .filtration-section .card-header {
    background: #0d1e38 !important;
    border-color: rgba(255,255,255,.06) !important;
}

/* ── active filter tags ── */
body.dark-mode .active-filters {
    background: #0f2040 !important;
    border-color: rgba(255,255,255,.06) !important;
}
body.dark-mode .filter-tag {
    background-color: #0d1e38 !important;
    border-color: rgba(255,255,255,.1) !important;
    color: #a8b8d0 !important;
}
body.dark-mode .filter-badge .badge {
    background-color: #0d2a4a !important;
    color: #90c8f0 !important;
}

/* ── filters panel ── */
body.dark-mode .filters-panel {
    background: #0f2040 !important;
    box-shadow: 0 5px 20px rgba(0,0,0,.4) !important;
}
body.dark-mode .filters-header,
body.dark-mode .filters-footer {
    background-color: #0d1e38 !important;
    border-color: rgba(255,255,255,.07) !important;
}
body.dark-mode .filters-header h5 { color: #d0d8e8 !important; }

/* ── students table wrapper ── */
body.dark-mode .students-view {
    background: #0f2040 !important;
    box-shadow: 0 3px 10px rgba(0,0,0,.4) !important;
}

/* ── table head ── */
body.dark-mode .students-view .table thead th {
    background-color: #0d1e38 !important;
    color: #90a4c8 !important;
    border-bottom: 1px solid rgba(255,255,255,.07) !important;
}

/* ── table rows ── */
body.dark-mode .students-view .table td {
    border-color: rgba(255,255,255,.05) !important;
    color: #c8d2e6 !important;
}
body.dark-mode .student-row:hover {
    background-color: rgba(26,107,255,.07) !important;
}

/* status-highlighted rows */
body.dark-mode .bg-warning-ultra-light { background-color: rgba(255,193,7,.06) !important; }
body.dark-mode .bg-success-ultra-light { background-color: rgba(40,167,69,.06) !important; }
body.dark-mode .bg-danger-ultra-light  { background-color: rgba(220,53,69,.06) !important; }

/* ── id badge ── */
body.dark-mode .id-badge {
    background-color: #1a3050 !important;
    color: #90a4c8 !important;
}

/* ── student name link ── */
body.dark-mode .student-name { color: #c8d2e6 !important; }
body.dark-mode .student-name:hover { color: #6ea8fe !important; }

/* ── passport / small text ── */
body.dark-mode .passport-id    { color: #4a6080 !important; }
body.dark-mode .study-item     { color: #90a4c8 !important; }
body.dark-mode .contact-item   { color: #90a4c8 !important; }
body.dark-mode .last-updated span { color: #3a5070 !important; }
body.dark-mode .nationality    { color: #c8d2e6 !important; }

/* ── created date ── */
body.dark-mode .created-date { color: #c8d2e6 !important; }
body.dark-mode .created-time { color: #4a6080 !important; }

/* ── action icon buttons ── */
body.dark-mode .btn-icon.btn-light {
    background-color: #1a3050 !important;
    border-color: rgba(255,255,255,.08) !important;
    color: #90a4c8 !important;
}
body.dark-mode .btn-icon.btn-light:hover {
    background-color: #1e3d6a !important;
    color: #6ea8fe !important;
}

/* ── status badges ── */
body.dark-mode .status-badge.badge-primary { background-color: rgba(78,115,223,.2) !important; color: #6ea8fe !important; }
body.dark-mode .status-badge.badge-info    { background-color: rgba(23,162,184,.2) !important; color: #56d8e4 !important; }
body.dark-mode .status-badge.badge-warning { background-color: rgba(255,193,7,.2)  !important; color: #f6c23e !important; }
body.dark-mode .status-badge.badge-success { background-color: rgba(40,167,69,.2)  !important; color: #56d888 !important; }
body.dark-mode .status-badge.badge-danger  { background-color: rgba(220,53,69,.2)  !important; color: #f87171 !important; }
body.dark-mode .status-badge.badge-secondary { background-color: rgba(108,117,125,.2) !important; color: #94a3b8 !important; }
body.dark-mode .status-badge.badge-success-dark { background-color: rgba(40,167,69,.25) !important; color: #4ade80 !important; }

/* ── badge-soft-info (Transfer) ── */
body.dark-mode .badge-soft-info {
    background-color: rgba(23,162,184,.15) !important;
    color: #56d8e4 !important;
}

/* ── delete modal ── */
body.dark-mode .delete-modal .student-to-delete {
    background-color: #0d1e38 !important;
    color: #c8d2e6 !important;
}
body.dark-mode .delete-icon {
    background-color: rgba(220,53,69,.15) !important;
    color: #f87171 !important;
}

/* ── student cards view ── */
body.dark-mode .student-card {
    background: #0f2040 !important;
    box-shadow: 0 3px 10px rgba(0,0,0,.4) !important;
}
body.dark-mode .student-card:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,.5) !important;
}
body.dark-mode .student-card .card-header {
    background: #0f2040 !important;
    border-color: rgba(255,255,255,.06) !important;
}
body.dark-mode .student-card .card-body {
    background: #0f2040 !important;
}
body.dark-mode .student-card .card-footer {
    background-color: #0d1e38 !important;
    border-color: rgba(255,255,255,.06) !important;
}
body.dark-mode .info-label { color: #4a6080 !important; }
body.dark-mode .info-value { color: #c8d2e6 !important; }
body.dark-mode .student-card .student-name a { color: #c8d2e6 !important; }
body.dark-mode .student-card .student-name a:hover { color: #6ea8fe !important; }

/* ── quick summary bar ── */
body.dark-mode .quick-summary-bar {
    background: #0f2040 !important;
    box-shadow: 0 3px 10px rgba(0,0,0,.3) !important;
}
body.dark-mode .summary-info { color: #4a6080 !important; }

/* ── success notification ── */
body.dark-mode .success-notification {
    background: #0f2040 !important;
    box-shadow: 0 5px 20px rgba(0,0,0,.5) !important;
}
body.dark-mode .notification-content h6 { color: #d0d8e8 !important; }
body.dark-mode .notification-content p  { color: #4a6080 !important; }
body.dark-mode .notification-close      { color: #4a6080 !important; }

/* ── empty state ── */
body.dark-mode .empty-state h4 { color: #c8d2e6 !important; }
body.dark-mode .empty-state p  { color: #4a6080 !important; }

/* ── pagination ── */
body.dark-mode .page-item .page-link {
    background-color: #0f2040 !important;
    color: #90a4c8 !important;
    box-shadow: 0 2px 5px rgba(0,0,0,.3) !important;
}
body.dark-mode .page-item.active .page-link {
    background-color: #1a6bff !important;
    color: #fff !important;
}
body.dark-mode .page-item.disabled .page-link {
    background-color: #0a1628 !important;
    color: #3a5070 !important;
}
</style>
@endsection