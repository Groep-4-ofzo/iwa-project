<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = auth()->user();

        if (! $user || ! $user->userRole) {
            abort(403, 'Geen toegang.');
        }

        // Meerdere rollen mogelijk: 'role:admin,manager'
        if (! in_array($user->userRole->role, $roles)) {
            abort(403, 'Geen toegang.');
        }

        return $next($request);
    }
}
