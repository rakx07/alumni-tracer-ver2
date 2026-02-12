<?php

namespace App\Http\Controllers\Id\Officer;

use App\Http\Controllers\Controller;
use App\Models\AlumniIdRequest;
use App\Models\AlumniIdRequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AlumniIdOfficerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function requireOfficerOrItAdmin(): void
    {
        $role = Auth::user()->role ?? 'user';
        abort_unless(in_array($role, ['alumni_officer','it_admin'], true), 403);
    }

    public function index(Request $request)
    {
        $this->requireOfficerOrItAdmin();

        $q = AlumniIdRequest::query()
            // ✅ FIX: eager load alumnus -> educations -> program
            // so the blade fallback can compute course code even when request.course is NULL
            ->with([
                'alumnus.educations.program',
                'lastActor',
            ])
            ->orderByDesc('id');

        // Filters
        if ($request->filled('status')) {
            $q->where('status', $request->get('status'));
        }

        if ($request->filled('type')) {
            $q->where('request_type', $request->get('type'));
        }

        if ($request->filled('search')) {
            $s = trim($request->get('search'));

            $q->where(function($w) use ($s) {
                $w->where('first_name', 'like', "%{$s}%")
                  ->orWhere('middle_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('school_id', 'like', "%{$s}%")
                  ->orWhere('course', 'like', "%{$s}%");
            });
        }

        $requests = $q->paginate(15)->withQueryString();

        return view('id.officer.requests.index', compact('requests'));
    }

    public function show($id)
    {
        $this->requireOfficerOrItAdmin();

        $request = AlumniIdRequest::with([
                'attachments',
                'logs.actor',
                'alumnus.educations.program', // ✅ ADDED (needed for course fallback in show blade)
                'lastActor',
            ])
            ->findOrFail($id);

        return view('id.officer.requests.show', compact('request'));
    }

    public function updateStatus(Request $httpRequest, $id)
    {
        $this->requireOfficerOrItAdmin();

        $validated = $httpRequest->validate([
            'status' => ['required', Rule::in([
                'APPROVED',
                'PROCESSING',
                'DECLINED',
                'READY_FOR_PICKUP',
                'RELEASED',
            ])],
            'remarks' => ['nullable','string','max:5000'],
        ]);

        $newStatus = $validated['status'];
        $remarks   = $validated['remarks'] ?? null;

        // Decline must have remarks
        if ($newStatus === 'DECLINED' && !$remarks) {
            return back()->withErrors(['remarks' => 'Remarks is required when declining a request.']);
        }

        $actionMap = [
            'APPROVED'         => 'APPROVED',
            'PROCESSING'       => 'SET_PROCESSING',
            'DECLINED'         => 'DECLINED',
            'READY_FOR_PICKUP' => 'SET_READY_FOR_PICKUP',
            'RELEASED'         => 'RELEASED',
        ];

        // Prevent moving backwards (except DECLINED which is terminal)
        $rank = [
            'PENDING'          => 0,
            'APPROVED'         => 1,
            'PROCESSING'       => 2,
            'READY_FOR_PICKUP' => 3,
            'RELEASED'         => 4,
            'DECLINED'         => 99, // terminal
        ];

        return DB::transaction(function () use ($id, $newStatus, $remarks, $actionMap, $rank) {

            // lockForUpdate MUST be inside transaction
            $req = AlumniIdRequest::where('id', $id)->lockForUpdate()->firstOrFail();

            $currentStatus = $req->status;

            if ($newStatus !== 'DECLINED') {
                if (($rank[$newStatus] ?? -1) < ($rank[$currentStatus] ?? -1)) {
                    return back()->withErrors(['status' => 'You cannot move the status backwards.']);
                }
            }

            $req->status        = $newStatus;
            $req->remarks       = $remarks;
            $req->last_acted_by = Auth::id();

            // Set timestamps only when entering that state
            if ($newStatus === 'APPROVED' && !$req->approved_at) {
                $req->approved_at = now();
            } elseif ($newStatus === 'PROCESSING' && !$req->processing_at) {
                $req->processing_at = now();
            } elseif ($newStatus === 'READY_FOR_PICKUP' && !$req->ready_at) {
                $req->ready_at = now();
            } elseif ($newStatus === 'RELEASED' && !$req->released_at) {
                $req->released_at = now();
            } elseif ($newStatus === 'DECLINED' && !$req->declined_at) {
                $req->declined_at = now();
            }

            // End active request when DECLINED or RELEASED
            if (in_array($newStatus, ['DECLINED','RELEASED'], true)) {
                $req->is_active_request = null; // correct for unique constraint design
            }

            $req->save();

            AlumniIdRequestLog::create([
                'request_id'    => $req->id,
                'actor_user_id' => Auth::id(),
                'action'        => $actionMap[$newStatus] ?? 'UPDATED_DETAILS',
                'remarks'       => $remarks,
            ]);

            return redirect()
                ->route('id.officer.requests.show', $req->id)
                ->with('success', "Request updated to {$newStatus}.");
        });
    }
}
