<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;

class DashboardController extends Controller
{
    public function index()
    {
        return view('backoffice.dashboard', [
            'total' => Alumnus::count(),
            'deleted' => Alumnus::onlyTrashed()->count(),
        ]);
    }
}
