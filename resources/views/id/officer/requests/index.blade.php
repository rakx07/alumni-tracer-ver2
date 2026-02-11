<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Alumni ID Requests
                </h2>
                <div class="text-sm text-gray-600">
                    Review, approve, and process Alumni ID requests.
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto space-y-4 px-4">

        @if(session('success'))
            <div class="p-3 rounded border border-green-200 bg-green-50 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border rounded-lg p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-2">
                <input name="search" value="{{ request('search') }}"
                       placeholder="Search name / school id / course..."
                       class="rounded border-gray-300 w-full" />

                <select name="status" class="rounded border-gray-300 w-full">
                    <option value="">All Status</option>
                    @foreach(['PENDING','APPROVED','PROCESSING','DECLINED','READY_FOR_PICKUP','RELEASED'] as $s)
                        <option value="{{ $s }}" {{ request('status')===$s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>

                <select name="type" class="rounded border-gray-300 w-full">
                    <option value="">All Types</option>
                    @foreach(['NEW','LOST','STOLEN','BROKEN'] as $t)
                        <option value="{{ $t }}" {{ request('type')===$t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>

                <button class="px-4 py-2 rounded font-semibold text-white"
                        style="background:#0B3D2E;">
                    Filter
                </button>
            </form>
        </div>

        <div class="bg-white border rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="p-3">ID</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Course</th>
                    <th class="p-3">Grad Year</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Last Action By</th>
                    <th class="p-3">Submitted</th>
                    <th class="p-3"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($requests as $r)
                    <tr class="border-t">
                        <td class="p-3">#{{ $r->id }}</td>
                        <td class="p-3 font-medium">
                            {{ $r->last_name }}, {{ $r->first_name }} {{ $r->middle_name }}
                        </td>
                        <td class="p-3">{{ $r->request_type }}</td>
                        <td class="p-3">{{ $r->course ?? '—' }}</td>
                        <td class="p-3">{{ $r->grad_year ?? '—' }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded border bg-gray-50">
                                {{ $r->status }}
                            </span>
                        </td>
                        <td class="p-3">{{ optional($r->lastActor)->name ?? '—' }}</td>
                        <td class="p-3">{{ optional($r->created_at)->format('M d, Y') }}</td>
                        <td class="p-3 text-right">
                            <a href="{{ route('id.officer.requests.show', $r->id) }}"
                               class="underline" style="color:#0B3D2E;">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-5 text-center text-gray-500" colspan="9">No requests found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $requests->links() }}
        </div>
    </div>
</x-app-layout>
