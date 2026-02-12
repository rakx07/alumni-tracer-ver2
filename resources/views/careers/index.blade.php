{{-- resources/views/careers/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Careers
                </h2>
                <p class="text-sm text-gray-600">
                    Job opportunities for NDMU alumni (Upcoming / Active / Expired)
                </p>
            </div>

            <a href="{{ url('/') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to Home
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

        .link-card{
            border: 1px solid rgba(227,199,122,.80);
            border-radius: 16px;
            background:#fff;
            padding: 16px;
            transition: .15s ease;
            box-shadow: 0 8px 18px rgba(2,6,23,.05);
        }
        .link-card:hover{
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(2,6,23,.08);
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

        /* Careers list styling */
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

        .meta{
            color: rgba(15,23,42,.65);
            font-size: 12px;
            line-height: 1.5;
            margin-top: 4px;
        }
        .title{
            font-weight: 900;
            color: #0f172a;
        }
        .summary{
            margin-top: 10px;
            color: rgba(15,23,42,.72);
            font-size: 13px;
            line-height: 1.6;
        }
        .cta{
            margin-top: 14px;
            display:inline-flex;
            align-items:center;
            gap:8px;
            font-size: 12px;
            font-weight: 900;
            color: var(--ndmu-green);
        }

        .pager a, .pager span{
            font-size: 13px;
        }
    </style>

    <div class="py-10" style="background:var(--page);">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- QUICK ACCESS --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Quick Access</div>
                            <div class="strip-sub">Browse opportunities and open full job details.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            NDMU Alumni Tracer Portal
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="#openings" class="btn-ndmu btn-primary">
                            View Job Openings
                        </a>

                        @auth
                            @if (in_array(auth()->user()->role, ['it_admin','alumni_officer'], true))
                                <a href="{{ route('portal.careers.admin.index') }}" class="btn-ndmu btn-outline">
                                    Manage Careers
                                </a>
                            @endif
                        @endauth

                        <a href="{{ url('/') }}" class="btn-ndmu btn-outline">
                            Back to Home
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
                                Careers & Job Opportunities
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- JOB OPENINGS --}}
            <div class="strip" id="openings">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Job Openings</div>
                            <div class="strip-sub">Click a posting to view full details and attachments.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            Upcoming • Active • Expired
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    @if($posts->count())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($posts as $post)
                                @php
                                    $status = $post->statusLabel();
                                    $badgeClass = $status === 'Active'
                                        ? 'badge-active'
                                        : ($status === 'Upcoming'
                                            ? 'badge-upcoming'
                                            : 'badge-expired');
                                    $dotColor = $status === 'Active'
                                        ? 'background:#10b981;'
                                        : ($status === 'Upcoming'
                                            ? 'background:#3b82f6;'
                                            : 'background:#6b7280;');
                                @endphp

                                <a href="{{ route('careers.show', $post) }}" class="link-card">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="title truncate">{{ $post->title }}</div>

                                            <div class="meta">
                                                {{ $post->company ?: '—' }}
                                                @if($post->location) • {{ $post->location }} @endif
                                                @if($post->employment_type) • {{ $post->employment_type }} @endif
                                            </div>

                                            <div class="meta">
                                                @if($post->start_date || $post->end_date)
                                                    {{ $post->start_date ? $post->start_date->format('M d, Y') : '—' }}
                                                    —
                                                    {{ $post->end_date ? $post->end_date->format('M d, Y') : '—' }}
                                                @else
                                                    Date range not specified
                                                @endif
                                                • {{ $post->attachments_count }} file(s)
                                            </div>
                                        </div>

                                        <span class="status-badge {{ $badgeClass }}">
                                            <span class="dot" style="{{ $dotColor }}"></span>
                                            {{ $status }}
                                        </span>
                                    </div>

                                    @if($post->summary)
                                        <div class="summary">
                                            {{ $post->summary }}
                                        </div>
                                    @endif

                                    <div class="cta">
                                        View details
                                        <span aria-hidden="true">→</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-6 pager">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="rounded-xl border p-4"
                             style="border-color:var(--line); background:var(--paper);">
                            <div class="font-extrabold" style="color:var(--ndmu-green);">No career posts available</div>
                            <div class="text-sm text-gray-600 mt-1">
                                Job opportunities will appear here once posted by the Office of Alumni Relations and ICT.
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
