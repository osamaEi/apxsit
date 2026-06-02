@extends('admin.index')


@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          
          
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Notifications</h3>
                        <div class="card-tools">
                         
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse(auth()->user()->notifications as $notification)
                                @php
                                    $data = $notification->data;
                                    $isRead = $notification->read_at !== null;
                                @endphp
                                <li class="list-group-item {{ $isRead ? 'bg-light' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="notification-content">
                                            <div class="d-flex align-items-center">
                                                <i class="fas {{ $isRead ? 'fa-envelope-open' : 'fa-envelope' }} mr-2 {{ $isRead ? 'text-muted' : 'text-primary' }}"></i>
                                                <div>
                                                    <p class="mb-1 {{ $isRead ? 'text-muted' : 'font-weight-bold' }}">{{ $data['message'] ?? 'Notification' }}</p>
                                                    <small class="text-muted">{{ $data['time'] ?? $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="notification-actions">
                                            @if(isset($data['application_id']))
                                                <a href="{{ route('admin.applications.show', $data['application_id']) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View Application
                                                </a>
                                            @elseif(isset($data['student_id']))
                                                <a href="{{ route('admin.students.show', $data['student_id']) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View Student
                                                </a>
                                            @endif
                                            
                                            @if(!$isRead)
                                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline ml-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-check"></i> Mark as Read
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center py-4">
                                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">You don't have any notifications yet.</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    @if(auth()->user()->notifications->count() > 0)
                        <div class="card-footer">
                            <div class="float-right">
                               
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection