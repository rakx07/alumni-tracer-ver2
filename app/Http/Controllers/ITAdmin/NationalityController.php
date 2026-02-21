<?php

namespace App\Http\Controllers\ITAdmin;

use App\Http\Controllers\Controller;
use App\Models\Nationality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NationalityController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $rows = Nationality::query()
            ->when($q !== '', fn($qry) => $qry->where('name', 'like', '%' . strtoupper($q) . '%'))
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('itadmin.nationalities.index', compact('rows', 'q'));
    }

    public function create()
    {
        return view('itadmin.nationalities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $name = strtoupper(trim((string) $request->input('name')));

        Nationality::updateOrCreate(
            ['name' => $name],
            [
                'is_active' => true,
                'created_by' => Auth::id(),
            ]
        );

        return redirect()->route('itadmin.nationalities.index')
            ->with('success', 'Nationality saved.');
    }

    public function edit(Nationality $nationality)
    {
        return view('itadmin.nationalities.edit', compact('nationality'));
    }

    public function update(Request $request, Nationality $nationality)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $name = strtoupper(trim((string) $request->input('name')));

        // Avoid duplicate unique constraint error
        $exists = Nationality::where('name', $name)
            ->where('id', '!=', $nationality->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'Nationality already exists.'])->withInput();
        }

        $nationality->update(['name' => $name]);

        return redirect()->route('itadmin.nationalities.index')
            ->with('success', 'Nationality updated.');
    }

    public function toggle(Nationality $nationality)
    {
        $nationality->update(['is_active' => !$nationality->is_active]);

        return redirect()->route('itadmin.nationalities.index')
            ->with('success', 'Nationality status updated.');
    }
}
