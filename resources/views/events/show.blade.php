{{-- resources/views/events/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900">{{ $event->title }}</h2>
                <p class="text-sm text-gray-600">
                    {{ $event->start_date->format('F d, Y') }}
                    @if($event->end_date) – {{ $event->end_date->format('F d, Y') }} @endif
                    @if($event->location) • {{ $event->location }} @endif
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

            @if($event->poster_path)
                <div class="bg-white rounded-2xl shadow border overflow-hidden" style="border-color:#EDE7D1;">
                    <img src="{{ asset('storage/'.$event->poster_path) }}" class="w-full max-h-[520px] object-cover" alt="Event Poster">
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow border p-6" style="border-color:#EDE7D1;">
                <div class="flex flex-wrap gap-2">
                    @if($event->type)
                        <span class="px-3 py-1 rounded-full text-xs font-bold"
                              style="background:rgba(227,199,122,.25); border:1px solid rgba(227,199,122,.65); color:#0B3D2E;">
                            {{ ucwords(str_replace('_',' ', $event->type)) }}
                        </span>
                    @endif
                    @if($event->audience)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold border"
                              style="border-color:#EDE7D1; background:#fff;">
                            {{ $event->audience }}
                        </span>
                    @endif
                    @if($event->target_group)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold border"
                              style="border-color:#EDE7D1; background:#fff;">
                            {{ $event->target_group }}
                        </span>
                    @endif
                </div>

                <div class="mt-4 text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $event->description ?: 'No description provided.' }}
                </div>

                <div class="mt-5 border-t pt-4 text-sm text-gray-700 space-y-2" style="border-color:#EDE7D1;">
                    @if($event->organizer)
                        <div><span class="font-semibold">Organizer:</span> {{ $event->organizer }}</div>
                    @endif
                    @if($event->location)
                        <div><span class="font-semibold">Location:</span> {{ $event->location }}</div>
                    @endif
                    @if($event->contact_email)
                        <div><span class="font-semibold">Contact Email:</span> {{ $event->contact_email }}</div>
                    @endif
                </div>

                @if($event->registration_link)
                    <div class="mt-6">
                        <a href="{{ $event->registration_link }}" target="_blank"
                           class="inline-flex items-center justify-center px-5 py-2 rounded-lg text-sm font-bold"
                           style="background:#0B3D2E; color:#fff;">
                            Register / Learn More
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
