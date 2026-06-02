{{-- resources/views/admin/universities/show.blade.php --}}
@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">University Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.universities.edit', $university) }}" class="btn btn-primary mr-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.universities.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            @if($university->logo)
                                <img src="{{ Storage::url($university->logo) }}" alt="{{ $university->name }}" class="img-fluid img-thumbnail" style="max-height: 200px;">
                            @else
                                <div class="border p-5 rounded">
                                    <i class="fas fa-university fa-5x text-muted"></i>
                                    <p class="mt-3 text-muted">No logo available</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-8">
                            <h2>{{ $university->name }}</h2>
                            <p>
                                <span class="badge badge-{{ $university->is_active ? 'success' : 'danger' }}">
                                    {{ $university->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($university->type)
                                    <span class="badge badge-info">{{ $types[$university->type] ?? $university->type }}</span>
                                @endif
                            </p>
                            
                            <table class="table table-bordered mt-3">
                                <tr>
                                    <th style="width: 200px;">Country</th>
                                    <td>{{ $university->country->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td>{{ $university->city->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $university->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $university->created_at->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td>{{ $university->updated_at->format('F d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($university->description)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Description</h5>
                                    </div>
                                    <div class="card-body">
                                        {{ $university->description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="btn-group">
                        <a href="{{ route('admin.universities.edit', $university) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.universities.toggle-status', $university) }}" class="btn btn-{{ $university->is_active ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $university->is_active ? 'times' : 'check' }}"></i> 
                            {{ $university->is_active ? 'Deactivate' : 'Activate' }}
                        </a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
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
@endsection