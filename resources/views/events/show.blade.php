{{-- resources/views/events/show.blade.php --}}
<x-app-layout>

@php
    $posterUrl = !empty($event->poster_path)
        ? asset('storage/'.$event->poster_path)
        : null;

    $ext = $event->poster_path
        ? strtolower(pathinfo($event->poster_path, PATHINFO_EXTENSION))
        : null;

    $isPdf = ($ext === 'pdf');
@endphp

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">{{ $event->title }}</h2>
                <p class="text-sm text-gray-600">
                    {{ optional($event->start_date)->format('F d, Y') }}
                    @if($event->end_date)
                        – {{ optional($event->end_date)->format('F d, Y') }}
                    @endif
                    @if($event->location)
                        • {{ $event->location }}
                    @endif
                </p>
            </div>

            <a href="{{ route('events.calendar') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to Calendar
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
            --text:#0f172a;
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

        .badge{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
        }
        .badge-gold{
            background: rgba(227,199,122,.25);
            border:1px solid rgba(227,199,122,.70);
            color: var(--ndmu-green);
        }
        .badge-plain{
            background:#fff;
            border:1px solid var(--line);
            color: rgba(15,23,42,.78);
            font-weight: 800;
        }
        .badge-dot{
            width:8px; height:8px; border-radius:999px; background: var(--ndmu-green);
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

        .poster-img{
            width:100%;
            max-height: 520px;
            object-fit: cover;
            cursor: pointer;
        }
        .hint{
            font-size: 12px;
            color: rgba(15,23,42,.62);
            background: var(--paper);
            border-top: 1px solid var(--line);
            padding: 10px 14px;
        }

        /* Modal */
        .modal-backdrop{
            position: fixed;
            inset: 0;
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,.72);
            padding: 16px;
        }
        .modal-backdrop.show{ display:flex; }

        .modal-card{
            width: 100%;
            max-width: 1100px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(0,0,0,.30);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.35);
        }
        .modal-head{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap: 12px;
            padding: 12px 14px;
            background: var(--ndmu-green);
            color:#fff;
        }
        .modal-head .title{
            font-weight: 900;
            font-size: 13px;
            letter-spacing: .2px;
            display:flex;
            align-items:center;
            gap:10px;
        }
        .modal-head .title .bar{
            width:6px;
            height:22px;
            border-radius:999px;
            background: var(--ndmu-gold);
        }
        .modal-close{
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: rgba(255,255,255,.14);
            color:#fff;
            font-size: 22px;
            line-height: 1;
            display:flex;
            align-items:center;
            justify-content:center;
            border: 1px solid rgba(255,255,255,.18);
            transition: .15s ease;
        }
        .modal-close:hover{ filter: brightness(1.06); }

        .modal-body{
            padding: 12px;
            max-height: 82vh;
            overflow: auto;
            background: #fff;
        }
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- NDMU strip (summary) --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Event Details</div>
                            <div class="strip-sub">
                                {{ optional($event->start_date)->format('F d, Y') ?? 'Date not set' }}
                                @if($event->end_date)
                                    – {{ optional($event->end_date)->format('F d, Y') }}
                                @endif
                                @if($event->location)
                                    • {{ $event->location }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="badge badge-gold">
                            <span class="badge-dot"></span>
                            Public Event
                        </span>
                    </div>
                </div>

                <div class="p-5">
                    <div class="flex flex-wrap gap-2">
                        @if($event->type)
                            <span class="badge badge-gold">
                                {{ ucwords(str_replace('_',' ', $event->type)) }}
                            </span>
                        @endif

                        @if($event->audience)
                            <span class="badge badge-plain">{{ $event->audience }}</span>
                        @endif

                        @if($event->target_group)
                            <span class="badge badge-plain">{{ $event->target_group }}</span>
                        @endif

                        @if($event->organizer)
                            <span class="badge badge-plain">Organizer: {{ $event->organizer }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- POSTER --}}
            @if($posterUrl)
                <div class="panel overflow-hidden">
                    @if($isPdf)
                        <div class="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                                    Event Poster
                                </div>
                                <div class="text-xs text-gray-600 mt-1">
                                    PDF document uploaded
                                </div>
                            </div>

                            <button type="button"
                                    onclick="openPosterModal('{{ $posterUrl }}','pdf')"
                                    class="btn-ndmu btn-primary">
                                View Poster (PDF)
                            </button>
                        </div>
                    @else
                        <img src="{{ $posterUrl }}"
                             class="poster-img"
                             alt="Event Poster"
                             onclick="openPosterModal('{{ $posterUrl }}','image')">

                        <div class="hint">
                            Click image to enlarge • Press <strong>Esc</strong> to close
                        </div>
                    @endif
                </div>
            @endif

            {{-- DETAILS CARD --}}
            <div class="panel p-6">
                <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $event->description ?: 'No description provided.' }}
                </div>

                <div class="mt-5 border-t pt-4 text-sm text-gray-700 space-y-2"
                     style="border-color:var(--line);">
                    @if($event->organizer)
                        <div><strong>Organizer:</strong> {{ $event->organizer }}</div>
                    @endif
                    @if($event->location)
                        <div><strong>Location:</strong> {{ $event->location }}</div>
                    @endif
                    @if($event->contact_email)
                        <div><strong>Contact Email:</strong> {{ $event->contact_email }}</div>
                    @endif
                </div>

                @if($event->registration_link)
                    <div class="mt-6 flex flex-wrap items-center gap-2">
                        <a href="{{ $event->registration_link }}"
                           target="_blank" rel="noopener"
                           class="btn-ndmu btn-primary">
                            Register / Learn More
                        </a>

                        <a href="{{ route('events.calendar') }}"
                           class="btn-ndmu btn-outline">
                            Back to Calendar
                        </a>
                    </div>
                @else
                    <div class="mt-6">
                        <a href="{{ route('events.calendar') }}"
                           class="btn-ndmu btn-outline">
                            Back to Calendar
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="posterModal" class="modal-backdrop" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-label="Event Poster">
            <div class="modal-head">
                <div class="title">
                    <span class="bar"></span>
                    Event Poster Preview
                </div>
                <button type="button" class="modal-close" onclick="closePosterModal()" aria-label="Close">
                    &times;
                </button>
            </div>
            <div id="posterModalContent" class="modal-body"></div>
        </div>
    </div>

    <script>
        function openPosterModal(url, type) {
            const modal = document.getElementById('posterModal');
            const content = document.getElementById('posterModalContent');

            content.innerHTML = type === 'pdf'
                ? `<iframe src="${url}" style="width:100%; height:75vh;" frameborder="0"></iframe>`
                : `<img src="${url}" style="width:100%; height:auto;">`;

            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');

            // Prevent background scroll
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';
        }

        function closePosterModal() {
            const modal = document.getElementById('posterModal');
            document.getElementById('posterModalContent').innerHTML = '';
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');

            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
        }

        document.getElementById('posterModal').addEventListener('click', e => {
            if (e.target.id === 'posterModal') closePosterModal();
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closePosterModal();
        });
    </script>
</x-app-layout>
