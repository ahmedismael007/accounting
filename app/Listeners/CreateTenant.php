<?php

namespace App\Listeners;

use App\Events\TenantCreated;
use App\Models\Central\Tenant\Tenant;
use App\Models\Tenant\User\User;
use Illuminate\Support\Facades\Log;

class CreateTenant
{
    public function handle(TenantCreated $event): void
    {
        $data = $event->data;
        $userData = $data['user'];
        $tenantData = $data['tenant'];

        // Create tenant in central DB
        $tenantModel = Tenant::create($tenantData);

        // Initialize tenant context
        tenancy()->initialize($tenantModel);

        // Create user in tenant DB
        User::create($userData);
    }
}
