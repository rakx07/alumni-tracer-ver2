<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Alumni Record #{{ $alumnus->id }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white p-6 shadow rounded">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-lg font-semibold">{{ $alumnus->full_name }}</div>
                        <div class="text-sm text-gray-600">{{ $alumnus->email }}</div>
                    </div>
                    <div class="space-x-2">
                        <a href="{{ route('portal.records.edit', $alumnus) }}"
                           class="px-4 py-2 bg-indigo-600 text-white rounded">Edit</a>

                        <a href="{{ route('portal.records.index') }}"
                           class="px-4 py-2 bg-gray-700 text-white rounded">Back</a>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                    <div><b>Contact:</b> {{ $alumnus->contact_number }}</div>
                    <div><b>Nationality:</b> {{ $alumnus->nationality }}</div>
                    <div><b>Religion:</b> {{ $alumnus->religion }}</div>
                    <div><b>Facebook:</b> {{ $alumnus->facebook }}</div>
                </div>
            </div>

            <div class="bg-white p-6 shadow rounded">
                <h3 class="font-semibold mb-2">Education Records</h3>

                @forelse($alumnus->educations as $e)
                    <div class="border rounded p-3 mb-2 text-sm">
                        <div><b>Level:</b> {{ $e->level }}</div>
                        <div><b>Years:</b> {{ $e->year_entered }} - {{ $e->year_graduated }}</div>
                        <div><b>Program:</b> {{ $e->degree_program }}</div>
                        <div><b>Track:</b> {{ $e->strand_track }}</div>
                    </div>
                @empty
                    <div class="text-gray-500">No education records.</div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
