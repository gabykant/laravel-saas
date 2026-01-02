<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class SetTenantContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Sécurité : utilisateur non authentifié
        if (! $user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Vérifier que l'utilisateur a bien un tenant
        if (! $user->tenant_id) {
            return response()->json([
                'message' => 'Tenant not associated with user'
            ], 403);
        }

        $tenant = Tenant::find($user->tenant_id);

        if (! $tenant) {
            return response()->json([
                'message' => 'Tenant not found'
            ], 404);
        }

        // Partager le tenant dans toute l'app
        app()->instance('tenant', $tenant);

        return $next($request);
    }
}
