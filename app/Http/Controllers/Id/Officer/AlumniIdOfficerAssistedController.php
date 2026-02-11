<?php

namespace App\Http\Controllers\Id\Officer;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use App\Models\AlumniEducation;
use App\Models\AlumniIdRequest;
use App\Models\AlumniIdRequestAttachment;
use App\Models\AlumniIdRequestLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AlumniIdOfficerAssistedController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:alumni_officer,it_admin']);
    }

    private function pickEligibleEducationOrFail(int $alumnusId): AlumniEducation
    {
        $eligibleLevels = ['ndmu_college', 'ndmu_grad_school', 'ndmu_law'];

        $edu = AlumniEducation::where('alumnus_id', $alumnusId)
            ->whereIn('level', $eligibleLevels)
            ->orderByDesc(DB::raw('COALESCE(did_graduate, 0)'))
            ->orderByDesc('year_graduated')
            ->orderByDesc('id')
            ->first();

        abort_unless($edu, 422, 'No eligible education found (College/Graduate School/Law).');
        return $edu;
    }

    private function activeRequestForAlumnus(int $alumnusId): ?AlumniIdRequest
    {
        return AlumniIdRequest::where('alumnus_id', $alumnusId)
            ->where('is_active_request', 1)
            ->latest('id')
            ->first();
    }

    public function create(Request $request)
    {
        // Search alumni records for selection
        $q = trim((string) $request->get('q', ''));

        $alumni = collect();

        if ($q !== '') {
            $alumni = Alumnus::query()
                ->leftJoin('users as u', 'u.id', '=', 'alumni.user_id')
                ->select([
                    'alumni.*',
                    'u.first_name as u_first_name',
                    'u.middle_name as u_middle_name',
                    'u.last_name as u_last_name',
                    'u.email as u_email',
                ])
                ->where(function($w) use ($q) {
                    $w->where('alumni.full_name', 'like', "%{$q}%")
                      ->orWhere('u.email', 'like', "%{$q}%")
                      ->orWhere('u.first_name', 'like', "%{$q}%")
                      ->orWhere('u.middle_name', 'like', "%{$q}%")
                      ->orWhere('u.last_name', 'like', "%{$q}%");
                })
                ->orderByDesc('alumni.id')
                ->limit(25)
                ->get();
        }

        return view('id.officer.requests.assisted_create', [
            'q' => $q,
            'alumni' => $alumni,
            'selected_id' => $request->get('alumnus_id'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alumnus_id' => ['required','integer','exists:alumni,id'],
            'school_id' => ['nullable','string','max:255'],
            'request_type' => ['required', Rule::in(['NEW','LOST','STOLEN','BROKEN'])],

            'signature' => ['required','file','mimes:jpg,jpeg,png,pdf','max:10240'],

            'affidavit_loss' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:10240'],
            'broken_proof'   => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:10240'],
        ]);

        // Conditional required
        if (in_array($validated['request_type'], ['LOST','STOLEN'], true) && !$request->hasFile('affidavit_loss')) {
            return back()->withErrors(['affidavit_loss' => 'Affidavit of Loss is required for Lost/Stolen ID replacement.'])->withInput();
        }
        if ($validated['request_type'] === 'BROKEN' && !$request->hasFile('broken_proof')) {
            return back()->withErrors(['broken_proof' => 'Proof of broken ID is required.'])->withInput();
        }

        $alumnus = Alumnus::findOrFail($validated['alumnus_id']);

        // Must have completed intake (record_status not draft)
        if (($alumnus->record_status ?? null) === 'draft') {
            return back()->withErrors(['alumnus_id' => 'Selected alumnus has a draft intake record. Please complete/validate intake first.'])->withInput();
        }

        // Enforce one active request
        if ($this->activeRequestForAlumnus($alumnus->id)) {
            return back()->withErrors(['alumnus_id' => 'This alumnus already has an ACTIVE Alumni ID request.'])->withInput();
        }

        $edu = $this->pickEligibleEducationOrFail($alumnus->id);

        // Name snapshot from USERS (your setup)
        $u = null;
        if ($alumnus->user_id) {
            $u = User::find($alumnus->user_id);
        }
        if (!$u) {
            return back()->withErrors(['alumnus_id' => 'Selected alumnus is not linked to a user account.'])->withInput();
        }

        $course = $edu->specific_program ?: $edu->degree_program ?: null;
        $gradYear = $edu->year_graduated ? (int)$edu->year_graduated : null;

        DB::beginTransaction();
        try {
            $sigPath = $request->file('signature')->store('alumni-id/signatures', 'public');

            $req = AlumniIdRequest::create([
                'alumnus_id' => $alumnus->id,
                'school_id' => $validated['school_id'] ?? null,

                'last_name' => $u->last_name,
                'first_name' => $u->first_name,
                'middle_name' => $u->middle_name,

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
                'remarks' => 'Created by officer on behalf of alumnus (assisted).',
            ]);

            // attachments
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
                    'remarks' => 'Uploaded Affidavit of Loss (assisted).',
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
                    'remarks' => 'Uploaded Broken Proof (assisted).',
                ]);
            }

            DB::commit();

            return redirect()->route('id.officer.requests.show', $req->id)
                ->with('success', 'Assisted Alumni ID request created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            if (!empty($sigPath)) {
                Storage::disk('public')->delete($sigPath);
            }
            throw $e;
        }
    }
}
