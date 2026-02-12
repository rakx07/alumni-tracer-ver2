{{-- resources/views/id/officer/requests/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Alumni ID Requests
                </h2>
                <div class="text-sm text-gray-600">
                    Review, approve, and process Alumni ID requests.
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if(Route::has('id.officer.requests.assisted.create'))
                    <a href="{{ route('id.officer.requests.assisted.create') }}"
                       class="inline-flex items-center px-4 py-2 rounded font-semibold"
                       style="background:#E3C77A; color:#0B3D2E;">
                        + Assisted Request
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div class="rounded-xl border p-4 bg-green-50" style="border-color:#86EFAC;">
                    <div class="font-semibold text-green-800">Success</div>
                    <div class="text-sm text-green-700 mt-1">{{ session('success') }}</div>
                </div>
            @endif
            @if(session('warning'))
                <div class="rounded-xl border p-4 bg-yellow-50" style="border-color:#FDE68A;">
                    <div class="font-semibold text-yellow-900">Notice</div>
                    <div class="text-sm text-yellow-800 mt-1">{{ session('warning') }}</div>
                </div>
            @endif

            {{-- NDMU HERO --}}
            <div class="rounded-xl shadow border overflow-hidden" style="border-color:#E3C77A;">
                <div class="p-6 sm:p-8 text-white"
                     style="background:linear-gradient(135deg,#0B3D2E 0%, #0F5A41 55%, #0B3D2E 100%);">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">

                        <div>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold px-3 py-1 rounded-full"
                                 style="background:rgba(227,199,122,.18); border:1px solid rgba(227,199,122,.35);">
                                <span class="h-2.5 w-2.5 rounded-full" style="background:#E3C77A;"></span>
                                NDMU Alumni Tracer
                            </div>

                            <h3 class="mt-3 text-2xl font-bold tracking-tight">
                                Alumni ID Processing Queue
                            </h3>

                            <p class="mt-1 text-sm text-white/90 leading-relaxed">
                                Filter requests by status/type, review uploaded files, and update the processing status.
                                <span class="mx-2">•</span>
                                Use <span class="font-semibold" style="color:#E3C77A;">Assisted Request</span> for walk-in/PWD/Senior Citizen encoding.
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                @if(Route::has('id.officer.requests.assisted.create'))
                                    <a href="{{ route('id.officer.requests.assisted.create') }}"
                                       class="inline-flex items-center px-4 py-2 rounded font-semibold"
                                       style="background:#E3C77A; color:#0B3D2E;">
                                        + Assisted Request
                                    </a>
                                @endif

                                @if(Route::has('portal.records.index'))
                                    <a href="{{ route('portal.records.index') }}"
                                       class="inline-flex items-center px-4 py-2 rounded font-semibold border"
                                       style="border-color:rgba(255,255,255,.45); color:#fff;">
                                        Manage Alumni Records
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="w-full lg:w-[420px]">
                            <div class="rounded-lg p-4"
                                 style="background:rgba(255,255,255,.08); border:1px solid rgba(227,199,122,.25);">
                                <div class="text-sm font-semibold" style="color:#E3C77A;">Quick Notes</div>
                                <ul class="mt-2 text-sm text-white/90 space-y-2">
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        LOST/STOLEN require Affidavit of Loss.
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        BROKEN requires proof photo/document.
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        RELEASED/DECLINED ends the active request.
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- FILTERS --}}
            <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-lg font-semibold" style="color:#0B3D2E;">Filters</div>
                        <div class="text-sm text-gray-600">Search by name, school ID, or course.</div>
                    </div>
                    <div class="text-xs font-semibold px-3 py-1 rounded-full"
                         style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                        Queue Tools
                    </div>
                </div>

                <form method="GET" class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-2">
                    <input name="search" value="{{ request('search') }}"
                           placeholder="Search name / school id / course..."
                           class="rounded-lg border-gray-300 w-full focus:border-[#0B3D2E] focus:ring-[#0B3D2E]" />

                    <select name="status" class="rounded-lg border-gray-300 w-full focus:border-[#0B3D2E] focus:ring-[#0B3D2E]">
                        <option value="">All Status</option>
                        @foreach(['PENDING','APPROVED','PROCESSING','DECLINED','READY_FOR_PICKUP','RELEASED'] as $s)
                            <option value="{{ $s }}" {{ request('status')===$s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>

                    <select name="type" class="rounded-lg border-gray-300 w-full focus:border-[#0B3D2E] focus:ring-[#0B3D2E]">
                        <option value="">All Types</option>
                        @foreach(['NEW','LOST','STOLEN','BROKEN'] as $t)
                            <option value="{{ $t }}" {{ request('type')===$t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>

                    <button class="px-4 py-2 rounded-lg font-semibold text-white"
                            style="background:#0B3D2E;">
                        Apply Filters
                    </button>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="bg-white rounded-xl shadow border overflow-x-auto" style="border-color:#EDE7D1;">
                <table class="min-w-full text-sm">
                    <thead style="background:#FFFBF0;">
                        <tr class="text-left">
                            <th class="p-3 font-semibold" style="color:#0B3D2E;">ID</th>
                            <th class="p-3 font-semibold" style="color:#0B3D2E;">Name</th>
                            <th class="p-3 font-semibold" style="color:#0B3D2E;">Type</th>
                            <th class="p-3 font-semibold" style="color:#0B3D2E;">Course</th>
                            <th class="p-3 font-semibold" style="color:#0B3D2E;">Grad Year</th>
                            <th class="p-3 font-semibold" style="color:#0B3D2E;">Status</th>
                            <th class="p-3 font-semibold" style="color:#0B3D2E;">Last Action By</th>
                            <th class="p-3 font-semibold" style="color:#0B3D2E;">Submitted</th>
                            <th class="p-3 font-semibold" style="color:#0B3D2E;"></th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @forelse($requests as $r)
                            @php
                                $badge = match($r->status) {
                                    'PENDING'          => ['bg'=>'rgba(253,230,138,.22)','bd'=>'rgba(253,230,138,.55)','tx'=>'#92400E'],
                                    'APPROVED'         => ['bg'=>'rgba(227,199,122,.22)','bd'=>'rgba(227,199,122,.55)','tx'=>'#0B3D2E'],
                                    'PROCESSING'       => ['bg'=>'rgba(59,130,246,.12)','bd'=>'rgba(59,130,246,.30)','tx'=>'#1D4ED8'],
                                    'READY_FOR_PICKUP' => ['bg'=>'rgba(34,197,94,.12)','bd'=>'rgba(34,197,94,.30)','tx'=>'#166534'],
                                    'RELEASED'         => ['bg'=>'rgba(16,185,129,.12)','bd'=>'rgba(16,185,129,.30)','tx'=>'#065F46'],
                                    'DECLINED'         => ['bg'=>'rgba(239,68,68,.12)','bd'=>'rgba(239,68,68,.35)','tx'=>'#B91C1C'],
                                    default            => ['bg'=>'rgba(229,231,235,.45)','bd'=>'rgba(229,231,235,.90)','tx'=>'#374151'],
                                };

                                /**
                                 * COURSE CODE ONLY (INDEX ONLY):
                                 * 1) Use request.course if present (split "BSIT - ..." or "BSIT — ...")
                                 * 2) Fallback to alumnus latest graduated eligible education program code
                                 */
                                $courseCode = null;

                                if (!empty($r->course)) {
                                    $courseRaw = trim((string)$r->course);

                                    if (str_contains($courseRaw, '—')) {
                                        $courseCode = trim(explode('—', $courseRaw, 2)[0]);
                                    } elseif (str_contains($courseRaw, ' - ')) {
                                        $courseCode = trim(explode(' - ', $courseRaw, 2)[0]);
                                    } else {
                                        $courseCode = $courseRaw;
                                    }
                                }

                                if (empty($courseCode)) {
                                    $latestEdu = $r->alumnus?->educations
                                        ?->whereIn('level', ['ndmu_college','ndmu_grad_school','ndmu_law'])
                                        ?->where('did_graduate', 1)
                                        ?->sortByDesc(fn($e) => (int)($e->year_graduated ?? 0))
                                        ?->first();

                                    $courseCode = $latestEdu?->program?->code
                                        ?: ($latestEdu?->specific_program ?: null)
                                        ?: ($latestEdu?->degree_program ?: null);
                                }

                                $courseCode = $courseCode ?: '—';
                            @endphp

                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3 text-gray-700">#{{ $r->id }}</td>
                                <td class="p-3 font-medium text-gray-900">
                                    {{ $r->last_name }}, {{ $r->first_name }} {{ $r->middle_name }}
                                </td>
                                <td class="p-3 text-gray-700">{{ $r->request_type }}</td>

                                {{-- ✅ ONLY THIS CELL CHANGED: show BSIT only (fallback-safe) --}}
                                <td class="p-3 text-gray-700">{{ $courseCode }}</td>

                                <td class="p-3 text-gray-700">{{ $r->grad_year ?? '—' }}</td>
                                <td class="p-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                          style="background:{{ $badge['bg'] }}; border:1px solid {{ $badge['bd'] }}; color:{{ $badge['tx'] }};">
                                        {{ $r->status }}
                                    </span>
                                </td>
                                <td class="p-3 text-gray-700">{{ optional($r->lastActor)->name ?? '—' }}</td>
                                <td class="p-3 text-gray-700">{{ optional($r->created_at)->format('M d, Y') }}</td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('id.officer.requests.show', $r->id) }}"
                                       class="inline-flex items-center px-3 py-1 rounded font-semibold border"
                                       style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="p-6 text-center text-gray-500" colspan="9">No requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div>
                {{ $requests->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
