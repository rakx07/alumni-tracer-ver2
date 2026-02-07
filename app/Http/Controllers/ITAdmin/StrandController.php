<?php

namespace App\Http\Controllers\ITAdmin;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StrandsImport;

class StrandController extends Controller
{
    public function index(Request $request)
    {
        $q = Strand::query();

        if ($request->filled('status')) {
            $q->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(fn ($qq) =>
                $qq->where('name', 'like', "%{$s}%")
                   ->orWhere('code', 'like', "%{$s}%")
            );
        }

        $strands = $q->orderBy('name')
                     ->paginate(20)
                     ->withQueryString();

        return view('itadmin.strands.index', compact('strands'));
    }

    public function create()
    {
        return view('itadmin.strands.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = (bool)($data['is_active'] ?? true);

        Strand::create($data);

        return redirect()->route('itadmin.strands.index')
            ->with('success', 'Strand added successfully.');
    }

    public function edit(Strand $strand)
    {
        return view('itadmin.strands.edit', compact('strand'));
    }

    public function update(Request $request, Strand $strand)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = (bool)($data['is_active'] ?? false);

        $strand->update($data);

        return redirect()->route('itadmin.strands.index')
            ->with('success', 'Strand updated successfully.');
    }

    public function toggle(Strand $strand)
    {
        $strand->update([
            'is_active' => ! $strand->is_active
        ]);

        return back()->with('success', 'Strand status updated.');
    }

    public function uploadForm()
    {
        return view('itadmin.strands.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt'],
        ]);

        $import = new StrandsImport();
        Excel::import($import, $request->file('file'));

        return redirect()->route('itadmin.strands.index')
            ->with('success',
                "Upload completed. Inserted: {$import->inserted}, Updated: {$import->updated}, Skipped: {$import->skipped}"
            );
    }
}
