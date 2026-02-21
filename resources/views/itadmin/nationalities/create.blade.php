<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">Add Nationality</h2>
                <p class="text-sm text-gray-600">Creates a selectable nationality (stored uppercase).</p>
            </div>
            <a href="{{ route('itadmin.nationalities.index') }}"
               class="inline-flex items-center px-4 py-2 rounded font-semibold border"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 px-4">
            <div class="bg-white border rounded-lg p-4">
                @if ($errors->any())
                    <div class="p-3 mb-3 rounded border border-red-200 bg-red-50 text-red-800 text-sm">
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('itadmin.nationalities.store') }}">
                    @csrf

                    <label class="block text-sm font-medium">Nationality Name</label>
                    <input name="name"
                           value="{{ old('name') }}"
                           class="w-full border rounded p-2 mt-1"
                           placeholder="e.g., FILIPINO" required>

                    <div class="mt-4">
                        <button class="px-4 py-2 rounded text-white" style="background:#0B3D2E;">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
