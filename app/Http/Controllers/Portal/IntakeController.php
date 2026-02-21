<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Program;
use App\Models\Strand;
use App\Models\Religion;
use App\Models\Nationality;

class IntakeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $role = Auth::user()?->role ?? 'user';
            $allowedRoles = ['user', 'admin', 'it_admin', 'alumni_officer'];
            abort_unless(in_array($role, $allowedRoles, true), 403);
            return $next($request);
        });
    }

    public function form()
    {
        $alumnus = Alumnus::with([
            'educations',
            'employments',
            'communityInvolvements',
            'engagementPreference',
            'consent'
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
            ->toArray();

        $strands_list = Strand::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'code' => $s->code,
                'name' => $s->name,
            ])
            ->toArray();

        $religions_list = Religion::where('is_active', true)
            ->orderBy('name')
            ->pluck('name')
            ->values()
            ->toArray();

        $nationalities_list = Nationality::where('is_active', true)
            ->orderByRaw("CASE WHEN UPPER(name) = 'FILIPINO' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->pluck('name')
            ->values()
            ->toArray();

        return view('user.intake', compact(
            'alumnus',
            'user',
            'programs_by_cat',
            'strands_list',
            'religions_list',
            'nationalities_list'
        ));
    }

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

        if ($base === '') {
            $base = trim((string) ($user->name ?? ''));
        }

        return $base;
    }

    public function save(Request $request)
    {
        $request->validate([
            'first_name'  => ['required','string','max:100'],
            'middle_name' => ['nullable','string','max:100'],
            'last_name'   => ['required','string','max:100'],
            'suffix'      => ['nullable','string','max:30'],
            'email'       => ['nullable','email','max:255'],
            'educations'  => ['nullable','array'],
            'educations.*.level' => ['required_with:educations','string'],
        ]);

        $educations = $request->input('educations', []);

        foreach ($educations as $idx => $row) {

            $level = $row['level'] ?? null;
            $didGraduate = $row['did_graduate'] ?? null;

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

            // ✅ Server-side uppercase enforcement
            $firstName  = strtoupper(trim((string) $request->input('first_name')));
            $middleName = strtoupper(trim((string) $request->input('middle_name')));
            $lastName   = strtoupper(trim((string) $request->input('last_name')));
            $suffix     = strtoupper(trim((string) $request->input('suffix')));

            $nationality = strtoupper(trim((string) $request->input('nationality')));
            $religion    = strtoupper(trim((string) $request->input('religion')));

            $user->forceFill([
                'first_name'  => $firstName,
                'middle_name' => $middleName ?: null,
                'last_name'   => $lastName,
                'suffix'      => $suffix ?: null,
                'name'        => trim(collect([$firstName, $middleName, $lastName, $suffix])->filter()->implode(' '))
            ]);

            $user->save();

            $alumnus = Alumnus::where('user_id', $user->id)->first();

            $data = $request->only([
                'nickname','sex','birthdate','age','civil_status',
                'home_address','current_address','contact_number','email','facebook'
            ]);

            // Enforce uppercase for relevant fields
            $data['nickname']        = isset($data['nickname']) ? strtoupper(trim((string)$data['nickname'])) : null;
            $data['home_address']    = isset($data['home_address']) ? strtoupper(trim((string)$data['home_address'])) : null;
            $data['current_address'] = isset($data['current_address']) ? strtoupper(trim((string)$data['current_address'])) : null;
            $data['nationality']     = $nationality ?: null;
            $data['religion']        = $religion ?: null;

            $data['user_id'] = $user->id;
            $data['full_name'] = $this->buildFullNameFromUser($user);
            $data['encoding_mode'] = 'self_service';
            $data['encoded_by'] = $user->id;
            $data['validated_by'] = $user->id;
            $data['validated_at'] = now();
            $data['record_status'] = 'validated';

            if (!$alumnus) {
                $alumnus = Alumnus::create($data);
            } else {
                $alumnus->update($data);
            }

            // Existing logic preserved
            $alumnus->educations()->delete();
            $alumnus->employments()->delete();
            $alumnus->communityInvolvements()->delete();

            foreach ($request->input('educations', []) as $row) {
                if (empty($row['level'])) continue;

                $alumnus->educations()->create([
                    'level' => $row['level'],
                    'did_graduate' => $row['did_graduate'] ?? null,
                    'program_id' => (($row['program_id'] ?? null) === '__other__') ? null : ($row['program_id'] ?? null),
                    'strand_id' => $row['strand_id'] ?? null,
                    'student_number' => $row['student_number'] ?? null,
                    'year_entered' => $row['year_entered'] ?? null,
                    'year_graduated' => $row['year_graduated'] ?? null,
                    'last_year_attended' => $row['last_year_attended'] ?? null,
                    'degree_program' => $row['degree_program'] ?? null,
                    'specific_program' => trim((string)($row['specific_program'] ?? '')) ?: null,
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
        });

        $user = Auth::user();
        $alumnus = Alumnus::where('user_id', $user->id)->first();

        if ($alumnus && $this->intakeIsComplete($alumnus)) {
            if (empty($user->intake_completed_at)) {
                $user->forceFill(['intake_completed_at' => now()])->save();
            }

            return redirect()->route('intake.form')
                ->with('success', 'Intake completed successfully! You may now proceed to the Dashboard.');
        }

        $user->forceFill(['intake_completed_at' => null])->save();

        return redirect()->route('intake.form')
            ->with('warning', 'Saved, but intake is not yet complete. Please fill all required fields.');
    }

    private function intakeIsComplete(Alumnus $alumnus): bool
    {
        if (empty(trim((string) $alumnus->full_name))) return false;
        if (empty((string) $alumnus->sex)) return false;
        if (empty((string) $alumnus->birthdate)) return false;
        if (empty(trim((string) $alumnus->nationality))) return false;
        if (empty(trim((string) $alumnus->home_address))) return false;
        if (empty(trim((string) $alumnus->current_address))) return false;

        $hasNdmuEducation = $alumnus->educations()
            ->where('level', '!=', 'post_ndmu')
            ->exists();

        if (!$hasNdmuEducation) return false;

        $consent = $alumnus->consent()->first();
        if (!$consent) return false;
        if (empty(trim((string) $consent->signature_name))) return false;

        return true;
    }
}
