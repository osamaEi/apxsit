@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Universities</h3>
                    <div class="card-tools">
                            <div class="btn-group">
                                <a href="{{ route('admin.universities.export-excel', [
                                    'search' => request('search'),
                                    'country_id' => request('country_id'),
                                    'type' => request('type'),
                                    'is_active' => request('is_active')
                                ]) }}" class="btn btn-success mr-2">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                                <a href="{{ route('admin.universities.export-pdf', [
                                    'search' => request('search'),
                                    'country_id' => request('country_id'),
                                    'type' => request('type'),
                                    'is_active' => request('is_active')
                                ]) }}" class="btn btn-danger mr-2">
                                    <i class="fas fa-file-pdf"></i> Export PDF
                                </a>
                        
                                @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register')
                                <a href="{{ route('admin.universities.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add University
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="mb-4">
                        <form action="{{ route('admin.universities.index') }}" method="GET" class="row">
                            <div class="col-md-3 mb-2">
                                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ $search }}">
                            </div>
                            <div class="col-md-2 mb-2">
                                <select name="country_id" class="form-control">
                                    <option value="">All Countries</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $countryFilter == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <select name="type" class="form-control">
                                    <option value="">All Types</option>
                                    @foreach($types as $key => $value)
                                        <option value="{{ $key }}" {{ $typeFilter == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <select name="is_active" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="1" {{ $statusFilter === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $statusFilter === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>

                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Universities Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($universities as $university)
                                    <tr>
                                        <td class="text-center">
                                            @if($university->logo)
                                                <img src="{{ Storage::url($university->logo) }}" alt="{{ $university->name }}" class="img-thumbnail" style="max-height: 50px;">
                                            @else
                                                <span class="text-muted">No logo</span>
                                            @endif
                                        </td>
                                        <td>{{ $university->name }}</td>
                                        <td>{{ $university->country->name ?? 'N/A' }}</td>
                                        <td>{{ $university->city->name ?? 'N/A' }}</td>
                                        <td>{{ $types[$university->type] ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $university->is_active ? 'success' : 'danger' }}">
                                                {{ $university->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(auth()->user()->role == 'Admin' || auth()->user()->role == 'Register' )

                                            <div class="btn-group">

                                                <a href="{{ route('admin.universities.show', $university) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('admin.universities.edit', $university) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                           

                                                <a href="{{ route('admin.universities.toggle-status', $university) }}" class="btn btn-sm btn-{{ $university->is_active ? 'warning' : 'success' }}">
                                                    <i class="fas fa-{{ $university->is_active ? 'times' : 'check' }}"></i>
                                                </a>
                                             
                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal{{ $university->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                

                                            </div>
                                            @endif

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $university->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $university->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $university->id }}">Confirm Delete</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete <strong>{{ $university->name }}</strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('admin.universities.destroy', $university) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No universities found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $universities->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection