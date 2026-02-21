<?php

namespace App\Http\Controllers\ITAdmin;

use App\Http\Controllers\Controller;
use App\Models\Religion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReligionController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $rows = Religion::query()
            ->when($q !== '', fn($qry) => $qry->where('name', 'like', '%' . strtoupper($q) . '%'))
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('itadmin.religions.index', compact('rows', 'q'));
    }

    public function create()
    {
        return view('itadmin.religions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $name = strtoupper(trim((string) $request->input('name')));

        Religion::updateOrCreate(
            ['name' => $name],
            [
                'is_active' => true,
                'created_by' => Auth::id(),
            ]
        );

        return redirect()->route('itadmin.religions.index')
            ->with('success', 'Religion saved.');
    }

    public function edit(Religion $religion)
    {
        return view('itadmin.religions.edit', compact('religion'));
    }

    public function update(Request $request, Religion $religion)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $name = strtoupper(trim((string) $request->input('name')));

        $exists = Religion::where('name', $name)
            ->where('id', '!=', $religion->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'Religion already exists.'])->withInput();
        }

        $religion->update(['name' => $name]);

        return redirect()->route('itadmin.religions.index')
            ->with('success', 'Religion updated.');
    }

    public function toggle(Religion $religion)
    {
        $religion->update(['is_active' => !$religion->is_active]);

        return redirect()->route('itadmin.religions.index')
            ->with('success', 'Religion status updated.');
    }
}
