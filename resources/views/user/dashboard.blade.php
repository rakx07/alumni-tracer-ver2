<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Alumni Dashboard
            </h2>
            <a href="{{ route('intake.form') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                {{ $alumnus ? 'Update Intake' : 'Fill Intake' }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="p-3 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="p-6 bg-white shadow rounded">
                <div class="text-lg font-semibold">Welcome, {{ auth()->user()->name }}</div>
                <div class="text-sm text-gray-600 mt-1">
                    Please complete your alumni intake form.
                </div>

                <div class="mt-4 text-sm">
                    @if($alumnus)
                        <div><b>Status:</b> Submitted</div>
                        <div><b>Name on record:</b> {{ $alumnus->full_name }}</div>
                        <div><b>Last updated:</b> {{ $alumnus->updated_at }}</div>
                    @else
                        <div><b>Status:</b> Not submitted</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
