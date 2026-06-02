<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ApplicationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $applications;

    public function __construct($applications)
    {
        $this->applications = $applications;
    }

    public function collection()
    {
        return $this->applications;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Student Email',
            'University',
            'Department',
            'Degree',
            'Status',
            'Created Date',
            'Updated Date'
        ];
    }

    public function map($application): array
    {
        return [
            $application->id,
            $application->student->first_name . ' ' . $application->student->last_name,
            $application->student->email,
            $application->university->name,
            $application->department,
            $application->degree,
            ucfirst($application->status),
            $application->created_at->format('d M Y'),
            $application->updated_at->format('d M Y')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Set column widths
            'A' => ['width' => 10],  // ID
            'B' => ['width' => 25],  // Student Name
            'C' => ['width' => 30],  // Student Email
            'D' => ['width' => 30],  // University
            'E' => ['width' => 30],  // Department
            'F' => ['width' => 15],  // Degree
            'G' => ['width' => 15],  // Status
            'H' => ['width' => 15],  // Created Date
            'I' => ['width' => 15],  // Updated Date
        ];
    }
}