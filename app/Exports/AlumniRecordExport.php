<?php

namespace App\Exports;

use App\Models\Alumnus;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AlumniRecordExport implements FromArray, WithHeadings
{
    public function __construct(public int $alumnusId) {}

    public function headings(): array
    {
        return ['ID','Full Name','Email','Contact','Created At'];
    }

    public function array(): array
    {
        $a = Alumnus::findOrFail($this->alumnusId);

        return [[
            $a->id,
            $a->full_name,
            $a->email,
            $a->contact_number,
            $a->created_at,
        ]];
    }
}
