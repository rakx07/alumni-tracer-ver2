<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Alumni Record #{{ $alumnus->id }}
            </h2>
            <a href="{{ route('portal.records.show', $alumnus) }}" class="px-4 py-2 bg-gray-800 text-white rounded">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="p-4 mb-4 bg-red-100 border border-red-300 rounded">
                    <div class="font-semibold mb-2">Please fix the following:</div>
                    <ul class="list-disc ml-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="p-3 mb-4 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('portal.records.update', $alumnus) }}">
                @csrf
                @method('PUT')

                {{-- Reuse the exact intake form UI --}}
                @include('user._intake_form', ['alumnus' => $alumnus])

                <div class="mt-4">
                    <button class="px-5 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
