<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationalitySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'FILIPINO',
            'AMERICAN',
            'CANADIAN',
            'AUSTRALIAN',
            'BRITISH',
            'CHINESE',
            'JAPANESE',
            'KOREAN',
            'INDIAN',
            'MALAYSIAN',
            'SINGAPOREAN',
            'INDONESIAN',
            'THAI',
            'VIETNAMESE',
            'GERMAN',
            'FRENCH',
            'SPANISH',
        ];

        $now = now();

        foreach ($items as $name) {
            DB::table('nationalities')->updateOrInsert(
                ['name' => strtoupper(trim($name))],
                ['is_active' => 1, 'created_by' => null, 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
