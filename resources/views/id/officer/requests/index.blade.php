{{-- resources/views/id/officer/requests/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="min-w-0">
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

    <style>
        :root{
            --ndmu-green:#0B3D2E;
            --ndmu-gold:#E3C77A;
            --paper:#FFFBF0;
            --page:#FAFAF8;
            --line:#EDE7D1;
        }

        /* NDMU strip + cards */
        .page-wrap{ background:var(--page); }
        .strip{
            border:1px solid rgba(227,199,122,.75);
            border-radius: 18px;
            overflow:hidden;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
            background:#fff;
        }
        .strip-top{
            padding: 14px 16px;
            background: var(--ndmu-green);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }
        .strip-left{
            display:flex;
            align-items:center;
            gap:12px;
            min-width:0;
        }
        .gold-bar{
            width: 6px;
            height: 38px;
            background: var(--ndmu-gold);
            border-radius: 999px;
            flex: 0 0 auto;
        }
        .strip-title{
            color:#fff;
            font-weight: 900;
            letter-spacing: .2px;
        }
        .strip-sub{
            color: rgba(255,255,255,.78);
            font-size: 12px;
            margin-top: 2px;
            line-height: 1.35;
        }

        .pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,251,240,.95);
            border: 1px solid rgba(227,199,122,.85);
            color: var(--ndmu-green);
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
        }
        .pill-dot{ width:8px; height:8px; border-radius:999px; background: var(--ndmu-green); }

        .card{
            border:1px solid rgba(15,23,42,.10);
            border-radius: 18px;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
            overflow:hidden;
        }
        .muted{ color:rgba(15,23,42,.65); font-size:12px; }

        .btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:10px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 900;
            border:1px solid rgba(15,23,42,.12);
            background:#fff;
            transition: .15s ease;
            box-shadow: 0 6px 14px rgba(2,6,23,.06);
            white-space: nowrap;
        }
        .btn-gold{ background:var(--paper); border-color:var(--ndmu-gold); color:var(--ndmu-green); }
        .btn-primary{ background:var(--ndmu-green); border-color:var(--ndmu-green); color:#fff; }
        .btn:hover{ filter:brightness(.98); transform: translateY(-.5px); }

        /* Desktop table */
        .table-wrap{ border:1px solid rgba(15,23,42,.10); border-radius:18px; background:#fff; overflow:hidden; box-shadow: 0 10px 24px rgba(2,6,23,.06); }
        table th{ font-size:12px; letter-spacing:.02em; color:rgba(15,23,42,.65); text-transform:uppercase; }
        .thead{ background:#FFFBF0; border-bottom: 1px solid var(--line); }
        .row{ border-top:1px solid rgba(15,23,42,.08); }
        .row:hover{ background: rgba(2,6,23,.02); }

        /* Mobile request cards */
        .mcard{
            border:1px solid rgba(15,23,42,.10);
            border-radius: 18px;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
            overflow:hidden;
        }
        .mcard-top{
            padding: 14px;
            background: linear-gradient(135deg, rgba(11,61,46,.06), rgba(227,199,122,.18));
            border-bottom: 1px solid rgba(15,23,42,.08);
        }
        .mcard-body{ padding: 14px; }
        .kv{ display:flex; align-items:flex-start; justify-content:space-between; gap:10px; }
        .k{ font-size:12px; color: rgba(15,23,42,.60); font-weight: 800; }
        .v{ font-size:13px; color: rgba(15,23,42,.86); text-align:right; word-break: break-word; }
        .badge-dot{ width:8px; height:8px; border-radius:999px; }
    </style>

    <div class="py-8 page-wrap">
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

                        <div class="min-w-0">
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
            <div class="card p-6" style="border-color:#EDE7D1;">
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

            {{-- ✅ MOBILE VIEW (cards) --}}
            <div class="space-y-3 sm:hidden">
                @forelse($requests as $r)
                    @php
                        $badge = match($r->status) {
                            'PENDING'          => ['bg'=>'rgba(253,230,138,.22)','bd'=>'rgba(253,230,138,.55)','tx'=>'#92400E','dot'=>'#f59e0b'],
                            'APPROVED'         => ['bg'=>'rgba(227,199,122,.22)','bd'=>'rgba(227,199,122,.55)','tx'=>'#0B3D2E','dot'=>'#E3C77A'],
                            'PROCESSING'       => ['bg'=>'rgba(59,130,246,.12)','bd'=>'rgba(59,130,246,.30)','tx'=>'#1D4ED8','dot'=>'#3b82f6'],
                            'READY_FOR_PICKUP' => ['bg'=>'rgba(34,197,94,.12)','bd'=>'rgba(34,197,94,.30)','tx'=>'#166534','dot'=>'#22c55e'],
                            'RELEASED'         => ['bg'=>'rgba(16,185,129,.12)','bd'=>'rgba(16,185,129,.30)','tx'=>'#065F46','dot'=>'#10b981'],
                            'DECLINED'         => ['bg'=>'rgba(239,68,68,.12)','bd'=>'rgba(239,68,68,.35)','tx'=>'#B91C1C','dot'=>'#ef4444'],
                            default            => ['bg'=>'rgba(229,231,235,.45)','bd'=>'rgba(229,231,235,.90)','tx'=>'#374151','dot'=>'#9ca3af'],
                        };

                        $courseCode = null;
                        if (!empty($r->course)) {
                            $courseRaw = trim((string)$r->course);
                            if (str_contains($courseRaw, '—'))      $courseCode = trim(explode('—', $courseRaw, 2)[0]);
                            elseif (str_contains($courseRaw, ' - ')) $courseCode = trim(explode(' - ', $courseRaw, 2)[0]);
                            else                                    $courseCode = $courseRaw;
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

                        $fullName = trim(($r->last_name ?? '').', '.($r->first_name ?? '').' '.($r->middle_name ?? ''));
                    @endphp

                    <div class="mcard">
                        <div class="mcard-top">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="font-extrabold text-gray-900 leading-tight">
                                        {{ $fullName ?: '—' }}
                                    </div>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                              style="background:{{ $badge['bg'] }}; border:1px solid {{ $badge['bd'] }}; color:{{ $badge['tx'] }};">
                                            <span class="badge-dot" style="background:{{ $badge['dot'] }};"></span>
                                            {{ $r->status }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-xs font-extrabold px-3 py-1 rounded-full"
                                     style="background:#FFFBF0; border:1px solid #E3C77A; color:#0B3D2E;">
                                    #{{ $r->id }}
                                </div>
                            </div>
                        </div>

                        <div class="mcard-body space-y-3">
                            <div class="kv">
                                <div class="k">Type</div>
                                <div class="v">{{ $r->request_type }}</div>
                            </div>

                            <div class="kv">
                                <div class="k">Course</div>
                                <div class="v">{{ $courseCode }}</div>
                            </div>

                            <div class="kv">
                                <div class="k">Grad Year</div>
                                <div class="v">{{ $r->grad_year ?? '—' }}</div>
                            </div>

                            <div class="kv">
                                <div class="k">Last Action By</div>
                                <div class="v">{{ optional($r->lastActor)->name ?? '—' }}</div>
                            </div>

                            <div class="kv">
                                <div class="k">Submitted</div>
                                <div class="v">{{ optional($r->created_at)->format('M d, Y') }}</div>
                            </div>

                            <div class="pt-2">
                                <a href="{{ route('id.officer.requests.show', $r->id) }}"
                                   class="btn btn-gold w-full">
                                    View Request
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="mcard p-6 text-center text-gray-600">
                        No requests found.
                    </div>
                @endforelse
            </div>

            {{-- ✅ DESKTOP/TABLET VIEW (table) --}}
            <div class="table-wrap hidden sm:block" style="border-color:#EDE7D1;">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="thead">
                            <tr class="text-left" style="color:#0B3D2E;">
                                <th class="p-3 font-semibold">ID</th>
                                <th class="p-3 font-semibold">Name</th>
                                <th class="p-3 font-semibold">Type</th>
                                <th class="p-3 font-semibold">Course</th>
                                <th class="p-3 font-semibold">Grad Year</th>
                                <th class="p-3 font-semibold">Status</th>
                                <th class="p-3 font-semibold">Last Action By</th>
                                <th class="p-3 font-semibold">Submitted</th>
                                <th class="p-3 font-semibold"></th>
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

                                <tr class="row">
                                    <td class="p-3 text-gray-700">#{{ $r->id }}</td>
                                    <td class="p-3 font-medium text-gray-900">
                                        {{ $r->last_name }}, {{ $r->first_name }} {{ $r->middle_name }}
                                    </td>
                                    <td class="p-3 text-gray-700">{{ $r->request_type }}</td>
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
                                           class="btn btn-gold">
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
            </div>

            {{-- PAGINATION --}}
            <div class="space-y-3">
                <div class="hidden sm:block">
                    {{ $requests->links() }}
                </div>
                <div class="sm:hidden">
                    {{ $requests->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
