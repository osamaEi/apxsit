{{-- resources/views/admin/announcements/show.blade.php --}}
@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Announcement Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-primary mr-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 order-2 order-md-1">
                            <h2>{{ $announcement->title }}</h2>
                            
                            <div class="mb-4">
                                <span class="badge badge-{{ $announcement->status_color }} p-2">
                                    {{ $announcement->status_label }}
                                </span>
                                
                                @if($announcement->published_at)
                                    <span class="text-muted ml-2">
                                        <i class="far fa-calendar-alt"></i> Published: {{ $announcement->published_at->format('F d, Y \a\t H:i') }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Description</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-justify">{!! $announcement->description !!}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Location Details</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th class="bg-light" width="40%">University</th>
                                                    <td>
                                                        <a href="{{ route('admin.universities.show', $announcement->university_id) }}">
                                                            {{ $announcement->university->name ?? 'N/A' }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">Country</th>
                                                    <td>{{ $announcement->country->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">City</th>
                                                    <td>{{ $announcement->city->name ?? 'N/A' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Additional Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th class="bg-light" width="40%">Created By</th>
                                                    <td>{{ $announcement->creator->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">Created At</th>
                                                    <td>{{ $announcement->created_at->format('M d, Y H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">Last Updated</th>
                                                    <td>{{ $announcement->updated_at->format('M d, Y H:i') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 order-1 order-md-2 mb-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Announcement Image</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($announcement->image)
                                        <img src="{{ Storage::url($announcement->image) }}" alt="{{ $announcement->title }}" class="img-fluid rounded">
                                    @else
                                        <div class="border p-5 rounded">
                                            <i class="fas fa-bullhorn fa-5x text-muted"></i>
                                            <p class="mt-3 text-muted">No image available</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card mt-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-edit mr-2"></i> Edit Announcement
                                        </a>
                                        
                                        @if(!$announcement->is_active)
                                            <a href="{{ route('admin.announcements.toggle-status', $announcement) }}" class="list-group-item list-group-item-action list-group-item-success">
                                                <i class="fas fa-check mr-2"></i> Activate Announcement
                                            </a>
                                        @else
                                            <a href="{{ route('admin.announcements.toggle-status', $announcement) }}" class="list-group-item list-group-item-action list-group-item-warning">
                                                <i class="fas fa-ban mr-2"></i> Deactivate Announcement
                                            </a>
                                        @endif
                                        
                                        @if(!$announcement->published_at)
                                            <a href="{{ route('admin.announcements.publish', $announcement) }}" class="list-group-item list-group-item-action list-group-item-primary">
                                                <i class="fas fa-cloud-upload-alt mr-2"></i> Publish Now
                                            </a>
                                        @else
                                            <a href="{{ route('admin.announcements.unpublish', $announcement) }}" class="list-group-item list-group-item-action list-group-item-secondary">
                                                <i class="fas fa-undo mr-2"></i> Revert to Draft
                                            </a>
                                        @endif
                                        
                                        <button type="button" class="list-group-item list-group-item-action list-group-item-danger" data-toggle="modal" data-target="#deleteModal">
                                            <i class="fas fa-trash mr-2"></i> Delete Announcement
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                Are you sure you want to delete the announcement: <strong>{{ $announcement->title }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection