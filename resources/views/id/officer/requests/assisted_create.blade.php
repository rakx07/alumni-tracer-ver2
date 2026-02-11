{{-- resources/views/id/officer/requests/assisted_create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Assisted Alumni ID Request
                </h2>
                <div class="text-sm text-gray-600">
                    Create an Alumni ID request on behalf of an alumnus (PWD / Senior Citizen assisted processing).
                </div>
            </div>

            <a href="{{ route('id.officer.requests.index') }}"
               class="inline-flex items-center px-4 py-2 rounded font-semibold border"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6">

            {{-- Flash Errors --}}
            @if ($errors->any())
                <div class="rounded-xl border p-4 bg-red-50" style="border-color:#FCA5A5;">
                    <div class="font-semibold text-red-900">Please fix the errors below:</div>
                    <ul class="list-disc pl-5 text-sm text-red-800 mt-2">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- NDMU HERO --}}
            <div class="rounded-xl shadow border overflow-hidden" style="border-color:#E3C77A;">
                <div class="p-6 sm:p-8 text-white"
                     style="background:linear-gradient(135deg,#0B3D2E 0%, #0F5A41 55%, #0B3D2E 100%);">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">

                        <div>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold px-3 py-1 rounded-full"
                                 style="background:rgba(227,199,122,.18); border:1px solid rgba(227,199,122,.35);">
                                <span class="h-2.5 w-2.5 rounded-full" style="background:#E3C77A;"></span>
                                NDMU Alumni Tracer
                            </div>

                            <h3 class="mt-3 text-2xl font-bold tracking-tight">
                                Assisted Alumni ID Processing
                            </h3>

                            <p class="mt-1 text-sm text-white/90 leading-relaxed">
                                Search an alumnus record and create an Alumni ID request on their behalf.
                                This is intended for assisted processing (e.g., PWD / Senior Citizens).
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('id.officer.requests.index') }}"
                                   class="inline-flex items-center px-4 py-2 rounded font-semibold"
                                   style="background:#E3C77A; color:#0B3D2E;">
                                    Back to Requests
                                </a>

                                @if(Route::has('portal.records.index'))
                                    <a href="{{ route('portal.records.index') }}"
                                       class="inline-flex items-center px-4 py-2 rounded font-semibold border"
                                       style="border-color:rgba(255,255,255,.45); color:#fff;">
                                        Manage Alumni Records
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="w-full lg:w-[420px]">
                            <div class="rounded-lg p-4"
                                 style="background:rgba(255,255,255,.08); border:1px solid rgba(227,199,122,.25);">
                                <div class="text-sm font-semibold" style="color:#E3C77A;">Requirements</div>
                                <ul class="mt-2 text-sm text-white/90 space-y-2">
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        Signature upload is required.
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        LOST/STOLEN requires Affidavit of Loss.
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        BROKEN requires proof photo/document.
                                    </li>
                                </ul>
                                <div class="mt-3 text-xs text-white/80">
                                    Allowed uploads: JPG/PNG/PDF up to 10MB.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- SEARCH --}}
            <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-lg font-semibold" style="color:#0B3D2E;">Find an Alumnus</div>
                        <div class="text-sm text-gray-600">
                            Search by name or email, then click <span class="font-semibold">Use</span>.
                        </div>
                    </div>
                    <div class="text-xs font-semibold px-3 py-1 rounded-full"
                         style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                        Assisted Mode
                    </div>
                </div>

                <form method="GET" action="{{ route('id.officer.requests.assisted.create') }}"
                      class="mt-4 flex flex-col md:flex-row gap-2">
                    <input name="q" value="{{ $q }}"
                           class="w-full rounded-lg border-gray-300 focus:border-[#0B3D2E] focus:ring-[#0B3D2E]"
                           placeholder="Search by name or email (ex: Dela Cruz, Juan / juan@...)" />
                    <button class="px-4 py-2 rounded-lg font-semibold text-white"
                            style="background:#0B3D2E;">
                        Search
                    </button>
                </form>

                @if($q !== '')
                    <div class="mt-4 text-sm text-gray-600">
                        Showing up to 25 results for: <span class="font-semibold" style="color:#0B3D2E;">{{ $q }}</span>
                    </div>

                    <div class="mt-3 overflow-x-auto rounded-lg border" style="border-color:#EDE7D1;">
                        <table class="min-w-full text-sm">
                            <thead style="background:#FFFBF0;">
                                <tr class="text-left">
                                    <th class="p-3 font-semibold" style="color:#0B3D2E;">Select</th>
                                    <th class="p-3 font-semibold" style="color:#0B3D2E;">Alumnus ID</th>
                                    <th class="p-3 font-semibold" style="color:#0B3D2E;">Name</th>
                                    <th class="p-3 font-semibold" style="color:#0B3D2E;">Email</th>
                                    <th class="p-3 font-semibold" style="color:#0B3D2E;">Record Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @forelse($alumni as $a)
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="p-3">
                                            <a class="inline-flex items-center px-3 py-1 rounded font-semibold border"
                                               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;"
                                               href="{{ route('id.officer.requests.assisted.create', ['q' => $q, 'alumnus_id' => $a->id]) }}">
                                                Use
                                            </a>
                                        </td>
                                        <td class="p-3 text-gray-700">#{{ $a->id }}</td>
                                        <td class="p-3 font-medium text-gray-900">
                                            {{ $a->u_last_name ?? '' }}, {{ $a->u_first_name ?? '' }} {{ $a->u_middle_name ?? '' }}
                                        </td>
                                        <td class="p-3 text-gray-700">{{ $a->u_email ?? '—' }}</td>
                                        <td class="p-3">
                                            @php
                                                $rs = $a->record_status ?? '—';
                                                $rsBadge = match($rs) {
                                                    'validated' => ['bg'=>'rgba(134,239,172,.22)','bd'=>'rgba(134,239,172,.55)','tx'=>'#065F46'],
                                                    'submitted' => ['bg'=>'rgba(253,230,138,.22)','bd'=>'rgba(253,230,138,.55)','tx'=>'#92400E'],
                                                    'draft'     => ['bg'=>'rgba(229,231,235,.50)','bd'=>'rgba(229,231,235,.90)','tx'=>'#374151'],
                                                    default     => ['bg'=>'rgba(229,231,235,.30)','bd'=>'rgba(229,231,235,.70)','tx'=>'#374151'],
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                                  style="background:{{ $rsBadge['bg'] }}; border:1px solid {{ $rsBadge['bd'] }}; color:{{ $rsBadge['tx'] }};">
                                                {{ strtoupper($rs) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="p-4 text-center text-gray-500" colspan="5">No results.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- CREATE REQUEST --}}
            @if($selected_id)
                <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-lg font-semibold" style="color:#0B3D2E;">
                                Create Request for Alumnus ID #{{ $selected_id }}
                            </div>
                            <div class="text-sm text-gray-600">
                                Upload the signature and required supporting documents (if applicable).
                            </div>
                        </div>
                        <div class="text-xs font-semibold px-3 py-1 rounded-full"
                             style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                            Request Form
                        </div>
                    </div>

                    <form method="POST" action="{{ route('id.officer.requests.assisted.store') }}"
                          enctype="multipart/form-data" class="mt-5 space-y-5">
                        @csrf
                        <input type="hidden" name="alumnus_id" value="{{ $selected_id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">School ID (optional)</label>
                                <input type="text" name="school_id" value="{{ old('school_id') }}"
                                       class="mt-1 w-full rounded-lg border-gray-300 focus:border-[#0B3D2E] focus:ring-[#0B3D2E]" />
                                <div class="text-xs text-gray-500 mt-1">Optional if forgotten.</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Request Type</label>
                                @php $rt = old('request_type','NEW'); @endphp
                                <select name="request_type" id="request_type"
                                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-[#0B3D2E] focus:ring-[#0B3D2E]">
                                    <option value="NEW" {{ $rt==='NEW' ? 'selected' : '' }}>NEW</option>
                                    <option value="LOST" {{ $rt==='LOST' ? 'selected' : '' }}>LOST</option>
                                    <option value="STOLEN" {{ $rt==='STOLEN' ? 'selected' : '' }}>STOLEN</option>
                                    <option value="BROKEN" {{ $rt==='BROKEN' ? 'selected' : '' }}>BROKEN</option>
                                </select>
                            </div>
                        </div>

                        <div class="rounded-lg p-4"
                             style="background:#FFFBF0; border:1px solid #E3C77A;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Uploads</div>
                            <div class="text-xs text-gray-600 mt-1">Allowed: JPG/PNG/PDF up to 10MB.</div>

                            <div class="mt-4 grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Signature Upload (Required)</label>
                                    <input type="file" name="signature" accept=".jpg,.jpeg,.png,.pdf"
                                           class="mt-1 block w-full text-sm" required>
                                </div>

                                <div id="affidavit_wrap" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700">Affidavit of Loss (Required for Lost/Stolen)</label>
                                    <input type="file" name="affidavit_loss" accept=".jpg,.jpeg,.png,.pdf"
                                           class="mt-1 block w-full text-sm">
                                </div>

                                <div id="broken_wrap" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700">Proof of Broken ID (Required for Broken)</label>
                                    <input type="file" name="broken_proof" accept=".jpg,.jpeg,.png,.pdf"
                                           class="mt-1 block w-full text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-1">
                            <a href="{{ route('id.officer.requests.index') }}"
                               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border"
                               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                                Cancel
                            </a>
                            <button class="px-5 py-2 rounded-lg font-semibold text-white"
                                    style="background:#0B3D2E;">
                                Create Assisted Request
                            </button>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>

    <script>
        (function(){
            const typeSel = document.getElementById('request_type');
            const affidavitWrap = document.getElementById('affidavit_wrap');
            const brokenWrap = document.getElementById('broken_wrap');

            function sync(){
                if(!typeSel) return;
                const v = typeSel.value;
                affidavitWrap?.classList.toggle('hidden', !(v === 'LOST' || v === 'STOLEN'));
                brokenWrap?.classList.toggle('hidden', v !== 'BROKEN');
            }
            if(typeSel){
                typeSel.addEventListener('change', sync);
                sync();
            }
        })();
    </script>
</x-app-layout>
