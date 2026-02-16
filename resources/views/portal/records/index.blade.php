{{-- resources/views/portal/records/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Alumni Records
                </h2>
                <div class="text-sm text-gray-600">
                    Search, filter, and manage alumni intake records.
                </div>
            </div>

            {{-- Optional quick hint / count --}}
            <div class="flex items-center gap-2">
                <div class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-lg border"
                     style="border-color:#E3C77A; background:#FFFBF0; color:#0B3D2E;">
                    <span class="inline-block h-2 w-2 rounded-full" style="background:#0B3D2E;"></span>
                    <span class="text-xs font-semibold tracking-wide">
                        NDMU Alumni Tracer Portal
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    @php
        // Persist filters
        $q       = request('q');
        $field   = request('field', 'all');   // all|name|email|id
        $from    = request('from');          // YYYY-MM-DD
        $to      = request('to');            // YYYY-MM-DD
        $perPage = request('per_page', 10);

        // Pagination should keep filters
        $append = request()->only(['q','field','from','to','per_page']);
    @endphp

    <style>
        :root{
            --ndmu-green:#0B3D2E;
            --ndmu-gold:#E3C77A;
            --paper:#FFFBF0;
            --page:#FAFAF8;
            --line:#EDE7D1;
        }

        .chip{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 6px 10px;
            border-radius: 999px;
            border:1px solid var(--ndmu-gold);
            background: var(--paper);
            color: var(--ndmu-green);
            font-weight: 800;
            font-size: 12px;
        }
        .chip-dot{ width: 8px; height: 8px; border-radius: 999px; background: var(--ndmu-green); }

        /* Mobile cards */
        .card{
            border: 1px solid var(--line);
            border-radius: 16px;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
            overflow: hidden;
        }
        .card-hd{
            padding: 14px 14px 10px 14px;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(135deg, rgba(11,61,46,.06), rgba(227,199,122,.16));
        }
        .card-title{
            font-weight: 900;
            color: var(--ndmu-green);
            line-height: 1.25;
        }
        .card-sub{
            margin-top: 4px;
            font-size: 12px;
            color: rgba(15,23,42,.62);
            display:flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .card-body{ padding: 12px 14px 14px 14px; }
        .meta{
            font-size: 12px;
            color: rgba(15,23,42,.70);
            line-height: 1.5;
        }
        .meta strong{ color: rgba(15,23,42,.85); }
        .actions{
            margin-top: 12px;
            display:flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding: 8px 10px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 900;
            border:1px solid transparent;
            box-shadow: 0 6px 14px rgba(2,6,23,.06);
            white-space: nowrap;
        }
        .btn-view{ border-color:#D1FAE5; background:#ECFDF5; color:#065F46; }
        .btn-edit{ border-color:var(--ndmu-gold); background:var(--paper); color:var(--ndmu-green); }
        .btn-lite{ border-color:#E5E7EB; background:#fff; color:#374151; }
        .btn-del{ border-color:#FECACA; background:#FEF2F2; color:#991B1B; }
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Success --}}
            @if(session('success'))
                <div class="rounded-lg border p-3 text-sm"
                     style="border-color:#BBF7D0; background:#ECFDF5; color:#065F46;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- NDMU Section Title Strip --}}
            <div class="rounded-xl overflow-hidden border bg-white shadow-sm">
                <div class="px-5 py-4 flex items-center justify-between gap-3"
                     style="background:var(--ndmu-green);">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-1 rounded-full" style="background:var(--ndmu-gold);"></div>
                        <div>
                            <div class="text-sm font-semibold text-white">Records Management</div>
                            <div class="text-xs text-white/80">
                                Use filters below to narrow results; changes auto-apply.
                            </div>
                        </div>
                    </div>

                    <div class="hidden md:flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-lg"
                         style="background:var(--paper); color:var(--ndmu-green);">
                        Total on this page: {{ $records->count() }}
                    </div>
                </div>

                {{-- Filter Bar --}}
                <div class="p-5">
                    <form method="GET" id="filtersForm">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-end">

                            {{-- Search --}}
                            <div class="lg:col-span-3">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
                                <input
                                    type="text"
                                    name="q"
                                    id="q"
                                    class="w-full border rounded-lg px-3 py-2 h-10 focus:outline-none focus:ring-2"
                                    style="border-color:#E5E7EB;"
                                    placeholder="Type name, email, or ID..."
                                    value="{{ $q }}"
                                >
                            </div>

                            {{-- Search Field --}}
                            <div class="lg:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Search in</label>
                                <select
                                    name="field"
                                    id="field"
                                    class="w-full border rounded-lg px-3 py-2 h-10"
                                    style="border-color:#E5E7EB;"
                                >
                                    <option value="all"   {{ $field==='all'?'selected':'' }}>All fields</option>
                                    <option value="name"  {{ $field==='name'?'selected':'' }}>Full name</option>
                                    <option value="email" {{ $field==='email'?'selected':'' }}>Email</option>
                                    <option value="id"    {{ $field==='id'?'selected':'' }}>Record ID</option>
                                </select>
                            </div>

                            {{-- Created From --}}
                            <div class="lg:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Created from</label>
                                <input
                                    type="date"
                                    name="from"
                                    id="from"
                                    class="w-full border rounded-lg px-3 py-2 h-10"
                                    style="border-color:#E5E7EB;"
                                    value="{{ $from }}"
                                >
                            </div>

                            {{-- Created To --}}
                            <div class="lg:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Created to</label>
                                <input
                                    type="date"
                                    name="to"
                                    id="to"
                                    class="w-full border rounded-lg px-3 py-2 h-10"
                                    style="border-color:#E5E7EB;"
                                    value="{{ $to }}"
                                >
                            </div>

                            {{-- Rows --}}
                            <div class="lg:col-span-1">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Rows</label>
                                <select
                                    name="per_page"
                                    id="per_page"
                                    class="w-full border rounded-lg px-3 py-2 h-10"
                                    style="border-color:#E5E7EB;"
                                >
                                    @foreach([10,25,50,100] as $n)
                                        <option value="{{ $n }}" {{ (string)$perPage === (string)$n ? 'selected' : '' }}>
                                            {{ $n }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="lg:col-span-2 flex gap-2">
                                <button
                                    type="submit"
                                    class="h-10 px-4 rounded-lg w-full text-sm font-semibold shadow-sm transition"
                                    style="background:var(--ndmu-green); color:white;"
                                >
                                    Apply
                                </button>

                                <a
                                    href="{{ route('portal.records.index') }}"
                                    class="h-10 px-4 rounded-lg w-full text-sm font-semibold border text-center flex items-center justify-center shadow-sm transition"
                                    style="border-color:var(--ndmu-gold); background:var(--paper); color:var(--ndmu-green);"
                                >
                                    Reset
                                </a>
                            </div>

                        </div>

                        {{-- Active filter chips --}}
                        <div class="mt-4 flex flex-wrap gap-2">
                            @php
                                $chips = [];
                                if ($q) $chips[] = ['label' => 'Search: '.$q];
                                if ($field && $field !== 'all') $chips[] = ['label' => 'Field: '.$field];
                                if ($from) $chips[] = ['label' => 'From: '.$from];
                                if ($to) $chips[] = ['label' => 'To: '.$to];
                                if ($perPage) $chips[] = ['label' => 'Rows: '.$perPage];
                            @endphp

                            @foreach($chips as $c)
                                <span class="chip">
                                    <span class="chip-dot"></span>
                                    {{ $c['label'] }}
                                </span>
                            @endforeach

                            @if(empty($chips))
                                <span class="text-xs text-gray-500">No filters applied.</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- ✅ MOBILE: cards (ID hidden) --}}
            <div class="space-y-3 sm:hidden">
                @forelse($records as $a)
                    <div class="card">
                        <div class="card-hd">
                            <div class="card-title">{{ $a->full_name }}</div>
                            <div class="card-sub">
                                <span>✉️ {{ $a->email ?: '—' }}</span>
                                <span>📅 {{ optional($a->created_at)->format('Y-m-d') ?: '—' }}</span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="meta">
                                <div><strong>Contact:</strong> {{ $a->contact_number ?: '—' }}</div>
                                <div><strong>Nationality:</strong> {{ $a->nationality ?: '—' }}</div>
                            </div>

                            <div class="actions">
                                <a href="{{ route('portal.records.show', $a) }}" class="btn btn-view">View</a>
                                <a href="{{ route('portal.records.edit', $a) }}" class="btn btn-edit">Edit</a>
                                <a href="{{ route('portal.records.pdf', $a) }}" class="btn btn-lite">PDF</a>
                                <a href="{{ route('portal.records.excel', $a) }}" class="btn btn-lite">Excel</a>

                                @if(auth()->user()->role === 'it_admin')
                                    <form method="POST"
                                          action="{{ route('portal.records.destroy', $a) }}"
                                          class="inline"
                                          onsubmit="return confirm('Soft delete this record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-del">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500 bg-white rounded-xl border">
                        <div class="font-semibold">No records found.</div>
                        <div class="text-xs mt-1">Try adjusting your filters.</div>
                    </div>
                @endforelse

                {{-- Pagination (mobile) --}}
                <div class="p-3 bg-white rounded-xl border">
                    {{ $records->appends($append)->links() }}
                </div>
            </div>

            {{-- ✅ DESKTOP/TABLET: table (unchanged, ID shown) --}}
            <div class="hidden sm:block bg-white shadow-sm rounded-xl border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b" style="background:var(--paper);">
                            <tr style="color:var(--ndmu-green);">
                                <th class="p-3 text-left font-extrabold">ID</th>
                                <th class="p-3 text-left font-extrabold">Full Name</th>
                                <th class="p-3 text-left font-extrabold">Email</th>
                                <th class="p-3 text-left font-extrabold">Created</th>
                                <th class="p-3 text-left font-extrabold">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($records as $a)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 font-bold text-gray-900 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-extrabold border"
                                              style="border-color:var(--ndmu-gold); background:var(--paper); color:var(--ndmu-green);">
                                            #{{ $a->id }}
                                        </span>
                                    </td>

                                    <td class="p-3">
                                        <div class="font-extrabold text-gray-900">{{ $a->full_name }}</div>
                                        <div class="text-xs text-gray-500">
                                            Contact: {{ $a->contact_number ?: '—' }}
                                        </div>
                                    </td>

                                    <td class="p-3">
                                        <div class="text-gray-900">{{ $a->email ?: '—' }}</div>
                                        <div class="text-xs text-gray-500">
                                            Nationality: {{ $a->nationality ?: '—' }}
                                        </div>
                                    </td>

                                    <td class="p-3 text-gray-700 whitespace-nowrap">
                                        {{ optional($a->created_at)->format('Y-m-d') }}
                                    </td>

                                    <td class="p-3 whitespace-nowrap">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('portal.records.show', $a) }}"
                                               class="px-3 py-1.5 rounded-lg text-sm font-semibold border shadow-sm transition"
                                               style="border-color:#D1FAE5; background:#ECFDF5; color:#065F46;">
                                                View
                                            </a>

                                            <a href="{{ route('portal.records.edit', $a) }}"
                                               class="px-3 py-1.5 rounded-lg text-sm font-semibold border shadow-sm transition"
                                               style="border-color:var(--ndmu-gold); background:var(--paper); color:var(--ndmu-green);">
                                                Edit
                                            </a>

                                            <a href="{{ route('portal.records.pdf', $a) }}"
                                               class="px-3 py-1.5 rounded-lg text-sm font-semibold border shadow-sm transition hover:bg-gray-50"
                                               style="border-color:#E5E7EB; background:white; color:#374151;">
                                                PDF
                                            </a>

                                            <a href="{{ route('portal.records.excel', $a) }}"
                                               class="px-3 py-1.5 rounded-lg text-sm font-semibold border shadow-sm transition hover:bg-gray-50"
                                               style="border-color:#E5E7EB; background:white; color:#374151;">
                                                Excel
                                            </a>

                                            @if(auth()->user()->role === 'it_admin')
                                                <form method="POST"
                                                      action="{{ route('portal.records.destroy', $a) }}"
                                                      class="inline"
                                                      onsubmit="return confirm('Soft delete this record?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1.5 rounded-lg text-sm font-semibold border shadow-sm transition"
                                                            style="border-color:#FECACA; background:#FEF2F2; color:#991B1B;">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-500">
                                        <div class="font-semibold">No records found.</div>
                                        <div class="text-xs mt-1">Try adjusting your filters.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-4 border-t bg-white">
                    {{ $records->appends($append)->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- Auto submit filters (no AJAX) --}}
    <script>
        (function () {
            const form = document.getElementById('filtersForm');
            if (!form) return;

            const q = document.getElementById('q');
            const field = document.getElementById('field');
            const from = document.getElementById('from');
            const to = document.getElementById('to');
            const perPage = document.getElementById('per_page');

            // Debounced auto-submit for the search box
            let t = null;
            if (q) {
                q.addEventListener('input', function () {
                    clearTimeout(t);
                    t = setTimeout(() => form.submit(), 450);
                });
            }

            // Auto-submit when changing dropdowns/dates
            [field, from, to, perPage].forEach(el => {
                if (!el) return;
                el.addEventListener('change', () => form.submit());
            });
        })();
    </script>
</x-app-layout>
