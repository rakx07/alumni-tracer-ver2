<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Program;
use App\Models\Strand;

class IntakeController extends Controller
{
    public function __construct()
    {
        // ✅ Allow regular users to access intake (and optionally allow admins too)
        $this->middleware(function ($request, $next) {
            $role = Auth::user()?->role ?? 'user';

            // Allow these roles to use intake
            $allowedRoles = ['user', 'admin', 'it_admin', 'alumni_officer'];

            abort_unless(in_array($role, $allowedRoles, true), 403);

            return $next($request);
        });
    }

// public function form()
// {
//     $alumnus = Alumnus::with([
//         'educations','employments','communityInvolvements','engagementPreference','consent'
//     ])->where('user_id', Auth::id())->first();

//     $user = Auth::user();

//     $programs_by_cat = Program::where('is_active', true)
//         ->orderBy('name')
//         ->get()
//         ->groupBy('category')
//         ->map(fn($items) => $items->map(fn($p) => [
//             'id' => $p->id,
//             'code' => $p->code,
//             'name' => $p->name,
//         ]))
//         ->toArray(); // make it plain array for clean JSON

//     $strands_list = Strand::where('is_active', true)
//         ->orderBy('name')
//         ->get()
//         ->map(fn($s) => [
//             'id' => $s->id,
//             'code' => $s->code,
//             'name' => $s->name,
//         ])
//         ->toArray();

//     return view('user.intake', compact('alumnus', 'user', 'programs_by_cat', 'strands_list'));
// }
public function form()
{
    $alumnus = Alumnus::with([
        'educations','employments','communityInvolvements','engagementPreference','consent'
    ])->where('user_id', Auth::id())->first();

    $user = Auth::user();

    $programs_by_cat = Program::where('is_active', true)
        ->orderBy('name')
        ->get()
        ->groupBy('category')
        ->map(fn($items) => $items->map(fn($p) => [
            'id' => $p->id,
            'code' => $p->code,
            'name' => $p->name,
        ]))
        ->toArray(); // make it plain array for clean JSON

    $strands_list = Strand::where('is_active', true)
        ->orderBy('name')
        ->get()
        ->map(fn($s) => [
            'id' => $s->id,
            'code' => $s->code,
            'name' => $s->name,
        ])
        ->toArray();

    return view('user.intake', compact('alumnus', 'user', 'programs_by_cat', 'strands_list'));
}


    /**
     * Build full name from user name parts:
     * "Last, First Middle Suffix"
     */
    private function buildFullNameFromUser($user): string
    {
        $last   = trim((string) ($user->last_name ?? ''));
        $first  = trim((string) ($user->first_name ?? ''));
        $middle = trim((string) ($user->middle_name ?? ''));
        $suffix = trim((string) ($user->suffix ?? ''));

        $firstMiddle = trim($first . ' ' . $middle);

        $base = trim($last);
        if ($firstMiddle !== '') {
            $base = $base !== '' ? ($base . ', ' . $firstMiddle) : $firstMiddle;
        }

        if ($suffix !== '') {
            $base = trim($base . ' ' . $suffix);
        }

        // fallback (if name parts are not yet added to users table)
        if ($base === '') {
            $base = trim((string) ($user->name ?? ''));
        }

        return $base;
    }

