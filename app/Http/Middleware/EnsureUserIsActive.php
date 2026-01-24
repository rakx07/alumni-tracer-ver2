<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && !$user->is_active) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account is currently disabled. Please contact MIS/IT Admin.',
            ]);
        }

        return $next($request);
    }
}
