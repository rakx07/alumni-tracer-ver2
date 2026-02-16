{{-- resources/views/itadmin/users/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    User Management
                </h2>
                <div class="text-sm text-gray-600">
                    IT Admin — Manage portal users, roles, and access status.
                </div>
            </div>

            <a href="{{ route('itadmin.users.create') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                + Create User
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
        .pill-dot{ width:8px; height:8px; border-radius:999px; background: var(--ndmu-green); }

        .card{
            border:1px solid var(--line);
            border-radius: 18px;
            overflow:hidden;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }

        .input{
            width:100%;
            border-radius: 14px;
            border:1px solid rgba(15,23,42,.18);
            padding: 10px 12px;
            outline:none;
            transition:.15s ease;
            background:#fff;
        }
        .input:focus{
            border-color: rgba(227,199,122,.95);
            box-shadow: 0 0 0 4px rgba(227,199,122,.22);
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
            border:1px solid rgba(227,199,122,.75);
            background: rgba(227,199,122,.18);
            color: var(--ndmu-green);
            white-space: nowrap;
        }
        .dot{ width:8px; height:8px; border-radius:999px; }

        .status{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            border: 1px solid var(--line);
            background:#fff;
            color: rgba(15,23,42,.78);
            white-space: nowrap;
        }

        .link{
            font-weight: 900;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        /* Mobile card rows */
        .row-card{
            border: 1px solid var(--line);
            border-radius: 18px;
            background:#fff;
            padding: 14px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }
        .row-k{ font-size: 12px; color: rgba(15,23,42,.62); font-weight: 800; }
        .row-v{ font-size: 14px; color: rgba(15,23,42,.90); font-weight: 900; }

        /* Keep table comfortable */
        th, td{ vertical-align: top; }
    </style>

    <div class="py-10" style="background:var(--page);">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="rounded-xl border p-4"
                     style="border-color: rgba(134,239,172,.55); background: rgba(236,253,245,.85); color:#065F46;">
                    <div class="font-extrabold">Success</div>
                    <div class="text-sm mt-1">{{ session('success') }}</div>
                </div>
            @endif

            {{-- Header strip --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Manage Users</div>
                            <div class="strip-sub">Search accounts, update roles, and control portal access.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            Total: {{ $users->total() }}
                        </span>
                    </div>
                </div>

                {{-- Search --}}
                <div class="p-5 bg-white">
                    <form method="GET" class="flex flex-col sm:flex-row gap-3">
                        <input name="q"
                               value="{{ $q }}"
                               placeholder="Search by name or email…"
                               class="input" />
                        <button class="btn btn-primary w-full sm:w-auto" type="submit">
                            Search
                        </button>

                        @if(!empty($q))
                            <a href="{{ route('itadmin.users.index') }}"
                               class="btn btn-outline w-full sm:w-auto">
                                Reset
                            </a>
                        @endif
                    </form>

                    <div class="mt-3 flex flex-wrap gap-2">
                        @if(!empty($q))
                            <span class="badge">
                                <span class="dot" style="background:var(--ndmu-green)"></span>
                                Query: {{ $q }}
                            </span>
                        @else
                            <span class="text-xs text-gray-500">No search filter applied.</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- MOBILE LIST (cards) --}}
            <div class="grid grid-cols-1 gap-3 lg:hidden">
                @forelse($users as $u)
                    <div class="row-card">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="row-v break-words">
                                    {!! nl2br(e($u->vertical_name ?? $u->name)) !!}
                                </div>
                                <div class="text-sm text-gray-700 break-words mt-1">
                                    {{ $u->email }}
                                </div>

                                <div class="mt-2 flex flex-wrap gap-2">
                                    <span class="badge">{{ $u->role_label }}</span>

                                    @if($u->is_active)
                                        <span class="status">
                                            <span class="dot" style="background:#16a34a"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="status">
                                            <span class="dot" style="background:#dc2626"></span>
                                            Disabled
                                        </span>
                                    @endif

                                    @if($u->must_change_password)
                                        <span class="status">
                                            <span class="dot" style="background:#f59e0b"></span>
                                            Must change password
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="shrink-0 flex flex-col gap-2">
                                <a href="{{ route('itadmin.users.edit', $u) }}"
                                   class="btn btn-outline">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('itadmin.users.toggle_active', $u) }}">
                                    @csrf
                                    <button class="btn"
                                            type="submit"
                                            style="background:#fff; border:1px solid rgba(185,28,28,.35); color:#7A1F1F;">
                                        {{ $u->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="row-card text-center text-gray-600">
                        No users found.
                    </div>
                @endforelse
            </div>

            {{-- DESKTOP TABLE --}}
            <div class="card hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead style="background:var(--paper);">
                            <tr>
                                <th class="text-left px-5 py-3 font-extrabold" style="color:var(--ndmu-green);">
                                    Name
                                </th>
                                <th class="text-left px-5 py-3 font-extrabold" style="color:var(--ndmu-green);">
                                    Email
                                </th>
                                <th class="text-left px-5 py-3 font-extrabold" style="color:var(--ndmu-green);">
                                    Role
                                </th>
                                <th class="text-left px-5 py-3 font-extrabold" style="color:var(--ndmu-green);">
                                    Status
                                </th>
                                <th class="text-right px-5 py-3 font-extrabold" style="color:var(--ndmu-green);">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($users as $u)
                                <tr class="border-t hover:bg-gray-50/60 transition"
                                    style="border-color:var(--line);">

                                    {{-- Name --}}
                                    <td class="px-5 py-4 font-extrabold leading-tight text-gray-900">
                                        {!! nl2br(e($u->vertical_name ?? $u->name)) !!}
                                    </td>

                                    {{-- Email --}}
                                    <td class="px-5 py-4 text-gray-700">
                                        <div class="break-words">{{ $u->email }}</div>
                                    </td>

                                    {{-- Role --}}
                                    <td class="px-5 py-4">
                                        <span class="badge">{{ $u->role_label }}</span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @if($u->is_active)
                                                <span class="status">
                                                    <span class="dot" style="background:#16a34a"></span>
                                                    Active
                                                </span>
                                            @else
                                                <span class="status">
                                                    <span class="dot" style="background:#dc2626"></span>
                                                    Disabled
                                                </span>
                                            @endif

                                            @if($u->must_change_password)
                                                <span class="status">
                                                    <span class="dot" style="background:#f59e0b"></span>
                                                    Must change password
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-5 py-4 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-4">
                                            <a href="{{ route('itadmin.users.edit', $u) }}"
                                               class="link"
                                               style="color:var(--ndmu-green);">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('itadmin.users.toggle_active', $u) }}" class="inline">
                                                @csrf
                                                <button class="link"
                                                        type="submit"
                                                        style="color:#7A1F1F;">
                                                    {{ $u->is_active ? 'Disable' : 'Enable' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-gray-600">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div>
                {{ $users->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
