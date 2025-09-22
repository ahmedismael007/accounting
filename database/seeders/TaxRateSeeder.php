<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('data/tax_rates.json'));

        $tax_rates = json_decode($json, true);

        $mapped = array_map(function ($item) {
            return [
                'name_ar'    => $item['name']['ar'] ?? null,
                'name_en'    => $item['name']['en'] ?? null,
                'tax_type'   => $item['tax_type'] ?? null,
                'tax_rate'   => $item['tax_rate'] ?? 0,
                'description' => $item['description'] ?? null,
                'is_system'  => $item['is_system'] ?? false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $tax_rates);

        DB::table('tax_rates')->insert($mapped);
    }
}
