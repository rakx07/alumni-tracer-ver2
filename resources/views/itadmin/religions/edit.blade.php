<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">
                    Edit Religion
                </h2>
                <p class="text-sm text-gray-600">
                    Update religion label (stored in UPPERCASE).
                </p>
            </div>

            <a href="{{ route('itadmin.religions.index') }}"
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
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('itadmin.religions.update', $religion) }}">
                    @csrf
                    @method('PUT')

                    <label class="block text-sm font-medium">
                        Religion Name
                    </label>

                    <input name="name"
                           value="{{ old('name', $religion->name) }}"
                           class="w-full border rounded p-2 mt-1"
                           required>

                    <div class="mt-4 flex items-center gap-2">
                        <button type="submit"
                                class="px-4 py-2 rounded text-white"
                                style="background:#0B3D2E;">
                            Update
                        </button>
                    </div>
                </form>

                <div class="mt-4">
                    <form method="POST"
                          action="{{ route('itadmin.religions.toggle', $religion) }}">
                        @csrf
                        <button class="px-4 py-2 rounded border">
                            {{ $religion->is_active ? 'Disable' : 'Enable' }}
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
