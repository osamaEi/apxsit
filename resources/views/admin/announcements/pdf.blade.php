<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Announcements Report</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        
        /* Header Styles */
        .header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        
        .header-title {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .header-subtitle {
            font-size: 12px;
            color: #6c757d;
        }
        
        .logo {
            position: absolute;
            top: 0;
            right: 0;
            max-height: 50px;
        }
        
        /* Stats Section */
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .stat-box {
            width: 23%;
            padding: 10px;
            border-radius: 5px;
            color: white;
            text-align: center;
        }
        
        .bg-info {
            background-color: #17a2b8;
        }
        
        .bg-success {
            background-color: #28a745;
        }
        
        .bg-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .bg-danger {
            background-color: #dc3545;
        }
        
        .stat-box h3 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        
        .stat-box p {
            margin: 5px 0 0;
            font-size: 10px;
        }
        
        /* Filter Info */
        .filter-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 8px;
            margin-bottom: 15px;
            font-size: 10px;
        }
        
        .filter-label {
            font-weight: bold;
            margin-right: 5px;
            color: #007bff;
        }
        
        /* Announcement Detail Styles */
        .announcement-container {
            margin-bottom: 30px;
            page-break-inside: avoid;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .announcement-header {
            background-color: #f8f9fa;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            position: relative;
        }
        
        .announcement-title {
            font-weight: bold;
            font-size: 14px;
            color: #007bff;
            margin-right: 80px;
        }
        
        .announcement-status {
            display: inline-block;
            padding: 3px 8px;
            font-size: 9px;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        
        .announcement-body {
            display: flex;
            padding: 10px;
        }
        
        .announcement-media {
            width: 120px;
            margin-right: 15px;
        }
        
        .announcement-image {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .announcement-image img {
            max-width: 100%;
            max-height: 100px;
            border: 1px solid #dee2e6;
        }
        
        .university-container {
            text-align: center;
            margin-top: 10px;
        }
        
        .university-logo {
            max-width: 80px;
            max-height: 40px;
            margin-bottom: 5px;
            border: 1px solid #dee2e6;
            background-color: #fff;
            padding: 2px;
        }
        
        .university-name {
            font-size: 9px;
            font-weight: bold;
        }
        
        .announcement-content {
            flex-grow: 1;
        }
        
        .announcement-meta {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
            border-bottom: 1px dashed #dee2e6;
            padding-bottom: 8px;
        }
        
        .announcement-meta-item {
            width: 50%;
            margin-bottom: 5px;
        }
        
        .meta-label {
            font-weight: bold;
            color: #6c757d;
            display: inline-block;
            width: 80px;
        }
        
        .announcement-description {
            font-size: 10px;
            line-height: 1.4;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #dee2e6;
        }
        
        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 3px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 10px;
            color: white;
        }
        
        .badge-success {
            background-color: #28a745;
        }
        
        .badge-danger {
            background-color: #dc3545;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-info {
            background-color: #17a2b8;
        }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            font-size: 9px;
            color: #6c757d;
            text-align: center;
        }
        
        /* Page Numbers */
        .page-number {
            text-align: right;
            font-size: 9px;
            color: #6c757d;
            font-style: italic;
        }
        
        /* Description content styling */
        .description-content {
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 4px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-title">
            Announcements Report
        </div>
        <div class="header-subtitle">Generated on {{ $date }}</div>
        <img src="{{ $companyLogo }}" alt="Company Logo" class="logo">
    </div>
    

    
    <!-- Filters -->
    
    
    <!-- Announcements Details -->
    @foreach($announcements as $announcement)
        <div class="announcement-container">
            <div class="announcement-header">
                <div class="announcement-title">{{ $announcement->title }}</div>
                <div class="announcement-status badge-{{ $announcement->status_color }}">
                    {{ $announcement->status_label }}
                </div>
            </div>
            <div class="announcement-body">
                <div class="announcement-media">
                    <div class="announcement-image">
                        @if($announcement->image_base64)
                            <img src="{{ $announcement->image_base64 }}" alt="Announcement">
                        @else
                            <div style="text-align: center; padding: 30px 0; background: #f8f9fa; border: 1px dashed #dee2e6;">
                                <span style="font-size: 9px; color: #6c757d;">No Image</span>
                            </div>
                        @endif
                    </div>
                    <div class="university-container">
                        @if($announcement->university && $announcement->university->logo_base64)
                            <img src="{{ $announcement->university->logo_base64 }}" alt="{{ $announcement->university->name }}" class="university-logo">
                            <div class="university-name">{{ $announcement->university->name }}</div>
                        @endif
                    </div>
                </div>
                <div class="announcement-content">
                    <div class="announcement-meta">
                        <div class="announcement-meta-item">
                            <span class="meta-label">University:</span>
                            {{ $announcement->university->name ?? 'N/A' }}
                        </div>
                        <div class="announcement-meta-item">
                            <span class="meta-label">Location:</span>
                            {{ $announcement->city->name ?? 'N/A' }}, {{ $announcement->country->name ?? 'N/A' }}
                        </div>
                        <div class="announcement-meta-item">
                            <span class="meta-label">Created:</span>
                            {{ $announcement->created_at ? $announcement->created_at->format('M d, Y') : 'N/A' }}
                        </div>
                        <div class="announcement-meta-item">
                            <span class="meta-label">Published:</span>
                            {{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : 'Not Published' }}
                        </div>
                        <div class="announcement-meta-item">
                            <span class="meta-label">Created By:</span>
                            {{ $announcement->creator->name ?? 'N/A' }}
                        </div>
                        <div class="announcement-meta-item">
                            <span class="meta-label">Status:</span>
                            <span class="badge badge-{{ $announcement->status_color }}">{{ $announcement->status_label }}</span>
                        </div>
                    </div>
                    
                    <div class="description-content">
                        {!! $announcement->description !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    
    <!-- Footer -->
    <div class="footer">
        <p>© {{ date('Y') }} Your Company Name | Total Records: {{ $announcements->count() }}</p>
        <p>This report is confidential and intended for authorized personnel only.</p>
    </div>
    
    <!-- Page Numbers -->
    <div class="page-number">
        Page <span class="pagenum"></span>
    </div>
    
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $size = 9;
            $font = $fontMetrics->getFont("Arial");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) - 40;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>