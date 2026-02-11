<?php

namespace App\Http\Controllers\Id\User;

use App\Http\Controllers\Controller;
use App\Models\Alumnus; // your model name
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

    private function currentAlumnusOrFail(): Alumnus
    {
        $alumnus = Alumnus::where('user_id', Auth::id())->first();

        // Must complete intake
        // You have record_status = 'draft' by default in alumni table
        if (!$alumnus || ($alumnus->record_status ?? null) === 'draft') {
            abort(403, 'Please complete your Alumni Intake Form before requesting an Alumni ID.');
        }

        return $alumnus;
    }

    private function pickEligibleEducationOrFail(int $alumnusId): AlumniEducation
    {
        $eligibleLevels = ['ndmu_college', 'ndmu_grad_school', 'ndmu_law'];

        // Pick most recent graduated among eligible levels.
        // Priority:
        // 1) did_graduate = 1
        // 2) higher year_graduated
        // 3) latest id
        $edu = AlumniEducation::where('alumnus_id', $alumnusId)
            ->whereIn('level', $eligibleLevels)
            ->orderByDesc(DB::raw('COALESCE(did_graduate, 0)'))
            ->orderByDesc('year_graduated')
            ->orderByDesc('id')
            ->first();

        if (!$edu) {
            abort(403, 'You do not have an eligible education record (College / Graduate School / Law) in your intake form.');
        }

        // If you strictly require graduated, uncomment below:
        // if ((int)($edu->did_graduate ?? 0) !== 1) {
        //     abort(403, 'Your eligible education record must be marked as graduated to request an Alumni ID.');
        // }

        return $edu;
    }

    private function activeRequestForAlumnus(int $alumnusId): ?AlumniIdRequest
    {
        return AlumniIdRequest::where('alumnus_id', $alumnusId)
            ->where('is_active_request', 1)
            ->latest('id')
            ->first();
    }

    public function status()
    {
        $alumnus = Alumnus::where('user_id', Auth::id())->first();

        $request = null;
        if ($alumnus) {
            $request = AlumniIdRequest::with(['attachments','logs.actor'])
                ->where('alumnus_id', $alumnus->id)
                ->latest('id')
                ->first();
        }

        return view('id.user.request.status', compact('alumnus', 'request'));
    }

    public function create()
    {
        $alumnus = $this->currentAlumnusOrFail();

        // Block if active request exists
        $active = $this->activeRequestForAlumnus($alumnus->id);
        if ($active) {
            return redirect()->route('id.user.request.status')
                ->with('warning', 'You already have an active Alumni ID request. Please wait until it is Declined or Released.');
        }

        $edu = $this->pickEligibleEducationOrFail($alumnus->id);

        // From users table (you have first/middle/last there)
        $user = Auth::user();

        // Best course text
        $course = $edu->specific_program ?: $edu->degree_program ?: null;

        return view('id.user.request.create', [
            'alumnus' => $alumnus,
            'edu' => $edu,
            'user' => $user,
            'course' => $course,
        ]);
    }

    public function store(Request $request)
    {
        $alumnus = $this->currentAlumnusOrFail();

        // Enforce "1 active at a time"
        $active = $this->activeRequestForAlumnus($alumnus->id);
        if ($active) {
            return redirect()->route('id.user.request.status')
                ->with('warning', 'You already have an active Alumni ID request. Please wait until it is Declined or Released.');
        }

        $edu = $this->pickEligibleEducationOrFail($alumnus->id);
        $user = Auth::user();

        $validated = $request->validate([
            'school_id' => ['nullable','string','max:255'],
            'request_type' => ['required', Rule::in(['NEW','LOST','STOLEN','BROKEN'])],

            // Signature required always
            'signature' => ['required','file','mimes:jpg,jpeg,png,pdf','max:10240'], // 10MB

            // Conditional attachments
            'affidavit_loss' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:10240'],
            'broken_proof'   => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:10240'],
        ]);

        // Enforce required attachments based on type
        if (in_array($validated['request_type'], ['LOST','STOLEN'], true) && !$request->hasFile('affidavit_loss')) {
            return back()->withErrors(['affidavit_loss' => 'Affidavit of Loss is required for Lost/Stolen ID replacement.'])->withInput();
        }
        if ($validated['request_type'] === 'BROKEN' && !$request->hasFile('broken_proof')) {
            return back()->withErrors(['broken_proof' => 'Proof of broken ID is required.'])->withInput();
        }

        // Determine snapshot values
        $course = $edu->specific_program ?: $edu->degree_program ?: null;

        // Graduation year exists; month not in your schema, keep null
        $gradYear = $edu->year_graduated ? (int)$edu->year_graduated : null;

        DB::beginTransaction();
        try {
            // Save signature
            $sigPath = $request->file('signature')->store('alumni-id/signatures', 'public');

            // Create request
            $req = AlumniIdRequest::create([
                'alumnus_id' => $alumnus->id,
                'school_id' => $validated['school_id'] ?? null,

                // Name from USERS (as per your setup)
                'last_name' => $user->last_name,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,

                // Intake-derived
                'course' => $course,
                'grad_month' => null,
                'grad_year' => $gradYear,
                'birthdate' => $alumnus->birthdate,

                'request_type' => $validated['request_type'],
                'signature_path' => $sigPath,

                'status' => 'PENDING',
                'is_active_request' => 1,
                'last_acted_by' => Auth::id(),
            ]);

            AlumniIdRequestLog::create([
                'request_id' => $req->id,
                'actor_user_id' => Auth::id(),
                'action' => 'CREATED',
                'remarks' => 'User submitted Alumni ID request.',
            ]);

            // Attachments (conditional)
            if ($request->hasFile('affidavit_loss')) {
                $path = $request->file('affidavit_loss')->store('alumni-id/attachments', 'public');

                AlumniIdRequestAttachment::create([
                    'request_id' => $req->id,
                    'attachment_type' => 'AFFIDAVIT_LOSS',
                    'file_path' => $path,
                    'original_name' => $request->file('affidavit_loss')->getClientOriginalName(),
                    'uploaded_by' => Auth::id(),
                ]);

                AlumniIdRequestLog::create([
                    'request_id' => $req->id,
                    'actor_user_id' => Auth::id(),
                    'action' => 'UPLOADED_ATTACHMENT',
                    'remarks' => 'Uploaded Affidavit of Loss.',
                ]);
            }

            if ($request->hasFile('broken_proof')) {
                $path = $request->file('broken_proof')->store('alumni-id/attachments', 'public');

                AlumniIdRequestAttachment::create([
                    'request_id' => $req->id,
                    'attachment_type' => 'BROKEN_PROOF',
                    'file_path' => $path,
                    'original_name' => $request->file('broken_proof')->getClientOriginalName(),
                    'uploaded_by' => Auth::id(),
                ]);

                AlumniIdRequestLog::create([
                    'request_id' => $req->id,
                    'actor_user_id' => Auth::id(),
                    'action' => 'UPLOADED_ATTACHMENT',
                    'remarks' => 'Uploaded Broken Proof.',
                ]);
            }

            DB::commit();

            return redirect()->route('id.user.request.status')->with('success', 'Alumni ID request submitted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Best effort cleanup if signature saved
            if (!empty($sigPath)) {
                Storage::disk('public')->delete($sigPath);
            }

            throw $e;
        }
    }
}
