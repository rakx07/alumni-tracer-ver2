{{-- resources/views/portal/alumni_encoding/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900">Assisted Encoding</h2>
                <p class="text-sm text-gray-600">
                    Status: <span class="font-medium">{{ $alumnus->record_status }}</span>
                    • Linked User: <span class="font-medium">{{ $alumnus->user?->email ?? '—' }}</span>
                </p>
            </div>
            <div class="flex gap-2">
                <a class="underline text-sm" href="{{ route('portal.alumni_encoding.index') }}">Back</a>
                <a class="underline text-sm" href="{{ route('portal.alumni_encoding.audit', $alumnus) }}">Audit</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto space-y-4">

        {{-- Flash --}}
        @if(session('success'))
            <div class="p-3 rounded bg-green-50 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="p-3 rounded bg-yellow-50 border border-yellow-200 text-yellow-800">
                {{ session('warning') }}
            </div>
        @endif

        @if(session('temp_password'))
            <div class="p-3 rounded bg-blue-50 border border-blue-200 text-blue-800">
                Temporary Password (show once):
                <span class="font-mono font-semibold">{{ session('temp_password') }}</span>
            </div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded text-red-800">
                <div class="font-semibold mb-2">Please fix the following:</div>
                <ul class="list-disc ml-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Workflow --}}
        <div class="bg-white border rounded p-4 space-y-2">
            <div class="font-semibold">Workflow</div>

            <div class="flex flex-wrap gap-2">
                <form method="POST" action="{{ route('portal.alumni_encoding.submit', $alumnus) }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded border hover:bg-gray-50">
                        Submit
                    </button>
                </form>

                <form method="POST" action="{{ route('portal.alumni_encoding.validate', $alumnus) }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded bg-green-700 text-white hover:bg-green-800">
                        Validate
                    </button>
                </form>

                <form method="POST" action="{{ route('portal.alumni_encoding.return', $alumnus) }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded bg-yellow-600 text-white hover:bg-yellow-700">
                        Return
                    </button>
                </form>
            </div>

            <div class="text-xs text-gray-600">
                Validated by: {{ $alumnus->validated_by ?? '—' }}
                • {{ optional($alumnus->validated_at)?->format('M d, Y h:i A') ?? '—' }}
            </div>

            <div class="text-xs text-gray-500">
                Note: Record is editable even after validation — all saves are logged in Audit.
            </div>
        </div>

        {{-- Link / Create user --}}
        <div class="bg-white border rounded p-4 space-y-3">
            <div class="font-semibold">Link / Create User Account</div>

            <div class="text-xs text-gray-600">
                If the email does not exist, a new user will be created with a temporary password and required password change.
            </div>

            <form method="POST"
                  action="{{ route('portal.alumni_encoding.link_user', $alumnus) }}"
                  class="grid grid-cols-1 md:grid-cols-2 gap-2">
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
                            class="px-4 py-2 rounded bg-[#0B3D2E] text-white hover:bg-[#083325]">
                        Link / Create
                    </button>
                </div>
            </form>
        </div>

        {{-- IT Admin: edit user basic info --}}
        @if(in_array(auth()->user()->role, ['it_admin','admin'], true) && $alumnus->user)
            <div class="bg-white border rounded p-4 space-y-3">
                <div class="font-semibold">User Basic Info (IT Admin)</div>

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
                        <button type="submit" class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-black">
                            Save User
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Main intake form --}}
        <div class="bg-white border rounded p-4">
            <form method="POST" action="{{ route('portal.alumni_encoding.update', $alumnus) }}" novalidate>
                @csrf
                @method('PUT')

                {{-- shared form partial (OK to reuse) --}}
                @include('user._intake_form', ['alumnus' => $alumnus])

                <div class="mt-6">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Save Changes (Logged)
                    </button>
                </div>
            </form>
        </div>

        {{-- ✅ Portal-only JS (DO NOT use user._intake_form_js) --}}
        @include('portal.alumni_encoding._intake_js', ['alumnus' => $alumnus])

    </div>
</x-app-layout>
