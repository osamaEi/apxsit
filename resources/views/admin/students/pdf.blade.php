<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Students List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.3;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .filter-info {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            padding: 6px 8px;
            vertical-align: middle;
            font-size: 10px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        /* Status Colors */
        .New {
            color: #4e73df;
        }
        .In-Review {
            color: #17a2b8;
        }
        .Pending-Documents {
            color: #ffc107;
            font-weight: bold;
        }
        .Accepted {
            color: #28a745;
            font-weight: bold;
        }
        .Rejected {
            color: #dc3545;
        }
        .Enrolled {
            color: #1e7e34;
            font-weight: bold;
        }
        .Cancelled {
            color: #6c757d;
        }
        /* Student photo */
        .photo-cell {
            text-align: center;
            width: 60px;
            height: 50px;
        }
        .photo-img {
            max-height: 40px;
            max-width: 40px;
            border-radius: 50%;
        }
        .avatar-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #4e73df;
            color: white;
            text-align: center;
            font-weight: bold;
            line-height: 40px;
            margin: 0 auto;
            font-size: 14px;
        }
        /* Page header */
        .page-header {
            margin-bottom: 20px;
        }
        .header-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .stat-item {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            width: 23%;
            margin-bottom: 10px;
        }
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 10px;
            color: #6c757d;
        }
        .is-transfer {
            background-color: #e8f4fd;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Students List</h1>
        
      
      
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="50">Photo</th>
                <th>Student Name</th>
                <th>Passport</th>
                <th>Contact</th>
                <th>Study Details</th>
                <th>Status</th>
                <th>Updated</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td class="photo-cell">
                        @if($student->photo_base64)
                            <img src="{{ $student->photo_base64 }}" class="photo-img" alt="{{ $student->first_name }}">
                        @else
                            <div class="avatar-placeholder">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                        @if($student->is_transfer)
                            <span class="is-transfer">Transfer</span>
                        @endif
                    </td>
                    <td>{{ $student->passport_id }}</td>
                    <td>
                        {{ $student->email }}<br>
                        {{ $student->phone }}
                    </td>
                    <td>
                        <strong>Country:</strong> {{ $student->studyCountry->name ?? 'N/A' }}<br>
                        <strong>Program:</strong> {{ $student->applyingDegree->department ?? 'N/A' }}<br>
                        <strong>Staff:</strong> {{ $student->responsibleEmployee->name ?? 'N/A' }}
                    </td>
                    <td class="{{ str_replace(' ', '-', $student->status) }}">
                        {{ $student->status }}
                    </td>
                    <td>
                        {{ $student->updated_at->format('M d, Y') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No students found matching your criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Generated on {{ $date }} | Total records: {{ $students->count() }}</p>
    </div>
</body>
</html>