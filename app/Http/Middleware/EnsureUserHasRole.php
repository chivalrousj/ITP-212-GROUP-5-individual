<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // If the user's role is not in the allowed list, show 403 Forbidden
        abort_unless($request->user()?->hasRole(...$roles), 403);

        return $next($request);
    }
}