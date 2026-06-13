<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Programs Report</title>
<style>
body { font-family: Arial, sans-serif; font-size: 10px; color: #222; margin: 0; padding: 15px; }
h2 { font-size: 15px; color: #1a6bff; margin: 0 0 4px; }
.meta { font-size: 9px; color: #666; margin-bottom: 10px; }
table { width: 100%; border-collapse: collapse; }
th { background: #1a6bff; color: #fff; padding: 5px 4px; text-align: left; font-size: 9px; }
td { padding: 4px; border-bottom: 1px solid #eee; font-size: 9px; vertical-align: top; }
tr:nth-child(even) td { background: #fafafa; }
.status-active { color: #198754; font-weight: bold; }
.status-inactive { color: #dc3545; }
.status-other { color: #fd7e14; }
.footer { margin-top: 10px; font-size: 8px; color: #999; border-top: 1px solid #ddd; padding-top: 6px; }
</style>
</head>
<body>
<h2>Programs Report</h2>
<div class="meta">
    Generated: {{ $date }} &nbsp;|&nbsp; Showing: {{ count($programs) }} of {{ $totalCount }} programs
    @if($universityName !== 'All') &nbsp;|&nbsp; University: {{ $universityName }} @endif
    @if($departmentName !== 'All') &nbsp;|&nbsp; Department: {{ $departmentName }} @endif
    @if($degreeName !== 'All') &nbsp;|&nbsp; Degree: {{ $degreeName }} @endif
    @if($statusName !== 'All') &nbsp;|&nbsp; Status: {{ $statusName }} @endif
</div>
@if(!empty($isTruncated))
<div style="background:#fff3cd; border:1px solid #ffc107; padding:5px 10px; border-radius:4px; margin-bottom:8px; font-size:9px; color:#856404;">
    Note: PDF is limited to 500 records. {{ $totalCount }} total found — use filters to narrow results and export specific data.
</div>
@endif

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>University</th>
            <th>Department</th>
            <th>Degree</th>
            <th>Language</th>
            <th>Shift</th>
            <th>Before ($)</th>
            <th>After ($)</th>
            <th>Discount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($programs as $i => $p)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $p->university->name ?? 'N/A' }}</td>
            <td>{{ $p->department }}</td>
            <td>{{ $p->degree }}</td>
            <td>{{ $p->language }}</td>
            <td>{{ $p->shift_type }}</td>
            <td>{{ number_format($p->before_discount, 0) }}</td>
            <td>{{ number_format($p->after_discount, 0) }}</td>
            <td>{{ $p->discount_percentage }}%</td>
            <td class="status-{{ strtolower($p->status) === 'active' ? 'active' : (strtolower($p->status) === 'inactive' ? 'inactive' : 'other') }}">
                {{ $p->status }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">ABX SITE &copy; {{ date('Y') }}</div>
</body>
</html>
