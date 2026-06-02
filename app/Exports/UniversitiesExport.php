<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UniversitiesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $universities;
    protected $types;

    public function __construct($universities, $types)
    {
        $this->universities = $universities;
        $this->types = $types;
    }

    public function collection()
    {
        return $this->universities;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Country',
            'City',
            'Type',
            'Status'
        ];
    }

    public function map($university): array
    {
        return [
            $university->id,
            $university->name,
            $university->country->name ?? 'N/A',
            $university->city->name ?? 'N/A',
            $this->types[$university->type] ?? 'N/A',
            $university->is_active ? 'Active' : 'Inactive'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}