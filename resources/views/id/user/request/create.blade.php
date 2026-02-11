{{-- resources/views/id/user/request/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
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

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6">

            {{-- Errors --}}
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

            {{-- NDMU HERO / INTRO --}}
            <div class="rounded-xl shadow border overflow-hidden" style="border-color:#E3C77A;">
                <div class="p-6 sm:p-8 text-white"
                     style="background:linear-gradient(135deg,#0B3D2E 0%, #0F5A41 55%, #0B3D2E 100%);">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold px-3 py-1 rounded-full"
                                 style="background:rgba(227,199,122,.18); border:1px solid rgba(227,199,122,.35);">
                                <span class="h-2.5 w-2.5 rounded-full" style="background:#E3C77A;"></span>
                                NDMU Alumni Tracer
                            </div>

                            <h3 class="mt-3 text-2xl font-bold tracking-tight">
                                Alumni ID Request
                            </h3>

                            <p class="mt-1 text-sm text-white/90 leading-relaxed">
                                Your details below are pulled from your intake form.
                                If something is incorrect, please update your intake record first.
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('intake.form') }}"
                                   class="inline-flex items-center px-4 py-2 rounded font-semibold"
                                   style="background:#E3C77A; color:#0B3D2E;">
                                    Update Intake Form
                                </a>

                                <a href="{{ route('id.user.request.status') }}"
                                   class="inline-flex items-center px-4 py-2 rounded font-semibold border"
                                   style="border-color:rgba(255,255,255,.45); color:#fff;">
                                    View Request Status
                                </a>
                            </div>
                        </div>

                        <div class="w-full lg:w-[380px]">
                            <div class="rounded-lg p-4"
                                 style="background:rgba(255,255,255,.08); border:1px solid rgba(227,199,122,.25);">
                                <div class="text-sm font-semibold" style="color:#E3C77A;">Quick Reminders</div>
                                <ul class="mt-2 text-sm text-white/90 space-y-2">
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        Upload a clear signature (JPG/PNG/PDF, max 10MB).
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        LOST/STOLEN requires an Affidavit of Loss.
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        BROKEN requires proof photo/document.
                                    </li>
                                </ul>
                                <div class="mt-3 text-xs text-white/80">
                                    Note: You can only have one active request at a time.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- CONTENT GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT: PULLED DETAILS --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow border p-5 space-y-4" style="border-color:#EDE7D1;">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-sm font-semibold" style="color:#0B3D2E;">Pulled from Intake</div>
                                <div class="text-xs text-gray-500">Review your details before submitting.</div>
                            </div>
                            <div class="text-xs font-semibold px-3 py-1 rounded-full"
                                 style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                                Auto-filled
                            </div>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="p-3 rounded-lg border bg-gray-50">
                                <div class="text-xs text-gray-500">Full Name</div>
                                <div class="font-semibold">
                                    {{ $user->last_name }}, {{ $user->first_name }} {{ $user->middle_name }}
                                </div>
                            </div>

                            <div class="p-3 rounded-lg border bg-gray-50">
                                <div class="text-xs text-gray-500">Birthdate</div>
                                <div class="font-semibold">
                                    {{ $alumnus?->birthdate ? \Illuminate\Support\Carbon::parse($alumnus->birthdate)->format('M d, Y') : '—' }}
                                </div>
                            </div>

                            <div class="p-3 rounded-lg border bg-gray-50">
                                <div class="text-xs text-gray-500">Course / Program</div>
                                <div class="font-semibold">
                                    {{ $course ?? '—' }}
                                </div>
                            </div>

                            <div class="p-3 rounded-lg border bg-gray-50">
                                <div class="text-xs text-gray-500">Graduation Year</div>
                                <div class="font-semibold">
                                    {{ $edu->year_graduated ?? '—' }}
                                </div>
                            </div>

                            <div class="p-3 rounded-lg border bg-gray-50">
                                <div class="text-xs text-gray-500">Eligible Level Used</div>
                                <div class="font-semibold">
                                    {{ isset($edu->level) ? strtoupper($edu->level) : '—' }}
                                </div>
                            </div>

                            <div class="rounded-lg p-3"
                                 style="background:#FFFBF0; border:1px solid #E3C77A;">
                                <div class="text-xs font-semibold" style="color:#0B3D2E;">Tip</div>
                                <div class="text-xs text-gray-700 mt-1 leading-relaxed">
                                    If your name/course/birthdate is incorrect, go back to the Intake Form and update it first.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: FORM --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-semibold" style="color:#0B3D2E;">Request Details</div>
                                <div class="text-sm text-gray-600">
                                    Fill in optional School ID and upload required attachments.
                                </div>
                            </div>
                            <div class="text-xs font-semibold px-3 py-1 rounded-full"
                                 style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                                No Payment
                            </div>
                        </div>

                        <form method="POST"
                              action="{{ route('id.user.request.store') }}"
                              enctype="multipart/form-data"
                              class="mt-5 space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        School ID (optional)
                                    </label>
                                    <input type="text" name="school_id" value="{{ old('school_id') }}"
                                           class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-offset-1"
                                           style="--tw-ring-color:#0B3D2E;"
                                           placeholder="ex: 2012-1234" />
                                    <div class="text-xs text-gray-500 mt-1">Optional if forgotten.</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Request Type
                                    </label>
                                    <select name="request_type"
                                            id="request_type"
                                            class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-offset-1"
                                            style="--tw-ring-color:#0B3D2E;">
                                        @php $rt = old('request_type','NEW'); @endphp
                                        <option value="NEW" {{ $rt==='NEW' ? 'selected' : '' }}>NEW (First time)</option>
                                        <option value="LOST" {{ $rt==='LOST' ? 'selected' : '' }}>LOST</option>
                                        <option value="STOLEN" {{ $rt==='STOLEN' ? 'selected' : '' }}>STOLEN</option>
                                        <option value="BROKEN" {{ $rt==='BROKEN' ? 'selected' : '' }}>BROKEN</option>
                                    </select>
                                </div>
                            </div>

                            <div class="rounded-xl p-4 border"
                                 style="border-color:#E3C77A; background:#FFFBF0;">
                                <div class="text-sm font-semibold" style="color:#0B3D2E;">Uploads</div>
                                <div class="text-xs text-gray-600 mt-1">
                                    Allowed: JPG/PNG/PDF up to 10MB.
                                </div>

                                <div class="mt-4 space-y-4">

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            Signature Upload <span class="text-red-600">*</span>
                                        </label>
                                        <input type="file" name="signature" accept=".jpg,.jpeg,.png,.pdf"
                                               class="mt-1 block w-full text-sm" required>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Clear signature on white background is recommended.
                                        </div>
                                    </div>

                                    <div id="affidavit_wrap" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Affidavit of Loss <span class="text-red-600">*</span>
                                            <span class="text-xs text-gray-500">(Lost/Stolen)</span>
                                        </label>
                                        <input type="file" name="affidavit_loss" accept=".jpg,.jpeg,.png,.pdf"
                                               class="mt-1 block w-full text-sm">
                                        <div class="text-xs text-gray-500 mt-1">
                                            Required if request type is LOST or STOLEN.
                                        </div>
                                    </div>

                                    <div id="broken_wrap" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Proof of Broken ID <span class="text-red-600">*</span>
                                            <span class="text-xs text-gray-500">(Broken)</span>
                                        </label>
                                        <input type="file" name="broken_proof" accept=".jpg,.jpeg,.png,.pdf"
                                               class="mt-1 block w-full text-sm">
                                        <div class="text-xs text-gray-500 mt-1">
                                            Required if request type is BROKEN.
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 pt-2">
                                <a href="{{ route('id.user.request.status') }}"
                                   class="px-4 py-2 rounded font-semibold border"
                                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                                    Cancel
                                </a>

                                <button type="submit"
                                        class="px-5 py-2 rounded font-semibold text-white"
                                        style="background:#0B3D2E;">
                                    Submit Request
                                </button>
                            </div>

                            <div class="text-xs text-gray-500 pt-2">
                                By submitting, you confirm the information provided is accurate and will be used for Alumni ID processing.
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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
