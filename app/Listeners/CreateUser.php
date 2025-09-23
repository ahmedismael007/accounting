<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Tenant\User\User;
use Illuminate\Support\Facades\Log;

class CreateUser
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
    public function handle(UserCreated $event): void
    {
        $tenantId = $event->tenantId; 
        $userData = $event->userData;

        if($tenantId) {
            tenancy()->initialize($tenantId);
        }

        User::create($userData);

        Log::alert('success');
    }
}
