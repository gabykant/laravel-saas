<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = app('tenant');

        if (! $tenant || ! $tenant->subscribed('default')) {
            return response()->json([
                'message' => 'Subscription required.'
            ], 402); // 402 Payment Required
        }
        
        return $next($request);
    }
}
