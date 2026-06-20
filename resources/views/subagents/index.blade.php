@extends('admin.index')

@section('content')
<div class="container-fluid">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-friends mr-2 text-primary"></i>Subagents</h1>
            <p class="mb-0 text-muted small">Manage your sub-agents and their commissions</p>
        </div>
        <a href="{{ route('subagents.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus mr-1"></i> New Subagent
        </a>
    </div>

    {{-- Stats --}}
    @php
        $total      = $subagents->count();
        $individual = $subagents->where('type','individual')->count();
        $company    = $subagents->where('type','company')->count();
    @endphp
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="sa-stat-card" style="border-left:4px solid #4e73df">
                <div class="sa-stat-icon" style="color:#4e73df"><i class="fas fa-users"></i></div>
                <div><div class="sa-stat-num">{{ $total }}</div><div class="sa-stat-lbl">Total Subagents</div></div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="sa-stat-card" style="border-left:4px solid #1cc88a">
                <div class="sa-stat-icon" style="color:#1cc88a"><i class="fas fa-user"></i></div>
                <div><div class="sa-stat-num">{{ $individual }}</div><div class="sa-stat-lbl">Individual</div></div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="sa-stat-card" style="border-left:4px solid #f6c23e">
                <div class="sa-stat-icon" style="color:#f6c23e"><i class="fas fa-building"></i></div>
                <div><div class="sa-stat-num">{{ $company }}</div><div class="sa-stat-lbl">Company</div></div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="sa-table-wrap shadow">

        {{-- Toolbar --}}
        <div class="sa-toolbar">
            <div class="sa-search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="saSearch" placeholder="Search name or email…">
            </div>
            <select id="saTypeFilter" class="sa-filter-select">
                <option value="">All Types</option>
                <option value="individual">Individual</option>
                <option value="company">Company</option>
            </select>
        </div>

        <div class="table-responsive">
            <table class="table sa-table mb-0" id="saTable">
                <thead>
                    <tr>
                        <th><i class="fas fa-image"></i><span>Photo</span></th>
                        <th><i class="fas fa-user"></i><span>Name</span></th>
                        <th><i class="fas fa-envelope"></i><span>Email</span></th>
                        <th><i class="fas fa-phone"></i><span>Phone</span></th>
                        <th><i class="fas fa-globe"></i><span>Country</span></th>
                        <th><i class="fas fa-tag"></i><span>Type</span></th>
                        <th><i class="fas fa-percentage"></i><span>Commissions</span></th>
                        <th><i class="fas fa-calendar-alt"></i><span>Created</span></th>
                        <th><i class="fas fa-cogs"></i><span>Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($subagents as $subagent)
                    <tr class="sa-row"
                        data-name="{{ strtolower($subagent->name) }}"
                        data-email="{{ strtolower($subagent->email) }}"
                        data-type="{{ $subagent->type }}">
                        <td>
                            @if($subagent->photo)
                                <img src="{{ asset('storage/'.$subagent->photo) }}"
                                     class="sa-avatar-img" alt="{{ $subagent->name }}">
                            @else
                                <div class="sa-avatar-initials">{{ strtoupper(substr($subagent->name,0,1)) }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="sa-name">{{ $subagent->name }}</div>
                            <div class="sa-sub-text">{{ $subagent->email }}</div>
                        </td>
                        <td class="sa-email-col sa-sub-text">{{ $subagent->email }}</td>
                        <td class="sa-sub-text">{{ $subagent->phone ?: '—' }}</td>
                        <td class="sa-sub-text">{{ $subagent->country->name ?? '—' }}</td>
                        <td>
                            @if($subagent->type === 'individual')
                                <span class="sa-pill sa-pill-ind"><i class="fas fa-user mr-1"></i>Individual</span>
                            @else
                                <span class="sa-pill sa-pill-com"><i class="fas fa-building mr-1"></i>Company</span>
                            @endif
                        </td>
                        <td>
                            @forelse($subagent->countryCommissions as $c)
                                <span class="sa-comm-tag">
                                    {{ $c->country->name ?? '?' }}
                                    <strong>{{ $c->commission_rate }}%</strong>
                                </span>
                            @empty
                                <span class="sa-sub-text">—</span>
                            @endforelse
                        </td>
                        <td class="sa-sub-text">{{ $subagent->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="sa-actions">
                                <a href="{{ route('subagents.edit', $subagent->id) }}"
                                   class="sa-btn sa-btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="sa-btn sa-btn-del" title="Delete"
                                        onclick="confirmDelete({{ $subagent->id }}, '{{ addslashes($subagent->name) }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="del-form-{{ $subagent->id }}"
                                      action="{{ route('subagents.destroy', $subagent->id) }}"
                                      method="POST" style="display:none">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                            No subagents found.
                            <a href="{{ route('subagents.create') }}" class="d-block mt-2 text-primary">Add your first subagent</a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="sa-footer">
            <span class="sa-sub-text">{{ $subagents->count() }} subagent(s)</span>
        </div>
    </div>
</div>

<style>
.sa-stat-card { background:#fff; border-radius:10px; padding:16px 20px; display:flex; align-items:center; gap:16px; box-shadow:0 1px 6px rgba(0,0,0,.06); }
.sa-stat-icon { font-size:28px; }
.sa-stat-num  { font-size:22px; font-weight:700; color:#1e293b; line-height:1; }
.sa-stat-lbl  { font-size:12px; color:#94a3b8; margin-top:2px; }

.sa-table-wrap { background:#fff; border-radius:14px; overflow:hidden; }

.sa-toolbar {
    display:flex; align-items:center; justify-content:space-between;
    padding:14px 20px; border-bottom:1px solid #f1f5f9; gap:12px; flex-wrap:wrap;
}
.sa-search-box {
    display:flex; align-items:center; gap:8px;
    background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:9px;
    padding:6px 14px; flex:1; max-width:320px;
}
.sa-search-box i { color:#94a3b8; font-size:13px; }
.sa-search-box input { border:none; background:transparent; outline:none; font-size:13px; width:100%; color:#1e293b; }
.sa-filter-select { border:1.5px solid #e2e8f0; border-radius:9px; padding:6px 12px; font-size:13px; color:#334155; background:#f8fafc; outline:none; cursor:pointer; }

.sa-table thead tr { background: linear-gradient(135deg,#1a6bff,#0a3d99); }
.sa-table thead th { color:#fff; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; padding:13px 16px; border:none; white-space:nowrap; border-right:1px solid rgba(255,255,255,.1); }
.sa-table thead th:last-child { border-right:none; }
.sa-table thead th i { display:block; font-size:14px; margin-bottom:3px; opacity:.8; }

.sa-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background .15s; }
.sa-table tbody tr:hover { background:#f8fafc; }
.sa-table tbody td { padding:12px 16px; font-size:13px; color:#334155; vertical-align:middle; border:none; }

.sa-avatar-img { width:36px; height:36px; border-radius:50%; object-fit:cover; }
.sa-avatar-initials { width:36px; height:36px; border-radius:50%; background:#dbeafe; color:#2563eb; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:15px; }
.sa-name { font-weight:600; color:#1e293b; font-size:13px; }
.sa-sub-text { font-size:12px; color:#94a3b8; }
.sa-email-col { display:none; }

.sa-pill { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
.sa-pill-ind { background:#dbeafe; color:#2563eb; }
.sa-pill-com { background:#fef9c3; color:#ca8a04; }

.sa-comm-tag { display:inline-flex; align-items:center; gap:4px; background:#f1f5f9; border-radius:6px; padding:2px 8px; font-size:11px; color:#475569; margin:1px; }
.sa-comm-tag strong { color:#16a34a; }

.sa-actions { display:flex; align-items:center; gap:5px; }
.sa-btn { width:30px; height:30px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:12px; border:none; cursor:pointer; text-decoration:none; transition:opacity .15s; }
.sa-btn:hover { opacity:.8; }
.sa-btn-edit { background:#dcfce7; color:#16a34a; }
.sa-btn-del  { background:#fee2e2; color:#dc2626; }

.sa-footer { display:flex; align-items:center; justify-content:space-between; padding:12px 20px; border-top:1px solid #f1f5f9; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('saSearch');
    const filter = document.getElementById('saTypeFilter');
    const rows   = document.querySelectorAll('#saTable tbody .sa-row');

    function filterRows() {
        const term = search.value.toLowerCase();
        const type = filter.value;
        rows.forEach(function (r) {
            const ok = (!term || r.dataset.name.includes(term) || r.dataset.email.includes(term))
                    && (!type || r.dataset.type === type);
            r.style.display = ok ? '' : 'none';
        });
    }
    search.addEventListener('input', filterRows);
    filter.addEventListener('change', filterRows);
});

function confirmDelete(id, name) {
    if (confirm('Delete subagent "' + name + '"? This cannot be undone.')) {
        document.getElementById('del-form-' + id).submit();
    }
}
</script>
@endsection
