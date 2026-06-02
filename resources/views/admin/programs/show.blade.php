@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Program Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-primary mr-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.programs.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="bg-light" width="40%">University</th>
                                            <td>
                                                <a href="{{ route('admin.universities.show', $program->university_id) }}">
                                                    {{ $program->university->name ?? 'N/A' }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Department</th>
                                            <td>{{ $program->department }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Degree</th>
                                            <td>{{ $program->degree }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Language</th>
                                            <td>{{ $program->language }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Shift Type</th>
                                            <td>{{ $program->shift_type }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Status</th>
                                            <td>
                                                <span class="badge badge-{{ $program->status === 'Active' ? 'success' : ($program->status === 'Inactive' ? 'danger' : 'warning') }}">
                                                    {{ $program->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Financial Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="bg-light" width="40%">Price Before Discount</th>
                                            <td>${{ number_format($program->before_discount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Price After Discount</th>
                                            <td>${{ number_format($program->after_discount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Discount</th>
                                            <td>
                                                <span class="badge badge-info">{{ $program->discount_percentage }}%</span>
                                                (${{ number_format($program->before_discount - $program->after_discount, 2) }})
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Cash Discount</th>
                                            <td>${{ number_format($program->cash_discount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Deposit Payment</th>
                                            <td>${{ number_format($program->deposit_payment, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Siblings Discount</th>
                                            <td>{{ $program->siblings_discount }}%</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Timeline</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="bg-light" width="20%">Created At</th>
                                            <td>{{ $program->created_at->format('F d, Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Last Updated</th>
                                            <td>{{ $program->updated_at->format('F d, Y H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group">
                        <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.programs.toggle-status', $program) }}" class="btn btn-{{ $program->status === 'Active' ? 'warning' : 'success' }}">
                            <i class="fas fa-{{ $program->status === 'Active' ? 'times' : 'check' }}"></i> 
                            {{ $program->status === 'Active' ? 'Deactivate' : 'Activate' }}
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
                Are you sure you want to delete the program: <strong>{{ $program->department }} ({{ $program->degree }})</strong> from <strong>{{ $program->university->name ?? 'Unknown University' }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.programs.destroy', $program) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection