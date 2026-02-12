<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IntakeController extends Controller
{
    public function form()
    {
        $alumnus = Alumnus::with([
                'educations',
                'employments',
                'communityInvolvements',
                'engagementPreference',
                'consent',
            ])
            ->where('user_id', Auth::id())
            ->first();

        $user = Auth::user();

        return view('user.intake', compact('alumnus', 'user'));
    }

    /**
     * Build a Full Name string from user name parts.
     * Format: Last, First Middle Suffix
     * Example: "Dela Cruz, Juan P. Jr."
     */
    private function buildFullNameFromUser($user): string
    {
        $last   = trim((string) ($user->last_name ?? ''));
        $first  = trim((string) ($user->first_name ?? ''));
        $middle = trim((string) ($user->middle_name ?? ''));
        $suffix = trim((string) ($user->suffix ?? ''));

        // Build "First Middle" part
        $firstMiddle = trim($first . ' ' . $middle);

        // Build "Last, First Middle"
        $base = trim($last);
        if ($firstMiddle !== '') {
            $base = $base !== '' ? ($base . ', ' . $firstMiddle) : $firstMiddle;
        }

        // Append suffix
        if ($suffix !== '') {
            $base = trim($base . ' ' . $suffix);
        }

        // Fallback if name parts are empty
        if ($base === '') {
            // If your users table still has `name`, use it as final fallback
            $base = trim((string) ($user->name ?? ''));
        }

        return $base;
    }

    public function save(Request $request)
    {
        $request->validate([
            // Name fields are not required here because they're coming from the User table.
            // Keep them only if you allow editing name parts in the intake form.
            'last_name'   => ['nullable', 'string', 'max:255'],
            'first_name'  => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'suffix'      => ['nullable', 'string', 'max:50'],

            'nickname' => ['nullable','string','max:255'],
            'sex' => ['nullable','string','max:20'],
            'birthdate' => ['nullable','date'],
            'age' => ['nullable','integer','min:0','max:150'],
            'civil_status' => ['nullable','string','max:50'],
            'home_address' => ['nullable','string'],
            'current_address' => ['nullable','string'],
            'contact_number' => ['nullable','string','max:50'],
            'email' => ['nullable','email','max:255'],
            'facebook' => ['nullable','string','max:255'],
            'nationality' => ['nullable','string','max:255'],
            'religion' => ['nullable','string','max:255'],

            'educations' => ['nullable','array'],
            'educations.*.level' => ['required_with:educations','string'],

            'employments' => ['nullable','array'],
            'community' => ['nullable','array'],

            'engagement' => ['nullable','array'],
            'consent' => ['nullable','array'],
            'consent.signature_name' => ['nullable','string','max:255'],
        ]);

        DB::transaction(function () use ($request) {
            $user = Auth::user();

            $alumnus = Alumnus::where('user_id', $user->id)->first();

            // ✅ IMPORTANT: do NOT read full_name from request anymore
            // Instead, auto-generate it from user table.
            $data = $request->only([
                'nickname','sex','birthdate','age','civil_status',
                'home_address','current_address','contact_number','email','facebook',
                'nationality','religion'
            ]);

            $data['user_id'] = $user->id;

            // ✅ If alumni table still requires full_name (NOT NULL), set it automatically.
            $data['full_name'] = $this->buildFullNameFromUser($user);

            // (Optional) also store name parts in alumni table IF you have these columns
            // If you don't have these columns, remove these lines.
            if (Schema::hasColumn('alumni', 'last_name'))   $data['last_name']   = $user->last_name;
            if (Schema::hasColumn('alumni', 'first_name'))  $data['first_name']  = $user->first_name;
            if (Schema::hasColumn('alumni', 'middle_name')) $data['middle_name'] = $user->middle_name;
            if (Schema::hasColumn('alumni', 'suffix'))      $data['suffix']      = $user->suffix;

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
                $hasAny = collect($row)->filter(fn ($v) => $v !== null && $v !== '')->isNotEmpty();
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
