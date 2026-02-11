<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Request Alumni ID
                </h2>
                <div class="text-sm text-gray-600">
                    Submit a request for Alumni ID processing (no payment).
                </div>
            </div>

            <a href="{{ route('id.user.request.status') }}"
               class="inline-flex items-center px-4 py-2 rounded font-semibold border"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto space-y-4 px-4">
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

        <div class="bg-white border rounded-lg p-5 space-y-4">
            <div class="text-sm text-gray-600">
                Your information below is pulled from your intake form. If something is wrong, update your intake record first.
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div class="p-3 rounded border bg-gray-50">
                    <div class="text-xs text-gray-500">Full Name</div>
                    <div class="font-semibold">
                        {{ $user->last_name }}, {{ $user->first_name }} {{ $user->middle_name }}
                    </div>
                </div>

                <div class="p-3 rounded border bg-gray-50">
                    <div class="text-xs text-gray-500">Birthdate</div>
                    <div class="font-semibold">
                        {{ optional($alumnus->birthdate)->format('M d, Y') ?? '—' }}
                    </div>
                </div>

                <div class="p-3 rounded border bg-gray-50">
                    <div class="text-xs text-gray-500">Course / Program</div>
                    <div class="font-semibold">
                        {{ $course ?? '—' }}
                    </div>
                </div>

                <div class="p-3 rounded border bg-gray-50">
                    <div class="text-xs text-gray-500">Graduation Year</div>
                    <div class="font-semibold">
                        {{ $edu->year_graduated ?? '—' }}
                    </div>
                </div>

                <div class="p-3 rounded border bg-gray-50 md:col-span-2">
                    <div class="text-xs text-gray-500">Eligible Level Used</div>
                    <div class="font-semibold">
                        {{ strtoupper($edu->level) }}
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('id.user.request.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">School ID (optional)</label>
                        <input type="text" name="school_id" value="{{ old('school_id') }}"
                               class="mt-1 w-full rounded border-gray-300 focus:border-green-700 focus:ring-green-700"
                               placeholder="ex: 2012-1234" />
                        <div class="text-xs text-gray-500 mt-1">Optional if forgotten.</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Request Type</label>
                        <select name="request_type"
                                id="request_type"
                                class="mt-1 w-full rounded border-gray-300 focus:border-green-700 focus:ring-green-700">
                            @php $rt = old('request_type','NEW'); @endphp
                            <option value="NEW" {{ $rt==='NEW' ? 'selected' : '' }}>NEW (First time)</option>
                            <option value="LOST" {{ $rt==='LOST' ? 'selected' : '' }}>LOST</option>
                            <option value="STOLEN" {{ $rt==='STOLEN' ? 'selected' : '' }}>STOLEN</option>
                            <option value="BROKEN" {{ $rt==='BROKEN' ? 'selected' : '' }}>BROKEN</option>
                        </select>
                    </div>
                </div>

                <div class="border-t pt-4 space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Signature Upload (Required)
                        </label>
                        <input type="file" name="signature" accept=".jpg,.jpeg,.png,.pdf"
                               class="mt-1 block w-full text-sm" required>
                        <div class="text-xs text-gray-500 mt-1">Allowed: JPG/PNG/PDF up to 10MB.</div>
                    </div>

                    <div id="affidavit_wrap" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">
                            Affidavit of Loss (Required for Lost/Stolen)
                        </label>
                        <input type="file" name="affidavit_loss" accept=".jpg,.jpeg,.png,.pdf"
                               class="mt-1 block w-full text-sm">
                        <div class="text-xs text-gray-500 mt-1">Required if request type is LOST or STOLEN.</div>
                    </div>

                    <div id="broken_wrap" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">
                            Proof of Broken ID (Required for Broken)
                        </label>
                        <input type="file" name="broken_proof" accept=".jpg,.jpeg,.png,.pdf"
                               class="mt-1 block w-full text-sm">
                        <div class="text-xs text-gray-500 mt-1">Required if request type is BROKEN.</div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="submit"
                            class="px-4 py-2 rounded font-semibold text-white"
                            style="background:#0B3D2E;">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function(){
            const typeSel = document.getElementById('request_type');
            const affidavitWrap = document.getElementById('affidavit_wrap');
            const brokenWrap = document.getElementById('broken_wrap');

            function sync(){
                const v = typeSel.value;
                affidavitWrap.classList.toggle('hidden', !(v === 'LOST' || v === 'STOLEN'));
                brokenWrap.classList.toggle('hidden', v !== 'BROKEN');
            }
            typeSel.addEventListener('change', sync);
            sync();
        })();
    </script>
</x-app-layout>
