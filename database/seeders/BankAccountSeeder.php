<?php

namespace Database\Seeders;

use App\Models\Tenant\Accounting\BankAccounts\BankAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
class BankAccountSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('data/bank_accounts.json'));

        $bank_accounts = json_decode($json, true);

        $mapped = array_map(function ($item) {
            return [
                'name_ar'  => $item['name']['ar'] ?? null,
                'name_en'  => $item['name']['en'] ?? null,
                'type'     => $item['type'],
                'currency' => $item['currency'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $bank_accounts);

        DB::table('bank_accounts')->insert($mapped);
    }
}
