<?php

namespace App\Listeners;

use App\Events\TenantCreated;
use App\Events\UserCreated;
use App\Models\Central\Tenant\Tenant;
use App\Models\Tenant\User\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class CreateTenant
{
    public function handle(TenantCreated $event): void
    {
        $data = $event->data;
        $userData = $data['user'];
        $tenantData = $data['tenant'];
        $tenantId = $tenantData['id'];

        // Create tenant in central DB
        $tenantModel = Tenant::create($tenantData);
 
        Event::dispatch(new UserCreated($userData, $tenantId));

     }
}
