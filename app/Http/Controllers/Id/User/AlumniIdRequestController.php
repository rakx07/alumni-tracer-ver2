<?php

namespace App\Http\Controllers\Id\User;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use App\Models\AlumniEducation;
use App\Models\AlumniIdRequest;
use App\Models\AlumniIdRequestAttachment;
use App\Models\AlumniIdRequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AlumniIdRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Require a completed intake record.
     */
    private function currentAlumnusOrFail(): Alumnus
    {
        $alumnus = Alumnus::where('user_id', Auth::id())->first();

        // Must complete intake; your alumni table defaults record_status='draft'
        if (!$alumnus || ($alumnus->record_status ?? null) === 'draft') {
            abort(403, 'Please complete your Alumni Intake Form before requesting an Alumni ID.');
        }

        return $alumnus;
    }

    /**
     * Enforce 1 active request at a time.
     */
    private function activeRequestForAlumnus(int $alumnusId): ?AlumniIdRequest
    {
        return AlumniIdRequest::where('alumnus_id', $alumnusId)
            ->where('is_active_request', 1)
            ->latest('id')
            ->first();
    }

    /**
     * Build dynamic education display (graduated only) for SHS/College/Grad/Law.
     * Also computes the "latest eligible" among college/grad/law (graduated only).
     *
     * Returns:
     * - graduated_list: array of rows for display
     * - latest_eligible: null|['level','year_graduated','program']
     * - missing_eligible: bool
     */
    private function buildEducationSummary(int $alumnusId): array
    {
        $levels = [
            'ndmu_shs',
            'ndmu_college',
            'ndmu_grad_school',
            'ndmu_law',
        ];

        $graduated = AlumniEducation::with(['program', 'strand'])
            ->where('alumnus_id', $alumnusId)
            ->whereIn('level', $levels)
            ->where('did_graduate', 1)
            ->orderByDesc('year_graduated')
            ->orderByDesc('id')
            ->get();

        // pick “latest eligible” among college/grad/law (graduated only)
        $eligibleLevels = ['ndmu_college', 'ndmu_grad_school', 'ndmu_law'];

        $latestEligible = $graduated
            ->whereIn('level', $eligibleLevels)
            ->sortByDesc(fn($e) => (int)($e->year_graduated ?? 0))
            ->sortByDesc('id')
            ->first();

        // build display labels (no DB changes, purely view)
        $mapped = $graduated->map(function ($e) {
            $level = $e->level;

            $strandLabel = null;
            if ($level === 'ndmu_shs') {
                if ($e->strand) {
                    $strandLabel = trim(($e->strand->code ? $e->strand->code . ' — ' : '') . $e->strand->name);
                } elseif (!empty($e->strand_track)) {
                    $strandLabel = $e->strand_track;
                }
            }

            $programLabel = null;
            if (in_array($level, ['ndmu_college', 'ndmu_grad_school', 'ndmu_law'], true)) {
                if ($e->program) {
                    $programLabel = trim(($e->program->code ? $e->program->code . ' — ' : '') . $e->program->name);
                } elseif (!empty($e->specific_program)) {
                    $programLabel = $e->specific_program . ' (Others)';
                } elseif (!empty($e->degree_program)) {
                    $programLabel = $e->degree_program;
                }
            }

            return [
                'id'             => $e->id,
                'level'          => $level,
                'year_entered'   => $e->year_entered,
                'year_graduated' => $e->year_graduated,
                'strand'         => $strandLabel,
                'program'        => $programLabel,
            ];
        })->values();

        // missing rules: user must have at least ONE graduated eligible program to request ID
        $missingEligible = $latestEligible ? false : true;

        return [
            'graduated_list' => $mapped,
            'latest_eligible' => $latestEligible ? [
                'level' => $latestEligible->level,
                'year_graduated' => $latestEligible->year_graduated,
                'program' => $latestEligible->program
                    ? trim(($latestEligible->program->code ? $latestEligible->program->code . ' — ' : '') . $latestEligible->program->name)
                    : ($latestEligible->specific_program ?: $latestEligible->degree_program ?: null),
            ] : null,
            'missing_eligible' => $missingEligible,
        ];
    }

    /**
     * User status page (latest request + logs).
     */
    public function status()
    {
        $alumnus = Alumnus::where('user_id', Auth::id())->first();

        $request = null;
        $eduSummary = null;

        if ($alumnus) {
            $request = AlumniIdRequest::with(['attachments', 'logs.actor'])
                ->where('alumnus_id', $alumnus->id)
                ->latest('id')
                ->first();

            // only build summary if intake exists (even if draft, you can still display missing)
            $eduSummary = $this->buildEducationSummary($alumnus->id);
        }

        return view('id.user.request.status', compact('alumnus', 'request', 'eduSummary'));
    }

    /**
     * Create request form page (dynamic education summary shown).
     * If missing eligible (graduated college/grad/law), we still load the page and show warning.
     */
    public function create()
    {
        $alumnus = $this->currentAlumnusOrFail();

        // Block if active request exists
        $active = $this->activeRequestForAlumnus($alumnus->id);
        if ($active) {
            return redirect()->route('id.user.request.status')
                ->with('warning', 'You already have an active Alumni ID request. Please wait until it is Declined or Released.');
        }

        $user = Auth::user();

        // ✅ Education Summary (graduated only)
        $eduSummary = $this->buildEducationSummary($alumnus->id);

        // For display convenience (optional)
        $course = $eduSummary['latest_eligible']['program'] ?? null;

        return view('id.user.request.create', [
            'alumnus'     => $alumnus,
            'user'        => $user,
            'eduSummary'  => $eduSummary,
            'course'      => $course, // keep if your blade still prints $course
        ]);
    }

    /**
     * Store request (ENFORCES: must have graduated eligible program).
     */
    public function store(Request $request)
    {
        $alumnus = $this->currentAlumnusOrFail();

        // Enforce "1 active at a time"
        $active = $this->activeRequestForAlumnus($alumnus->id);
        if ($active) {
            return redirect()->route('id.user.request.status')
                ->with('warning', 'You already have an active Alumni ID request. Please wait until it is Declined or Released.');
        }

        // ✅ Enforce graduated eligible program (college/grad/law)
        $eduSummary = $this->buildEducationSummary($alumnus->id);
        if (($eduSummary['missing_eligible'] ?? true) === true) {
            return back()->withErrors([
                'education' => 'No graduated eligible program found (College / Graduate School / Law). Please update your intake form.'
            ])->withInput();
        }

        $user = Auth::user();

        $validated = $request->validate([
            'school_id' => ['nullable', 'string', 'max:255'],
            'request_type' => ['required', Rule::in(['NEW', 'LOST', 'STOLEN', 'BROKEN'])],

            // Signature required always
            'signature' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'], // 10MB

            // Conditional attachments
            'affidavit_loss' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'broken_proof'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ]);

        // Enforce required attachments based on type
        if (in_array($validated['request_type'], ['LOST', 'STOLEN'], true) && !$request->hasFile('affidavit_loss')) {
            return back()->withErrors(['affidavit_loss' => 'Affidavit of Loss is required for Lost/Stolen ID replacement.'])->withInput();
        }
        if ($validated['request_type'] === 'BROKEN' && !$request->hasFile('broken_proof')) {
            return back()->withErrors(['broken_proof' => 'Proof of broken ID is required.'])->withInput();
        }

        // Determine snapshot values from summary (graduated latest eligible)
        $course = $eduSummary['latest_eligible']['program'] ?? null;
        $gradYear = !empty($eduSummary['latest_eligible']['year_graduated'])
            ? (int) $eduSummary['latest_eligible']['year_graduated']
            : null;

        DB::beginTransaction();
        try {
            // Save signature
            $sigPath = $request->file('signature')->store('alumni-id/signatures', 'public');

            // Create request
            $req = AlumniIdRequest::create([
                'alumnus_id' => $alumnus->id,
                'school_id'  => $validated['school_id'] ?? null,

                // Name from USERS (as per your setup)
                'last_name'   => $user->last_name,
                'first_name'  => $user->first_name,
                'middle_name' => $user->middle_name,

                // Intake-derived
                'course'     => $course,
                'grad_month' => null,
                'grad_year'  => $gradYear,
                'birthdate'  => $alumnus->birthdate,

                'request_type'   => $validated['request_type'],
                'signature_path' => $sigPath,

                'status'            => 'PENDING',
                'is_active_request' => 1,
                'last_acted_by'     => Auth::id(),
            ]);

            AlumniIdRequestLog::create([
                'request_id'    => $req->id,
                'actor_user_id' => Auth::id(),
                'action'        => 'CREATED',
                'remarks'       => 'User submitted Alumni ID request.',
            ]);

            // Attachments (conditional)
            if ($request->hasFile('affidavit_loss')) {
                $path = $request->file('affidavit_loss')->store('alumni-id/attachments', 'public');

                AlumniIdRequestAttachment::create([
                    'request_id'      => $req->id,
                    'attachment_type' => 'AFFIDAVIT_LOSS',
                    'file_path'       => $path,
                    'original_name'   => $request->file('affidavit_loss')->getClientOriginalName(),
                    'uploaded_by'     => Auth::id(),
                ]);

                AlumniIdRequestLog::create([
                    'request_id'    => $req->id,
                    'actor_user_id' => Auth::id(),
                    'action'        => 'UPLOADED_ATTACHMENT',
                    'remarks'       => 'Uploaded Affidavit of Loss.',
                ]);
            }

            if ($request->hasFile('broken_proof')) {
                $path = $request->file('broken_proof')->store('alumni-id/attachments', 'public');

                AlumniIdRequestAttachment::create([
                    'request_id'      => $req->id,
                    'attachment_type' => 'BROKEN_PROOF',
                    'file_path'       => $path,
                    'original_name'   => $request->file('broken_proof')->getClientOriginalName(),
                    'uploaded_by'     => Auth::id(),
                ]);

                AlumniIdRequestLog::create([
                    'request_id'    => $req->id,
                    'actor_user_id' => Auth::id(),
                    'action'        => 'UPLOADED_ATTACHMENT',
                    'remarks'       => 'Uploaded Broken Proof.',
                ]);
            }

            DB::commit();

            return redirect()->route('id.user.request.status')
                ->with('success', 'Alumni ID request submitted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Best effort cleanup if signature saved
            if (!empty($sigPath ?? null)) {
                Storage::disk('public')->delete($sigPath);
            }

            throw $e;
        }
    }
}
