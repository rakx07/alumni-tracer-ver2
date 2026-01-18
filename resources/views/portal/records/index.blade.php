{{-- resources/views/portals/records/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Alumni Records
                </h2>
                <div class="text-sm text-gray-600">
                    Search, filter, and manage alumni intake records.
                </div>
            </div>
        </div>
    </x-slot>

    @php
        // Keep form values persistent
        $q = request('q');
        $field = request('field', 'all');   // all|name|email|id
        $from = request('from');           // YYYY-MM-DD
        $to = request('to');               // YYYY-MM-DD
        $perPage = request('per_page', 10);

        // Small helper for building query strings in pagination links
        $append = request()->only(['q','field','from','to','per_page']);
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="p-3 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- FILTER BAR --}}
{{-- FILTER BAR --}}
<div class="bg-white shadow rounded border border-gray-100">
    <div class="p-4">
        <form method="GET" id="filtersForm">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-end">

                {{-- Search --}}
                <div class="lg:col-span-3">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Search
                    </label>
                    <input
                        type="text"
                        name="q"
                        id="q"
                        class="w-full border rounded px-3 py-2 h-10"
                        placeholder="Type to search..."
                        value="{{ request('q') }}"
                    >
                </div>

                {{-- Search Field --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Search in
                    </label>
                    <select
                        name="field"
                        id="field"
                        class="w-full border rounded px-3 py-2 h-10"
                    >
                        <option value="all">All fields</option>
                        <option value="name" {{ request('field')==='name'?'selected':'' }}>Full name</option>
                        <option value="email" {{ request('field')==='email'?'selected':'' }}>Email</option>
                        <option value="id" {{ request('field')==='id'?'selected':'' }}>Record ID</option>
                    </select>
                </div>

                {{-- Created From --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Created from
                    </label>
                    <input
                        type="date"
                        name="from"
                        id="from"
                        class="w-full border rounded px-3 py-2 h-10"
                        value="{{ request('from') }}"
                    >
                </div>

                {{-- Created To --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Created to
                    </label>
                    <input
                        type="date"
                        name="to"
                        id="to"
                        class="w-full border rounded px-3 py-2 h-10"
                        value="{{ request('to') }}"
                    >
                </div>

                {{-- Rows --}}
                <div class="lg:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        Rows
                    </label>
                    <select
                        name="per_page"
                        id="per_page"
                        class="w-full border rounded px-3 py-2 h-10"
                    >
                        @foreach([10,25,50,100] as $n)
                            <option value="{{ $n }}" {{ request('per_page',10)==$n?'selected':'' }}>
                                {{ $n }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="lg:col-span-2 flex gap-2">
                    <button
                        type="submit"
                        class="h-10 px-4 bg-gray-900 text-white rounded hover:bg-gray-800 w-full"
                    >
                        Apply
                    </button>

                    <a
                        href="{{ route('portal.records.index') }}"
                        class="h-10 px-4 bg-gray-100 text-gray-800 rounded border hover:bg-gray-200 w-full text-center flex items-center justify-center"
                    >
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>


            {{-- TABLE --}}
            <div class="bg-white shadow rounded border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr class="text-gray-700">
                                <th class="p-3 text-left font-semibold">ID</th>
                                <th class="p-3 text-left font-semibold">Full Name</th>
                                <th class="p-3 text-left font-semibold">Email</th>
                                <th class="p-3 text-left font-semibold">Created</th>
                                <th class="p-3 text-left font-semibold">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($records as $a)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 font-medium text-gray-900">{{ $a->id }}</td>

                                    <td class="p-3">
                                        <div class="font-semibold text-gray-900">{{ $a->full_name }}</div>
                                        <div class="text-xs text-gray-500">
                                            Contact: {{ $a->contact_number ?: '—' }}
                                        </div>
                                    </td>

                                    <td class="p-3">
                                        <div class="text-gray-900">{{ $a->email ?: '—' }}</div>
                                        <div class="text-xs text-gray-500">
                                            Nationality: {{ $a->nationality ?: '—' }}
                                        </div>
                                    </td>

                                    <td class="p-3 text-gray-700 whitespace-nowrap">
                                        {{ optional($a->created_at)->format('Y-m-d') }}
                                    </td>

                                    <td class="p-3 whitespace-nowrap">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('portal.records.show', $a) }}"
                                               class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded border border-blue-100 hover:bg-blue-100">
                                                View
                                            </a>

                                            <a href="{{ route('portal.records.edit', $a) }}"
                                               class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded border border-indigo-100 hover:bg-indigo-100">
                                                Edit
                                            </a>

                                            <a href="{{ route('portal.records.pdf', $a) }}"
                                               class="px-3 py-1.5 bg-gray-50 text-gray-700 rounded border hover:bg-gray-100">
                                                PDF
                                            </a>

                                            <a href="{{ route('portal.records.excel', $a) }}"
                                               class="px-3 py-1.5 bg-gray-50 text-gray-700 rounded border hover:bg-gray-100">
                                                Excel
                                            </a>

                                            @if(auth()->user()->role === 'it_admin')
                                                <form method="POST"
                                                      action="{{ route('portal.records.destroy', $a) }}"
                                                      class="inline"
                                                      onsubmit="return confirm('Soft delete this record?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1.5 bg-red-50 text-red-700 rounded border border-red-100 hover:bg-red-100">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-gray-500">
                                        No records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-4 border-t bg-white">
                    {{ $records->appends($append)->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- “Dynamic” filtering: auto-submit after typing / changing filters (no AJAX needed) --}}
    <script>
        (function () {
            const form = document.getElementById('filtersForm');
            if (!form) return;

            const q = document.getElementById('q');
            const field = document.getElementById('field');
            const from = document.getElementById('from');
            const to = document.getElementById('to');
            const perPage = document.getElementById('per_page');

            // Debounced auto-submit for the search box
            let t = null;
            if (q) {
                q.addEventListener('input', function () {
                    clearTimeout(t);
                    t = setTimeout(() => form.submit(), 500); // submit after user stops typing
                });
            }

            // Auto-submit when changing dropdowns/dates
            [field, from, to, perPage].forEach(el => {
                if (!el) return;
                el.addEventListener('change', () => form.submit());
            });
        })();
    </script>
</x-app-layout>
