<?php

namespace App\Http\Controllers\ITAdmin;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StrandsImport;
use Throwable;

class StrandController extends Controller
{
    public function index(Request $request)
    {
        $q = Strand::query();

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
            'code'      => ['required', 'string', 'max:50'],
            'name'      => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] = strtoupper(trim((string) $data['code']));
        // default active = true on create
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        Strand::create($data);

        return redirect()
            ->route('itadmin.strands.index')
            ->with('success', 'Strand added successfully.');
    }

    public function edit(Strand $strand)
    {
        return view('itadmin.strands.edit', compact('strand'));
    }

    public function update(Request $request, Strand $strand)
    {
        $data = $request->validate([
            'code'      => ['required', 'string', 'max:50'],
            'name'      => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] = strtoupper(trim((string) $data['code']));
        // default active = false on update (your current behavior)
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $strand->update($data);

        return redirect()
            ->route('itadmin.strands.index')
            ->with('success', 'Strand updated successfully.');
    }

    public function toggle(Strand $strand)
    {
        $strand->update([
            'is_active' => ! (bool) $strand->is_active
        ]);

        return back()->with('success', 'Strand status updated.');
    }

    /* =========================
     | Upload (Excel/CSV)
     ========================= */

    public function uploadForm()
    {
        return view('itadmin.strands.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240', // 10MB
            ],
        ]);

        try {
            $import = new StrandsImport();
            Excel::import($import, $request->file('file'));

            return redirect()
                ->route('itadmin.strands.index')
                ->with(
                    'success',
                    "Upload completed. Inserted: {$import->inserted}, Updated: {$import->updated}, Skipped: {$import->skipped}"
                );
        } catch (Throwable $e) {
            return back()
                ->with('warning', 'Upload failed. Please check the file headers (code, name, is_active).')
                ->withErrors(['file' => $e->getMessage()]);
        }
    }
    public function downloadTemplate()
{
    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="strands_template.csv"',
    ];

    $rows = [
        ['code','name','is_active'],
        ['STEM','Science, Technology, Engineering and Mathematics',1],
        ['ABM','Accountancy, Business and Management',1],
        ['HUMSS','Humanities and Social Sciences',1],
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
