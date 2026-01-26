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
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900">{{ $event->title }}</h2>
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
               class="inline-flex items-center px-4 py-2 rounded font-semibold border"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to Calendar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- POSTER --}}
            @if($posterUrl)
                <div class="bg-white rounded-2xl shadow border overflow-hidden"
                     style="border-color:#EDE7D1;">

                    @if($isPdf)
                        <div class="p-5 flex items-center justify-between gap-4">
                            <div>
                                <div class="text-sm font-semibold" style="color:#0B3D2E;">
                                    Event Poster
                                </div>
                                <div class="text-xs text-gray-600 mt-1">
                                    PDF document uploaded
                                </div>
                            </div>

                            <button type="button"
                                    onclick="openPosterModal('{{ $posterUrl }}','pdf')"
                                    class="px-4 py-2 rounded font-semibold text-white"
                                    style="background:#0B3D2E;">
                                View Poster (PDF)
                            </button>
                        </div>
                    @else
                        <img src="{{ $posterUrl }}"
                             class="w-full max-h-[520px] object-cover cursor-pointer"
                             alt="Event Poster"
                             onclick="openPosterModal('{{ $posterUrl }}','image')">

                        <div class="px-4 py-2 text-xs text-gray-600"
                             style="background:#FFFBF0; border-top:1px solid #EDE7D1;">
                            Click image to enlarge
                        </div>
                    @endif
                </div>
            @endif

            {{-- DETAILS --}}
            <div class="bg-white rounded-2xl shadow border p-6"
                 style="border-color:#EDE7D1;">

                <div class="flex flex-wrap gap-2">
                    @if($event->type)
                        <span class="px-3 py-1 rounded-full text-xs font-bold"
                              style="background:rgba(227,199,122,.25);
                                     border:1px solid rgba(227,199,122,.65);
                                     color:#0B3D2E;">
                            {{ ucwords(str_replace('_',' ', $event->type)) }}
                        </span>
                    @endif

                    @if($event->audience)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold border"
                              style="border-color:#EDE7D1;">
                            {{ $event->audience }}
                        </span>
                    @endif

                    @if($event->target_group)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold border"
                              style="border-color:#EDE7D1;">
                            {{ $event->target_group }}
                        </span>
                    @endif
                </div>

                <div class="mt-4 text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $event->description ?: 'No description provided.' }}
                </div>

                <div class="mt-5 border-t pt-4 text-sm text-gray-700 space-y-2"
                     style="border-color:#EDE7D1;">
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
                    <div class="mt-6">
                        <a href="{{ $event->registration_link }}"
                           target="_blank"
                           class="inline-flex px-5 py-2 rounded-lg text-sm font-bold text-white"
                           style="background:#0B3D2E;">
                            Register / Learn More
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="posterModal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70">
        <div class="relative bg-white rounded-xl shadow-xl max-w-5xl w-full mx-4">
            <button onclick="closePosterModal()"
                    class="absolute top-3 right-3 text-2xl font-bold text-gray-600">
                &times;
            </button>

            <div id="posterModalContent" class="p-4 max-h-[85vh] overflow-auto"></div>
        </div>
    </div>

    <script>
        function openPosterModal(url, type) {
            const modal = document.getElementById('posterModal');
            const content = document.getElementById('posterModalContent');

            content.innerHTML = type === 'pdf'
                ? `<iframe src="${url}" style="width:100%; height:75vh;" frameborder="0"></iframe>`
                : `<img src="${url}" style="width:100%; height:auto;">`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePosterModal() {
            const modal = document.getElementById('posterModal');
            document.getElementById('posterModalContent').innerHTML = '';
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('posterModal').addEventListener('click', e => {
            if (e.target.id === 'posterModal') closePosterModal();
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closePosterModal();
        });
    </script>
</x-app-layout>
