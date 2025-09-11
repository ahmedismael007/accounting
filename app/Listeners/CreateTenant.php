<?php

namespace App\Listeners;

use App\Events\TenantCreated;
use App\Events\UserCreated;
use App\Models\Central\Tenant\Tenant;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class CreateTenant
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
        $data = $event->data;
        $user = $data['user'];
        $tenant = $data['tenant'];

        Log::alert($data);

        Tenant::create($tenant);

        tenancy()->initialize($tenant['id']);

        Event::dispatch(new UserCreated($user));
    }
}
