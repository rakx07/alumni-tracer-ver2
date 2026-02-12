<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCareerManager
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user, 403);

        // Works with Spatie (hasRole) OR plain string role column.
        $ok = false;

        if (method_exists($user, 'hasRole')) {
            $ok = $user->hasRole(['it_admin', 'alumni_officer']);
        } else {
            $role = $user->role ?? null;
            $ok = in_array($role, ['it_admin', 'alumni_officer'], true);
        }

        abort_unless($ok, 403);

        return $next($request);
    }
}
