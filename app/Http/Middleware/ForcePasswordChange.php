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

            // ✅ Allow routes needed to change password + logout
            if (
                $request->routeIs('profile.*') ||
                $request->routeIs('password.update') ||   // PUT /password (Breeze update-password form)
                $request->routeIs('password.*') ||        // (optional) allow reset flows
                $request->routeIs('logout')
            ) {
                return $next($request);
            }

            return redirect()
                ->route('profile.edit')
                ->with('status', 'password-change-required');
        }

        return $next($request);
    }
}
