<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Admission Applications</title>
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
        .header-info {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
        }
        .filter-info {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
            padding: 5px;
            background-color: #f8f9fa;
            border-radius: 5px;
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
        .approved {
            color: green;
            font-weight: bold;
        }
        .rejected {
            color: red;
            font-weight: bold;
        }
        .pending {
            color: orange;
            font-weight: bold;
        }
        .page-break {
            page-break-after: always;
        }
        .student-photo {
            max-width: 40px;
            max-height: 40px;
            border-radius: 50%;
        }
        .logo-img {
            max-width: 40px;
            max-height: 40px;
        }
        .photo-cell {
            width: 50px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Admission Applications</h1>
    


    <table>
        <thead>
            <tr>
                <th width="60">Student</th>
                <th width="60">University</th>
                <th width="50">App ID</th>
                <th>Student Name</th>
                <th>University</th>
                <th>Department</th>
                <th>Degree</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $application)
                <tr>
                    <td class="photo-cell">
                        @if(isset($application->student) && $application->student->photo_base64)
                            <img src="{{ $application->student->photo_base64 }}" class="student-photo" alt="Student">
                        @else
                            <div class="avatar-placeholder">
                                {{ substr($application->student->first_name ?? '', 0, 1) }}{{ substr($application->student->last_name ?? '', 0, 1) }}
                            </div>
                        @endif
                    </td>
                    <td class="photo-cell">
                        @if(isset($application->university) && $application->university->logo_base64)
                            <img src="{{ $application->university->logo_base64 }}" class="logo-img" alt="University">
                        @else
                            <span>-</span>
                        @endif
                    </td>
                    <td>{{ $application->id }}</td>
                    <td>
                        <strong>{{ $application->student->first_name ?? '' }} {{ $application->student->last_name ?? '' }}</strong><br>
                        <small>{{ $application->student->email ?? '' }}</small>
                    </td>
                    <td>{{ $application->university->name ?? 'N/A' }}</td>
                    <td>{{ $application->department }}</td>
                    <td>{{ $application->degree }}</td>
                    <td class="{{ $application->status }}">
                        {{ ucfirst($application->status) }}
                    </td>
                    <td>{{ $application->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">No applications found matching your criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Generated on {{ $date }} | Total records: {{ $applications->count() }}</p>
    </div>
</body>
</html>