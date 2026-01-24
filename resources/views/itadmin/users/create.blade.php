<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Create User Account
                </h2>
                <div class="text-sm text-gray-600">
                    IT Admin — Add a portal user and assign an access role.
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
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Errors --}}
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

                {{-- Main Form --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow border overflow-hidden"
                     style="border-color:#EDE7D1;">
                    <div class="px-6 py-4 border-b"
                         style="border-color:#EDE7D1; background:#F6F2E6;">
                        <div class="text-sm font-semibold" style="color:#0B3D2E;">User Information</div>
                        <div class="text-xs text-gray-600 mt-1">
                            Enter the user’s name details and email address.
                        </div>
                    </div>

                    <form method="POST" action="{{ route('itadmin.users.store') }}" class="p-6 space-y-6">
                        @csrf

                        {{-- Name Fields --}}
                        <div>
                            <div class="text-sm font-semibold text-gray-800">Full Name</div>
                            <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600">Last Name</label>
                                    <input name="last_name" value="{{ old('last_name') }}"
                                           class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                           required>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600">First Name</label>
                                    <input name="first_name" value="{{ old('first_name') }}"
                                           class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                           required>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600">Middle Name (Optional)</label>
                                    <input name="middle_name" value="{{ old('middle_name') }}"
                                           class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                </div>
                            </div>

                            <div class="mt-2 text-xs text-gray-500">
                                The system will automatically build the display name from Last / First / Middle.
                            </div>
                        </div>

                        {{-- Email + Role --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                       placeholder="name@ndmu.edu.ph"
                                       required>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600">Role</label>
                                <select name="role"
                                        class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                        required>
                                    <option value="">— Select Role —</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="alumni_officer" {{ old('role') === 'alumni_officer' ? 'selected' : '' }}>Alumni Officer</option>
                                    <option value="it_admin" {{ old('role') === 'it_admin' ? 'selected' : '' }}>IT Admin</option>
                                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>
                        </div>

                        {{-- Temporary Password --}}
                        <div class="rounded-xl border p-4"
                             style="border-color:#E3C77A; background:#FFFBF0;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Temporary Password</div>
                            <div class="text-xs text-gray-700 mt-1 leading-relaxed">
                                If you leave this blank, the system will generate a secure temporary password.
                                The user will be required to change the password upon first login.
                            </div>

                            <div class="mt-3">
                                <label class="block text-xs font-semibold text-gray-600">Set Temporary Password (Optional)</label>
                                <input name="temp_password"
                                       class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                       placeholder="Leave blank to auto-generate">
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-2 pt-2">
                            <a href="{{ route('itadmin.users.index') }}"
                               class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>
                            <button class="px-5 py-2 rounded font-semibold text-white"
                                    style="background:#0B3D2E;">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Side Guidance --}}
                <div class="bg-white rounded-xl shadow border p-6"
                     style="border-color:#EDE7D1;">
                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Guidelines</div>
                    <ul class="mt-3 text-sm text-gray-700 space-y-3">
                        <li class="flex items-start gap-2">
                            <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                            Use an official email address whenever possible.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                            Assign only necessary access based on role and responsibility.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                            Temporary passwords should be shared securely (avoid public messages).
                        </li>
                    </ul>

                    <div class="mt-5 rounded-xl border p-4"
                         style="border-color:#E3C77A; background:#F6F2E6;">
                        <div class="text-sm font-semibold" style="color:#0B3D2E;">Data Privacy</div>
                        <div class="text-xs text-gray-700 mt-1 leading-relaxed">
                            User accounts provide access to sensitive alumni information.
                            Ensure account creation complies with NDMU data protection guidelines.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
