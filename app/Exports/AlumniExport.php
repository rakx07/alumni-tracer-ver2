<?php

namespace App\Exports;

use App\Models\Alumnus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AlumniExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Alumnus::select('id','full_name','email','contact_number','nationality','religion','created_at')->get();
    }

    public function headings(): array
    {
        return ['ID','Full Name','Email','Contact','Nationality','Religion','Created At'];
    }
}
