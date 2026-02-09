<?php

namespace App\Imports;

use App\Models\Program;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProgramsImport implements ToCollection, WithHeadingRow
{
    public int $inserted = 0;
    public int $updated  = 0;
    public int $skipped  = 0;

    /**
     * Expected headers:
     * category | code | name | is_active
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $category = strtolower(trim((string)($row['category'] ?? '')));
            $code     = trim((string)($row['code'] ?? ''));
            $name     = trim((string)($row['name'] ?? ''));
            $active   = $row['is_active'] ?? 1;

            // Required fields
            if ($category === '' || $name === '') {
                $this->skipped++;
                continue;
            }

            if (!in_array($category, ['college', 'grad_school', 'law'], true)) {
                $this->skipped++;
                continue;
            }

            $program = Program::where('category', $category)
                ->where(function ($q) use ($code, $name) {
                    if ($code !== '') {
                        $q->where('code', $code);
                    } else {
                        $q->where('name', $name);
                    }
                })
                ->first();

            $data = [
                'category'  => $category,
                'code'      => $code !== '' ? strtoupper($code) : null,
                'name'      => $name,
                'is_active' => (bool)$active,
            ];

            if ($program) {
                $program->update($data);
                $this->updated++;
            } else {
                Program::create($data);
                $this->inserted++;
            }
        }
    }
}
