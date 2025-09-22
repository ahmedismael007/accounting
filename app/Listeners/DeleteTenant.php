<?php

namespace App\Listeners;

use App\Events\TenantDeleted;

class DeleteTenant
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
    public function handle(TenantDeleted $event): void
    {
        //
    }
}
