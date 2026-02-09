{{-- resources/views/portal/alumni_encoding/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-900">Encode Alumni</h2>
            <p class="text-sm text-gray-600">Create assisted record (Mode A or B).</p>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto space-y-4">

        {{-- Search possible duplicates --}}
        <div class="bg-white border rounded p-4 space-y-3">
            <form method="GET" class="flex gap-2">
                <input name="search"
                       value="{{ $search }}"
                       class="border rounded px-3 py-2 w-full"
                       placeholder="Search possible existing alumni (avoid duplicates)...">
                <button class="px-4 py-2 rounded bg-gray-900 text-white">Search</button>
            </form>

            @if($matches->count())
                <div class="border rounded p-3 bg-gray-50">
                    <div class="text-sm font-semibold mb-2">Possible matches:</div>
                    <div class="space-y-2">
                        @foreach($matches as $m)
                            <div class="flex items-center justify-between bg-white border rounded p-2">
                                <div>
                                    <div class="font-medium">{{ $m->full_name }}</div>
                                    <div class="text-xs text-gray-600">
                                        {{ $m->email ?? '—' }} • {{ $m->record_status }}
                                    </div>
                                </div>
                                <a class="underline"
                                   href="{{ route('portal.alumni_encoding.edit', $m) }}">
                                    Open
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Create assisted record --}}
        <div class="bg-white border rounded p-4 space-y-4">

            @if(session('warning'))
                <div class="p-3 rounded bg-yellow-50 border border-yellow-200 text-yellow-800">
                    {{ session('warning') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-3 rounded bg-red-50 border border-red-200 text-red-800">
                    <ul class="list-disc ml-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('portal.alumni_encoding.store') }}"
                  class="space-y-4">
                @csrf

                {{-- Name fields --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="text-sm font-medium">First Name <span class="text-red-600">*</span></label>
                        <input name="first_name"
                               value="{{ old('first_name') }}"
                               class="mt-1 w-full border rounded px-3 py-2"
                               required>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Middle Name</label>
                        <input name="middle_name"
                               value="{{ old('middle_name') }}"
                               class="mt-1 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Last Name <span class="text-red-600">*</span></label>
                        <input name="last_name"
                               value="{{ old('last_name') }}"
                               class="mt-1 w-full border rounded px-3 py-2"
                               required>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium">Alumni Email (optional)</label>
                    <input name="email"
                           value="{{ old('email') }}"
                           class="mt-1 w-full border rounded px-3 py-2">
                </div>

                {{-- Mode selection --}}
                <div class="border rounded p-3 space-y-2">
                    <div class="font-semibold text-sm">Mode</div>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="radio"
                               name="create_user"
                               value="1"
                               @checked(old('create_user','0') === '1')>
                        Mode A — Create User + Temporary Password
                    </label>

                    <div>
                        <label class="text-sm text-gray-700">
                            User Email (required for Mode A)
                        </label>
                        <input name="user_email"
                               value="{{ old('user_email') }}"
                               class="mt-1 w-full border rounded px-3 py-2">
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="radio"
                               name="create_user"
                               value="0"
                               @checked(old('create_user','0') === '0')>
                        Mode B — Record Only (link user later)
                    </label>
                </div>

                <button class="px-4 py-2 rounded bg-[#0B3D2E] text-white hover:bg-[#083325]">
                    Create Record
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
