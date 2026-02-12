<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use App\Models\AlumniAudit;
use App\Models\AlumniEducation;
use App\Models\AlumniEmployment;
use App\Models\AlumniCommunityInvolvement;
use App\Models\AlumniEngagementPreference;
use App\Models\AlumniConsent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AlumniEncodingController extends Controller
{
    /* ============================================================
     | Helpers
     ============================================================ */

    private function isItAdmin(): bool
    {
        return in_array(Auth::user()?->role, ['it_admin', 'admin'], true);
    }

    private function logAudit(Alumnus $alumnus, string $action, $old, $new): void
    {
        AlumniAudit::create([
            'alumnus_id' => $alumnus->id,
            'user_id'    => Auth::id(),
            'action'     => $action,
            'old_values' => $old,
            'new_values' => $new,
        ]);
    }

    private function buildFullName($fn, $mn, $ln): string
    {
        return trim($fn . ' ' . ($mn ? $mn . ' ' : '') . $ln);
    }

    /* ============================================================
     | Index / Create
     ============================================================ */

    public function index(Request $request)
    {
        $q = Alumnus::with('user')
            ->where('encoding_mode', 'assisted')
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $q->where('record_status', $request->status);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(fn ($w) =>
                $w->where('full_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
            );
        }

        return view('portal.alumni_encoding.index', [
            'alumni' => $q->paginate(15)->withQueryString(),
        ]);
    }

    public function create(Request $request)
    {
        $matches = collect();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $matches = Alumnus::where('full_name', 'like', "%{$s}%")
                ->limit(10)
                ->latest()
                ->get();
        }

        return view('portal.alumni_encoding.create', [
            'matches' => $matches,
            'search'  => $request->search,
        ]);
    }

    /* ============================================================
     | Store (Mode A / Mode B)
     ============================================================ */

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'  => ['required','string','max:255'],
            'middle_name' => ['nullable','string','max:255'],
            'last_name'   => ['required','string','max:255'],
            'email'       => ['nullable','email','max:255'],
            'create_user' => ['required','in:0,1'],
            'user_email'  => ['nullable','email','max:255'],
        ]);

        return DB::transaction(function () use ($data) {

            $fullName = $this->buildFullName(
                $data['first_name'],
                $data['middle_name'],
                $data['last_name']
            );

            $user = null;
            $tempPassword = null;

            /* ---------- MODE A ---------- */
            if ($data['create_user'] === '1') {

                if (!$data['user_email']) {
                    return back()->withInput()->withErrors([
                        'user_email' => 'User email is required for Mode A.',
                    ]);
                }

                if (User::where('email', $data['user_email'])->exists()) {
                    return back()->withInput()->withErrors([
                        'user_email' => 'User email already exists.',
                    ]);
                }

                $tempPassword = Str::random(10);

                $user = User::create([
                    'first_name'           => $data['first_name'],
                    'middle_name'          => $data['middle_name'],
                    'last_name'            => $data['last_name'],
                    'name'                 => $fullName,
                    'email'                => $data['user_email'],
                    'password'             => Hash::make($tempPassword),
                    'must_change_password' => 1,
                    'is_active'            => 1,
                    'role'                 => 'user',
                ]);
            }

            $alumnus = Alumnus::create([
                'user_id'       => $user?->id,
                'full_name'     => $fullName,
                'email'         => $data['email'],
                'encoding_mode' => 'assisted',
                'encoded_by'    => Auth::id(),
                'record_status' => 'draft',
            ]);

            $this->logAudit($alumnus, 'create', null, $alumnus->toArray());

            return redirect()
                ->route('portal.alumni_encoding.edit', $alumnus)
                ->with('success', 'Assisted alumni record created.')
                ->with('temp_password', $tempPassword);
        });
    }

    /* ============================================================
     | Edit / Update
     ============================================================ */

    public function edit(Alumnus $alumnus)
{
    // Officers can only edit assisted
    if (!$this->isItAdmin()) {
        abort_unless($alumnus->encoding_mode === 'assisted', 404);
    }

    $alumnus->load([
        'user',
        'educations',
        'employments',
        'communityInvolvements',
        'engagementPreference',
        'consent',
    ]);

        return view('portal.alumni_encoding.edit', compact('alumnus'));
    }

    public function update(Request $request, Alumnus $alumnus)
    {
        // Officers can only update assisted
        if (!$this->isItAdmin()) {
            abort_unless($alumnus->encoding_mode === 'assisted', 404);
        }

        $old = $alumnus->load([
            'educations','employments','communityInvolvements',
            'engagementPreference','consent'
        ])->toArray();

        $request->validate([
            'full_name' => ['required','string','max:255'],
            'email'     => ['nullable','email'],
        ]);

        return DB::transaction(function () use ($request, $alumnus, $old) {

            $alumnus->update($request->only([
                'full_name','nickname','sex','birthdate','age','civil_status',
                'home_address','current_address','contact_number',
                'email','facebook','nationality','religion',
            ]));

           AlumniEducation::where('alumnus_id', $alumnus->id)->delete();

            foreach ($request->input('educations', []) as $row) {
                if (empty($row['level'])) continue;

                // normalize Others
                $programId = $row['program_id'] ?? null;
                $specificProgram = trim((string)($row['specific_program'] ?? '')) ?: null;

                if ($programId === '__other__') {
                    $programId = null;
                } else {
                    // if a real program is selected, you can keep specific_program (or null it)
                    // choose one behavior; here we keep it as-is if user typed something
                }

                // normalize graduate fields
                $didGraduate = $row['did_graduate'] ?? null;
                $yearGraduated = $row['year_graduated'] ?? null;
                $lastYearAttended = $row['last_year_attended'] ?? null;

                if ($didGraduate === '1' || $didGraduate === 1) $lastYearAttended = null;
                if ($didGraduate === '0' || $didGraduate === 0) $yearGraduated = null;

                AlumniEducation::create([
                    'alumnus_id' => $alumnus->id,
                    'level'      => $row['level'],

                    'did_graduate' => $didGraduate,
                    'program_id'   => $programId,
                    'specific_program' => $specificProgram,

                    'strand_id'    => $row['strand_id'] ?? null,
                    'strand_track' => $row['strand_track'] ?? null,

                    'student_number'     => $row['student_number'] ?? null,
                    'year_entered'       => $row['year_entered'] ?? null,
                    'year_graduated'     => $yearGraduated,
                    'last_year_attended' => $lastYearAttended,

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



            AlumniEmployment::where('alumnus_id', $alumnus->id)->delete();
            foreach ($request->input('employments', []) as $row) {
                if (array_filter($row)) {
                    AlumniEmployment::create($row + ['alumnus_id' => $alumnus->id]);
                }
            }

            AlumniCommunityInvolvement::where('alumnus_id', $alumnus->id)->delete();
            foreach ($request->input('community', []) as $row) {
                if (array_filter($row)) {
                    AlumniCommunityInvolvement::create($row + ['alumnus_id' => $alumnus->id]);
                }
            }

            AlumniEngagementPreference::updateOrCreate(
                ['alumnus_id' => $alumnus->id],
                (array)$request->input('engagement', [])
            );

            AlumniConsent::updateOrCreate(
                ['alumnus_id' => $alumnus->id],
                (array)$request->input('consent', [])
            );

            $this->logAudit($alumnus, 'update', $old, $alumnus->fresh()->toArray());

            return back()->with('success', 'Saved (changes logged).');
        });
    }


    /* ============================================================
     | Link / Update User
     ============================================================ */

    public function linkUser(Request $request, Alumnus $alumnus)
    {
        $request->validate([
            'first_name' => ['required','string'],
            'last_name'  => ['required','string'],
            'link_email' => ['required','email'],
        ]);

        $user = User::where('email', $request->link_email)->first();
        $tempPassword = null;

        if (!$user) {
            $tempPassword = Str::random(10);
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name'=> $request->middle_name,
                'last_name'  => $request->last_name,
                'name'       => $this->buildFullName(
                    $request->first_name,
                    $request->middle_name,
                    $request->last_name
                ),
                'email'      => $request->link_email,
                'password'   => Hash::make($tempPassword),
                'must_change_password' => 1,
                'is_active'  => 1,
                'role'       => 'user',
            ]);
        }

        $old = $alumnus->only('user_id');
        $alumnus->update(['user_id' => $user->id]);

        $this->logAudit($alumnus, 'link_user', $old, ['user_id' => $user->id]);

        return back()
            ->with('success', 'User linked successfully.')
            ->with('temp_password', $tempPassword);
    }

    public function updateUserBasic(Request $request, Alumnus $alumnus)
    {
        abort_unless($this->isItAdmin(), 403);

        $u = User::findOrFail($alumnus->user_id);

        $data = $request->validate([
            'first_name' => ['nullable','string'],
            'middle_name'=> ['nullable','string'],
            'last_name'  => ['nullable','string'],
            'name'       => ['required','string'],
            'email'      => ['required','email'],
            'role'       => ['required'],
            'is_active'  => ['required','in:0,1'],
        ]);

        $old = $u->only(array_keys($data));
        $u->update($data);

        $this->logAudit($alumnus, 'update_user', $old, $u->only(array_keys($data)));

        return back()->with('success', 'User updated (logged).');
    }


    public function validateRecord(Alumnus $alumnus)
    {
        // Officers can only validate assisted; IT Admin can validate any
        if (!$this->isItAdmin()) {
            abort_unless($alumnus->encoding_mode === 'assisted', 404);
        }

        $old = $alumnus->only(['record_status','validated_by','validated_at']);

        $alumnus->update([
            'record_status' => 'validated',
            'validated_by'  => Auth::id(),
            'validated_at'  => now(),
        ]);

        $this->logAudit($alumnus, 'validate', $old, $alumnus->only(['record_status','validated_by','validated_at']));

        return back()->with('success', 'Record validated successfully.');
    }

}
