<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use Illuminate\Support\Facades\Auth;

class PortalDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user?->role ?? 'user';

        // Officer/Admin dashboard
        if (in_array($role, ['it_admin', 'alumni_officer'], true)) {
            return view('portal.dashboard');
        }

        // User dashboard
        $alumnus = Alumnus::where('user_id', Auth::id())->first();
        return view('user.dashboard', compact('alumnus'));
    }
}
