<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnnouncementsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $announcements;

    public function __construct($announcements)
    {
        $this->announcements = $announcements;
    }

    public function collection()
    {
        return $this->announcements;
    }

    public function headings(): array
    {
        return [
            'Title',
            'University',
            'City',
            'Country',
            'Status',
            'Published At',
            'Created At'
        ];
    }

    public function map($announcement): array
    {
        return [
            $announcement->title,
            $announcement->university->name ?? 'N/A',
            $announcement->city->name ?? 'N/A',
            $announcement->country->name ?? 'N/A',
            $announcement->status_label,
            $announcement->published_at ? $announcement->published_at->format('M d, Y H:i') : 'Not published',
            $announcement->created_at->format('M d, Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Set column widths
            'A' => ['width' => 40], // Title
            'B' => ['width' => 30], // University
            'C' => ['width' => 20], // City
            'D' => ['width' => 20], // Country
            'E' => ['width' => 15], // Status
            'F' => ['width' => 20], // Published At
            'G' => ['width' => 20], // Created At
        ];
    }
}