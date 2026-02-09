<?php

namespace App\Http\Controllers\ITAdmin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProgramsImport;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $q = Program::query();

        if ($request->filled('category')) {
            $q->where('category', $request->category);
        }

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

        $programs = $q->orderBy('category')
                      ->orderBy('name')
                      ->paginate(20)
                      ->withQueryString();

        return view('itadmin.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('itadmin.programs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => ['required', 'in:college,grad_school,law'],
            'code' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool)($data['is_active'] ?? true);

        Program::create($data);

        return redirect()->route('itadmin.programs.index')
            ->with('success', 'Program added successfully.');
    }

    public function edit(Program $program)
    {
        return view('itadmin.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'category' => ['required', 'in:college,grad_school,law'],
            'code' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool)($data['is_active'] ?? false);

        $program->update($data);

        return redirect()->route('itadmin.programs.index')
            ->with('success', 'Program updated successfully.');
    }

    public function toggle(Program $program)
    {
        $program->update([
            'is_active' => ! $program->is_active
        ]);

        return back()->with('success', 'Program status updated.');
    }

    public function uploadForm()
    {
        return view('itadmin.programs.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt'],
        ]);

        $import = new ProgramsImport();
        Excel::import($import, $request->file('file'));

        return redirect()->route('itadmin.programs.index')
            ->with('success',
                "Upload completed. Inserted: {$import->inserted}, Updated: {$import->updated}, Skipped: {$import->skipped}"
            );
    }
}
