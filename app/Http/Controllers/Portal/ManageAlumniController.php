<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AlumniRecordExport;

class ManageAlumniController extends Controller
{
    public function __construct()
    {
        // Officer/Admin can access all routes in this controller
        $this->middleware(function ($request, $next) {
            $role = Auth::user()?->role ?? 'user';
            abort_unless(in_array($role, ['admin', 'it_admin', 'alumni_officer'], true), 403);
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $q = $request->query('q');

        $records = Alumnus::query()
            ->when($q, function ($qr) use ($q) {
                $qr->where('full_name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('portal.records.index', compact('records', 'q'));
    }

    public function show(Alumnus $alumnus)
    {
        $alumnus->load(['educations','employments','communityInvolvements','engagementPreference','consent','user']);
        return view('portal.records.show', compact('alumnus'));
    }

    public function edit(Alumnus $alumnus)
    {
        $alumnus->load(['educations','employments','communityInvolvements','engagementPreference','consent']);
        return view('portal.records.edit', compact('alumnus'));
    }

    public function update(Request $request, Alumnus $alumnus)
    {
        $request->validate([
            'full_name' => ['required','string','max:255'],
            'email' => ['nullable','email','max:255'],
        ]);

        $alumnus->update($request->only([
            'full_name','nickname','sex','birthdate','age','civil_status',
            'home_address','current_address','contact_number','email','facebook',
            'nationality','religion'
        ]));

        $alumnus->educations()->delete();
        $alumnus->employments()->delete();
        $alumnus->communityInvolvements()->delete();

        foreach ($request->input('educations', []) as $row) {
            if (empty($row['level'])) continue;
            $alumnus->educations()->create($row);
        }

        foreach ($request->input('employments', []) as $row) {
            $hasAny = collect($row)->filter(fn($v) => $v !== null && $v !== '')->isNotEmpty();
            if (!$hasAny) continue;
            $alumnus->employments()->create($row);
        }

        foreach ($request->input('community', []) as $row) {
            if (empty($row['organization'])) continue;
            $alumnus->communityInvolvements()->create($row);
        }

        $pref = $request->input('engagement', []);
        $alumnus->engagementPreference()->updateOrCreate(
            ['alumnus_id' => $alumnus->id],
            [
                'willing_contacted' => !empty($pref['willing_contacted']),
                'willing_events' => !empty($pref['willing_events']),
                'willing_mentor' => !empty($pref['willing_mentor']),
                'willing_donation' => !empty($pref['willing_donation']),
                'willing_scholarship' => !empty($pref['willing_scholarship']),
                'prefer_email' => !empty($pref['prefer_email']),
                'prefer_sms' => !empty($pref['prefer_sms']),
                'prefer_facebook' => !empty($pref['prefer_facebook']),
            ]
        );

        $consent = $request->input('consent', []);
        $alumnus->consent()->updateOrCreate(
            ['alumnus_id' => $alumnus->id],
            [
                'signature_name' => $consent['signature_name'] ?? null,
                'consented_at' => !empty($consent['signature_name']) ? now() : null,
                'ip_address' => $request->ip(),
            ]
        );

        return redirect()->route('portal.records.edit', $alumnus)->with('success', 'Record updated.');
    }

    public function destroy(Alumnus $alumnus)
    {
        // IT ADMIN ONLY
        $role = Auth::user()?->role ?? 'user';
        abort_unless(in_array($role, ['admin', 'it_admin'], true), 403);

        $alumnus->delete(); // soft delete
        return redirect()->route('portal.records.index')->with('success', 'Record soft-deleted.');
    }

    public function downloadPdf(Alumnus $alumnus)
    {
        $alumnus->load(['educations','employments','communityInvolvements','engagementPreference','consent']);
        $pdf = Pdf::loadView('exports.alumni_record_pdf', compact('alumnus'))->setPaper('a4', 'portrait');
        return $pdf->download('alumni_record_'.$alumnus->id.'.pdf');
    }

    public function downloadExcel(Alumnus $alumnus)
    {
        return Excel::download(new AlumniRecordExport((int)$alumnus->id), 'alumni_record_'.$alumnus->id.'.xlsx');
    }
}
