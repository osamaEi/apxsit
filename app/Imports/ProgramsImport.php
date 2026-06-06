<?php

namespace App\Imports;

use App\Models\Program;
use App\Models\University;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProgramsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures, SkipsErrors;

    private array $universityCache = [];
    public int $importedCount = 0;

    public function model(array $row): ?Program
    {
        $universityId = $this->resolveUniversity($row['university'] ?? null);

        if (!$universityId) {
            return null;
        }

        $beforeDiscount = (float) ($row['price_before_discount'] ?? 0);
        $afterDiscount  = (float) ($row['price_after_discount'] ?? $beforeDiscount);

        $this->importedCount++;

        return new Program([
            'university_id'     => $universityId,
            'department'        => trim($row['department']),
            'degree'            => trim($row['degree']),
            'language'          => trim($row['language']),
            'before_discount'   => $beforeDiscount,
            'after_discount'    => $afterDiscount,
            'cash_discount'     => (float) ($row['cash_discount'] ?? 0),
            'deposit_payment'   => (float) ($row['deposit_payment'] ?? 0),
            'siblings_discount' => (float) ($row['siblings_discount'] ?? 0),
            'shift_type'        => trim($row['shift_type'] ?? 'Day'),
            'status'            => trim($row['status'] ?? 'Active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'university'            => 'required|string',
            'department'            => 'required|string',
            'degree'                => 'required|string',
            'language'              => 'required|string',
            'price_before_discount' => 'required|numeric|min:0',
            'price_after_discount'  => 'nullable|numeric|min:0',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    private function resolveUniversity(?string $name): ?int
    {
        if (!$name) {
            return null;
        }

        $key = strtolower(trim($name));

        if (!array_key_exists($key, $this->universityCache)) {
            $university = University::whereRaw('LOWER(name) = ?', [$key])->first();

            if (!$university) {
                $university = University::create([
                    'name'       => trim($name),
                    'is_active'  => true,
                    'country_id' => 0,
                    'city_id'    => 0,
                ]);
            }

            $this->universityCache[$key] = $university->id;
        }

        return $this->universityCache[$key];
    }
}
