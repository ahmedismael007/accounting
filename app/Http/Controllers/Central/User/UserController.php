<?php

namespace App\Http\Controllers\Central\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Central\User\LoginRequest;
use App\Http\Requests\Central\User\RegisterRequest;
use App\Http\Requests\Central\User\UpdateProfileRequest;
use App\Http\Resources\Central\User\UserResource;
use App\Models\Central\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $credentials = $request->only('email', 'password');

        $token = auth()->attempt($credentials);

        return response()->json([
            'message' => trans('auth.registered'),
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => trans('auth.failed')], 401);
        }

        $tenant = $user->tenants()->first();

        if (!$tenant) {
            return response()->json(['message' => trans('auth.logged_in'), 'domain' =>  'authentication/create-new-organization', 'token'
            => $token], 200);
        }

        $domain = $tenant->domains()->first()->domain ?? null;

        $response = ['message' => trans('auth.logged_in'), 'domain' => $domain, 'token' => $token];

        return response()->json($response, 200);
    }

    public function user_tenants(Request $request)
    {
        $user = Auth::user()->load(['tenants.domains', 'tenants.media', 'media']);

        $user['avatar'] = $user->getFirstMediaUrl('avatar');

        $tenants = $user->tenants->map(function ($tenant) {
            return [
                'name' => $tenant->name,
                'domain' => $tenant->domains->first()?->domain,
                'logo' => $tenant->getFirstMediaUrl('organization_logo'),
            ];
        });

        return response()->json([
            'user' => new UserResource($user),
            'organizations' => $tenants,
        ], 200);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();

        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
        ]);

        $user->update($data);

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatar');
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        return response()->json([
            'message' => 'تم تحديث البيانات بنجاح.',
            'user' => $user->load('media'),
            'avatar' => $user->getFirstMediaUrl('avatar'),
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح.'
        ]);
    }
}
