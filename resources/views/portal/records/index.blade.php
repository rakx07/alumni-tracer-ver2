<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Alumni Records
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="p-3 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-4 shadow rounded">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="q"
                        class="w-full border rounded px-3 py-2"
                        placeholder="Search name or email"
                        value="{{ request('q') }}">
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">
                        Search
                    </button>
                </form>
            </div>

            <div class="bg-white shadow rounded overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">ID</th>
                            <th class="p-3 text-left">Full Name</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Created</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $a)
                            <tr class="border-t">
                                <td class="p-3">{{ $a->id }}</td>
                                <td class="p-3">{{ $a->full_name }}</td>
                                <td class="p-3">{{ $a->email }}</td>
                                <td class="p-3">{{ $a->created_at->format('Y-m-d') }}</td>
                                <td class="p-3 space-x-2 whitespace-nowrap">

                                    <a href="{{ route('portal.records.show', $a) }}"
                                       class="text-blue-600 hover:underline">View</a>

                                    <a href="{{ route('portal.records.edit', $a) }}"
                                       class="text-indigo-600 hover:underline">Edit</a>

                                    <a href="{{ route('portal.records.pdf', $a) }}"
                                       class="text-gray-700 hover:underline">PDF</a>

                                    <a href="{{ route('portal.records.excel', $a) }}"
                                       class="text-gray-700 hover:underline">Excel</a>

                                    @if(auth()->user()->role === 'it_admin')
                                        <form method="POST"
                                              action="{{ route('portal.records.destroy', $a) }}"
                                              class="inline"
                                              onsubmit="return confirm('Soft delete this record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:underline">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">
                                    No records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $records->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
