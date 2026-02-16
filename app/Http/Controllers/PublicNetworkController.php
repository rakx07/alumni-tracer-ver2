<?php

namespace App\Http\Controllers;

use App\Models\AlumniNetwork;

class PublicNetworkController extends Controller
{
    public function index()
    {
        $networks = AlumniNetwork::query()
            ->where('is_active', true)
            // sort_order if provided; otherwise alphabetical by title
            ->orderByRaw('sort_order IS NULL, sort_order ASC')
            ->orderBy('title')
            ->get();

        return view('networks.index', compact('networks'));
    }
    
}
