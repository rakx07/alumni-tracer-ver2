<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureIntakeCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Enforce ONLY for regular alumni users
        if (!$user || ($user->role ?? 'user') !== 'user') {
            return $next($request);
        }

        // Completed already
        if (!empty($user->intake_completed_at)) {
            return $next($request);
        }

        // Always allow the intake pages even if incomplete
        if ($request->routeIs('intake.form', 'intake.save')) {
            return $next($request);
        }

        // Allow profile even if incomplete (per your rule)
        if ($request->routeIs('profile.edit', 'profile.update', 'profile.password', 'profile.destroy')) {
            return $next($request);
        }

        return redirect()
            ->route('intake.form')
            ->with('warning', 'Please complete the Alumni Intake Form before accessing the dashboard and other services.');
    }
}
