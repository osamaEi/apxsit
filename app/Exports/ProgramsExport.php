<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProgramsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $programs;

    public function __construct($programs)
    {
        $this->programs = $programs;
    }

    public function collection()
    {
        return $this->programs;
    }

    public function headings(): array
    {
        return [
            'University',
            'Department',
            'Degree',
            'Language',
            'Price Before Discount ($)',
            'Price After Discount ($)',
            'Discount (%)',
            'Status'
        ];
    }

    public function map($program): array
    {
        return [
            $program->university->name ?? 'N/A',
            $program->department,
            $program->degree,
            $program->language,
            number_format($program->before_discount, 2),
            number_format($program->after_discount, 2),
            $program->discount_percentage . '%',
            $program->status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Set column widths
            'A' => ['width' => 30],
            'B' => ['width' => 30],
            'C' => ['width' => 15],
            'D' => ['width' => 15],
            'E' => ['width' => 20],
            'F' => ['width' => 20],
            'G' => ['width' => 15],
            'H' => ['width' => 15],
        ];
    }
}