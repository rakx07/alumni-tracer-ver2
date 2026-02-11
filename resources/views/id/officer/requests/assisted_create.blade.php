<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
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

    <div class="py-6 max-w-6xl mx-auto space-y-4 px-4">
        @if ($errors->any())
            <div class="p-3 rounded border border-red-200 bg-red-50 text-red-800">
                <div class="font-semibold mb-1">Please fix the errors below:</div>
                <ul class="list-disc pl-5 text-sm">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border rounded-lg p-4">
            <form method="GET" action="{{ route('id.officer.requests.assisted.create') }}" class="flex flex-col md:flex-row gap-2">
                <input name="q" value="{{ $q }}"
                       class="w-full rounded border-gray-300"
                       placeholder="Search by name or email (ex: Dela Cruz, Juan / juan@...)" />
                <button class="px-4 py-2 rounded font-semibold text-white"
                        style="background:#0B3D2E;">
                    Search
                </button>
            </form>

            @if($q !== '')
                <div class="mt-4 text-sm text-gray-600">
                    Showing up to 25 results for: <span class="font-semibold">{{ $q }}</span>
                </div>

                <div class="mt-3 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                        <tr class="text-left">
                            <th class="p-3">Select</th>
                            <th class="p-3">Alumnus ID</th>
                            <th class="p-3">Name</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Record Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($alumni as $a)
                            <tr class="border-t">
                                <td class="p-3">
                                    <a class="underline" style="color:#0B3D2E;"
                                       href="{{ route('id.officer.requests.assisted.create', ['q' => $q, 'alumnus_id' => $a->id]) }}">
                                        Use
                                    </a>
                                </td>
                                <td class="p-3">#{{ $a->id }}</td>
                                <td class="p-3 font-medium">
                                    {{ $a->u_last_name ?? '' }}, {{ $a->u_first_name ?? '' }} {{ $a->u_middle_name ?? '' }}
                                </td>
                                <td class="p-3">{{ $a->u_email ?? '—' }}</td>
                                <td class="p-3">{{ $a->record_status ?? '—' }}</td>
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

        @if($selected_id)
            <div class="bg-white border rounded-lg p-5">
                <div class="font-semibold mb-3">Create Request for Alumnus ID #{{ $selected_id }}</div>

                <form method="POST" action="{{ route('id.officer.requests.assisted.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="alumnus_id" value="{{ $selected_id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">School ID (optional)</label>
                            <input type="text" name="school_id" value="{{ old('school_id') }}"
                                   class="mt-1 w-full rounded border-gray-300" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Request Type</label>
                            @php $rt = old('request_type','NEW'); @endphp
                            <select name="request_type" id="request_type"
                                    class="mt-1 w-full rounded border-gray-300">
                                <option value="NEW" {{ $rt==='NEW' ? 'selected' : '' }}>NEW</option>
                                <option value="LOST" {{ $rt==='LOST' ? 'selected' : '' }}>LOST</option>
                                <option value="STOLEN" {{ $rt==='STOLEN' ? 'selected' : '' }}>STOLEN</option>
                                <option value="BROKEN" {{ $rt==='BROKEN' ? 'selected' : '' }}>BROKEN</option>
                            </select>
                        </div>
                    </div>

                    <div class="border-t pt-4 space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Signature Upload (Required)</label>
                            <input type="file" name="signature" accept=".jpg,.jpeg,.png,.pdf" class="mt-1 block w-full text-sm" required>
                            <div class="text-xs text-gray-500 mt-1">Allowed: JPG/PNG/PDF up to 10MB.</div>
                        </div>

                        <div id="affidavit_wrap" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Affidavit of Loss (Required for Lost/Stolen)</label>
                            <input type="file" name="affidavit_loss" accept=".jpg,.jpeg,.png,.pdf" class="mt-1 block w-full text-sm">
                        </div>

                        <div id="broken_wrap" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Proof of Broken ID (Required for Broken)</label>
                            <input type="file" name="broken_proof" accept=".jpg,.jpeg,.png,.pdf" class="mt-1 block w-full text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button class="px-4 py-2 rounded font-semibold text-white" style="background:#0B3D2E;">
                            Create Assisted Request
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <script>
        (function(){
            const typeSel = document.getElementById('request_type');
            const affidavitWrap = document.getElementById('affidavit_wrap');
            const brokenWrap = document.getElementById('broken_wrap');

            function sync(){
                if(!typeSel) return;
                const v = typeSel.value;
                affidavitWrap.classList.toggle('hidden', !(v === 'LOST' || v === 'STOLEN'));
                brokenWrap.classList.toggle('hidden', v !== 'BROKEN');
            }
            if(typeSel){
                typeSel.addEventListener('change', sync);
                sync();
            }
        })();
    </script>
</x-app-layout>
