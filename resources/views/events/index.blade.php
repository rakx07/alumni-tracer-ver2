{{-- resources/views/events/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Events & Updates
                </h2>
                <p class="text-sm text-gray-600">
                    Official sources and calendar of alumni events
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to Dashboard
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
    </style>

    <div class="py-10" style="background:var(--page);">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- OFFICIAL UPDATES --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Official University Updates</div>
                            <div class="strip-sub">Verified sources for announcements, posters, and advisories.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            NDMU Official Sources
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <a href="https://www.ndmu.edu.ph/news-and-updates"
                           target="_blank" rel="noopener"
                           class="link-card">
                            <div class="flex items-start gap-3">
                                <div class="text-2xl">🌐</div>
                                <div class="min-w-0">
                                    <div class="font-extrabold text-gray-900">
                                        NDMU News & Updates
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        Official announcements from the NDMU website.
                                    </div>
                                    <div class="mt-3 inline-flex items-center gap-2 text-xs font-semibold"
                                         style="color:#0B3D2E;">
                                        <span class="inline-block h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        opens in a new tab
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="https://www.facebook.com/ndmuofficial/"
                           target="_blank" rel="noopener"
                           class="link-card">
                            <div class="flex items-start gap-3">
                                <div class="text-2xl">📘</div>
                                <div class="min-w-0">
                                    <div class="font-extrabold text-gray-900">
                                        NDMU Official Facebook Page
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        Real-time updates, posters, and announcements.
                                    </div>
                                    <div class="mt-3 inline-flex items-center gap-2 text-xs font-semibold"
                                         style="color:#0B3D2E;">
                                        <span class="inline-block h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        opens in a new tab
                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                </div>
            </div>

            {{-- CALENDAR --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Calendar of Events</div>
                            <div class="strip-sub">Upcoming alumni activities and university-related events.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            Office of Alumni Relations
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">
                        View upcoming alumni activities and university-related events managed by the Office of Alumni Relations.
                    </p>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('events.calendar') }}"
                           class="btn-ndmu btn-primary">
                            View Calendar of Events
                        </a>

                        <a href="{{ route('dashboard') }}"
                           class="btn-ndmu btn-outline">
                            Back to Dashboard
                        </a>
                    </div>

                    {{-- Small “seal” block (optional) --}}
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
                                Alumni Tracer Portal
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
