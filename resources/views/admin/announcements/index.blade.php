@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bullhorn mr-1"></i>
                        Announcements Management
                    </h3>
                       <!-- Updated Export Buttons -->
<div class="card-tools">
    <div class="btn-group">
        <a href="{{ route('admin.announcements.export-excel', [
            'search' => request('search'),
            'university_id' => request('university_id'),
            'country_id' => request('country_id'),
            'city_id' => request('city_id'),
            'status' => request('status'),
            'date_from' => request('date_from'),
            'date_to' => request('date_to')
        ]) }}" class="btn btn-success btn-sm mr-1">
            <i class="fas fa-file-excel mr-1"></i> Export Excel
        </a>
        <a href="{{ route('admin.announcements.export-pdf', [
            'search' => request('search'),
            'university_id' => request('university_id'),
            'country_id' => request('country_id'),
            'city_id' => request('city_id'),
            'status' => request('status'),
            'date_from' => request('date_from'),
            'date_to' => request('date_to')
        ]) }}" class="btn btn-danger btn-sm mr-1">
            <i class="fas fa-file-pdf mr-1"></i> Export PDF
        </a>
        @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register')
            <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Add Announcement
            </a>
        @endif
    </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $announcements->total() }}</h3>
                                    <p>Total Announcements</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $publishedCount ?? $announcements->where('status_label', 'Published')->count() }}</h3>
                                    <p>Published</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $draftCount ?? $announcements->where('status_label', 'Draft')->count() }}</h3>
                                    <p>Drafts</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $inactiveCount ?? $announcements->where('is_active', false)->count() }}</h3>
                                    <p>Inactive</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-ban"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Search -->
                    <div class="card card-outline card-info mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-search mr-1"></i> Advanced Search</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.announcements.index') }}" method="GET" class="row">
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label>Search</label>
                                        <input type="text" name="search" class="form-control" placeholder="Search title or description..." value="{{ $search }}">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label>University</label>
                                        <select name="university_id" class="form-control select2bs4">
                                            <option value="">All Universities</option>
                                            @foreach($universities as $university)
                                                <option value="{{ $university->id }}" {{ $universityFilter == $university->id ? 'selected' : '' }}>
                                                    {{ $university->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <select name="country_id" class="form-control select2bs4">
                                            <option value="">All Countries</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ $countryFilter == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label>City</label>
                                        <select name="city_id" class="form-control select2bs4">
                                            <option value="">All Cities</option>
                                            @if(isset($cities))
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}" {{ $cityFilter == $city->id ? 'selected' : '' }}>
                                                        {{ $city->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control select2bs4">
                                            <option value="">All Status</option>
                                            <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="published" {{ $statusFilter === 'published' ? 'selected' : '' }}>Published</option>
                                            <option value="draft" {{ $statusFilter === 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="scheduled" {{ $statusFilter === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label>Publication Date Range</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                                            <div class="input-group-prepend input-group-append">
                                                <span class="input-group-text">to</span>
                                            </div>
                                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2 d-flex align-items-end">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-redo mr-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Announcements Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="70">Image</th>
                                    <th>Title & Description</th>
                                    <th>University</th>
                                    <th>Location</th>
                                    <th>Publication</th>
                                    <th>Status</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($announcements as $announcement)
                                    <tr>
                                        <td class="text-center">
                                            @if($announcement->image)
                                                <img src="{{ Storage::url($announcement->image) }}" alt="{{ $announcement->title }}" class="img-thumbnail" style="max-height: 50px;">
                                            @else
                                                <i class="fas fa-bullhorn fa-2x text-muted"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.announcements.show', $announcement) }}" class="font-weight-bold">
                                                {{ Str::limit($announcement->title, 50) }}
                                            </a>
                                         
                                        </td>
                                        <td>
                                            @if($announcement->university)
                                                <div class="d-flex align-items-center">
                                                    @if($announcement->university->logo)
                                                        <img src="{{ Storage::url($announcement->university->logo) }}" alt="{{ $announcement->university->name }}" class="img-thumbnail mr-2" style="max-height: 40px;">
                                                    @endif
                                                    <span>{{ $announcement->university->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($announcement->country)
                                                <span class="d-block">
                                                    <i class="fas fa-map-marker-alt mr-1 text-danger"></i>
                                                    {{ $announcement->city->name ?? 'N/A' }}
                                                </span>
                                                <span class="badge badge-light">
                                                    {{ $announcement->country->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($announcement->published_at)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-calendar-check mr-1"></i>
                                                    {{ $announcement->published_at->format('M d, Y') }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-calendar-times mr-1"></i>
                                                    Not Published
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $announcement->status_color }} badge-pill px-3 py-2">
                                                <i class="fas fa-{{ $announcement->status_label === 'Published' ? 'check-circle' : ($announcement->status_label === 'Draft' ? 'edit' : ($announcement->status_label === 'Scheduled' ? 'clock' : 'times-circle')) }} mr-1"></i>
                                                {{ $announcement->status_label }}
                                            </span>
                                        </td>

                                        
                                        <td>

                                            @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register' )

                                            <div class="btn-group">
                                                <a href="{{ route('admin.announcements.show', $announcement) }}" class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                @if(!$announcement->published_at)
                                                    <a href="{{ route('admin.announcements.publish', $announcement) }}" class="btn btn-sm btn-success" title="Publish">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.announcements.unpublish', $announcement) }}" class="btn btn-sm btn-warning" title="Unpublish">
                                                        <i class="fas fa-undo"></i>
                                                    </a>
                                                @endif
                                                
                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal{{ $announcement->id }}" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
@endif
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $announcement->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $announcement->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $announcement->id }}">
                                                                <i class="fas fa-exclamation-triangle mr-1"></i> Confirm Delete
                                                            </h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete this announcement?</p>
                                                            <div class="alert alert-warning">
                                                                <strong>{{ $announcement->title }}</strong>
                                                            </div>
                                                            <p class="text-danger"><small><i class="fas fa-info-circle mr-1"></i> This action cannot be undone.</small></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                <i class="fas fa-times mr-1"></i> Cancel
                                                            </button>
                                                            <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST">
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
                                        <td colspan="7" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                                                <h5>No announcements found</h5>
                                                <p class="text-muted">Try adjusting your search criteria or add a new announcement.</p>
                                                <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary mt-2">
                                                    <i class="fas fa-plus mr-1"></i> Add Announcement
                                                </a>
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
                                @if($announcements->total() > 0)
                                    Showing {{ $announcements->firstItem() }} to {{ $announcements->lastItem() }} of {{ $announcements->total() }} entries
                                @else
                                    Showing 0 to 0 of 0 entries
                                @endif
                            </span>
                        </div>
                        <div>
                            {{ $announcements->appends(request()->query())->links() }}
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
    .description-preview {
        max-height: 70px;
        overflow: hidden;
        color: #6c757d;
        font-size: 0.85rem;
    }
</style>

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<!-- Select2 and CardWidget JS -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(function() {
    // Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);
    
    // Dynamic city loading based on country selection
    $('select[name="country_id"]').on('change', function() {
        var countryId = $(this).val();
        if(countryId) {
            $.ajax({
                url: '/api/countries/'+countryId+'/cities',
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('select[name="city_id"]').empty();
                    $('select[name="city_id"]').append('<option value="">All Cities</option>');
                    $.each(data, function(key, value) {
                        $('select[name="city_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        } else {
            $('select[name="city_id"]').empty();
            $('select[name="city_id"]').append('<option value="">All Cities</option>');
        }
    });
});
</script>
@endsection