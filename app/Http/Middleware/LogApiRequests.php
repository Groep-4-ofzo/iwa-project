<?php

namespace App\Http\Middleware;

use App\Models\EndpointActivity;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $subscription = $request->attributes->get('subscription');

        EndpointActivity::create([
            'identifier' => $subscription?->identifier,
            'endpoint_used' => $request->route()->uri,
            'files_downloaded' => 1,
            'activity_date' => now()->toDateString(),
            'activity_time' => now()->toTimeString(),
            'authorized' => $subscription ? 1 : 0,
            'data_transferred' => strlen($response->getContent()),
        ]);

        return $response;
    }
}
