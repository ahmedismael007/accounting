<?php

namespace App\Listeners;

use App\Events\TenantUpdated;

class UpdateTenant
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
    public function handle(TenantUpdated $event): void
    {
        //
    }
}
