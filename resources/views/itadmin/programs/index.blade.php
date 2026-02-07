<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Programs</h2>
            <div class="flex gap-2">
                <a href="{{ route('itadmin.programs.upload_form') }}"
                   class="px-3 py-2 bg-gray-700 text-white rounded">
                    Upload
                </a>
                <a href="{{ route('itadmin.programs.create') }}"
                   class="px-3 py-2 bg-green-700 text-white rounded">
                    + Add
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

        @if(session('success'))
            <div class="p-3 bg-green-100 border border-green-300 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form class="bg-white border rounded p-4 flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs text-gray-600">Category</label>
                <select name="category" class="border rounded p-2 text-sm">
                    <option value="">All</option>
                    <option value="college" {{ request('category')==='college'?'selected':'' }}>College</option>
                    <option value="grad_school" {{ request('category')==='grad_school'?'selected':'' }}>Graduate School</option>
                    <option value="law" {{ request('category')==='law'?'selected':'' }}>Law</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-600">Status</label>
                <select name="status" class="border rounded p-2 text-sm">
                    <option value="">All</option>
                    <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
                </select>
            </div>

            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs text-gray-600">Search</label>
                <input name="search" value="{{ request('search') }}"
                       class="w-full border rounded p-2 text-sm"
                       placeholder="Program name or code">
            </div>

            <button class="px-4 py-2 bg-gray-800 text-white rounded">
                Filter
            </button>
        </form>

        <div class="bg-white border rounded overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left">Category</th>
                        <th class="p-3 text-left">Code</th>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left w-44">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($programs as $p)
                        <tr class="border-t">
                            <td class="p-3">{{ $p->category }}</td>
                            <td class="p-3">{{ $p->code ?: '—' }}</td>
                            <td class="p-3">{{ $p->name }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs {{ $p->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                    {{ $p->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="p-3 flex gap-3 items-center">
                                <a href="{{ route('itadmin.programs.edit', $p) }}" class="underline">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('itadmin.programs.toggle', $p) }}">
                                    @csrf
                                    <button type="submit" class="underline text-red-700">
                                        {{ $p->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-4 text-gray-500" colspan="5">No programs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $programs->links() }}
    </div>
</x-app-layout>
