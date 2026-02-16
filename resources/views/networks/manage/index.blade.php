<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Manage Networks
                </h2>
                <p class="text-sm text-gray-600">
                    Add, edit, and toggle visibility for Alumni Network logos/links.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('networks.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                    View Public Page
                </a>

                <a href="{{ route('portal.networks.manage.create') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm text-white"
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
        }
        .wrap{ background:var(--page); }
        .card{ border:1px solid rgba(15,23,42,.10); border-radius:18px; background:#fff; box-shadow:0 10px 24px rgba(2,6,23,.06); }
        .badge{ padding:4px 10px; border-radius:999px; font-size:12px; font-weight:900; }
        .badge-on{ background:rgba(34,197,94,.10); color:#166534; border:1px solid rgba(34,197,94,.25); }
        .badge-off{ background:rgba(239,68,68,.08); color:#991b1b; border:1px solid rgba(239,68,68,.20); }
        .btn{ display:inline-flex; align-items:center; justify-content:center; padding:8px 12px; border-radius:12px; font-size:12px; font-weight:900; border:1px solid rgba(15,23,42,.12); background:#fff; }
        .btn:hover{ filter:brightness(.98); }
        .btn-primary{ background:var(--ndmu-green); border-color:var(--ndmu-green); color:#fff; }
        .btn-gold{ background:var(--paper); border-color:var(--ndmu-gold); color:var(--ndmu-green); }
        .thumb{ width:44px; height:44px; border-radius:12px; background:var(--paper); border:1px solid rgba(227,199,122,.65); overflow:hidden; display:flex; align-items:center; justify-content:center; }
        .thumb img{ width:100%; height:100%; object-fit:contain; }
        .muted{ color:rgba(15,23,42,.65); font-size:12px; }
        table th{ font-size:12px; letter-spacing:.02em; color:rgba(15,23,42,.65); text-transform:uppercase; }
    </style>

    <div class="py-10 wrap">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="rounded-xl border p-4"
                     style="border-color: rgba(34,197,94,.25); background: rgba(34,197,94,.08);">
                    <div class="font-extrabold text-green-900">{{ session('success') }}</div>
                </div>
            @endif

            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead style="background:#f8fafc;">
                            <tr>
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
                                <tr class="border-t">
                                    <td class="px-4 py-3">
                                        <div class="thumb">
                                            @if($n->logo_url)
                                                <img src="{{ $n->logo_url }}" alt="logo" onerror="this.style.display='none';">
                                            @else
                                                <span class="text-[10px] font-bold text-gray-600">NO LOGO</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-extrabold text-gray-900">{{ $n->title }}</div>
                                        @if($n->description)
                                            <div class="muted line-clamp-2">{{ $n->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ $n->link }}" target="_blank" rel="noopener noreferrer"
                                           class="underline text-sm" style="color:var(--ndmu-green);">
                                            Open ↗
                                        </a>
                                        <div class="muted break-all">{{ $n->link }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($n->is_active)
                                            <span class="badge badge-on">Active</span>
                                        @else
                                            <span class="badge badge-off">Hidden</span>
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

                                            <form method="POST" action="{{ route('portal.networks.manage.destroy', $n) }}"
                                                  onsubmit="return confirm('Delete this network?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn" type="submit" style="border-color:rgba(239,68,68,.25); background:rgba(239,68,68,.06); color:#991b1b;">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-t">
                                    <td class="px-4 py-6 text-center text-gray-600" colspan="6">
                                        No networks found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $networks->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
