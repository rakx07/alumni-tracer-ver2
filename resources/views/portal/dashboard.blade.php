<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Portal Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="p-6 bg-white shadow rounded">
                <div class="text-lg font-semibold">
                    Welcome, {{ auth()->user()->name }}
                </div>
                <div class="text-sm text-gray-600 mt-1">
                    Role: <b>{{ auth()->user()->role }}</b>
                </div>

                <div class="mt-4 flex gap-2">
                    <a href="{{ route('portal.records.index') }}"
                       class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Manage Alumni Records
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
