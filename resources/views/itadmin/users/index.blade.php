<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    User Management
                </h2>
                <div class="text-sm text-gray-600">
                    IT Admin — Manage portal users, roles, and access status.
                </div>
            </div>

            <a href="{{ route('itadmin.users.create') }}"
               class="inline-flex items-center px-4 py-2 rounded text-white"
               style="background:#0B3D2E;">
                Create User
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="rounded-xl border p-4 bg-green-50"
                     style="border-color:#86EFAC;">
                    <div class="font-semibold text-green-800">Success</div>
                    <div class="text-sm text-green-700 mt-1">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- Search --}}
            <div class="bg-white rounded-xl shadow border p-5"
                 style="border-color:#EDE7D1;">
                <form method="GET" class="flex flex-col sm:flex-row gap-3">
                    <input name="q"
                           value="{{ $q }}"
                           placeholder="Search by name or email…"
                           class="w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
                    <button class="px-5 py-2 rounded font-semibold text-white"
                            style="background:#0B3D2E;">
                        Search
                    </button>
                </form>
            </div>

            {{-- Users Table --}}
            <div class="bg-white rounded-xl shadow border overflow-hidden"
                 style="border-color:#EDE7D1;">
                <table class="w-full text-sm">
                    <thead style="background:#F6F2E6;">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold" style="color:#0B3D2E;">
                                Name
                            </th>
                            <th class="text-left px-5 py-3 font-semibold" style="color:#0B3D2E;">
                                Email
                            </th>
                            <th class="text-left px-5 py-3 font-semibold" style="color:#0B3D2E;">
                                Role
                            </th>
                            <th class="text-left px-5 py-3 font-semibold" style="color:#0B3D2E;">
                                Status
                            </th>
                            <th class="text-right px-5 py-3 font-semibold" style="color:#0B3D2E;">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $u)
                            <tr class="border-t hover:bg-gray-50/60 transition">

                                {{-- Name --}}
                                <td class="px-5 py-4 font-semibold leading-tight text-gray-900">
                                    {!! nl2br(e($u->vertical_name ?? $u->name)) !!}
                                </td>

                                {{-- Email --}}
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $u->email }}
                                </td>

                                {{-- Role --}}
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                          style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                                        {{ $u->role_label }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4 space-x-2">

                                    @if($u->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                     bg-green-50 border border-green-200 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                     bg-red-50 border border-red-200 text-red-800">
                                            Disabled
                                        </span>
                                    @endif

                                    @if($u->must_change_password)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                     bg-yellow-50 border border-yellow-200 text-yellow-800">
                                            Must change password
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 text-right whitespace-nowrap space-x-3">
                                    <a href="{{ route('itadmin.users.edit', $u) }}"
                                       class="text-sm font-semibold underline"
                                       style="color:#0B3D2E;">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('itadmin.users.toggle_active', $u) }}"
                                          class="inline">
                                        @csrf
                                        <button class="text-sm font-semibold underline"
                                                style="color:#7A1F1F;">
                                            {{ $u->is_active ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-6 text-center text-gray-600">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div>
                {{ $users->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
