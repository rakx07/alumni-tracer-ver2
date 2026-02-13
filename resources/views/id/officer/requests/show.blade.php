{{-- resources/views/id/officer/requests/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Alumni ID Request #{{ $request->id }}
                </h2>
                <div class="text-sm text-gray-600">
                    Review details, signature, attachments, and update status.
                </div>
            </div>

            <a href="{{ route('id.officer.requests.index') }}"
               class="inline-flex items-center px-4 py-2 rounded font-semibold border"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div class="rounded-xl border p-4 bg-green-50" style="border-color:#86EFAC;">
                    <div class="font-semibold text-green-800">Success</div>
                    <div class="text-sm text-green-700 mt-1">{{ session('success') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-xl border p-4 bg-red-50" style="border-color:#FCA5A5;">
                    <div class="font-semibold text-red-800">Please fix:</div>
                    <ul class="list-disc pl-5 text-sm text-red-700 mt-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- NDMU HERO --}}
            @php
                $status = (string)($request->status ?? 'PENDING');

                $statusBadge = match($status) {
                    'PENDING'          => ['bg'=>'rgba(253,230,138,.22)','bd'=>'rgba(253,230,138,.55)','tx'=>'#92400E','dot'=>'#F59E0B'],
                    'APPROVED'         => ['bg'=>'rgba(227,199,122,.22)','bd'=>'rgba(227,199,122,.55)','tx'=>'#0B3D2E','dot'=>'#E3C77A'],
                    'PROCESSING'       => ['bg'=>'rgba(59,130,246,.12)','bd'=>'rgba(59,130,246,.30)','tx'=>'#1D4ED8','dot'=>'#3B82F6'],
                    'READY_FOR_PICKUP' => ['bg'=>'rgba(34,197,94,.12)','bd'=>'rgba(34,197,94,.30)','tx'=>'#166534','dot'=>'#22C55E'],
                    'RELEASED'         => ['bg'=>'rgba(16,185,129,.12)','bd'=>'rgba(16,185,129,.30)','tx'=>'#065F46','dot'=>'#10B981'],
                    'DECLINED'         => ['bg'=>'rgba(239,68,68,.12)','bd'=>'rgba(239,68,68,.35)','tx'=>'#B91C1C','dot'=>'#EF4444'],
                    default            => ['bg'=>'rgba(229,231,235,.45)','bd'=>'rgba(229,231,235,.90)','tx'=>'#374151','dot'=>'#9CA3AF'],
                };

                // ✅ Course (FULL: "BSIT - Bachelor of ...")
                $displayCourse = $request->course;

                if (empty($displayCourse)) {
                    $latestEdu = $request->alumnus?->educations
                        ?->whereIn('level', ['ndmu_college','ndmu_grad_school','ndmu_law'])
                        ?->where('did_graduate', 1)
                        ?->sortByDesc(fn($e) => (int)($e->year_graduated ?? 0))
                        ?->first();

                    if ($latestEdu?->program) {
                        $code = trim((string)($latestEdu->program->code ?? ''));
                        $name = trim((string)($latestEdu->program->name ?? ''));
                        $displayCourse = trim(($code !== '' ? $code.' - ' : '').$name);
                    } else {
                        $displayCourse = $latestEdu?->specific_program ?: ($latestEdu?->degree_program ?: null);
                    }
                }

                $displayCourse = $displayCourse ?: '—';

                // ✅ FIX: do NOT call ->url() on disk instance
                // Use Storage::url() OR asset('storage/...') fallback
                $sigPath = (string)($request->signature_path ?? '');
                $sigUrl = $sigPath !== ''
                    ? \Illuminate\Support\Facades\Storage::url($sigPath)
                    : null;

                $isPdf = $sigPath !== '' && str_ends_with(strtolower($sigPath), '.pdf');
            @endphp

            <div class="rounded-xl shadow border overflow-hidden" style="border-color:#E3C77A;">
                <div class="p-6 sm:p-8 text-white"
                     style="background:linear-gradient(135deg,#0B3D2E 0%, #0F5A41 55%, #0B3D2E 100%);">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">

                        <div>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold px-3 py-1 rounded-full"
                                 style="background:rgba(227,199,122,.18); border:1px solid rgba(227,199,122,.35);">
                                <span class="h-2.5 w-2.5 rounded-full" style="background:#E3C77A;"></span>
                                NDMU Alumni Tracer • Alumni ID Processing
                            </div>

                            <h3 class="mt-3 text-2xl font-bold tracking-tight">
                                Request #{{ $request->id }}
                            </h3>

                            <p class="mt-1 text-sm text-white/90 leading-relaxed">
                                View request details, validate files, and update processing status.
                            </p>

                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold"
                                      style="background:{{ $statusBadge['bg'] }}; border:1px solid {{ $statusBadge['bd'] }}; color:{{ $statusBadge['tx'] }};">
                                    <span class="h-2 w-2 rounded-full" style="background:{{ $statusBadge['dot'] }};"></span>
                                    {{ $status }}
                                </span>

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                      style="background:rgba(255,255,255,.10); border:1px solid rgba(255,255,255,.20);">
                                    Type: {{ $request->request_type }}
                                </span>

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                      style="background:rgba(255,255,255,.10); border:1px solid rgba(255,255,255,.20);">
                                    Submitted: {{ optional($request->created_at)->format('M d, Y h:i A') }}
                                </span>

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                      style="background:rgba(255,255,255,.10); border:1px solid rgba(255,255,255,.20);">
                                    Active: {{ $request->is_active_request ? 'YES' : 'NO' }}
                                </span>
                            </div>
                        </div>

                        <div class="w-full lg:w-[420px]">
                            <div class="rounded-lg p-4"
                                 style="background:rgba(255,255,255,.08); border:1px solid rgba(227,199,122,.25);">
                                <div class="text-sm font-semibold" style="color:#E3C77A;">Quick Notes</div>
                                <ul class="mt-2 text-sm text-white/90 space-y-2">
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        DECLINED & RELEASED automatically end the active request.
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        LOST/STOLEN require Affidavit of Loss.
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        BROKEN requires proof photo/document.
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- CONTENT --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT: Details + Status + Logs --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Details Card --}}
                    <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-semibold" style="color:#0B3D2E;">Request Details</div>
                                <div class="text-sm text-gray-600">Snapshot information captured upon submission.</div>
                            </div>
                            <div class="text-xs font-semibold px-3 py-1 rounded-full"
                                 style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                                Alumni ID
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div class="p-3 rounded-lg border" style="background:#FFFBF0; border-color:#EDE7D1;">
                                <div class="text-xs" style="color:#0B3D2E;">Name</div>
                                <div class="font-semibold text-gray-900">
                                    {{ $request->last_name }}, {{ $request->first_name }} {{ $request->middle_name }}
                                </div>
                            </div>

                            <div class="p-3 rounded-lg border" style="background:#FFFBF0; border-color:#EDE7D1;">
                                <div class="text-xs" style="color:#0B3D2E;">School ID (optional)</div>
                                <div class="font-semibold text-gray-900">{{ $request->school_id ?? '—' }}</div>
                            </div>

                            <div class="p-3 rounded-lg border md:col-span-2" style="background:#FFFBF0; border-color:#EDE7D1;">
                                <div class="text-xs" style="color:#0B3D2E;">Course</div>
                                <div class="font-semibold text-gray-900">{{ $displayCourse }}</div>
                            </div>

                            <div class="p-3 rounded-lg border" style="background:#FFFBF0; border-color:#EDE7D1;">
                                <div class="text-xs" style="color:#0B3D2E;">Graduation Year</div>
                                <div class="font-semibold text-gray-900">{{ $request->grad_year ?? '—' }}</div>
                            </div>

                            <div class="p-3 rounded-lg border" style="background:#FFFBF0; border-color:#EDE7D1;">
                                <div class="text-xs" style="color:#0B3D2E;">Birthdate</div>
                                <div class="font-semibold text-gray-900">{{ optional($request->birthdate)->format('M d, Y') ?? '—' }}</div>
                            </div>

                            <div class="p-3 rounded-lg border" style="background:#FFFBF0; border-color:#EDE7D1;">
                                <div class="text-xs" style="color:#0B3D2E;">Last Action By</div>
                                <div class="font-semibold text-gray-900">{{ optional($request->lastActor)->name ?? '—' }}</div>
                            </div>

                            <div class="p-3 rounded-lg border" style="background:#FFFBF0; border-color:#EDE7D1;">
                                <div class="text-xs" style="color:#0B3D2E;">Active Request</div>
                                <div class="font-semibold text-gray-900">{{ $request->is_active_request ? 'YES' : 'NO' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Update Status Card --}}
                    <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-semibold" style="color:#0B3D2E;">Update Status</div>
                                <div class="text-sm text-gray-600">Use this to move the request through the queue.</div>
                            </div>
                            <div class="text-xs font-semibold px-3 py-1 rounded-full"
                                 style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                                Queue Action
                            </div>
                        </div>

                        <form method="POST"
                              action="{{ route('id.officer.requests.updateStatus', $request->id) }}"
                              class="mt-4 space-y-3">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">New Status</label>
                                    @php $currentStatus = old('status', $request->status); @endphp

                                    <select name="status" id="status"
                                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-[#0B3D2E] focus:ring-[#0B3D2E]"
                                            {{ $request->status === 'RELEASED' ? 'disabled' : '' }}>
                                        @foreach(['APPROVED','PROCESSING','DECLINED','READY_FOR_PICKUP','RELEASED'] as $s)
                                            <option value="{{ $s }}" {{ $currentStatus === $s ? 'selected' : '' }}>
                                                {{ $s }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if($request->status === 'RELEASED')
                                        <div class="text-xs text-gray-500 mt-1">
                                            This request is already RELEASED and can no longer be changed.
                                        </div>
                                    @endif

                                    <div class="text-xs text-gray-500 mt-1">
                                        Declined & Released will automatically end the active request.
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Remarks (required if Declined)</label>
                                    <textarea name="remarks" rows="3"
                                              class="mt-1 w-full rounded-lg border-gray-300 focus:border-[#0B3D2E] focus:ring-[#0B3D2E]"
                                              placeholder="Add remarks...">{{ old('remarks') }}</textarea>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white"
                                        style="background:#0B3D2E;">
                                    Save Status
                                </button>
                            </div>
                        </form>

                        @if($request->status === 'DECLINED' && $request->remarks)
                            <div class="mt-4 rounded-lg border p-4 bg-red-50" style="border-color:#FCA5A5;">
                                <div class="font-semibold text-red-800">Decline Remarks</div>
                                <div class="text-sm text-red-700 mt-1">{{ $request->remarks }}</div>
                            </div>
                        @endif
                    </div>

                    {{-- Logs Card --}}
                    <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-semibold" style="color:#0B3D2E;">Activity Logs</div>
                                <div class="text-sm text-gray-600">Audit trail of actions taken on this request.</div>
                            </div>
                            <div class="text-xs font-semibold px-3 py-1 rounded-full"
                                 style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                                Audit
                            </div>
                        </div>

                        <div class="mt-4 space-y-2 text-sm">
                            @forelse($request->logs as $log)
                                <div class="p-4 rounded-lg border" style="background:#FFFBF0; border-color:#EDE7D1;">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="font-semibold text-gray-900">{{ $log->action }}</div>
                                        <div class="text-xs text-gray-500">{{ optional($log->created_at)->format('M d, Y h:i A') }}</div>
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        By: {{ optional($log->actor)->name ?? 'System' }}
                                    </div>
                                    @if($log->remarks)
                                        <div class="mt-2 text-gray-800">{{ $log->remarks }}</div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-sm text-gray-500">No logs recorded.</div>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- RIGHT: Signature + Attachments --}}
                <div class="space-y-6">

                    <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-semibold" style="color:#0B3D2E;">Signature</div>
                                <div class="text-sm text-gray-600">Submitted file for verification.</div>
                            </div>
                            <div class="text-xs font-semibold px-3 py-1 rounded-full"
                                 style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                                File
                            </div>
                        </div>

                        <div class="mt-4">
                            @if(!$sigUrl)
                                <div class="text-sm text-gray-500">No signature uploaded.</div>
                            @elseif($isPdf)
                                <a href="{{ $sigUrl }}" target="_blank"
                                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border"
                                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                                    Open Signature PDF
                                </a>
                            @else
                                <img src="{{ $sigUrl }}" class="w-full rounded-lg border" style="border-color:#EDE7D1;" alt="Signature">
                                <a href="{{ $sigUrl }}" target="_blank"
                                   class="mt-2 inline-flex text-sm underline font-semibold"
                                   style="color:#0B3D2E;">
                                    Open full
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-semibold" style="color:#0B3D2E;">Attachments</div>
                                <div class="text-sm text-gray-600">Supporting documents, if any.</div>
                            </div>
                            <div class="text-xs font-semibold px-3 py-1 rounded-full"
                                 style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                                Proof
                            </div>
                        </div>

                        <div class="mt-4">
                            @if($request->attachments->isEmpty())
                                <div class="text-sm text-gray-500">No attachments uploaded.</div>
                            @else
                                <div class="space-y-2">
                                    @foreach($request->attachments as $a)
                                        @php
                                            $filePath = (string)($a->file_path ?? '');
                                            $url = $filePath !== '' ? \Illuminate\Support\Facades\Storage::url($filePath) : null;
                                            $isPdfA = $filePath !== '' && str_ends_with(strtolower($filePath), '.pdf');
                                        @endphp

                                        <div class="p-4 rounded-lg border" style="background:#FFFBF0; border-color:#EDE7D1;">
                                            <div class="text-sm font-semibold text-gray-900">{{ $a->attachment_type }}</div>
                                            <div class="text-xs text-gray-600 mt-1">
                                                {{ $a->original_name ?? ($filePath !== '' ? basename($filePath) : '—') }}
                                            </div>

                                            @if($url)
                                                <a href="{{ $url }}" target="_blank"
                                                   class="mt-2 inline-flex text-sm underline font-semibold"
                                                   style="color:#0B3D2E;">
                                                    {{ $isPdfA ? 'Open PDF' : 'Open file' }}
                                                </a>
                                            @else
                                                <div class="text-xs text-gray-500 mt-2">File not available.</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
