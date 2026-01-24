<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Edit User Account
                </h2>
                <div class="text-sm text-gray-600">
                    Update user details, role, status, and reset password if needed.
                </div>
            </div>

            <a href="{{ route('itadmin.users.index') }}"
               class="inline-flex items-center px-4 py-2 rounded text-white"
               style="background:#0B3D2E;">
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-xl border p-4 bg-green-50" style="border-color:#86EFAC;">
                    <div class="text-green-800 font-semibold">Success</div>
                    <div class="text-sm text-green-700 mt-1">{{ session('success') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-xl border p-4 bg-red-50" style="border-color:#FCA5A5;">
                    <div class="font-semibold text-red-800">Please fix the following:</div>
                    <ul class="mt-2 list-disc ml-5 text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- MAIN: Update Info --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow border overflow-hidden"
                     style="border-color:#EDE7D1;">
                    <div class="px-6 py-4 border-b"
                         style="border-color:#EDE7D1; background:#F6F2E6;">
                        <div class="text-sm font-semibold" style="color:#0B3D2E;">Account Details</div>
                        <div class="text-xs text-gray-600 mt-1">
                            Update the user’s name, email, and role assignment.
                        </div>
                    </div>

                    <form method="POST" action="{{ route('itadmin.users.update', $user) }}" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Current quick info --}}
                        <div class="rounded-xl border p-4"
                             style="border-color:#E3C77A; background:#FFFBF0;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Current Record</div>
                            <div class="mt-2 text-sm text-gray-800 leading-relaxed">
                                <div class="font-semibold">
                                    {!! nl2br(e($user->vertical_name ?? ($user->name ?? ''))) !!}
                                </div>
                                <div class="text-gray-600 text-xs mt-1">
                                    {{ $user->email }} • Role: {{ $user->role_label ?? $user->role }}
                                </div>
                            </div>
                        </div>

                        {{-- Name fields --}}
                        <div>
                            <div class="text-sm font-semibold text-gray-800">Full Name</div>
                            <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600">Last Name</label>
                                    <input name="last_name"
                                           value="{{ old('last_name', $user->last_name) }}"
                                           class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                           required>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600">First Name</label>
                                    <input name="first_name"
                                           value="{{ old('first_name', $user->first_name) }}"
                                           class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                           required>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600">Middle Name (Optional)</label>
                                    <input name="middle_name"
                                           value="{{ old('middle_name', $user->middle_name) }}"
                                           class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                </div>
                            </div>
                        </div>

                        {{-- Email + role --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600">Email Address</label>
                                <input type="email" name="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                       required>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600">Role</label>
                                <select name="role"
                                        class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                        required>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="alumni_officer" {{ old('role', $user->role) === 'alumni_officer' ? 'selected' : '' }}>Alumni Officer</option>
                                    <option value="it_admin" {{ old('role', $user->role) === 'it_admin' ? 'selected' : '' }}>IT Admin</option>
                                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button class="px-5 py-2 rounded font-semibold text-white"
                                    style="background:#0B3D2E;">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                {{-- SIDE: Status + Password --}}
                <div class="space-y-6">

                    {{-- Status --}}
                    <div class="bg-white rounded-xl shadow border p-6"
                         style="border-color:#EDE7D1;">
                        <div class="text-sm font-semibold" style="color:#0B3D2E;">Account Status</div>

                        <div class="mt-3 text-sm text-gray-700">
                            Status:
                            @if($user->is_active)
                                <span class="ml-1 px-2 py-1 rounded bg-green-50 border border-green-200">Active</span>
                            @else
                                <span class="ml-1 px-2 py-1 rounded bg-red-50 border border-red-200">Disabled</span>
                            @endif
                        </div>

                        <div class="mt-3 text-sm text-gray-700">
                            Password policy:
                            @if($user->must_change_password)
                                <span class="ml-1 px-2 py-1 rounded bg-yellow-50 border border-yellow-200">Must change password</span>
                            @else
                                <span class="ml-1 px-2 py-1 rounded bg-gray-50 border border-gray-200">Normal</span>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('itadmin.users.toggle_active', $user) }}" class="mt-4">
                            @csrf
                            <button class="w-full px-4 py-2 rounded font-semibold border"
                                    style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                                {{ $user->is_active ? 'Disable Account' : 'Enable Account' }}
                            </button>
                        </form>
                    </div>

                    {{-- Reset Password --}}
                    <div class="bg-white rounded-xl shadow border p-6"
                         style="border-color:#EDE7D1;">
                        <div class="text-sm font-semibold" style="color:#0B3D2E;">Reset Password</div>
                        <div class="text-xs text-gray-600 mt-1 leading-relaxed">
                            Set a new temporary password (or leave blank to auto-generate).
                            The user will be required to change the password on next login.
                        </div>

                        <form method="POST" action="{{ route('itadmin.users.reset_password', $user) }}" class="mt-4 space-y-3">
                            @csrf

                            <div>
                                <label class="block text-xs font-semibold text-gray-600">New Temporary Password (Optional)</label>
                                <input name="new_password"
                                       class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                       placeholder="Leave blank to auto-generate">
                            </div>

                            <button class="w-full px-4 py-2 rounded font-semibold text-white"
                                    style="background:#0B3D2E;">
                                Reset Password
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
