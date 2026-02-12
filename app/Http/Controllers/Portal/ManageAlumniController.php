<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AlumniRecordExport;

use App\Models\Program;
use App\Models\Strand;

class ManageAlumniController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $role = Auth::user()?->role ?? 'user';
            abort_unless(in_array($role, ['admin', 'it_admin', 'alumni_officer'], true), 403);
            return $next($request);
        });
    }

    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        $q       = $request->query('q');
        $field   = $request->query('field', 'all');
        $from    = $request->query('from');
        $to      = $request->query('to');
        $perPage = (int) $request->query('per_page', 10);

        $trashed = $request->query('trashed', 'active'); // active|deleted|all
        $sort    = $request->query('sort', 'created_at');
        $dir     = strtolower($request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['id', 'full_name', 'email', 'created_at'];
        if (!in_array($sort, $allowedSorts, true)) $sort = 'created_at';

        $query = Alumnus::query();

        if ($trashed === 'deleted') {
            $query->onlyTrashed();
        } elseif ($trashed === 'all') {
            $query->withTrashed();
        }

        if ($q) {
            $query->where(function ($sub) use ($q, $field) {
                if ($field === 'name') {
                    $sub->where('full_name', 'like', "%{$q}%");
                } elseif ($field === 'email') {
                    $sub->where('email', 'like', "%{$q}%");
                } elseif ($field === 'id') {
                    $sub->where('id', $q);
                } else {
                    $sub->where('full_name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('id', $q);
                }
            });
        }

        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to)   $query->whereDate('created_at', '<=', $to);

        $records = $query
            ->orderBy($sort, $dir)
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('portal.records._table', compact('records'));
        }

        return view('portal.records.index', compact('records'));
    }

    /* =========================
     * SHOW
     * ========================= */
    public function show(Alumnus $alumnus)
    {
        $alumnus->load([
            'educations.program',
            'educations.strand',
            'employments',
            'communityInvolvements',
            'engagementPreference',
            'consent',
            'user',
        ]);

        return view('portal.records.show', compact('alumnus'));
    }

    /* =========================
     * EDIT
     * ========================= */
//     public function edit(Alumnus $alumnus)
// {
//     $alumnus->load([
//         'educations.program',
//         'educations.strand',
//         'employments',
//         'communityInvolvements',
//         'engagementPreference',
//         'consent',
//     ]);

//     $programs = Program::active()
//         ->orderBy('category')
//         ->orderBy('name')
//         ->get()
//         ->groupBy('category');

//     $strands = Strand::active()
//         ->orderBy('name')
//         ->get();

