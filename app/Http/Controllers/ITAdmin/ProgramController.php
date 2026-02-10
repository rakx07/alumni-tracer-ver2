<?php

namespace App\Http\Controllers\ITAdmin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProgramsImport;
use Throwable;

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
            $s = trim((string) $request->search);
            $q->where(function ($qq) use ($s) {
                $qq->where('name', 'like', "%{$s}%")
                   ->orWhere('code', 'like', "%{$s}%");
            });
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
            'category'  => ['required', 'in:college,grad_school,law'],
            'code'      => ['nullable', 'string', 'max:50'],
            'name'      => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] = isset($data['code']) && trim((string)$data['code']) !== ''
            ? strtoupper(trim((string)$data['code']))
            : null;

        // if checkbox is not sent, default active = true on create
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        Program::create($data);

        return redirect()
            ->route('itadmin.programs.index')
            ->with('success', 'Program added successfully.');
    }

    public function edit(Program $program)
    {
        return view('itadmin.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'category'  => ['required', 'in:college,grad_school,law'],
            'code'      => ['nullable', 'string', 'max:50'],
            'name'      => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] = isset($data['code']) && trim((string)$data['code']) !== ''
            ? strtoupper(trim((string)$data['code']))
            : null;

        // if checkbox is not sent, default active = false on update (your current behavior)
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $program->update($data);

        return redirect()
            ->route('itadmin.programs.index')
            ->with('success', 'Program updated successfully.');
    }

    public function toggle(Program $program)
    {
        $program->update([
            'is_active' => ! (bool) $program->is_active
        ]);

        return back()->with('success', 'Program status updated.');
    }

    /* =========================
     | Upload (Excel/CSV)
     ========================= */

    public function uploadForm()
    {
        return view('itadmin.programs.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                // Excel or CSV
                'mimes:xlsx,xls,csv',
                // 10MB
                'max:10240',
            ],
        ]);

        try {
            $import = new ProgramsImport();
            Excel::import($import, $request->file('file'));

            return redirect()
                ->route('itadmin.programs.index')
                ->with(
                    'success',
                    "Upload completed. Inserted: {$import->inserted}, Updated: {$import->updated}, Skipped: {$import->skipped}"
                );
        } catch (Throwable $e) {
            return back()
                ->with('warning', 'Upload failed. Please check the file format and headers (category, code, name, is_active).')
                ->withErrors(['file' => $e->getMessage()]);
        }
    }

    public function downloadTemplate()
{
    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="programs_template.csv"',
    ];

    $rows = [
        ['category','code','name','is_active'],
        ['college','BSCS','Bachelor of Science in Computer Science',1],
        ['grad_school','MIT','Master in Information Technology',1],
        ['law','JD','Juris Doctor',1],
    ];

    $callback = function () use ($rows) {
        $out = fopen('php://output', 'w');

        // Optional: Excel-friendly UTF-8 BOM
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

        foreach ($rows as $row) {
            fputcsv($out, $row);
        }
        fclose($out);
    };

    return response()->stream($callback, 200, $headers);
}

}
