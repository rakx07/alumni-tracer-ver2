<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Usage: ->middleware('role:it_admin') or ->middleware('role:admin,it_admin')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $userRole = auth()->user()?->role;

        if (!$userRole || !in_array($userRole, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
