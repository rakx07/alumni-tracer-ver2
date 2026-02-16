{{-- resources/views/portal/networks/manage/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="min-w-0">
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Manage Networks
                </h2>
                <p class="text-sm text-gray-600">
                    Add, edit, and toggle visibility for Alumni Network logos/links.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('networks.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                    View Public Page
                </a>

                <a href="{{ route('portal.networks.manage.create') }}"
                   class="inline-flex items-center justify-center px-4 py-2 rounded-lg font-semibold border shadow-sm text-white"
                   style="border-color:#0B3D2E; background:#0B3D2E;">
                    + Add Network
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
            --text:#0f172a;
        }

        .page-wrap{ background:var(--page); }

        /* NDMU strip */
        .strip{
            border:1px solid var(--line);
            border-radius: 18px;
            overflow:hidden;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
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

        /* Buttons */
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
        .btn:hover{ filter:brightness(.98); transform: translateY(-.5px); }
        .btn-primary{ background:var(--ndmu-green); border-color:var(--ndmu-green); color:#fff; }
        .btn-gold{ background:var(--paper); border-color:var(--ndmu-gold); color:var(--ndmu-green); }
        .btn-danger{ border-color:rgba(239,68,68,.25); background:rgba(239,68,68,.06); color:#991b1b; }

        /* Badge */
        .badge{ padding:4px 10px; border-radius:999px; font-size:12px; font-weight:900; display:inline-flex; align-items:center; gap:6px; }
        .badge-on{ background:rgba(34,197,94,.10); color:#166534; border:1px solid rgba(34,197,94,.25); }
        .badge-off{ background:rgba(239,68,68,.08); color:#991b1b; border:1px solid rgba(239,68,68,.20); }
        .badge-dot{ width:8px; height:8px; border-radius:999px; }

        /* ✅ Uniform thumbnail (NOW NDMU DARK GREEN) */
        .thumb{
            width:56px;
            height:56px;
            border-radius: 16px;

            background: var(--ndmu-green);
            border:1px solid rgba(227,199,122,.90);

            overflow:hidden;
            display:flex;
            align-items:center;
            justify-content:center;

            position:relative;

            box-shadow:
                0 10px 18px rgba(2,6,23,.10),
                inset 0 0 0 1px rgba(255,255,255,.10);
        }
        .thumb::after{
            content:"";
            position:absolute;
            width:18px;
            height:18px;
            right:-6px;
            top:-6px;
            background: var(--ndmu-gold);
            opacity:.35;
            border-radius: 999px;
            pointer-events:none;
        }
        .thumb img{
            width:100%;
            height:100%;
            object-fit: cover; /* ✅ uniform shape */
            background: transparent;
        }
        .thumb span{
            color: rgba(255,255,255,.92) !important;
        }

        .muted{ color:rgba(15,23,42,.65); font-size:12px; }
        .link{
            color: var(--ndmu-green);
            font-weight: 900;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        /* Desktop table */
        .table-wrap{ border:1px solid rgba(15,23,42,.10); border-radius:18px; background:#fff; overflow:hidden; box-shadow: 0 10px 24px rgba(2,6,23,.06); }
        table th{ font-size:12px; letter-spacing:.02em; color:rgba(15,23,42,.65); text-transform:uppercase; }
        .thead{ background:#FFFBF0; border-bottom: 1px solid var(--line); }
        .row{ border-top:1px solid rgba(15,23,42,.08); }
        .row:hover{ background: rgba(2,6,23,.02); }

        /* Mobile cards */
        .mcard{
            border:1px solid rgba(15,23,42,.10);
            border-radius: 18px;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
            overflow:hidden;
        }
        .mcard-top{
            display:flex;
            align-items:center;
            gap:12px;
            padding: 14px 14px;
            border-bottom:1px solid rgba(15,23,42,.08);
            background: linear-gradient(135deg, rgba(11,61,46,.06), rgba(227,199,122,.18));
        }
        .mcard-body{ padding: 14px; }
        .mgrid{ display:grid; grid-template-columns: 1fr; gap: 10px; }
        .kv{ display:flex; align-items:flex-start; justify-content:space-between; gap:10px; }
        .k{ font-size:12px; color: rgba(15,23,42,.60); font-weight: 800; }
        .v{ font-size:13px; color: rgba(15,23,42,.86); text-align:right; word-break: break-word; }

        @media (min-width: 640px){
            .thumb{ width:52px; height:52px; }
        }
    </style>

    <div class="py-8 page-wrap">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="rounded-xl border p-4"
                     style="border-color: rgba(34,197,94,.25); background: rgba(34,197,94,.08);">
                    <div class="font-extrabold text-green-900">{{ session('success') }}</div>
                </div>
            @endif

            {{-- NDMU Strip Header --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Networks Directory</div>
                            <div class="strip-sub">Manage logos and external links shown on the public Networks page.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            Total: {{ method_exists($networks, 'total') ? $networks->total() : $networks->count() }}
                        </span>
                    </div>
                </div>

                <div class="p-4 text-sm text-gray-600" style="background:#fff;">
                    Tip: Make sure links include <span class="font-semibold">https://</span> so they open correctly (e.g., https://www.facebook.com).
                </div>
            </div>

            {{-- ✅ MOBILE VIEW (cards) --}}
            <div class="space-y-3 sm:hidden">
                @forelse($networks as $n)
                    <div class="mcard">
                        <div class="mcard-top">
                            <div class="thumb">
                                @if($n->logo_url)
                                    <img src="{{ $n->logo_url }}" alt="logo"
                                         onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=&quot;text-[10px] font-extrabold&quot;>NO LOGO</span>';">

                                @else
                                    <span class="text-[10px] font-extrabold">NO LOGO</span>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <div class="font-extrabold text-gray-900 leading-tight">
                                    {{ $n->title }}
                                </div>
                                <div class="mt-1">
                                    @if($n->is_active)
                                        <span class="badge badge-on"><span class="badge-dot" style="background:#16a34a;"></span> Active</span>
                                    @else
                                        <span class="badge badge-off"><span class="badge-dot" style="background:#ef4444;"></span> Hidden</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mcard-body">
                            @if($n->description)
                                <div class="text-sm text-gray-700 leading-relaxed">
                                    {{ $n->description }}
                                </div>
                                <div class="mt-3 h-px" style="background:rgba(15,23,42,.08);"></div>
                            @endif

                            <div class="mt-3 mgrid">
                                <div class="kv">
                                    <div class="k">Link</div>
                                    <div class="v">
                                        <a href="{{ $n->link }}" target="_blank" rel="noopener noreferrer" class="link">
                                            Open ↗
                                        </a>
                                    </div>
                                </div>

                                <div class="kv">
                                    <div class="k">Created by</div>
                                    <div class="v">
                                        {{ $n->createdBy->name ?? '—' }}
                                        <div class="muted">{{ optional($n->created_at)->format('M d, Y h:i A') }}</div>
                                    </div>
                                </div>

                                <div class="kv">
                                    <div class="k">Full URL</div>
                                    <div class="v muted">{{ $n->link }}</div>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <a class="btn btn-gold" href="{{ route('portal.networks.manage.edit', $n) }}">Edit</a>

                                <form method="POST" action="{{ route('portal.networks.manage.toggle', $n) }}">
                                    @csrf
                                    <button class="btn btn-primary" type="submit">
                                        {{ $n->is_active ? 'Hide' : 'Show' }}
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('portal.networks.manage.destroy', $n) }}"
                                      onsubmit="return confirm('Delete this network?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="mcard p-6 text-center text-gray-600">
                        No networks found.
                    </div>
                @endforelse
            </div>

            {{-- ✅ DESKTOP/TABLET VIEW (table) --}}
            <div class="table-wrap hidden sm:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="thead">
                            <tr style="color:var(--ndmu-green);">
                                <th class="text-left px-4 py-3">Logo</th>
                                <th class="text-left px-4 py-3">Title</th>
                                <th class="text-left px-4 py-3">Link</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-left px-4 py-3">Created By</th>
                                <th class="text-left px-4 py-3 w-56">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($networks as $n)
                                <tr class="row">
                                    <td class="px-4 py-3">
                                        <div class="thumb">
                                            @if($n->logo_url)
                                                <img src="{{ $n->logo_url }}" alt="logo"
                                                     onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=&quot;text-[10px] font-extrabold&quot;>NO LOGO</span>';">

                                            @else
                                                <span class="text-[10px] font-extrabold">NO LOGO</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="font-extrabold text-gray-900">{{ $n->title }}</div>
                                        @if($n->description)
                                            <div class="muted">{{ \Illuminate\Support\Str::limit($n->description, 110) }}</div>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        <a href="{{ $n->link }}" target="_blank" rel="noopener noreferrer" class="link">
                                            Open ↗
                                        </a>
                                        <div class="muted break-all">{{ $n->link }}</div>
                                    </td>

                                    <td class="px-4 py-3">
                                        @if($n->is_active)
                                            <span class="badge badge-on">
                                                <span class="badge-dot" style="background:#16a34a;"></span> Active
                                            </span>
                                        @else
                                            <span class="badge badge-off">
                                                <span class="badge-dot" style="background:#ef4444;"></span> Hidden
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $n->createdBy->name ?? '—' }}
                                        </div>
                                        <div class="muted">
                                            {{ optional($n->created_at)->format('M d, Y h:i A') }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <a class="btn btn-gold" href="{{ route('portal.networks.manage.edit', $n) }}">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('portal.networks.manage.toggle', $n) }}">
                                                @csrf
                                                <button class="btn btn-primary" type="submit">
                                                    {{ $n->is_active ? 'Hide' : 'Show' }}
                                                </button>
                                            </form>

                                            <form method="POST"
                                                  action="{{ route('portal.networks.manage.destroy', $n) }}"
                                                  onsubmit="return confirm('Delete this network?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger" type="submit">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="row">
                                    <td class="px-4 py-8 text-center text-gray-600" colspan="6">
                                        No networks found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t" style="border-color:rgba(15,23,42,.08); background:#fff;">
                    {{ $networks->links() }}
                </div>
            </div>

            {{-- Pagination for mobile too --}}
            <div class="sm:hidden">
                {{ $networks->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
