{{-- resources/views/portal/careers_admin/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Careers / Job Opportunities
                </h2>
                <p class="text-sm text-gray-600">
                    Manage job posts visible to alumni (with Upcoming/Active/Expired labels).
                </p>
            </div>

            <a href="{{ route('portal.careers.admin.create') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                + New Job Post
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
        .pill-dot{
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--ndmu-green);
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

        .table-wrap{
            border: 1px solid rgba(227,199,122,.55);
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }
        table{ width:100%; border-collapse: collapse; }
        thead th{
            background: #f8fafc;
            font-size: 12px;
            letter-spacing: .4px;
            text-transform: uppercase;
            color: rgba(15,23,42,.70);
            padding: 12px 16px;
            border-bottom: 1px solid rgba(15,23,42,.08);
            white-space: nowrap;
        }
        tbody td{
            padding: 14px 16px;
            border-bottom: 1px solid rgba(15,23,42,.08);
            vertical-align: top;
        }
        tbody tr:hover{ background: rgba(255,251,240,.55); }

        .title{
            font-weight: 900;
            color: #0f172a;
        }
        .meta{
            font-size: 12px;
            color: rgba(15,23,42,.60);
            margin-top: 4px;
            line-height: 1.45;
        }

        .status-badge{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 7px 10px;
            border-radius: 999px;
            border:1px solid rgba(15,23,42,.14);
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
        }
        .dot{ width:8px;height:8px;border-radius:999px;display:inline-block; }

        .badge-active{ background: rgba(16,185,129,.10); color:#065f46; border-color: rgba(16,185,129,.25); }
        .badge-upcoming{ background: rgba(59,130,246,.10); color:#1e3a8a; border-color: rgba(59,130,246,.25); }
        .badge-expired{ background: rgba(107,114,128,.10); color:#111827; border-color: rgba(107,114,128,.22); }
        .badge-hidden{ background: rgba(234,179,8,.14); color:#854d0e; border-color: rgba(234,179,8,.35); }

        .action-link{
            font-size: 13px;
            font-weight: 900;
            color: var(--ndmu-green);
            text-decoration: underline;
        }
        .action-danger{
            font-size: 13px;
            font-weight: 900;
            color: #b91c1c;
            text-decoration: underline;
        }

        .pager a, .pager span{ font-size: 13px; }
    </style>

    <div class="py-10" style="background:var(--page);">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-xl border p-4"
                     style="border-color: rgba(16,185,129,.25); background: rgba(16,185,129,.07);">
                    <div class="font-extrabold text-green-900">{{ session('success') }}</div>
                </div>
            @endif

            {{-- Header strip --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Manage Career Posts</div>
                            <div class="strip-sub">Create, edit, and remove job posts and their attachments.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            Alumni Officer / IT Admin
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('portal.careers.admin.create') }}" class="btn-ndmu btn-primary">
                            + New Job Post
                        </a>
                        <a href="{{ route('careers.index') }}" class="btn-ndmu btn-outline">
                            View Public Careers
                        </a>
                    </div>

                    <div class="mt-6 flex items-center gap-3 rounded-xl border p-4"
                         style="border-color:var(--line); background:var(--paper);">
                        <img src="{{ asset('images/ndmu-logo.png') }}"
                             alt="NDMU Logo"
                             class="h-10 w-10 rounded-full ring-2"
                             style="--tw-ring-color: var(--ndmu-gold);"
                             onerror="this.style.display='none';">
                        <div class="min-w-0">
                            <div class="text-xs font-semibold tracking-wide text-gray-600">
                                NOTRE DAME OF MARBEL UNIVERSITY
                            </div>
                            <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                                Careers Admin
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-wrap">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="text-left">Title</th>
                                <th class="text-left">Company</th>
                                <th class="text-left">Date Range</th>
                                <th class="text-left">Status</th>
                                <th class="text-left">Files</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                                @php
                                    $status = $post->statusLabel();
                                    $badgeClass = $status === 'Active'
                                        ? 'badge-active'
                                        : ($status === 'Upcoming'
                                            ? 'badge-upcoming'
                                            : ($status === 'Expired'
                                                ? 'badge-expired'
                                                : 'badge-hidden'));
                                    $dotColor = $status === 'Active'
                                        ? 'background:#10b981;'
                                        : ($status === 'Upcoming'
                                            ? 'background:#3b82f6;'
                                            : ($status === 'Expired'
                                                ? 'background:#6b7280;'
                                                : 'background:#eab308;'));
                                @endphp

                                <tr>
                                    <td>
                                        <div class="title">{{ $post->title }}</div>
                                        <div class="meta">
                                            {{ $post->employment_type ?: '—' }} • {{ $post->location ?: '—' }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="title" style="font-weight:800;">{{ $post->company ?: '—' }}</div>
                                    </td>

                                    <td>
                                        <div class="title" style="font-weight:800;">
                                            {{ $post->start_date ? $post->start_date->format('M d, Y') : '—' }}
                                        </div>
                                        <div class="meta">
                                            {{ $post->end_date ? $post->end_date->format('M d, Y') : '—' }}
                                        </div>
                                    </td>

                                    <td>
                                        <span class="status-badge {{ $badgeClass }}">
                                            <span class="dot" style="{{ $dotColor }}"></span>
                                            {{ $status }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="title" style="font-weight:800;">
                                            {{ $post->attachments_count }}
                                        </div>
                                        <div class="meta">attachments</div>
                                    </td>

                                    <td class="text-right whitespace-nowrap">
                                        <a href="{{ route('portal.careers.admin.edit', $post) }}" class="action-link">
                                            Edit
                                        </a>

                                        <span class="mx-2 text-gray-300">|</span>

                                        <form action="{{ route('portal.careers.admin.destroy', $post) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Delete this post? This will remove its attachments too.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center" style="padding: 24px 16px;">
                                        <div class="text-sm text-gray-600 font-semibold">
                                            No career posts yet.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pager">
                {{ $posts->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
