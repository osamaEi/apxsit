@extends('admin.index')

@section('content')
@php
$roleMap = [
    'Admin'    => ['bg'=>'#fee2e2','text'=>'#dc2626','icon'=>'fa-crown',         'bar'=>'#dc2626'],
    'Sales'    => ['bg'=>'#dcfce7','text'=>'#16a34a','icon'=>'fa-chart-line',    'bar'=>'#16a34a'],
    'Register' => ['bg'=>'#fef9c3','text'=>'#ca8a04','icon'=>'fa-clipboard-list','bar'=>'#ca8a04'],
    'Employee' => ['bg'=>'#dbeafe','text'=>'#2563eb','icon'=>'fa-user-tie',      'bar'=>'#2563eb'],
];
$rc = $roleMap[$user->role] ?? $roleMap['Employee'];
@endphp

<div class="container-fluid">

    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-circle mr-2 text-primary"></i>User Profile
            </h1>
            <small class="text-muted">
                <a href="{{ route('users.index') }}" class="text-muted">Users</a>
                <i class="fas fa-chevron-right mx-1" style="font-size:10px"></i>
                {{ $user->name }}
            </small>
        </div>
        <div class="d-flex" style="gap:8px">
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Left: Profile card --}}
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="usp-card shadow">
                {{-- Role color bar --}}
                <div style="height:5px; background:{{ $rc['bar'] }}; border-radius:14px 14px 0 0"></div>

                <div class="usp-card-body">
                    {{-- Avatar --}}
                    <div class="usp-avatar" style="background:{{ $rc['bg'] }}; color:{{ $rc['text'] }}">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>

                    <h4 class="usp-name">{{ $user->name }}</h4>
                    <p class="usp-email">{{ $user->email }}</p>

                    <span class="usp-role-pill" style="background:{{ $rc['bg'] }}; color:{{ $rc['text'] }}">
                        <i class="fas {{ $rc['icon'] }}"></i> {{ $user->role }}
                    </span>

                    {{-- Status --}}
                    <div class="usp-status-row">
                        @if($user->email_verified_at)
                            <span class="usp-badge-ok"><i class="fas fa-check-circle mr-1"></i>Verified</span>
                        @else
                            <span class="usp-badge-pending"><i class="fas fa-clock mr-1"></i>Pending Verification</span>
                        @endif
                    </div>

                    {{-- Meta --}}
                    <div class="usp-meta">
                        <div class="usp-meta-row">
                            <span class="usp-meta-lbl"><i class="fas fa-hashtag"></i> User ID</span>
                            <span class="usp-meta-val">#{{ $user->id }}</span>
                        </div>
                        <div class="usp-meta-row">
                            <span class="usp-meta-lbl"><i class="fas fa-calendar-plus"></i> Joined</span>
                            <span class="usp-meta-val">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="usp-meta-row">
                            <span class="usp-meta-lbl"><i class="fas fa-sync-alt"></i> Updated</span>
                            <span class="usp-meta-val">{{ $user->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="usp-actions">
                        <a href="{{ route('users.edit', $user->id) }}" class="usp-btn usp-btn-edit">
                            <i class="fas fa-edit mr-1"></i> Edit User
                        </a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                              onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="usp-btn usp-btn-del">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Details + Timeline --}}
        <div class="col-xl-8 col-lg-7">

            {{-- Info cards --}}
            <div class="usp-section-card shadow mb-4">
                <div class="usp-section-hd">
                    <i class="fas fa-info-circle mr-2"></i> Account Details
                </div>
                <div class="usp-section-bd">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="usp-info-block">
                                <div class="usp-info-lbl"><i class="fas fa-hashtag mr-1"></i>User ID</div>
                                <div class="usp-info-val">#{{ $user->id }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="usp-info-block">
                                <div class="usp-info-lbl"><i class="fas fa-shield-alt mr-1"></i>Role</div>
                                <div class="usp-info-val">
                                    <span class="usp-role-pill-sm" style="background:{{ $rc['bg'] }}; color:{{ $rc['text'] }}">
                                        <i class="fas {{ $rc['icon'] }}"></i> {{ $user->role }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="usp-info-block">
                                <div class="usp-info-lbl"><i class="fas fa-envelope mr-1"></i>Email</div>
                                <div class="usp-info-val">{{ $user->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="usp-info-block">
                                <div class="usp-info-lbl"><i class="fas fa-check-circle mr-1"></i>Email Verified</div>
                                <div class="usp-info-val">
                                    @if($user->email_verified_at)
                                        <span style="color:#16a34a"><i class="fas fa-check-circle mr-1"></i>{{ $user->email_verified_at->format('d M Y') }}</span>
                                    @else
                                        <span style="color:#dc2626"><i class="fas fa-times-circle mr-1"></i>Not Verified</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="usp-info-block">
                                <div class="usp-info-lbl"><i class="fas fa-calendar-alt mr-1"></i>Date Joined</div>
                                <div class="usp-info-val">{{ $user->created_at->format('d M Y — H:i') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="usp-info-block">
                                <div class="usp-info-lbl"><i class="fas fa-sync-alt mr-1"></i>Last Updated</div>
                                <div class="usp-info-val">{{ $user->updated_at->format('d M Y — H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="usp-section-card shadow">
                <div class="usp-section-hd">
                    <i class="fas fa-history mr-2"></i> Activity Timeline
                </div>
                <div class="usp-section-bd">
                    <div class="usp-timeline">
                        <div class="usp-tl-item">
                            <div class="usp-tl-dot" style="background:#16a34a"></div>
                            <div class="usp-tl-content">
                                <div class="usp-tl-title">Account Created</div>
                                <div class="usp-tl-date">{{ $user->created_at->format('d M Y — h:i A') }}</div>
                            </div>
                        </div>

                        @if($user->email_verified_at)
                        <div class="usp-tl-item">
                            <div class="usp-tl-dot" style="background:#2563eb"></div>
                            <div class="usp-tl-content">
                                <div class="usp-tl-title">Email Verified</div>
                                <div class="usp-tl-date">{{ $user->email_verified_at->format('d M Y — h:i A') }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="usp-tl-item last">
                            <div class="usp-tl-dot" style="background:#f59e0b"></div>
                            <div class="usp-tl-content">
                                <div class="usp-tl-title">Last Profile Update</div>
                                <div class="usp-tl-date">{{ $user->updated_at->format('d M Y — h:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
/* ── profile card ── */
.usp-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
}
.usp-card-body {
    padding: 28px 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}
.usp-avatar {
    width: 90px; height: 90px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 36px; font-weight: 700;
    margin-bottom: 14px;
    box-shadow: 0 4px 14px rgba(0,0,0,.1);
}
.usp-name  { font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 4px; }
.usp-email { font-size: 13px; color: #94a3b8; margin: 0 0 12px; }

.usp-role-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 14px; border-radius: 20px;
    font-size: 12px; font-weight: 700;
}
.usp-role-pill-sm {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
}

.usp-status-row { margin: 12px 0; }
.usp-badge-ok {
    display: inline-flex; align-items: center;
    padding: 4px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    background: #dcfce7; color: #16a34a;
}
.usp-badge-pending {
    display: inline-flex; align-items: center;
    padding: 4px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    background: #fef9c3; color: #ca8a04;
}

.usp-meta {
    width: 100%; margin: 16px 0;
    border-top: 1px solid #f1f5f9;
    padding-top: 16px;
    text-align: left;
}
.usp-meta-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 6px 0; border-bottom: 1px solid #f8fafc; font-size: 13px;
}
.usp-meta-lbl { color: #94a3b8; display: flex; align-items: center; gap: 5px; }
.usp-meta-val { color: #1e293b; font-weight: 600; }

.usp-actions {
    width: 100%; display: flex; gap: 8px; margin-top: 16px;
}
.usp-btn {
    flex: 1; padding: 8px 0; border-radius: 9px;
    font-size: 13px; font-weight: 600; border: none;
    cursor: pointer; display: flex; align-items: center;
    justify-content: center; gap: 5px; text-decoration: none;
    transition: opacity .15s;
}
.usp-btn:hover { opacity: .85; }
.usp-btn-edit { background: linear-gradient(135deg,#1a6bff,#0a3d99); color: #fff; }
.usp-btn-del  { background: #fee2e2; color: #dc2626; }

/* ── section cards ── */
.usp-section-card { background: #fff; border-radius: 14px; overflow: hidden; }
.usp-section-hd {
    padding: 14px 20px;
    background: linear-gradient(135deg, #1a6bff, #0a3d99);
    color: #fff; font-size: 13px; font-weight: 700;
    letter-spacing: .3px;
}
.usp-section-bd { padding: 20px; }

/* info blocks */
.usp-info-block {
    background: #f8fafc; border-radius: 10px;
    padding: 12px 14px; height: 100%;
}
.usp-info-lbl { font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
.usp-info-val { font-size: 14px; font-weight: 600; color: #1e293b; }

/* timeline */
.usp-timeline { padding-left: 24px; }
.usp-tl-item {
    position: relative; padding-bottom: 24px;
}
.usp-tl-item.last { padding-bottom: 0; }
.usp-tl-item::before {
    content: ''; position: absolute;
    left: -17px; top: 18px; bottom: 0;
    width: 2px; background: #e5e9f2;
}
.usp-tl-item.last::before { display: none; }
.usp-tl-dot {
    position: absolute; left: -24px; top: 4px;
    width: 14px; height: 14px; border-radius: 50%;
    box-shadow: 0 0 0 3px #fff;
}
.usp-tl-title { font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 2px; }
.usp-tl-date  { font-size: 12px; color: #94a3b8; }
</style>

@endsection
