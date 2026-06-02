@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Students with Pending Documents</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to All Students
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Students Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Missing Documents</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>{{ $student->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.students.show', $student) }}">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </a>
                                        </td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->phone_number }}</td>
                                        <td>
                                            @if(!$student->photo_path)
                                                <span class="badge badge-warning mr-1">Photo</span>
                                            @endif
                                            @if(!$student->passport_path)
                                                <span class="badge badge-warning mr-1">Passport</span>
                                            @endif
                                            @if(!$student->transcript_path)
                                                <span class="badge badge-warning mr-1">Transcript</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($student->status == 'New')
                                                <span class="badge badge-primary">New</span>
                                            @elseif($student->status == 'In Review')
                                                <span class="badge badge-info">In Review</span>
                                            @elseif($student->status == 'Pending Documents')
                                                <span class="badge badge-warning">Pending Documents</span>
                                            @elseif($student->status == 'Accepted')
                                                <span class="badge badge-success">Accepted</span>
                                            @elseif($student->status == 'Rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @elseif($student->status == 'Enrolled')
                                                <span class="badge badge-success">Enrolled</span>
                                            @elseif($student->status == 'Cancelled')
                                                <span class="badge badge-secondary">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#statusModal{{ $student->id }}">
                                                    <i class="fas fa-clipboard-check"></i>
                                                </button>
                                            </div>

                                            <!-- Status Modal -->
                                            <div class="modal fade" id="statusModal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel{{ $student->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="statusModalLabel{{ $student->id }}">Update Status</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('admin.students.change-status', $student) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="status{{ $student->id }}">Status</label>
                                                                    <select class="form-control" id="status{{ $student->id }}" name="status">
                                                                        @foreach($student::getStatuses() as $key => $value)
                                                                            <option value="{{ $key }}" {{ $student->status == $key ? 'selected' : '' }}>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="notes{{ $student->id }}">Notes</label>
                                                                    <textarea class="form-control" id="notes{{ $student->id }}" name="notes" rows="3">{{ $student->notes }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Update Status</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                                <p class="text-muted">Great! No students with pending documents.</p>
                                                <a href="{{ route('admin.students.index') }}" class="btn btn-primary">
                                                    <i class="fas fa-arrow-left"></i> Back to Students
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
    }
</style>
@endsection