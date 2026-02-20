<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'ROMAN CATHOLIC',
            'ISLAM',
            'IGLESIA NI CRISTO',
            'SEVENTH-DAY ADVENTIST',
            'BORN AGAIN CHRISTIAN',
            'BAPTIST',
            'METHODIST',
            'JEHOVAH\'S WITNESS',
            'OTHERS',
        ];

        $now = now();

        foreach ($items as $name) {
            DB::table('religions')->updateOrInsert(
                ['name' => strtoupper(trim($name))],
                ['is_active' => 1, 'created_by' => null, 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
