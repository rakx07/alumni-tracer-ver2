{{-- resources/views/portal/alumni_encoding/edit.blade.php --}}
<x-app-layout>
    @php
        /**
         * Local protected reference to prevent intake partial
         * from leaking auth()->user() defaults
         */
        $alumnusLocal = $alumnus;
    @endphp

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">Assisted Encoding</h2>
                <p class="text-sm text-gray-600">
                    Status: <span class="font-medium">{{ $alumnus->record_status }}</span>
                    • Linked User: <span class="font-medium">{{ optional($alumnus->user)->email ?? '—' }}</span>
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('portal.alumni_encoding.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded font-semibold border"
                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                    Back
                </a>

                <a href="{{ route('portal.alumni_encoding.audit', $alumnus) }}"
                   class="inline-flex items-center px-4 py-2 rounded font-semibold border"
                   style="border-color:#0B3D2E; color:#0B3D2E; background:#F7FBF9;">
                    Audit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6">

            {{-- Flash (same style as intake) --}}
            @if(session('success'))
                <div class="p-3 rounded border border-green-200 bg-green-50 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="p-3 rounded border border-yellow-200 bg-yellow-50 text-yellow-800">
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('temp_password'))
                <div class="p-3 rounded border border-blue-200 bg-blue-50 text-blue-800">
                    Temporary Password (show once):
                    <span class="font-mono font-semibold">{{ session('temp_password') }}</span>
                </div>
            @endif

            {{-- Validation errors (same style as intake) --}}
            @if ($errors->any())
                <div class="p-4 rounded border border-red-200 bg-red-50 text-red-800">
                    <div class="font-semibold mb-2">Please fix the following:</div>
                    <ul class="list-disc ml-6 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Workflow --}}
            <div class="bg-white border rounded-lg p-5 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="font-semibold text-gray-900">Workflow</div>
                        <div class="text-xs text-gray-600 mt-1">
                            Validated by: {{ $alumnus->validated_by ?? '—' }}
                            • {{ optional($alumnus->validated_at)->format('M d, Y h:i A') ?? '—' }}
                        </div>
                    </div>

                    <div class="text-xs text-gray-500">
                        Editable even after validation — all saves are logged in Audit.
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('portal.alumni_encoding.submit', $alumnus) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded border font-semibold hover:bg-gray-50">
                            Submit
                        </button>
                    </form>

                    <form method="POST" action="{{ route('portal.alumni_encoding.validate', $alumnus) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded font-semibold text-white"
                                style="background:#0B3D2E;">
                            Validate
                        </button>
                    </form>

                    <form method="POST" action="{{ route('portal.alumni_encoding.return', $alumnus) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded font-semibold text-white"
                                style="background:#B45309;">
                            Return
                        </button>
                    </form>
                </div>
            </div>

            {{-- Link / Create user --}}
            <div class="bg-white border rounded-lg p-5 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="font-semibold text-gray-900">Link / Create User Account</div>
                        <div class="text-xs text-gray-600 mt-1">
                            If the email does not exist, a new user will be created with a temporary password
                            and required password change.
                        </div>
                    </div>
                </div>

                <form method="POST"
                      action="{{ route('portal.alumni_encoding.link_user', $alumnus) }}"
                      class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @csrf

                    <div>
                        <label class="text-sm font-medium">First Name <span class="text-red-600">*</span></label>
                        <input name="first_name"
                               value="{{ old('first_name') }}"
                               class="mt-1 border rounded px-3 py-2 w-full"
                               placeholder="First name"
                               required>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Middle Name</label>
                        <input name="middle_name"
                               value="{{ old('middle_name') }}"
                               class="mt-1 border rounded px-3 py-2 w-full"
                               placeholder="Middle name (optional)">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Last Name <span class="text-red-600">*</span></label>
                        <input name="last_name"
                               value="{{ old('last_name') }}"
                               class="mt-1 border rounded px-3 py-2 w-full"
                               placeholder="Last name"
                               required>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Email <span class="text-red-600">*</span></label>
                        <input name="link_email"
                               value="{{ old('link_email') }}"
                               class="mt-1 border rounded px-3 py-2 w-full"
                               placeholder="Email to link/create user"
                               required>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit"
                                class="px-4 py-2 rounded font-semibold text-white"
                                style="background:#0B3D2E;">
                            Link / Create
                        </button>
                    </div>
                </form>
            </div>

            {{-- IT Admin: edit user basic info --}}
            @if(in_array(auth()->user()->role, ['it_admin','admin'], true) && $alumnus->user)
                <div class="bg-white border rounded-lg p-5 space-y-3">
                    <div class="font-semibold text-gray-900">User Basic Info (IT Admin)</div>

                    <form method="POST"
                          action="{{ route('portal.alumni_encoding.user_update', $alumnus) }}"
                          class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="text-sm font-medium">First Name</label>
                            <input name="first_name"
                                   value="{{ old('first_name', $alumnus->user->first_name) }}"
                                   class="mt-1 w-full border rounded px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm font-medium">Middle Name</label>
                            <input name="middle_name"
                                   value="{{ old('middle_name', $alumnus->user->middle_name) }}"
                                   class="mt-1 w-full border rounded px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm font-medium">Last Name</label>
                            <input name="last_name"
                                   value="{{ old('last_name', $alumnus->user->last_name) }}"
                                   class="mt-1 w-full border rounded px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm font-medium">Display Name</label>
                            <input name="name"
                                   value="{{ old('name', $alumnus->user->name) }}"
                                   class="mt-1 w-full border rounded px-3 py-2"
                                   required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-medium">Email</label>
                            <input name="email"
                                   value="{{ old('email', $alumnus->user->email) }}"
                                   class="mt-1 w-full border rounded px-3 py-2"
                                   required>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Role</label>
                            <select name="role" class="mt-1 w-full border rounded px-3 py-2">
                                @foreach(['user','alumni_officer','it_admin','admin'] as $r)
                                    <option value="{{ $r }}" @selected(old('role', $alumnus->user->role) === $r)>{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Active</label>
                            <select name="is_active" class="mt-1 w-full border rounded px-3 py-2">
                                <option value="1" @selected((int)old('is_active', $alumnus->user->is_active) === 1)>Yes</option>
                                <option value="0" @selected((int)old('is_active', $alumnus->user->is_active) === 0)>No</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <button type="submit"
                                    class="px-4 py-2 rounded font-semibold text-white bg-gray-900 hover:bg-black">
                                Save User
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Main intake form (same container style as intake page) --}}
            <div class="bg-white border rounded-lg p-5">
                <form method="POST" action="{{ route('portal.alumni_encoding.update', $alumnus) }}" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- shared form partial --}}
                    @include('user._intake_form', [
                        'alumnus' => $alumnusLocal,
                        'prefill_from_auth' => false
                    ])

                    <div class="mt-6 flex flex-wrap gap-2">
                        <button type="submit"
                                class="px-5 py-2 rounded font-semibold text-white"
                                style="background:#0B3D2E;">
                            Save Changes (Logged)
                        </button>

                        <a href="{{ route('portal.alumni_encoding.audit', $alumnus) }}"
                           class="px-5 py-2 rounded font-semibold border hover:bg-gray-50">
                            View Audit
                        </a>
                    </div>
                </form>
            </div>

            {{-- ✅ Portal-only JS (must be after the partial so wrappers exist) --}}
            {{-- IMPORTANT: this partial must ONLY include user._intake_js (no extra scripts) --}}
            @include('portal.alumni_encoding._intake_js')

        </div>
    </div>
</x-app-layout>