//     return view('portal.records.edit', compact('alumnus', 'programs', 'strands'));
// }
public function edit(Alumnus $alumnus)
{
    $alumnus->load(['educations','employments','communityInvolvements','engagementPreference','consent']);

    // Programs grouped by category (plain array for @json)
    $programs_by_cat = \App\Models\Program::where('is_active', true)
        ->orderBy('name')
        ->get()
        ->groupBy('category')
        ->map(function ($items) {
            return $items->map(function ($p) {
                return [
                    'id'   => $p->id,
                    'code' => $p->code,
                    'name' => $p->name,
                ];
            })->values();
        })
        ->toArray();

    // Strands list (plain array for @json)
    $strands_list = \App\Models\Strand::where('is_active', true)
        ->orderBy('name')
        ->get()
        ->map(function ($s) {
            return [
                'id'   => $s->id,
                'code' => $s->code,
                'name' => $s->name,
            ];
        })
        ->toArray();

    return view('portal.records.edit', compact('alumnus', 'programs_by_cat', 'strands_list'));
}



    /* =========================
     * UPDATE
     * ========================= */
    public function update(Request $request, Alumnus $alumnus)
    {
        $request->validate([
            'email' => ['nullable', 'email', 'max:255'],
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

    // Normalize Others
    if (($row['program_id'] ?? null) === '__other__') {
        $row['program_id'] = null;
        $row['specific_program'] = trim((string)($row['specific_program'] ?? '')) ?: null;
    } else {
        // If program selected, wipe specific_program
        $row['specific_program'] = null;
    }

    // Normalize graduate year logic
    if (($row['did_graduate'] ?? null) === '1' || ($row['did_graduate'] ?? null) === 1) {
        $row['last_year_attended'] = null;
    }
    if (($row['did_graduate'] ?? null) === '0' || ($row['did_graduate'] ?? null) === 0) {
        $row['year_graduated'] = null;
    }

    $alumnus->educations()->create([
    'level' => $row['level'],

    'did_graduate' => $row['did_graduate'] ?? null,
    'program_id'   => $row['program_id'] ?? null,
    'specific_program' => $row['specific_program'] ?? null,

    'strand_id'    => $row['strand_id'] ?? null,
    'strand_track' => $row['strand_track'] ?? null,

    'student_number'     => $row['student_number'] ?? null,
    'year_entered'       => $row['year_entered'] ?? null,
    'year_graduated'     => $row['year_graduated'] ?? null,
    'last_year_attended' => $row['last_year_attended'] ?? null,

    'degree_program' => $row['degree_program'] ?? null,
    'research_title' => $row['research_title'] ?? null,
    'thesis_title'   => $row['thesis_title'] ?? null,

    'honors_awards'              => $row['honors_awards'] ?? null,
    'extracurricular_activities' => $row['extracurricular_activities'] ?? null,
    'clubs_organizations'        => $row['clubs_organizations'] ?? null,

    'institution_name'    => $row['institution_name'] ?? null,
    'institution_address' => $row['institution_address'] ?? null,
    'course_degree'       => $row['course_degree'] ?? null,
    'year_completed'      => $row['year_completed'] ?? null,
    'scholarship_award'   => $row['scholarship_award'] ?? null,
    'notes'               => $row['notes'] ?? null,
]);

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
                'willing_contacted'   => !empty($pref['willing_contacted']),
                'willing_events'      => !empty($pref['willing_events']),
                'willing_mentor'      => !empty($pref['willing_mentor']),
                'willing_donation'    => !empty($pref['willing_donation']),
                'willing_scholarship' => !empty($pref['willing_scholarship']),
                'prefer_email'        => !empty($pref['prefer_email']),
                'prefer_sms'          => !empty($pref['prefer_sms']),
                'prefer_facebook'     => !empty($pref['prefer_facebook']),
            ]
        );

        $consent = $request->input('consent', []);
        $alumnus->consent()->updateOrCreate(
            ['alumnus_id' => $alumnus->id],
            [
                'signature_name' => $consent['signature_name'] ?? null,
                'consented_at'   => !empty($consent['signature_name']) ? now() : null,
                'ip_address'     => $request->ip(),
            ]
        );

        return redirect()
            ->route('portal.records.edit', $alumnus)
            ->with('success', 'Record updated.');
    }

    /* =========================
     * DELETE
     * ========================= */
    public function destroy(Alumnus $alumnus)
    {
        abort_unless(in_array(Auth::user()?->role, ['admin','it_admin'], true), 403);

        $alumnus->delete();

        return redirect()
            ->route('portal.records.index')
            ->with('success', 'Record soft-deleted.');
    }

    /* =========================
     * PDF
     * ========================= */
    public function downloadPdf(Alumnus $alumnus)
    {
        $alumnus->load([
            'educations.program',
            'educations.strand',
            'employments',
            'communityInvolvements',
            'engagementPreference',
            'consent',
        ]);

        $pdf = Pdf::loadView(
            'exports.alumni_record_pdf',
            compact('alumnus')
        )->setPaper('a4', 'portrait');

        return $pdf->download('alumni_record_'.$alumnus->id.'.pdf');
    }

    /* =========================
     * EXCEL (optional later)
     * ========================= */
    public function downloadExcel(Alumnus $alumnus)
    {
        return Excel::download(
            new AlumniRecordExport((int) $alumnus->id),
            'alumni_record_'.$alumnus->id.'.xlsx'
        );
    }
}
