<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Artisan;
use Stancl\Tenancy\Events\TenantCreated;

class SeedTenantData
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TenantCreated $event): void
    {
        tenancy()->initialize($event->tenant);

        // Artisan::call('migrate', [
        //     '--force' => true,
        // ]);

        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BankAccountSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\AccountSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\TaxRateSeeder']);


        tenancy()->end();
    }
}
