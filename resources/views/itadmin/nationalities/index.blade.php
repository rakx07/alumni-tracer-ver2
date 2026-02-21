<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">Nationalities</h2>
                <p class="text-sm text-gray-600">Manage selectable nationalities for the intake form.</p>
            </div>

            <a href="{{ route('itadmin.nationalities.create') }}"
               class="inline-flex items-center px-4 py-2 rounded font-semibold border shadow-sm"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                + Add Nationality
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 px-4 space-y-4">

            @if(session('success'))
                <div class="p-3 rounded border border-green-200 bg-green-50 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border rounded-lg p-4">
                <form method="GET" class="flex flex-col sm:flex-row gap-2">
                    <input name="q" value="{{ $q ?? '' }}"
                           placeholder="Search nationality..."
                           class="rounded border-gray-300 w-full" />
                    <button class="px-4 py-2 rounded bg-gray-800 text-white">Search</button>
                </form>
            </div>

            <div class="bg-white border rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left p-3">Name</th>
                                <th class="text-left p-3">Status</th>
                                <th class="text-right p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $row)
                                <tr class="border-t">
                                    <td class="p-3 font-semibold">{{ $row->name }}</td>
                                    <td class="p-3">
                                        @if($row->is_active)
                                            <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">ACTIVE</span>
                                        @else
                                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs font-semibold">INACTIVE</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-right whitespace-nowrap">
                                        <a href="{{ route('itadmin.nationalities.edit', $row) }}"
                                           class="px-3 py-1 rounded border">Edit</a>

                                        <form method="POST" action="{{ route('itadmin.nationalities.toggle', $row) }}"
                                              class="inline">
                                            @csrf
                                            <button class="px-3 py-1 rounded border">
                                                {{ $row->is_active ? 'Disable' : 'Enable' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="p-4 text-gray-500" colspan="3">No records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    {{ $rows->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
