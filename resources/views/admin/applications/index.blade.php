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
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.applications.index') }}" class="mb-4">
                        <div class="row">
                            <!-- Student Filter -->
                            <div class="col-lg-3 col-md-6 mb-2">
                                <div class="input-group">
                                    <select name="student_id" class="form-control select2">
                                        <option value="">All Students</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->first_name }} {{ $student->last_name }} (#{{ $student->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- University Filter -->
                            <div class="col-lg-3 col-md-6 mb-2">
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-left" type="button" id="universityDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ !empty(request('university_id')) ? count(request('university_id')) . ' Universities Selected' : 'All Universities' }}
                                    </button>
                                    <div class="dropdown-menu w-100 p-3" aria-labelledby="universityDropdown">
                                        <div class="mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="selectAllUniversities">
                                                <label class="custom-control-label" for="selectAllUniversities">Select All</label>
                                            </div>
                                        </div>
                                        <div class="dropdown-divider"></div>
                                        <div style="max-height: 200px; overflow-y: auto;">
                                            @foreach(App\Models\University::where('is_active', true)->orderBy('name')->get() as $university)
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input university-checkbox" 
                                                        name="university_id[]" 
                                                        id="university{{ $university->id }}" 
                                                        value="{{ $university->id }}"
                                                        {{ (is_array(request('university_id')) && in_array($university->id, request('university_id'))) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="university{{ $university->id }}">
                                                        {{ $university->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Department Filter -->
                            <div class="col-lg-2 col-md-6 mb-2">
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-left" type="button" id="departmentDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ !empty(request('department')) ? count(request('department')) . ' Departments Selected' : 'All Departments' }}
                                    </button>
                                    <div class="dropdown-menu w-100 p-3" aria-labelledby="departmentDropdown">
                                        <div class="mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="selectAllDepartments">
                                                <label class="custom-control-label" for="selectAllDepartments">Select All</label>
                                            </div>
                                        </div>
                                        <div class="dropdown-divider"></div>
                                        <div style="max-height: 200px; overflow-y: auto;">
                                            @foreach(App\Models\Department::orderBy('name')->get() as $department)
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input department-checkbox" 
                                                        name="department[]" 
                                                        id="department{{ $loop->index }}" 
                                                        value="{{ $department->name }}"
                                                        {{ (is_array(request('department')) && in_array($department->name, request('department'))) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="department{{ $loop->index }}">
                                                        {{ $department->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Degree Filter -->
                            <div class="col-lg-2 col-md-6 mb-2">
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-left" type="button" id="degreeDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ !empty(request('degree')) ? count(request('degree')) . ' Degrees Selected' : 'All Degrees' }}
                                    </button>
                                    <div class="dropdown-menu w-100 p-3" aria-labelledby="degreeDropdown">
                                        <div class="mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="selectAllDegrees">
                                                <label class="custom-control-label" for="selectAllDegrees">Select All</label>
                                            </div>
                                        </div>
                                        <div class="dropdown-divider"></div>
                                        <div style="max-height: 200px; overflow-y: auto;">
                                            @foreach(App\Models\Degree::orderBy('name')->get() as $degree)
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input degree-checkbox" 
                                                        name="degree[]" 
                                                        id="degree{{ $loop->index }}" 
                                                        value="{{ $degree->name }}"
                                                        {{ (is_array(request('degree')) && in_array($degree->name, request('degree'))) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="degree{{ $loop->index }}">
                                                        {{ $degree->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Filter -->
                            <div class="col-lg-2 col-md-4 mb-2">
                                <div class="input-group">
                                    <select name="status" class="form-control">
                                        <option value="">All Statuses</option>
                                        @foreach(App\Models\Application::STATUSES as $statusKey => $statusValue)
                                            <option value="{{ $statusKey }}" {{ request('status') == $statusKey ? 'selected' : '' }}>
                                                {{ $statusValue }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Filter Buttons -->
                            <div class="col-lg-12 mt-2 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search mr-1"></i> Search
                                </button>
                                <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-redo mr-1"></i> Reset
                                </a>
                                <div class="btn-group ml-2">
                                    <a href="{{ route('admin.applications.export-excel', request()->all()) }}" class="btn btn-success">
                                        <i class="fas fa-file-excel mr-1"></i> Excel
                                    </a>
                                    <a href="{{ route('admin.applications.export-pdf', request()->all()) }}" class="btn btn-danger">
                                        <i class="fas fa-file-pdf mr-1"></i> PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <style>
                        /* Bootstrap dropdown styles */
                        .dropdown-menu {
                            max-height: 300px;
                            overflow-y: auto;
                            width: 100%;
                            z-index: 1050;
                        }
                        
                        /* Keep dropdown open when clicking inside */
                        .dropdown-menu.show {
                            display: block;
                        }
                        
                        /* Custom checkbox spacing */
                        .custom-checkbox {
                            margin-bottom: 8px;
                        }
                    </style>
                    
                    <script>
                    $(document).ready(function() {
                        // Initialize Select2
                        $('.select2').select2({
                            theme: 'bootstrap4',
                            width: '100%'
                        });
                        
                        // Prevent dropdown from closing when clicking inside
                        $('.dropdown-menu').on('click', function(e) {
                            e.stopPropagation();
                        });
                        
                        // Update button text based on selections
                        function updateDropdownText(checkboxClass, buttonId) {
                            var selectedCount = $('.' + checkboxClass + ':checked').length;
                            var text = 'All Universities';
                            
                            if (buttonId === 'departmentDropdown') {
                                text = 'All Departments';
                            } else if (buttonId === 'degreeDropdown') {
                                text = 'All Degrees';
                            }
                            
                            if (selectedCount > 0) {
                                text = selectedCount + ' ' + 
                                      (buttonId === 'universityDropdown' ? 'Universities' : 
                                       buttonId === 'departmentDropdown' ? 'Departments' : 'Degrees') + 
                                      ' Selected';
                            }
                            
                            $('#' + buttonId).text(text);
                        }
                        
                        // Handle "Select All" checkboxes
                        $('#selectAllUniversities').on('change', function() {
                            $('.university-checkbox').prop('checked', $(this).prop('checked'));
                            updateDropdownText('university-checkbox', 'universityDropdown');
                        });
                        
                        $('#selectAllDepartments').on('change', function() {
                            $('.department-checkbox').prop('checked', $(this).prop('checked'));
                            updateDropdownText('department-checkbox', 'departmentDropdown');
                        });
                        
                        $('#selectAllDegrees').on('change', function() {
                            $('.degree-checkbox').prop('checked', $(this).prop('checked'));
                            updateDropdownText('degree-checkbox', 'degreeDropdown');
                        });
                        
                        // Update "Select All" checkbox when individual checkboxes change
                        $(document).on('change', '.university-checkbox', function() {
                            var allChecked = $('.university-checkbox:checked').length === $('.university-checkbox').length;
                            $('#selectAllUniversities').prop('checked', allChecked);
                            updateDropdownText('university-checkbox', 'universityDropdown');
                        });
                        
                        $(document).on('change', '.department-checkbox', function() {
                            var allChecked = $('.department-checkbox:checked').length === $('.department-checkbox').length;
                            $('#selectAllDepartments').prop('checked', allChecked);
                            updateDropdownText('department-checkbox', 'departmentDropdown');
                        });
                        
                        $(document).on('change', '.degree-checkbox', function() {
                            var allChecked = $('.degree-checkbox:checked').length === $('.degree-checkbox').length;
                            $('#selectAllDegrees').prop('checked', allChecked);
                            updateDropdownText('degree-checkbox', 'degreeDropdown');
                        });
                        
                        // Initialize dropdown text and "Select All" checkboxes
                        updateDropdownText('university-checkbox', 'universityDropdown');
                        updateDropdownText('department-checkbox', 'departmentDropdown');
                        updateDropdownText('degree-checkbox', 'degreeDropdown');
                        
                        // Check "Select All" if all are selected
                        $('#selectAllUniversities').prop('checked', 
                            $('.university-checkbox:checked').length === $('.university-checkbox').length && 
                            $('.university-checkbox').length > 0
                        );
                        
                        $('#selectAllDepartments').prop('checked', 
                            $('.department-checkbox:checked').length === $('.department-checkbox').length && 
                            $('.department-checkbox').length > 0
                        );
                        
                        $('#selectAllDegrees').prop('checked', 
                            $('.degree-checkbox:checked').length === $('.degree-checkbox').length && 
                            $('.degree-checkbox').length > 0
                        );
                    });
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
<!-- jQuery first (required for Bootstrap 4) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Popper.js (required for dropdown functionality) -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 Elements
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Select options',
        allowClear: true
    });
    
    // Setup CSRF for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Delete modal setup
    $('.delete-btn').on('click', function() {
        const url = $(this).data('url');
        $('#delete-form').attr('action', url);
    });
    
    // DataTable initialization with export buttons
    $('#applications-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "order": [[0, "desc"]],
        "pageLength": 25,
        "responsive": true,
        "stateSave": true,
        "columnDefs": [
            { "orderable": false, "targets": 9 }
        ]
    });
});
</script>

@endsection