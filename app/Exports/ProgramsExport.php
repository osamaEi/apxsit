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
            'Price Before Discount',
            'Price After Discount',
            'Cash Discount',
            'Deposit Payment',
            'Siblings Discount',
            'Shift Type',
            'Status',
        ];
    }

    public function map($program): array
    {
        return [
            $program->university->name ?? '',
            $program->department,
            $program->degree,
            $program->language,
            (float) $program->before_discount,
            (float) $program->after_discount,
            (float) $program->cash_discount,
            (float) $program->deposit_payment,
            (float) $program->siblings_discount,
            $program->shift_type,
            $program->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
            'A'  => ['width' => 30],
            'B'  => ['width' => 30],
            'C'  => ['width' => 15],
            'D'  => ['width' => 15],
            'E'  => ['width' => 22],
            'F'  => ['width' => 22],
            'G'  => ['width' => 18],
            'H'  => ['width' => 18],
            'I'  => ['width' => 18],
            'J'  => ['width' => 15],
            'K'  => ['width' => 15],
        ];
    }
}