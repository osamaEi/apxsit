@extends('admin.index')

@section('content')
<style>
/* ── Dashboard header ── */
.dash-header {
    background: linear-gradient(135deg, #0a1628 0%, #0d2550 60%, #1a6bff 100%);
    color: #fff;
    padding: 1.25rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 20px rgba(26,107,255,0.25);
}
.dash-header h4 { margin:0; font-weight:700; font-size:1.2rem; }
.dash-header p  { margin:0; opacity:.8; font-size:.875rem; }

/* ── Stat cards ── */
.stat-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.07);
    transition: transform .2s, box-shadow .2s;
    background: #fff;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(26,107,255,0.15); }
.stat-card .card-body { padding: 1.25rem; }
.stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; flex-shrink:0; }
.stat-value { font-size:1.75rem; font-weight:700; line-height:1; margin-bottom:2px; color:#0a1628; }
.stat-label { font-size:.8rem; color:#6c757d; margin:0; }

/* ── Section cards ── */
.section-card { border:none; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,.07); background:#fff; }
.section-card .card-header { background:#fff; border-bottom:1px solid #eef2ff; padding:.875rem 1.25rem; border-radius:12px 12px 0 0; }
.section-card .card-title { font-size:1rem; font-weight:600; margin:0; color:#0a1628; }

.legend-dot { width:10px; height:10px; border-radius:50%; display:inline-block; flex-shrink:0; }
.legend-row { display:flex; align-items:center; gap:.5rem; padding:.35rem 0; font-size:.875rem; }
.legend-row .val { margin-left:auto; font-weight:600; }

.student-avatar { width:34px; height:34px; border-radius:50%; background:#eef2ff; display:flex; align-items:center; justify-content:center; font-weight:600; font-size:.75rem; color:#1a6bff; flex-shrink:0; }

/* ── Dark mode ── */
body.dark-mode .stat-card,
body.dark-mode .section-card { background: #1a2035 !important; box-shadow: 0 2px 10px rgba(0,0,0,.3); }
body.dark-mode .stat-value { color: #e8efff !important; }
body.dark-mode .stat-label { color: #8899bb !important; }
body.dark-mode .section-card .card-header { background: #1a2035 !important; border-bottom-color: #243050 !important; }
body.dark-mode .section-card .card-title { color: #e8efff !important; }
body.dark-mode .stat-icon[style*="#eef2ff"] { background: #0d2550 !important; }
body.dark-mode .stat-icon[style*="#eef2ff"] { background: #0d2550 !important; }
body.dark-mode .stat-icon[style*="#e7f9f0"] { background: #0d2b1e !important; }
body.dark-mode .stat-icon[style*="#fff4e5"] { background: #2b1d00 !important; }
body.dark-mode .stat-icon[style*="#ffeef0"] { background: #2b0a0a !important; }
body.dark-mode .dash-header { box-shadow: 0 4px 20px rgba(0,0,0,.5); }
body.dark-mode .student-avatar { background: #0d2550 !important; }
body.dark-mode .legend-row { color: #c0cfee; }
</style>

{{-- Header --}}
<div class="dash-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4><i class="fas fa-tachometer-alt mr-2"></i>Dashboard Overview</h4>
            <p>{{ now()->format('l, F d, Y') }} — Welcome back, {{ auth()->user()->name }}</p>
        </div>
        <div class="d-none d-md-flex" style="gap:.5rem;">
            <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-light">
                <i class="fas fa-users mr-1"></i> Students
            </a>
            <a href="{{ route('admin.programs.index') }}" class="btn btn-sm btn-light">
                <i class="fas fa-graduation-cap mr-1"></i> Programs
            </a>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row mb-4">
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center" style="gap:1rem;">
                <div class="stat-icon" style="background:#eef2ff; color:#1a6bff;">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($studentCount) }}</div>
                    <p class="stat-label">Total Students</p>
                </div>
            </div>
            <div style="height:3px; background:linear-gradient(90deg,#1a6bff,#4d8eff); border-radius:0 0 10px 10px;"></div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center" style="gap:1rem;">
                <div class="stat-icon" style="background:#eef2ff; color:#1a6bff;">
                    <i class="fas fa-university"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($universityStats['totalUniversities']) }}</div>
                    <p class="stat-label">Universities</p>
                </div>
            </div>
            <div style="height:3px; background:linear-gradient(90deg,#1a6bff,#4d8eff); border-radius:0 0 10px 10px;"></div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center" style="gap:1rem;">
                <div class="stat-icon" style="background:#e7f9f0; color:#28c76f;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($programStats['totalPrograms']) }}</div>
                    <p class="stat-label">Programs</p>
                </div>
            </div>
            <div style="height:3px; background:linear-gradient(90deg,#28c76f,#70e0a4); border-radius:0 0 10px 10px;"></div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center" style="gap:1rem;">
                <div class="stat-icon" style="background:#fff4e5; color:#ff9500;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($applicationCount) }}</div>
                    <p class="stat-label">Applications</p>
                </div>
            </div>
            <div style="height:3px; background:linear-gradient(90deg,#ff9500,#ffb84d); border-radius:0 0 10px 10px;"></div>
        </div>
    </div>
</div>

{{-- Extra stat cards row --}}
<div class="row mb-4">
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center" style="gap:1rem;">
                <div class="stat-icon" style="background:#e7f9f0; color:#28c76f;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($programStats['activePrograms']) }}</div>
                    <p class="stat-label">Active Programs</p>
                </div>
            </div>
            <div style="height:3px; background:linear-gradient(90deg,#28c76f,#70e0a4); border-radius:0 0 10px 10px;"></div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center" style="gap:1rem;">
                <div class="stat-icon" style="background:#eef2ff; color:#1a6bff;">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($userStats['totalEmployees']) }}</div>
                    <p class="stat-label">Employees</p>
                </div>
            </div>
            <div style="height:3px; background:linear-gradient(90deg,#1a6bff,#4d8eff); border-radius:0 0 10px 10px;"></div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center" style="gap:1rem;">
                <div class="stat-icon" style="background:#fff4e5; color:#ff9500;">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($announcementStats['totalAnnouncements']) }}</div>
                    <p class="stat-label">Announcements</p>
                </div>
            </div>
            <div style="height:3px; background:linear-gradient(90deg,#ff9500,#ffb84d); border-radius:0 0 10px 10px;"></div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center" style="gap:1rem;">
                <div class="stat-icon" style="background:#ffeef0; color:#ea5455;">
                    <i class="fas fa-percentage"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($programStats['averageDiscount'], 1) }}%</div>
                    <p class="stat-label">Avg. Discount</p>
                </div>
            </div>
            <div style="height:3px; background:linear-gradient(90deg,#ea5455,#f08080); border-radius:0 0 10px 10px;"></div>
        </div>
    </div>
</div>

{{-- Main content --}}
<div class="row">
    {{-- Left: Programs by degree chart + Top Universities --}}
    <div class="col-lg-8 mb-3">

        {{-- Programs breakdown chart --}}
        <div class="card section-card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-2 text-primary"></i>Programs by Degree</h3>
                <a href="{{ route('admin.programs.index') }}" class="btn btn-sm btn-outline-primary">View Programs</a>
            </div>
            <div class="card-body">
                <canvas id="programsChart" height="220"></canvas>
            </div>
        </div>

        {{-- Top Universities table --}}
        <div class="card section-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-trophy mr-2 text-warning"></i>Top Universities by Programs</h3>
                <a href="{{ route('admin.universities.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>University</th>
                                <th class="text-center">Programs</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topUniversities as $i => $uni)
                            <tr>
                                <td class="text-muted" style="font-size:.85rem;">{{ $i + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center" style="gap:.6rem;">
                                        <div class="student-avatar" style="background:#f0e6f0; color:#1a6bff;">
                                            {{ strtoupper(substr($uni->name, 0, 2)) }}
                                        </div>
                                        <span style="font-weight:500;">{{ Str::limit($uni->name, 30) }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-primary">{{ $uni->programs_count ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $uni->is_active ? 'success' : 'secondary' }}">
                                        {{ $uni->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No universities found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Staff breakdown + Announcement stats --}}
    <div class="col-lg-4 mb-3">

{{-- Programs status summary --}}
        <div class="card section-card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-graduation-cap mr-2 text-info"></i>Programs Summary</h3>
            </div>
            <div class="card-body p-0">
                @php
                    $programRows = [
                        ['label'=>'Total Programs',    'val'=>$programStats['totalPrograms'],    'icon'=>'fa-list',           'color'=>'#6c757d'],
                        ['label'=>'Active',            'val'=>$programStats['activePrograms'],   'icon'=>'fa-check-circle',   'color'=>'#28c76f'],
                        ['label'=>'Inactive',          'val'=>$programStats['inactivePrograms'], 'icon'=>'fa-times-circle',   'color'=>'#ea5455'],
                        ['label'=>'Bachelor Programs', 'val'=>$programStats['bachelorPrograms'], 'icon'=>'fa-graduation-cap', 'color'=>'#1a6bff'],
                        ['label'=>'Master Programs',   'val'=>$programStats['masterPrograms'],   'icon'=>'fa-graduation-cap', 'color'=>'#1a6bff'],
                        ['label'=>'PhD Programs',      'val'=>$programStats['phdPrograms'],      'icon'=>'fa-graduation-cap', 'color'=>'#ff9500'],
                        ['label'=>'Announcements',     'val'=>$announcementStats['publishedAnnouncements'], 'icon'=>'fa-bullhorn', 'color'=>'#00cfe8'],
                        ['label'=>'Applications',      'val'=>$applicationCount,                'icon'=>'fa-clipboard-list', 'color'=>'#7367f0'],
                    ];
                @endphp
                @foreach($programRows as $row)
                <div class="d-flex align-items-center px-3 py-2" style="border-bottom:1px solid #f5f5f5;">
                    <i class="fas {{ $row['icon'] }} mr-2" style="color:{{ $row['color'] }}; width:16px;"></i>
                    <span style="font-size:.875rem; flex:1;">{{ $row['label'] }}</span>
                    <span style="font-weight:600; font-size:.875rem;">{{ number_format($row['val']) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(function() {
    // Programs by degree bar chart
    new Chart(document.getElementById('programsChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Bachelor', 'Master', 'PhD', 'Active', 'Inactive'],
            datasets: [{
                label: 'Programs',
                data: [
                    {{ $programStats['bachelorPrograms'] }},
                    {{ $programStats['masterPrograms'] }},
                    {{ $programStats['phdPrograms'] }},
                    {{ $programStats['activePrograms'] }},
                    {{ $programStats['inactivePrograms'] }}
                ],
                backgroundColor: ['#1a6bff','#1a6bff','#ff9500','#28c76f','#ea5455'],
                borderRadius: 6,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } }
            }
        }
    });

});
</script>
@endsection
