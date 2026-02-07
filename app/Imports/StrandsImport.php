<?php

namespace App\Imports;

use App\Models\Strand;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StrandsImport implements ToCollection, WithHeadingRow
{
    public int $inserted = 0;
    public int $updated  = 0;
    public int $skipped  = 0;

    /**
     * Expected headers:
     * code | name | is_active
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $code   = strtoupper(trim((string)($row['code'] ?? '')));
            $name   = trim((string)($row['name'] ?? ''));
            $active = $row['is_active'] ?? 1;

            if ($code === '' || $name === '') {
                $this->skipped++;
                continue;
            }

            $strand = Strand::where('code', $code)->first();

            $data = [
                'code'      => $code,
                'name'      => $name,
                'is_active' => (bool)$active,
            ];

            if ($strand) {
                $strand->update($data);
                $this->updated++;
            } else {
                Strand::create($data);
                $this->inserted++;
            }
        }
    }
}
