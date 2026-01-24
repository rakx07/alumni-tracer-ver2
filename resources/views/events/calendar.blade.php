<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900">
            Calendar of Events
        </h2>
        <p class="text-sm text-gray-600">
            Upcoming alumni and university-related events
        </p>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

        @forelse($events as $event)
            <div class="bg-white rounded-xl shadow border p-5"
                 style="border-color:#EDE7D1;">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold" style="color:#0B3D2E;">
                            {{ $event->title }}
                        </h3>
                        <div class="text-sm text-gray-600 mt-1">
                            {{ $event->start_date->format('F d, Y') }}
                            @if($event->end_date)
                                – {{ $event->end_date->format('F d, Y') }}
                            @endif
                        </div>

                        @if($event->location)
                            <div class="text-sm text-gray-600">
                                📍 {{ $event->location }}
                            </div>
                        @endif

                        @if($event->description)
                            <p class="mt-3 text-sm text-gray-700 leading-relaxed">
                                {{ $event->description }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-gray-600">
                No upcoming events at this time.
            </div>
        @endforelse

    </div>
</x-app-layout>
