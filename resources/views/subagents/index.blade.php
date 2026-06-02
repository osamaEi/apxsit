@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ __('Your Subagents') }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('subagents.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> {{ __('Add New Subagent') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="bg-white rounded shadow-sm">
        @if($subagents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>{{ __('Photo') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Country') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Created On') }}</th>
                            <th>{{ __('Contracted Countries') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subagents as $subagent)
                            <tr>
                                <td class="align-middle">
                                    @if($subagent->photo)
                                        <img src="{{ asset('storage/' . $subagent->photo) }}" alt="{{ $subagent->name }}" class="rounded-circle" width="40" height="40">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;">
                                            {{ substr($subagent->name, 0, 1) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle">{{ $subagent->name }}</td>
                                <td class="align-middle">{{ $subagent->email }}</td>
                                <td class="align-middle">{{ $subagent->phone ?: '-' }}</td>
                                <td class="align-middle">{{ $subagent->country ? $subagent->country->name : '-' }}</td>
                                <td class="align-middle">
                                    @if($subagent->type == 'individual')
                                        <span class="badge bg-info">{{ __('Individual') }}</span>
                                    @else
                                        <span class="badge bg-primary">{{ __('Company') }}</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ $subagent->created_at->format('M d, Y') }}</td>
                                <td class="align-middle">
                                    @if($subagent->countryCommissions->count() > 0)
                                        <div class="contracted-countries">
                                            @foreach($subagent->countryCommissions as $commission)
                                                <div class="mb-1">
                                                    <span class="badge bg-light text-dark border">
                                                        {{ $commission->country->name }} 
                                                        <span class="text-success">{{ $commission->commission_rate }}%</span>
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">{{ __('None') }}</span>
                                    @endif
                                </td>
                               <td class="align-middle text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('subagents.edit', $subagent->id) }}" class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                   
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $subagent->id }}" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $subagent->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">{{ __('Confirm Delete') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('Are you sure you want to delete this subagent?') }} <br>
                                                    <strong>{{ $subagent->name }} ({{ $subagent->email }})</strong>
                                                    <p class="text-danger mt-2">{{ __('This action cannot be undone.') }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                                    <form action="{{ route('subagents.destroy', $subagent->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td> 
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-users fa-3x text-muted"></i>
                </div>
                <h4>{{ __('No subagents found') }}</h4>
                <p class="text-muted">{{ __('You haven\'t added any subagents yet.') }}</p>
                <a href="{{ route('subagents.create') }}" class="btn btn-primary">
                    {{ __('Add Your First Subagent') }}
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Commission tooltip script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips if Bootstrap 5 is used
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });
</script>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const closeButton = alert.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.click();
                }
            }, 5000);
        });
    });
</script>
@endsection