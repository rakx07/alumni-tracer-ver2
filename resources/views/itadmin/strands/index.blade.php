{{-- resources/views/itadmin/strands/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">Strands</h2>
                <p class="text-sm text-gray-600">Manage SHS strands used in the Alumni Intake form.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('itadmin.strands.upload_form') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white"
                   style="background:#0B3D2E;">
                    Upload
                </a>

                <a href="{{ route('itadmin.strands.create') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold"
                   style="background:#E3C77A; color:#0B3D2E;">
                    + Add Strand
                </a>
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
            padding: 14px 18px;
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

        .input{
            width:100%;
            border-radius: 12px;
            border: 1px solid rgba(15,23,42,.18);
            padding: 10px 12px;
            font-size: 14px;
            outline: none;
            background:#fff;
        }
        .input:focus{
            box-shadow: 0 0 0 3px rgba(227,199,122,.35);
            border-color: rgba(227,199,122,.85);
        }

        /* Buttons */
        .btn{
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
            width: 100%;
        }
        .btn-primary{ background: var(--ndmu-green); color:#fff; }
        .btn-primary:hover{ filter: brightness(.95); }
        .btn-outline{
            background: var(--paper);
            color: var(--ndmu-green);
            border: 1px solid var(--ndmu-gold);
        }
        .btn-outline:hover{ filter: brightness(.98); }

        .badge{
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
        .badge-green{
            background: rgba(34,197,94,.10);
            border-color: rgba(34,197,94,.30);
            color: rgba(20,83,45,1);
        }
        .badge-muted{
            background: rgba(15,23,42,.06);
            border-color: rgba(15,23,42,.10);
            color: rgba(15,23,42,.70);
        }

        .help{
            font-size: 12px;
            color: rgba(15,23,42,.62);
            line-height: 1.25rem;
            min-height: 1.25rem; /* reserve equal helper space */
        }

        table.ndmu th{
            font-size: 12px;
            letter-spacing: .02em;
            color: rgba(15,23,42,.70);
            background: #FAFAF8;
        }
        table.ndmu td{
            vertical-align: top;
        }
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="panel p-4" style="border-color:rgba(34,197,94,.35); background:rgba(34,197,94,.10);">
                    <div class="font-semibold" style="color:rgba(20,83,45,1);">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- Filters --}}
            <div class="panel p-4">
                <form method="GET" action="{{ route('itadmin.strands.index') }}"
                      class="grid grid-cols-1 md:grid-cols-12 gap-4">

                    {{-- Status --}}
                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold" style="color:var(--ndmu-green);">
                            Status
                        </label>

                        <select name="status" class="input mt-2">
                            <option value="">All</option>
                            <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
                        </select>

                        {{-- keep same height as search help --}}
                        <div class="help mt-2 opacity-0">placeholder</div>
                    </div>

                    {{-- Search --}}
                    <div class="md:col-span-7">
                        <label class="block text-sm font-semibold" style="color:var(--ndmu-green);">
                            Search
                        </label>

                        <input name="search"
                               value="{{ request('search') }}"
                               class="input mt-2"
                               placeholder="Search by strand name or code">

                        <div class="help mt-2">Tip: search “STEM”, “ABM”, “HUMSS”, etc.</div>
                    </div>

                    {{-- Buttons --}}
                    <div class="md:col-span-2 flex flex-col">
                        {{-- match label height (roughly) --}}
                        <div style="height:20px;"></div>

                        <div class="mt-2 flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Filter
                            </button>

                            <a href="{{ route('itadmin.strands.index') }}" class="btn btn-outline">
                                Reset
                            </a>
                        </div>

                        {{-- keep same height as search help --}}
                        <div class="help mt-2 opacity-0">placeholder</div>
                    </div>

                </form>
            </div>

            {{-- Table --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Strand List</div>
                            <div class="strip-sub">Enable/disable strands without deleting historical data.</div>
                        </div>
                    </div>
                </div>

                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full text-sm ndmu">
                        <thead>
                            <tr>
                                <th class="p-3 text-left">Code</th>
                                <th class="p-3 text-left">Name</th>
                                <th class="p-3 text-left">Status</th>
                                <th class="p-3 text-left w-48">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($strands as $s)
                                <tr class="border-t" style="border-color:var(--line);">
                                    <td class="p-3 font-extrabold" style="color:var(--ndmu-green);">
                                        {{ $s->code }}
                                    </td>

                                    <td class="p-3">
                                        <div class="font-semibold text-gray-900">{{ $s->name }}</div>
                                    </td>

                                    <td class="p-3">
                                        @if($s->is_active)
                                            <span class="badge badge-green">Active</span>
                                        @else
                                            <span class="badge badge-muted">Inactive</span>
                                        @endif
                                    </td>

                                    <td class="p-3">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <a href="{{ route('itadmin.strands.edit', $s) }}"
                                               class="font-semibold underline"
                                               style="color:var(--ndmu-green);">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('itadmin.strands.toggle', $s) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="font-semibold underline"
                                                        style="color:{{ $s->is_active ? '#b91c1c' : 'var(--ndmu-green)' }};">
                                                    {{ $s->is_active ? 'Disable' : 'Enable' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="p-6 text-gray-500" colspan="4">
                                        No strands found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4" style="background:var(--page); border-top:1px solid var(--line);">
                    {{ $strands->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
