{{-- resources/views/portal/events/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">Manage Events</h2>
                <p class="text-sm text-gray-600">
                    Create, edit, publish, and manage alumni events shown on the public calendar.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('events.calendar') }}"
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                    View Public Calendar
                </a>

                <a href="{{ route('portal.events.create') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white shadow-sm"
                   style="background:#0B3D2E;">
                    Add Event
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

        .panel{
            background:#fff;
            border:1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }

        .section-strip{
            border:1px solid var(--line);
            border-radius: 18px;
            overflow:hidden;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }
        .section-strip-top{
            padding: 16px 18px;
            background: var(--ndmu-green);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }
        .section-strip-left{
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
        .section-title{
            color:#fff;
            font-weight: 900;
            letter-spacing: .2px;
        }
        .section-sub{
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
        .btn-danger{ background: rgba(185,28,28,1); color:#fff; }
        .btn-danger:hover{ filter: brightness(.95); }

        .event-card{
            border:1px solid var(--line);
            border-radius: 18px;
            overflow:hidden;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
            transition: .15s ease;
        }
        .event-card:hover{ box-shadow: 0 16px 34px rgba(2,6,23,.10); transform: translateY(-1px); }

        .event-logo-box{
            position: relative;
            height: 160px;
            display:flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg, rgba(11,61,46,.10), rgba(227,199,122,.25));
            border-right:1px solid var(--line);
        }
        .event-logo-img{
            max-width: 86px;
            max-height: 86px;
            object-fit: contain;
            filter: drop-shadow(0 10px 18px rgba(2,6,23,.15));
        }
        .event-date-badge{
            position:absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            padding: 6px 10px;
            font-size: 11px;
            font-weight: 900;
            border-radius: 999px;
            background: rgba(255,255,255,.95);
            border:1px solid rgba(227,199,122,.85);
            color: var(--ndmu-green);
            white-space: nowrap;
        }
        .event-date-badge span{ font-weight: 700; color: rgba(15,23,42,.65); }

        .event-details{ padding: 16px 16px; }
        .event-title{
            color: var(--ndmu-green);
            font-weight: 900;
            letter-spacing: .2px;
            line-height: 1.2;
        }
        .event-meta{
            margin-top: 6px;
            font-size: 13px;
            color: rgba(15,23,42,.62);
            display:flex;
            flex-wrap:wrap;
            gap: 10px 14px;
        }
        .event-badges{
            margin-top: 10px;
            display:flex;
            flex-wrap:wrap;
            gap: 8px;
        }
        .badge-gold{
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            color: var(--ndmu-green);
            background: rgba(227,199,122,.25);
            border: 1px solid rgba(227,199,122,.75);
        }
        .badge-plain{
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            color: rgba(15,23,42,.78);
            background:#fff;
            border: 1px solid var(--line);
        }
        .badge-status{
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            border: 1px solid transparent;
            white-space: nowrap;
        }
        .status-published{
            background: rgba(34,197,94,.10);
            border-color: rgba(34,197,94,.25);
            color: rgba(22,101,52,1);
        }
        .status-draft{
            background: rgba(234,179,8,.10);
            border-color: rgba(234,179,8,.25);
            color: rgba(133,77,14,1);
        }

        .event-desc{
            margin-top: 10px;
            font-size: 14px;
            color: rgba(15,23,42,.78);
            line-height: 1.65;
        }

        @media (max-width: 640px){
            .event-logo-box{ height: 150px; border-right: none; border-bottom:1px solid var(--line); }
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

            {{-- NDMU strip + search --}}
            <div class="section-strip">
                <div class="section-strip-top">
                    <div class="section-strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="section-title">Events Management</div>
                            <div class="section-sub">Search events by title, organizer, audience, and more.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            Showing: {{ $events->count() }}
                        </span>
                    </div>
                </div>

                <div class="p-4">
                    <form method="GET" class="flex flex-col sm:flex-row gap-2">
                        <input name="q"
                               value="{{ request('q') }}"
                               placeholder="Search events by title, organizer, audience..."
                               class="input" />
                        <button class="btn-ndmu btn-primary" type="submit">Search</button>
                        <a class="btn-ndmu btn-outline" href="{{ route('portal.events.index') }}">Reset</a>
                    </form>
                </div>
            </div>

            {{-- Event list --}}
            <div class="space-y-4">
                @forelse($events as $event)
                    <div class="event-card">
                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-0">

                            {{-- LEFT: logo --}}
                            <div class="sm:col-span-1">
                                <div class="event-logo-box">
                                    <img src="{{ asset('images/ndmu-logo.png') }}"
                                         alt="NDMU Logo"
                                         class="event-logo-img"
                                         onerror="this.style.display='none';">

                                    <div class="event-date-badge">
                                        {{ $event->start_date?->format('M d, Y') ?? '—' }}
                                        @if($event->end_date)
                                            <span>– {{ $event->end_date->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT: details --}}
                            <div class="sm:col-span-4">
                                <div class="event-details">
                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h3 class="event-title text-lg sm:text-xl">
                                                    {{ $event->title }}
                                                </h3>

                                                <span class="badge-status {{ $event->is_published ? 'status-published' : 'status-draft' }}">
                                                    {{ $event->is_published ? 'Published' : 'Draft' }}
                                                </span>

                                                @if(!empty($event->is_featured))
                                                    <span class="badge-gold">Featured</span>
                                                @endif
                                            </div>

                                            <div class="event-meta">
                                                @if($event->organizer)
                                                    <span><span class="font-semibold">Organizer:</span> {{ $event->organizer }}</span>
                                                @endif

                                                @if($event->location)
                                                    <span>📍 {{ $event->location }}</span>
                                                @endif

                                                @if($event->contact_email)
                                                    <span>✉️ {{ $event->contact_email }}</span>
                                                @endif
                                            </div>

                                            <div class="event-badges">
                                                @if($event->type)
                                                    <span class="badge-gold">
                                                        {{ ucwords(str_replace('_',' ', $event->type)) }}
                                                    </span>
                                                @endif

                                                @if($event->audience)
                                                    <span class="badge-plain">{{ $event->audience }}</span>
                                                @endif

                                                @if($event->target_group)
                                                    <span class="badge-plain">{{ $event->target_group }}</span>
                                                @endif
                                            </div>

                                            @if($event->description)
                                                <p class="event-desc">
                                                    {{ \Illuminate\Support\Str::limit($event->description, 220) }}
                                                </p>
                                            @else
                                                <p class="event-desc" style="color: rgba(15,23,42,.60);">
                                                    No description provided. Click edit to add details.
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Actions --}}
                                        <div class="shrink-0 flex flex-wrap gap-2">
                                            <a href="{{ route('portal.events.edit', $event) }}"
                                               class="btn-ndmu btn-outline">
                                                Edit
                                            </a>

                                            @if(Route::has('events.show') && $event->is_published)
                                                <a href="{{ route('events.show', $event) }}"
                                                   target="_blank"
                                                   class="btn-ndmu btn-primary">
                                                    View Public
                                                </a>
                                            @else
                                                <a href="{{ route('events.calendar') }}"
                                                   target="_blank"
                                                   class="btn-ndmu btn-primary">
                                                    Calendar
                                                </a>
                                            @endif

                                            <form method="POST" action="{{ route('portal.events.destroy', $event) }}"
                                                  onsubmit="return confirm('Delete this event? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-ndmu btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="panel p-6 text-gray-600">
                        No events found.
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-2">
                {{ $events->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
