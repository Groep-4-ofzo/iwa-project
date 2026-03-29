<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $subscription = Subscription::where('token', $token)->first();

        if (empty($subscription)) {
            return response()->json(['error' => 'Unauthorized, Invalid API key'], 401);
        }

        $request->attributes->add(['subscription' => $subscription]);

        return $next($request);
    }
}
