{{-- resources/views/events/calendar.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900">Calendar of Events</h2>
                <p class="text-sm text-gray-600">Upcoming alumni and university-related events</p>
            </div>

            <a href="https://www.ndmu.edu.ph/news-and-updates"
               target="_blank"
               class="inline-flex items-center px-4 py-2 rounded font-semibold border"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                NDMU News & Updates
            </a>
        </div>
    </x-slot>

    <style>
        :root{
            --ndmu-green:#0B3D2E;
            --ndmu-gold:#E3C77A;
            --paper:#FFFBF0;
            --line:#EDE7D1;
        }

        /* Card */
        .event-card{
            border:1px solid var(--line);
            border-radius: 18px;
            overflow:hidden;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
            transition: .15s ease;
        }
        .event-card:hover{ box-shadow: 0 16px 34px rgba(2,6,23,.10); transform: translateY(-1px); }

        /* LEFT (20%) logo box */
        .event-logo-box{
            position: relative;
            height: 180px; /* fixed */
            display:flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg, rgba(11,61,46,.10), rgba(227,199,122,.25));
            border-right:1px solid var(--line);
        }
        .event-logo-img{
            max-width: 92px;
            max-height: 92px;
            object-fit: contain;
            filter: drop-shadow(0 10px 18px rgba(2,6,23,.15));
        }
        .event-date-badge{
            position:absolute;
            bottom: 12px;
            left: 50%;
            transform: translateX(-50%);
            padding: 6px 10px;
            font-size: 11px;
            font-weight: 800;
            border-radius: 999px;
            background: rgba(255,255,255,.95);
            border:1px solid rgba(227,199,122,.85);
            color: var(--ndmu-green);
            white-space: nowrap;
        }
        .event-date-badge span{ font-weight: 600; color: rgba(15,23,42,.65); }

        /* RIGHT (80%) details */
        .event-details{
            height: 100%;
            padding: 18px 18px;
        }
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
            margin-top: 12px;
            display:flex;
            flex-wrap:wrap;
            gap: 8px;
        }
        .badge-gold{
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            color: var(--ndmu-green);
            background: rgba(227,199,122,.25);
            border: 1px solid rgba(227,199,122,.75);
        }
        .badge-plain{
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            color: rgba(15,23,42,.78);
            background:#fff;
            border: 1px solid var(--line);
        }
        .event-desc{
            margin-top: 12px;
            font-size: 14px;
            color: rgba(15,23,42,.78);
            line-height: 1.65;
        }
        .event-actions{
            margin-top: 14px;
            display:flex;
            flex-wrap:wrap;
            gap: 10px;
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
        }
        .btn-primary{
            background: var(--ndmu-green);
            color: #fff;
        }
        .btn-primary:hover{ filter: brightness(.95); }
        .btn-outline{
            background: var(--paper);
            color: var(--ndmu-green);
            border: 1px solid var(--ndmu-gold);
        }
        .btn-outline:hover{ filter: brightness(.98); }
    </style>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-5">

            @forelse($events as $event)
                {{-- Whole card clickable --}}
                <a href="{{ route('events.show', $event) }}" class="block">
                    <div class="event-card">
                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-0">

                            {{-- LEFT 20%: NDMU Logo --}}
                            <div class="sm:col-span-1">
                                <div class="event-logo-box">
                                    <img src="{{ asset('images/ndmu-logo.png') }}"
                                         alt="NDMU Logo"
                                         class="event-logo-img">

                                    <div class="event-date-badge">
                                        {{ $event->start_date?->format('M d, Y') }}
                                        @if($event->end_date)
                                            <span>– {{ $event->end_date->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT 80%: Details inside a rectangle (div) --}}
                            <div class="sm:col-span-4">
                                <div class="event-details">
                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-3">
                                        <div class="min-w-0">
                                            <h3 class="event-title text-lg sm:text-xl">
                                                {{ $event->title }}
                                            </h3>

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
                                                    {{ \Illuminate\Support\Str::limit($event->description, 260) }}
                                                </p>
                                            @else
                                                <p class="event-desc" style="color: rgba(15,23,42,.60);">
                                                    Click to view the full details of this event.
                                                </p>
                                            @endif
                                        </div>

                                        <div class="shrink-0">
                                            <div class="event-actions">
                                                <span class="btn-ndmu btn-primary">
                                                    View Details
                                                </span>

                                                @if($event->registration_link)
                                                    <a href="{{ $event->registration_link }}"
                                                       target="_blank"
                                                       onclick="event.stopPropagation();"
                                                       class="btn-ndmu btn-outline">
                                                        Register
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-xl shadow border p-6 text-gray-600" style="border-color:#EDE7D1;">
                    No upcoming events at this time.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