    public function save(Request $request)
    {
        $request->validate([
            // ✅ removed full_name requirement
            'email' => ['nullable', 'email', 'max:255'],
            'educations' => ['nullable', 'array'],
            'educations.*.level' => ['required_with:educations', 'string'],
        ]);
        
        //Intake validation for educations
        $educations = $request->input('educations', []);

        foreach ($educations as $idx => $row) {

            $level = $row['level'] ?? null;
            $didGraduate = $row['did_graduate'] ?? null;

            /* ================= Graduate logic ================= */

            if ($didGraduate === '1' || $didGraduate === 1) {
                if (empty($row['year_graduated'])) {
                    return back()->withErrors([
                        "educations.$idx.year_graduated" =>
                            'Year Graduated is required if you selected YES.'
                    ])->withInput();
                }
            }

            if ($didGraduate === '0' || $didGraduate === 0) {
                if (empty($row['last_year_attended'])) {
                    return back()->withErrors([
                        "educations.$idx.last_year_attended" =>
                            'Last School Year Attended is required if you selected NO.'
                    ])->withInput();
                }
            }

            /* ================= Program "Others" ================= */

            if (in_array($level, ['ndmu_college','ndmu_grad_school','ndmu_law'], true)) {
                if (($row['program_id'] ?? null) === '__other__') {
                    if (empty(trim((string)($row['specific_program'] ?? '')))) {
                        return back()->withErrors([
                            "educations.$idx.specific_program" =>
                                'Please specify the program when selecting Others.'
                        ])->withInput();
                    }
                }
            }
        }


        DB::transaction(function () use ($request) {

            $user = Auth::user();

            $alumnus = Alumnus::where('user_id', $user->id)->first();

            // ✅ IMPORTANT: removed 'full_name' from request->only()
            $data = $request->only([
                'nickname','sex','birthdate','age','civil_status',
                'home_address','current_address','contact_number','email','facebook',
                'nationality','religion'
            ]);

            $data['user_id'] = $user->id;

            // ✅ If alumni.full_name is NOT NULL in DB, always provide a value
            $data['full_name'] = $this->buildFullNameFromUser($user);

            if (!$alumnus) {
                $alumnus = Alumnus::create($data);
            } else {
                $alumnus->update($data);
            }

            // Replace child rows
            $alumnus->educations()->delete();
            $alumnus->employments()->delete();
            $alumnus->communityInvolvements()->delete();

            // Educations
            foreach ($request->input('educations', []) as $row) {
                if (empty($row['level'])) continue;

                $alumnus->educations()->create([
                    'level' => $row['level'],
                    'student_number' => $row['student_number'] ?? null,
                    'year_entered' => $row['year_entered'] ?? null,
                    'year_graduated' => $row['year_graduated'] ?? null,
                    'last_year_attended' => $row['last_year_attended'] ?? null,
                    'degree_program' => $row['degree_program'] ?? null,
                    'specific_program' => $row['specific_program'] ?? null,
                    'research_title' => $row['research_title'] ?? null,
                    'thesis_title' => $row['thesis_title'] ?? null,
                    'strand_track' => $row['strand_track'] ?? null,
                    'honors_awards' => $row['honors_awards'] ?? null,
                    'extracurricular_activities' => $row['extracurricular_activities'] ?? null,
                    'clubs_organizations' => $row['clubs_organizations'] ?? null,
                    'institution_name' => $row['institution_name'] ?? null,
                    'institution_address' => $row['institution_address'] ?? null,
                    'course_degree' => $row['course_degree'] ?? null,
                    'year_completed' => $row['year_completed'] ?? null,
                    'scholarship_award' => $row['scholarship_award'] ?? null,
                    'notes' => $row['notes'] ?? null,
                ]);
            }

            // Employments
            foreach ($request->input('employments', []) as $row) {
                $hasAny = collect($row)->filter(fn($v) => $v !== null && $v !== '')->isNotEmpty();
                if (!$hasAny) continue;

                $alumnus->employments()->create([
                    'current_status' => $row['current_status'] ?? null,
                    'occupation_position' => $row['occupation_position'] ?? null,
                    'company_name' => $row['company_name'] ?? null,
                    'org_type' => $row['org_type'] ?? null,
                    'work_address' => $row['work_address'] ?? null,
                    'contact_info' => $row['contact_info'] ?? null,
                    'years_of_service_or_start' => $row['years_of_service_or_start'] ?? null,
                    'licenses_certifications' => $row['licenses_certifications'] ?? null,
                    'achievements_awards' => $row['achievements_awards'] ?? null,
                ]);
            }

            // Community
            foreach ($request->input('community', []) as $row) {
                if (empty($row['organization'])) continue;

                $alumnus->communityInvolvements()->create([
                    'organization' => $row['organization'] ?? null,
                    'role_position' => $row['role_position'] ?? null,
                    'years_involved' => $row['years_involved'] ?? null,
                ]);
            }

            // Engagement (single row)
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

            // Consent (single row)
            $consent = $request->input('consent', []);
            $alumnus->consent()->updateOrCreate(
                ['alumnus_id' => $alumnus->id],
                [
                    'signature_name' => $consent['signature_name'] ?? null,
                    'consented_at' => !empty($consent['signature_name']) ? now() : null,
                    'ip_address' => $request->ip(),
                ]
            );
        });

        return redirect()->route('intake.form')->with('success', 'Intake record saved successfully.');
    }
}
