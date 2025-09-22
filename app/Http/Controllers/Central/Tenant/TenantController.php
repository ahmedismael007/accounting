<?php

namespace App\Http\Controllers\Central\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Central\Tenant\TenantRequest;
use App\Models\Central\Tenant\Tenant;
use App\Models\Tenant\User\User as TenantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Database\Models\Domain;

class TenantController extends Controller
{
    public function index()
    {
        $tenant = tenant();
        $logo = $tenant->getFirstMedia('organization_logo');
        if ($logo) {
            $tenant['logo'] = $logo->getUrl();
        } else {
            $tenant['logo'] = '';
        }
        return response()->json(['data' => $tenant], 200);
    }

    public function store(TenantRequest $request)
    {
        $domain = $request->input('domain');

        if (Domain::where('domain', $domain)->exists()) {
            return response()->json(['message' => trans('tenant.domain_used')], 409);
        };

        $response = null;

        $user = Auth::user();

        $tenant = Tenant::create($request->validated());
        $tenant->domains()->create(['domain' => $domain]);

        $tenant->users()->attach($user->id, ['is_owner' => true]);

        $tenant->run(function () use ($user, $domain, $tenant, &$response) {
            $tenant_user = TenantUser::create(
                [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'password' => $user->password,
                ]
            );

            $response = response()->json([
                'message' => 'تم إنشاء المؤسسة بنجاح.',
                'domain' => $domain,
                'tenant_id' => $tenant->id
            ], 201);
        });

        Auth::login($user);

        return $response;
    }

    public function update(TenantRequest $request)
    {
        $validated = $request->validated();

        $tenant_id = tenant()->id;
        $tenant = Tenant::findOrFail($tenant_id);
        $tenant->update($validated);

        return response()->json(['message' => 'تم تعديل بيانات المنشأة بنجاح.']);
    }


    public function update_logo(Request $request)
    {
        $tenant = tenant();

        $validated = $request->validate([
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $tenant->addMediaFromRequest('logo')
                ->toMediaCollection('organization_logo');
        } else {
            $tenant->clearMediaCollection('organization_logo');
        }

        return response()->json([
            'message' => 'تم تحديث الشعار بنجاح.',
            'logo' => $tenant->getFirstMediaUrl('organization_logo'),
        ]);
    }
}
