<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $alumnus = Alumnus::where('user_id', Auth::id())->first();
        return view('user.dashboard', compact('alumnus'));
    }
}
