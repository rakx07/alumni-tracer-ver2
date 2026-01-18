{{-- resources/views/portal/records/_table.blade.php --}}
@php
    $append = request()->all();

    $currentSort = request('sort', 'created_at');
    $currentDir  = request('dir', 'desc');

    $sortLink = function (string $col) use ($currentSort, $currentDir, $append) {
        $dir = 'asc';
        if ($currentSort === $col) {
            $dir = $currentDir === 'asc' ? 'desc' : 'asc';
        }
        $params = array_merge($append, ['sort' => $col, 'dir' => $dir, 'page' => 1]);
        return url()->current() . '?' . http_build_query($params);
    };

    $sortIcon = function (string $col) use ($currentSort, $currentDir) {
        if ($currentSort !== $col) return '';
        return $currentDir === 'asc' ? ' ▲' : ' ▼';
    };
@endphp

<div class="bg-white shadow rounded border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr class="text-gray-700">
                    <th class="p-3 text-left font-semibold">
                        <a href="{{ $sortLink('id') }}" class="hover:underline">ID{!! $sortIcon('id') !!}</a>
                    </th>
                    <th class="p-3 text-left font-semibold">
                        <a href="{{ $sortLink('full_name') }}" class="hover:underline">Full Name{!! $sortIcon('full_name') !!}</a>
                    </th>
                    <th class="p-3 text-left font-semibold">
                        <a href="{{ $sortLink('email') }}" class="hover:underline">Email{!! $sortIcon('email') !!}</a>
                    </th>
                    <th class="p-3 text-left font-semibold">
                        <a href="{{ $sortLink('created_at') }}" class="hover:underline">Created{!! $sortIcon('created_at') !!}</a>
                    </th>
                    <th class="p-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($records as $a)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-medium text-gray-900 whitespace-nowrap">
                            {{ $a->id }}
                            @if(method_exists($a, 'trashed') && $a->trashed())
                                <div class="text-[11px] text-red-600 font-semibold">DELETED</div>
                            @endif
                        </td>

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

    <div class="p-4 border-t bg-white">
        {{ $records->links() }}
    </div>
</div>
