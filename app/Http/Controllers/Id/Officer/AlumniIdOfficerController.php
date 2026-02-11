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
            ->with(['alumnus','lastActor'])
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
                $w->where('first_name','like',"%{$s}%")
                  ->orWhere('middle_name','like',"%{$s}%")
                  ->orWhere('last_name','like',"%{$s}%")
                  ->orWhere('school_id','like',"%{$s}%")
                  ->orWhere('course','like',"%{$s}%");
            });
        }

        $requests = $q->paginate(15)->withQueryString();

        return view('id.officer.requests.index', compact('requests'));
    }

    public function show($id)
    {
        $this->requireOfficerOrItAdmin();

        $request = AlumniIdRequest::with(['attachments','logs.actor','alumnus','lastActor'])
            ->findOrFail($id);

        return view('id.officer.requests.show', compact('request'));
    }

    public function updateStatus(Request $httpRequest, $id)
    {
        $this->requireOfficerOrItAdmin();

        $req = AlumniIdRequest::lockForUpdate()->findOrFail($id);

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

        // Map status -> log action + timestamp field
        $status = $validated['status'];
        $remarks = $validated['remarks'] ?? null;

        $actionMap = [
            'APPROVED' => 'APPROVED',
            'PROCESSING' => 'SET_PROCESSING',
            'DECLINED' => 'DECLINED',
            'READY_FOR_PICKUP' => 'SET_READY_FOR_PICKUP',
            'RELEASED' => 'RELEASED',
        ];

        // Decline must have remarks
        if ($status === 'DECLINED' && !$remarks) {
            return back()->withErrors(['remarks' => 'Remarks is required when declining a request.']);
        }

        DB::beginTransaction();
        try {
            $req->status = $status;
            $req->remarks = $remarks;

            $req->last_acted_by = Auth::id();

            if ($status === 'APPROVED') {
                $req->approved_at = now();
            } elseif ($status === 'PROCESSING') {
                $req->processing_at = now();
            } elseif ($status === 'READY_FOR_PICKUP') {
                $req->ready_at = now();
            } elseif ($status === 'RELEASED') {
                $req->released_at = now();
            } elseif ($status === 'DECLINED') {
                $req->declined_at = now();
            }

            // When DECLINED or RELEASED -> allow user to request again
            if (in_array($status, ['DECLINED','RELEASED'], true)) {
                $req->is_active_request = 0;
            }

            $req->save();

            AlumniIdRequestLog::create([
                'request_id' => $req->id,
                'actor_user_id' => Auth::id(),
                'action' => $actionMap[$status] ?? 'UPDATED_DETAILS',
                'remarks' => $remarks,
            ]);

            DB::commit();

            return redirect()->route('id.officer.requests.show', $req->id)
                ->with('success', "Request updated to {$status}.");
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
