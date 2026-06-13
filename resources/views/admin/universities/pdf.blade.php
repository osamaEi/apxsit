{{-- resources/views/admin/universities/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Universities List</title>
    <style>
        /* Main Document Styling */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }
        
        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #8E2989;
            margin-bottom: 20px;
        }
        
        .header-logo {
            height: 60px;
            width: auto;
        }
        
        .header-title {
            text-align: center;
            flex-grow: 1;
        }
        
        h1 {
            font-size: 24px;
            color: #8E2989;
            margin: 0;
            font-weight: 600;
        }
        
        .document-meta {
            text-align: right;
            color: #666;
            font-size: 11px;
            line-height: 1.4;
        }
        
        /* Filter Section */
        .filter-info {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-left: 4px solid #FF4A60;
            font-size: 12px;
        }
        
        .filter-title {
            font-weight: bold;
            color: #FF4A60;
            margin-right: 8px;
        }
        
        .filter-value {
            font-weight: 600;
        }
        
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        thead {
            background-color: #8E2989;
            color: white;
        }
        
        th {
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            border: none;
        }
        
        td {
            padding: 10px;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }
        
        tr:nth-child(even) {
            background-color: #f8f8f8;
        }
        
        /* Special Cells */
        .logo-cell {
            text-align: center;
            width: 80px;
            height: 55px;
            padding: 8px;
        }
        
        .logo-img {
            max-height: 45px;
            max-width: 70px;
            object-fit: contain;
        }
        
        .no-logo {
            color: #999;
            font-style: italic;
            font-size: 11px;
        }
        
        .active {
            color: #198754;
            font-weight: bold;
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            background-color: rgba(25, 135, 84, 0.1);
        }
        
        .inactive {
            color: #dc3545;
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            background-color: rgba(220, 53, 69, 0.1);
        }
        
        /* Footer */
        .footer {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #666;
        }
        
        .page-number {
            font-style: italic;
        }
        
        .logo-footer {
            height: 20px;
            vertical-align: middle;
            margin-right: 5px;
        }
        
        /* Summary Section */
        .summary {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 15px;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        
        .summary-box {
            text-align: center;
            padding: 10px;
            flex: 1;
        }
        
        .summary-value {
            font-size: 20px;
            font-weight: bold;
            color: #8E2989;
            margin-bottom: 5px;
        }
        
        .summary-label {
            font-size: 11px;
            color: #666;
        }
        
        /* No Results State */
        .no-records {
            text-align: center;
            padding: 30px;
            color: #666;
            font-style: italic;
        }
        
        /* Print Specific Styles */
        @page {
            margin: 0.5cm;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            thead {
                display: table-header-group;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('Apx.jpeg') }}" alt="ABX SITE" class="header-logo">
        <div class="header-title">
            <h1>Universities List</h1>
        </div>
        <div class="document-meta">
            <div>Generated: {{ $date }}</div>
            <div>Report ID: UNI-{{ date('Ymd') }}-{{ rand(1000, 9999) }}</div>
        </div>
    </div>
    
    <div class="filter-info">
   
    </div>
   
    <table>
        <thead>
            <tr>
                <th>Logo</th>
                <th>Name</th>
                <th>Country</th>
                <th>City</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($universities as $university)
                <tr>
                    <td class="logo-cell">
                        @if($university->logo_base64)
                            <img src="{{ $university->logo_base64 }}" class="logo-img" alt="{{ $university->name }}">
                        @else
                            <span class="no-logo">No logo</span>
                        @endif
                    </td>
                    <td><strong>{{ $university->name }}</strong></td>
                    <td>{{ $university->country->name ?? 'N/A' }}</td>
                    <td>{{ $university->city->name ?? 'N/A' }}</td>
                    <td>{{ $types[$university->type] ?? 'N/A' }}</td>
                    <td>
                        <span class="{{ $university->is_active ? 'active' : 'inactive' }}">
                            {{ $university->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-records">No universities found matching the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($universities->count() > 0)
    <div class="summary">
        <div class="summary-box">
            <div class="summary-value">{{ $universities->count() }}</div>
            <div class="summary-label">Total Universities</div>
        </div>
        <div class="summary-box">
            <div class="summary-value">{{ $universities->where('is_active', true)->count() }}</div>
            <div class="summary-label">Active Universities</div>
        </div>
        <div class="summary-box">
            <div class="summary-value">{{ $universities->pluck('country.name')->unique()->count() }}</div>
            <div class="summary-label">Countries</div>
        </div>
        <div class="summary-box">
            <div class="summary-value">{{ $universities->pluck('type')->unique()->count() }}</div>
            <div class="summary-label">University Types</div>
        </div>
    </div>
    @endif
   
    <div class="footer">
        <div>
            <img src="{{ public_path('Apx.jpeg') }}" alt="ABX SITE" class="logo-footer">
            ABX SITE © {{ date('Y') }} | All Rights Reserved
        </div>
        <div class="page-number">Page 1 of 1</div>
    </div>
</body>
</html>