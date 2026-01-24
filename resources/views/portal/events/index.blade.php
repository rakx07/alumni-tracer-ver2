<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl">Manage Events</h2>
            <a href="{{ route('portal.events.create') }}"
               class="px-4 py-2 bg-green-700 text-white rounded">
                Add Event
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto space-y-4">
        @foreach($events as $event)
            <div class="bg-white border rounded p-4 flex justify-between">
                <div>
                    <div class="font-semibold">{{ $event->title }}</div>
                    <div class="text-sm text-gray-600">
                        {{ $event->start_date->format('M d, Y') }}
                    </div>
                </div>
                <div class="space-x-2">
                    <a href="{{ route('portal.events.edit', $event) }}" class="underline">Edit</a>
                </div>
            </div>
        @endforeach

        {{ $events->links() }}
    </div>
</x-app-layout>
