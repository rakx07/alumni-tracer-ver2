<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-900">Alumni Encoding</h2>
                <p class="text-sm text-gray-600">Assisted encoding (Officer / IT Admin).</p>
            </div>
            <a href="{{ route('portal.alumni_encoding.create') }}"
               class="px-4 py-2 rounded bg-[#0B3D2E] text-white hover:bg-[#083325]">
                + Encode Alumni
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto space-y-4">
        @if(session('success'))
            <div class="p-3 rounded bg-green-50 border border-green-200 text-green-800">{{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div class="p-3 rounded bg-yellow-50 border border-yellow-200 text-yellow-800">{{ session('warning') }}</div>
        @endif

        <form class="bg-white border rounded p-3 grid grid-cols-1 sm:grid-cols-3 gap-2" method="GET">
            <input name="search" value="{{ request('search') }}" class="border rounded px-3 py-2" placeholder="Search name/email...">
            <select name="status" class="border rounded px-3 py-2">
                <option value="">All Status</option>
                @foreach(['draft','submitted','validated','needs_revision'] as $st)
                    <option value="{{ $st }}" @selected(request('status')===$st)>{{ ucfirst(str_replace('_',' ',$st)) }}</option>
                @endforeach
            </select>
            <button class="px-4 py-2 rounded bg-gray-900 text-white">Filter</button>
        </form>

        <div class="bg-white border rounded overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-3">Name</th>
                        <th class="text-left p-3">Email</th>
                        <th class="text-left p-3">Status</th>
                        <th class="text-left p-3">Linked User</th>
                        <th class="text-left p-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($alumni as $a)
                        <tr>
                            <td class="p-3 font-medium">{{ $a->full_name }}</td>
                            <td class="p-3 text-gray-600">{{ $a->email ?? '—' }}</td>
                            <td class="p-3"><span class="px-2 py-1 text-xs border rounded">{{ $a->record_status }}</span></td>
                            <td class="p-3 text-gray-600">{{ $a->user?->email ?? '—' }}</td>
                            <td class="p-3">
                                <a class="underline" href="{{ route('portal.alumni_encoding.edit', $a) }}">Edit</a>
                                <span class="mx-2 text-gray-300">|</span>
                                <a class="underline" href="{{ route('portal.alumni_encoding.audit', $a) }}">Audit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="p-4 text-gray-600" colspan="5">No assisted records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $alumni->links() }}
    </div>
</x-app-layout>
