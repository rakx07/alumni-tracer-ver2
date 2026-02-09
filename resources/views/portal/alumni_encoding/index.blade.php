{{-- resources/views/portal/alumni_encoding/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">Alumni Encoding</h2>
                <p class="text-sm text-gray-600">Assisted encoding (Officer / IT Admin).</p>
            </div>

            <a href="{{ route('portal.alumni_encoding.create') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white shadow-sm"
               style="background:#0B3D2E;">
                + Encode Alumni
            </a>
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

        .panel{
            background:#fff;
            border:1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }

        .strip{
            border:1px solid var(--line);
            border-radius: 18px;
            overflow:hidden;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }
        .strip-top{
            padding: 16px 18px;
            background: var(--ndmu-green);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }
        .strip-left{
            display:flex;
            align-items:center;
            gap: 12px;
            min-width: 0;
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
        .pill-dot{
            width: 8px; height: 8px;
            border-radius: 999px;
            background: var(--ndmu-green);
        }

        .input{
            width:100%;
            border-radius: 12px;
            border: 1px solid rgba(15,23,42,.18);
            padding: 10px 12px;
            font-size: 14px;
            outline: none;
        }
        .input:focus{
            box-shadow: 0 0 0 3px rgba(227,199,122,.35);
            border-color: rgba(227,199,122,.85);
        }

        .btn-ndmu{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 900;
            transition: .15s ease;
            white-space: nowrap;
            box-shadow: 0 6px 14px rgba(2,6,23,.06);
        }
        .btn-primary{ background: var(--ndmu-green); color:#fff; }
        .btn-primary:hover{ filter: brightness(.95); }
        .btn-outline{
            background: var(--paper);
            color: var(--ndmu-green);
            border: 1px solid var(--ndmu-gold);
        }
        .btn-outline:hover{ filter: brightness(.98); }

        .status{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            border: 1px solid transparent;
            white-space: nowrap;
        }
        .st-draft{
            background: rgba(148,163,184,.14);
            border-color: rgba(148,163,184,.35);
            color: rgba(51,65,85,1);
        }
        .st-submitted{
            background: rgba(59,130,246,.10);
            border-color: rgba(59,130,246,.25);
            color: rgba(30,64,175,1);
        }
        .st-validated{
            background: rgba(34,197,94,.10);
            border-color: rgba(34,197,94,.25);
            color: rgba(22,101,52,1);
        }
        .st-needs_revision{
            background: rgba(234,179,8,.10);
            border-color: rgba(234,179,8,.25);
            color: rgba(133,77,14,1);
        }
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="panel p-4" style="border-color:rgba(34,197,94,.30); background:rgba(34,197,94,.06);">
                    <div class="font-semibold" style="color:rgba(22,101,52,1);">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="panel p-4" style="border-color:rgba(234,179,8,.35); background:rgba(234,179,8,.10);">
                    <div class="font-semibold" style="color:rgba(133,77,14,1);">
                        {{ session('warning') }}
                    </div>
                </div>
            @endif

            {{-- NDMU strip + filter --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Assisted Encoding Records</div>
                            <div class="strip-sub">Search by name/email and filter by status.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            Showing: {{ $alumni->count() }}
                        </span>
                    </div>
                </div>

                <div class="p-4">
                    <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <input name="search"
                               value="{{ request('search') }}"
                               class="input"
                               placeholder="Search name/email...">

                        <select name="status" class="input">
                            <option value="">All Status</option>
                            @foreach(['draft','submitted','validated','needs_revision'] as $st)
                                <option value="{{ $st }}" @selected(request('status')===$st)>
                                    {{ ucfirst(str_replace('_',' ',$st)) }}
                                </option>
                            @endforeach
                        </select>

                        <div class="flex gap-2">
                            <button class="btn-ndmu btn-primary w-full" type="submit">Filter</button>
                            <a href="{{ route('portal.alumni_encoding.index') }}" class="btn-ndmu btn-outline w-full text-center">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="panel overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead style="background:var(--paper); border-bottom:1px solid var(--line);">
                        <tr style="color:var(--ndmu-green);">
                            <th class="text-left p-3 font-extrabold">Name</th>
                            <th class="text-left p-3 font-extrabold">Email</th>
                            <th class="text-left p-3 font-extrabold">Status</th>
                            <th class="text-left p-3 font-extrabold">Linked User</th>
                            <th class="text-left p-3 font-extrabold">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($alumni as $a)
                            @php
                                $st = $a->record_status ?? 'draft';
                                $cls = match($st){
                                    'submitted' => 'st-submitted',
                                    'validated' => 'st-validated',
                                    'needs_revision' => 'st-needs_revision',
                                    default => 'st-draft',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-semibold text-gray-900">
                                    {{ $a->full_name }}
                                </td>

                                <td class="p-3 text-gray-600">
                                    {{ $a->email ?? '—' }}
                                </td>

                                <td class="p-3">
                                    <span class="status {{ $cls }}">
                                        {{ ucfirst(str_replace('_',' ', $st)) }}
                                    </span>
                                </td>

                                <td class="p-3 text-gray-600">
                                    {{ $a->user?->email ?? '—' }}
                                </td>

                                <td class="p-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a class="btn-ndmu btn-outline"
                                           href="{{ route('portal.alumni_encoding.edit', $a) }}">
                                            Edit
                                        </a>

                                        <a class="btn-ndmu btn-primary"
                                           href="{{ route('portal.alumni_encoding.audit', $a) }}">
                                            Audit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="p-6 text-gray-600" colspan="5">
                                    No assisted records.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div>
                {{ $alumni->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
