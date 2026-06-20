@extends('admin.index')

@section('content')
<div class="container-fluid">

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif

    {{-- Page header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-users mr-2 text-primary"></i>Users</h1>
            <p class="mb-0 text-muted small">Manage team members and their roles</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-user-plus mr-1"></i> New User
        </a>
    </div>

    {{-- Stats row --}}
    @php
        $allUsers   = $users->getCollection();
        $adminCount = $allUsers->where('role','Admin')->count();
        $salesCount = $allUsers->where('role','Sales')->count();
        $regCount   = $allUsers->where('role','Register')->count();
    @endphp
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="usr-stat-card" style="border-left:4px solid #4e73df">
                <div class="usr-stat-icon" style="color:#4e73df"><i class="fas fa-users"></i></div>
                <div>
                    <div class="usr-stat-num">{{ $users->total() }}</div>
                    <div class="usr-stat-lbl">Total Users</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="usr-stat-card" style="border-left:4px solid #e74a3b">
                <div class="usr-stat-icon" style="color:#e74a3b"><i class="fas fa-crown"></i></div>
                <div>
                    <div class="usr-stat-num">{{ $adminCount }}</div>
                    <div class="usr-stat-lbl">Admins</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="usr-stat-card" style="border-left:4px solid #1cc88a">
                <div class="usr-stat-icon" style="color:#1cc88a"><i class="fas fa-chart-line"></i></div>
                <div>
                    <div class="usr-stat-num">{{ $salesCount }}</div>
                    <div class="usr-stat-lbl">Sales</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="usr-stat-card" style="border-left:4px solid #f6c23e">
                <div class="usr-stat-icon" style="color:#f6c23e"><i class="fas fa-clipboard-list"></i></div>
                <div>
                    <div class="usr-stat-num">{{ $regCount }}</div>
                    <div class="usr-stat-lbl">Register</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table card --}}
    <div class="usr-table-wrap shadow">

        {{-- Table toolbar --}}
        <div class="usr-toolbar">
            <div class="usr-search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="usrSearch" placeholder="Search name or email…">
            </div>
            <div class="d-flex align-items-center gap-2">
                <select id="usrRoleFilter" class="usr-filter-select">
                    <option value="">All Roles</option>
                    <option value="Admin">Admin</option>
                    <option value="Sales">Sales</option>
                    <option value="Register">Register</option>
                    <option value="Employee">Employee</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table usr-table mb-0" id="usrTable">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i><span>#</span></th>
                        <th><i class="fas fa-user"></i><span>Name</span></th>
                        <th><i class="fas fa-envelope"></i><span>Email</span></th>
                        <th><i class="fas fa-shield-alt"></i><span>Role</span></th>
                        <th><i class="fas fa-check-circle"></i><span>Verified</span></th>
                        <th><i class="fas fa-calendar-alt"></i><span>Joined</span></th>
                        <th><i class="fas fa-cogs"></i><span>Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    @php
                        $roleColor = match($user->role) {
                            'Admin'    => ['bg'=>'#fee2e2','text'=>'#dc2626','ico'=>'fa-crown'],
                            'Sales'    => ['bg'=>'#dcfce7','text'=>'#16a34a','ico'=>'fa-chart-line'],
                            'Register' => ['bg'=>'#fef9c3','text'=>'#ca8a04','ico'=>'fa-clipboard-list'],
                            default    => ['bg'=>'#dbeafe','text'=>'#2563eb','ico'=>'fa-user-tie'],
                        };
                        $initial = strtoupper(substr($user->name, 0, 1));
                    @endphp
                    <tr class="usr-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-role="{{ $user->role }}">
                        <td class="usr-id">#{{ $user->id }}</td>
                        <td>
                            <div class="usr-name-cell">
                                <div class="usr-avatar" style="background:{{ $roleColor['bg'] }}; color:{{ $roleColor['text'] }}">
                                    {{ $initial }}
                                </div>
                                <div>
                                    <div class="usr-fullname">{{ $user->name }}</div>
                                    <div class="usr-email-sm">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="usr-email-col">{{ $user->email }}</td>
                        <td>
                            <span class="usr-role-pill" style="background:{{ $roleColor['bg'] }}; color:{{ $roleColor['text'] }}">
                                <i class="fas {{ $roleColor['ico'] }}"></i> {{ $user->role }}
                            </span>
                        </td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="usr-badge-ok"><i class="fas fa-check-circle mr-1"></i>Verified</span>
                            @else
                                <span class="usr-badge-pending"><i class="fas fa-clock mr-1"></i>Pending</span>
                            @endif
                        </td>
                        <td class="usr-date">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="usr-actions">
                                <a href="{{ route('users.show', $user->id) }}" class="usr-btn usr-btn-view" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user->id) }}" class="usr-btn usr-btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline"
                                      onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="usr-btn usr-btn-del" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                            No users found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: count + pagination --}}
        <div class="usr-footer">
            <span class="usr-count text-muted small">
                Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} users
            </span>
            <div>{{ $users->links() }}</div>
        </div>
    </div>
