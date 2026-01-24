<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->must_change_password) {
            // allow profile/password + logout
            if ($request->routeIs('profile.*') || $request->routeIs('logout')) {
                return $next($request);
            }

            return redirect()->route('profile.edit')->with('status', 'password-change-required');
        }

        return $next($request);
    }
}
