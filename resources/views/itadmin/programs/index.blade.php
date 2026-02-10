{{-- resources/views/itadmin/programs/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">Programs</h2>
                <p class="text-sm text-gray-600">Manage program list, status, and bulk uploads.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('itadmin.programs.template') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border"
                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                    Download Template
                </a>

                <a href="{{ route('itadmin.programs.upload_form') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white"
                   style="background:#0B3D2E;">
                    Upload
                </a>

                <a href="{{ route('itadmin.programs.create') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white"
                   style="background:#0F5A41;">
                    + Add Program
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        :root{
            --ndmu-green:#0B3D2E;
            --ndmu-green2:#0F5A41;
            --ndmu-gold:#E3C77A;
            --paper:#FFFBF0;
            --page:#FAFAF8;
            --line:#EDE7D1;
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
        .panel{
            background:#fff;
            border:1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
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
        .badge-active{
            background: rgba(16,185,129,.12);
            border-color: rgba(16,185,129,.35);
            color: rgba(6,95,70,1);
        }
        .badge-inactive{
            background: rgba(148,163,184,.18);
            border-color: rgba(148,163,184,.35);
            color: rgba(51,65,85,1);
        }
        .pill{
            display:inline-flex;
            align-items:center;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            background: rgba(227,199,122,.20);
            border: 1px solid rgba(227,199,122,.55);
            color: var(--ndmu-green);
        }
        .table-head{
            background: #F6F2E6;
            border-bottom: 1px solid var(--line);
        }
        .row:hover{
            background: #FFFEFB;
        }
        .link{
            font-weight: 800;
            color: var(--ndmu-green);
        }
        .link:hover{ text-decoration: underline; }
        .danger{
            color: #b91c1c;
            font-weight: 800;
        }
        .danger:hover{ text-decoration: underline; }
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Flash --}}
            @if(session('success'))
                <div class="panel p-4" style="border-color:rgba(34,197,94,.35); background:rgba(34,197,94,.10);">
                    <div class="font-semibold" style="color:rgba(21,128,61,1);">
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

            {{-- Filters --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Filter & Search</div>
                            <div class="strip-sub">Category, status, and keyword search.</div>
                        </div>
                    </div>
                    <span class="pill">IT Admin</span>
                </div>

                <div class="p-4">
                    <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Category</label>
                            <select name="category" class="input">
                                <option value="">All</option>
                                <option value="college" {{ request('category')==='college'?'selected':'' }}>College</option>
                                <option value="grad_school" {{ request('category')==='grad_school'?'selected':'' }}>Graduate School</option>
                                <option value="law" {{ request('category')==='law'?'selected':'' }}>Law</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                            <select name="status" class="input">
                                <option value="">All</option>
                                <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
                            <input name="search"
                                   value="{{ request('search') }}"
                                   class="input"
                                   placeholder="Program name or code">
                        </div>

                        <div class="flex flex-wrap gap-2 sm:col-span-4">
                            <button class="btn btn-primary" type="submit">Apply Filters</button>
                            <a href="{{ route('itadmin.programs.index') }}" class="btn btn-outline">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="panel overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="table-head">
                            <tr>
                                <th class="p-3 text-left font-extrabold" style="color:#0B3D2E;">Category</th>
                                <th class="p-3 text-left font-extrabold" style="color:#0B3D2E;">Code</th>
                                <th class="p-3 text-left font-extrabold" style="color:#0B3D2E;">Name</th>
                                <th class="p-3 text-left font-extrabold" style="color:#0B3D2E;">Status</th>
                                <th class="p-3 text-left font-extrabold w-52" style="color:#0B3D2E;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($programs as $p)
                                <tr class="border-t row" style="border-color:var(--line);">
                                    <td class="p-3">
                                        <span class="text-xs font-bold text-gray-700">
                                            {{ str_replace('_',' ', $p->category) }}
                                        </span>
                                    </td>

                                    <td class="p-3 font-mono text-xs text-gray-800">
                                        {{ $p->code ?: '—' }}
                                    </td>

                                    <td class="p-3">
                                        <div class="font-semibold text-gray-900">{{ $p->name }}</div>
                                    </td>

                                    <td class="p-3">
                                        <span class="badge {{ $p->is_active ? 'badge-active' : 'badge-inactive' }}">
                                            {{ $p->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    <td class="p-3">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <a href="{{ route('itadmin.programs.edit', $p) }}" class="link">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('itadmin.programs.toggle', $p) }}">
                                                @csrf
                                                <button type="submit" class="{{ $p->is_active ? 'danger' : 'link' }}">
                                                    {{ $p->is_active ? 'Disable' : 'Enable' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="p-5 text-gray-500" colspan="5">No programs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{ $programs->links() }}
        </div>
    </div>
</x-app-layout>