</div>

<style>
/* ── stat cards ── */
.usr-stat-card {
    background: #fff;
    border-radius: 10px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 6px rgba(0,0,0,.06);
}
.usr-stat-icon { font-size: 28px; }
.usr-stat-num  { font-size: 22px; font-weight: 700; color: #1e293b; line-height: 1; }
.usr-stat-lbl  { font-size: 12px; color: #94a3b8; margin-top: 2px; }

/* ── table wrap ── */
.usr-table-wrap {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
}

/* ── toolbar ── */
.usr-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid #f1f5f9;
    gap: 12px;
    flex-wrap: wrap;
}
.usr-search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 9px;
    padding: 6px 14px;
    flex: 1;
    max-width: 320px;
}
.usr-search-box i { color: #94a3b8; font-size: 13px; }
.usr-search-box input {
    border: none; background: transparent; outline: none;
    font-size: 13px; width: 100%; color: #1e293b;
}
.usr-filter-select {
    border: 1.5px solid #e2e8f0; border-radius: 9px;
    padding: 6px 12px; font-size: 13px; color: #334155;
    background: #f8fafc; outline: none; cursor: pointer;
}

/* ── thead ── */
.usr-table thead tr {
    background: linear-gradient(135deg, #1a6bff, #0a3d99);
}
.usr-table thead th {
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    padding: 13px 16px;
    border: none;
    white-space: nowrap;
    border-right: 1px solid rgba(255,255,255,.1);
}
.usr-table thead th:last-child { border-right: none; }
.usr-table thead th i {
    display: block;
    font-size: 14px;
    margin-bottom: 3px;
    opacity: .8;
}

/* ── tbody ── */
.usr-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background .15s;
}
.usr-table tbody tr:hover { background: #f8fafc; }
.usr-table tbody td {
    padding: 12px 16px;
    font-size: 13px;
    color: #334155;
    vertical-align: middle;
    border: none;
}

/* ── cells ── */
.usr-id { color: #94a3b8; font-weight: 600; font-size: 12px; }

.usr-name-cell { display: flex; align-items: center; gap: 10px; }
.usr-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 15px; flex-shrink: 0;
}
.usr-fullname { font-weight: 600; color: #1e293b; font-size: 13px; }
.usr-email-sm { font-size: 11px; color: #94a3b8; }
.usr-email-col { color: #64748b; font-size: 12px; }
.usr-date { color: #94a3b8; font-size: 12px; white-space: nowrap; }

.usr-role-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 600; white-space: nowrap;
}

.usr-badge-ok {
    display: inline-flex; align-items: center;
    padding: 3px 9px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
    background: #dcfce7; color: #16a34a;
}
.usr-badge-pending {
    display: inline-flex; align-items: center;
    padding: 3px 9px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
    background: #fef9c3; color: #ca8a04;
}

/* ── action buttons ── */
.usr-actions { display: flex; align-items: center; gap: 5px; }
.usr-btn {
    width: 30px; height: 30px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 12px; border: none; cursor: pointer;
    text-decoration: none; transition: opacity .15s;
}
.usr-btn:hover { opacity: .8; }
.usr-btn-view { background: #dbeafe; color: #2563eb; }
.usr-btn-edit { background: #dcfce7; color: #16a34a; }
.usr-btn-del  { background: #fee2e2; color: #dc2626; }

/* ── footer ── */
.usr-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 20px; border-top: 1px solid #f1f5f9;
    flex-wrap: wrap; gap: 8px;
}
.usr-footer .pagination { margin: 0; }
.usr-footer .page-link  { font-size: 12px; padding: 4px 10px; }

/* hide email column on small screens */
@media (max-width: 768px) {
    .usr-email-col { display: none; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('usrSearch');
    const roleFilter  = document.getElementById('usrRoleFilter');
    const rows        = document.querySelectorAll('#usrTable tbody .usr-row');

    function filterRows() {
        const term = searchInput.value.toLowerCase();
        const role = roleFilter.value;
        rows.forEach(function (row) {
            const matchSearch = row.dataset.name.includes(term) || row.dataset.email.includes(term);
            const matchRole   = !role || row.dataset.role === role;
            row.style.display = (matchSearch && matchRole) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterRows);
    roleFilter.addEventListener('change', filterRows);
});
</script>

@endsection
