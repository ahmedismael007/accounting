<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenantFromHeader
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $tenantId = $request->header('X-Tenant');
        $tenantId = '019995aa-1d30-70bc-bfca-4d8483a67a79';
        // $tenantId = '0199982c-b054-7134-9065-d44c274f4fc6';
        
        if ($tenantId) {
            try {
                tenancy()->initialize($tenantId);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Invalid tenant identifier.',
                    'error' => $e->getMessage(),
                ], 400);
            }
        }

        return $next($request);
    }
}
