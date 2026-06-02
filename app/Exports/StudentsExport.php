<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $students;
    protected $statuses;

    public function __construct($students, $statuses)
    {
        $this->students = $students;
        $this->statuses = $statuses;
    }

    public function collection()
    {
        return $this->students;
    }

    public function headings(): array
    {
        return [
            'ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Passport',
            'Country',
            'Program',
            'Responsible',
            'Status',
            'Updated At'
        ];
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->first_name,
            $student->last_name,
            $student->email,
            $student->phone,
            $student->passport_id,
            $student->studyCountry->name ?? 'N/A',
            $student->applyingDegree->department ?? 'N/A',
            $student->responsibleEmployee->name ?? 'N/A',
            $student->status,
            $student->updated_at->format('M d, Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Set column widths
            'A' => ['width' => 10],  // ID
            'B' => ['width' => 20],  // First Name
            'C' => ['width' => 20],  // Last Name
            'D' => ['width' => 30],  // Email
            'E' => ['width' => 20],  // Phone
            'F' => ['width' => 20],  // Passport
            'G' => ['width' => 20],  // Country
            'H' => ['width' => 30],  // Program
            'I' => ['width' => 20],  // Responsible
            'J' => ['width' => 15],  // Status
            'K' => ['width' => 20],  // Updated At
        ];
    }
}