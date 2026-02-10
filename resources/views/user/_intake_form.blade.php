{{-- resources/views/user/_intake_form.blade.php --}}
@php
    /**
     * Reusable intake partial (Self-service + Assisted encoding)
     *
     * Usage:
     *  - Self-service (default): @include('user._intake_form', ['alumnus' => $alumnus])
     *  - Assisted encoding:      @include('user._intake_form', ['alumnus' => $alumnus, 'prefill_from_auth' => false])
     */

    $alumnus = $alumnus ?? null;

    // NEW: allow caller to disable pulling defaults from the logged-in user
    $prefill_from_auth = $prefill_from_auth ?? true;

    $sex = old('sex', $alumnus->sex ?? '');
    $cs  = old('civil_status', $alumnus->civil_status ?? '');

    $pref    = $alumnus->engagementPreference ?? null;
    $consent = $alumnus->consent ?? null;

    $u = $prefill_from_auth ? auth()->user() : null;

    // Default full name from logged-in user ONLY if enabled
    $defaultFullName = $prefill_from_auth
        ? trim(collect([
            $u?->first_name,
            $u?->middle_name,
            $u?->last_name,
        ])->filter()->implode(' '))
        : '';

    // Alumni table uses full_name; when assisted, we prefer alumnus->full_name only (no auth fallback)
    $fullNameValue = old('full_name', $alumnus->full_name ?? $defaultFullName);

    // Name fields:
    // - Assisted mode: use alumnus split if needed (NOT auth user)
    // - Self-service: use auth user first
    $first  = old('first_name',  $prefill_from_auth ? ($u?->first_name ?? '') : '');
    $middle = old('middle_name', $prefill_from_auth ? ($u?->middle_name ?? '') : '');
    $last   = old('last_name',   $prefill_from_auth ? ($u?->last_name ?? '') : '');

    if ((!$first && !$last) && $fullNameValue) {
        $parts = preg_split('/\s+/', trim($fullNameValue));
        $first = $parts[0] ?? '';
        $last  = count($parts) > 1 ? $parts[count($parts)-1] : '';
        $middle = count($parts) > 2 ? implode(' ', array_slice($parts, 1, -1)) : '';
    }
@endphp


<div class="bg-white shadow rounded p-6 space-y-8">

    {{-- I. PERSONAL INFORMATION --}}
    <section id="personal" class="scroll-mt-24">
        <div class="flex items-center justify-between mb-3">
            <div class="text-lg font-semibold">I. Personal Information</div>
            <a href="#personal" class="text-xs text-gray-500 hover:text-gray-700">#</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

            {{-- Name parts --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Name</label>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">First Name <span class="text-red-600">*</span></label>
                        <input id="first_name" name="first_name"
                               class="w-full border rounded p-2"
                               value="{{ $first }}"
                               autocomplete="given-name"
                               required>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Middle Name (optional)</label>
                        <input id="middle_name" name="middle_name"
                               class="w-full border rounded p-2"
                               value="{{ $middle }}"
                               autocomplete="additional-name">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Last Name <span class="text-red-600">*</span></label>
                        <input id="last_name" name="last_name"
                               class="w-full border rounded p-2"
                               value="{{ $last }}"
                               autocomplete="family-name"
                               required>
                    </div>
                </div>

                {{-- Alumni table uses full_name for now --}}
                <input type="hidden" id="full_name" name="full_name" value="{{ $fullNameValue }}">

                <div class="text-xs text-gray-500 mt-2">
                    Will be saved as:
                    <span id="full_name_preview" class="font-semibold text-gray-700">
                        {{ $fullNameValue ?: '—' }}
                    </span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium">Nickname</label>
                <input name="nickname" class="w-full border rounded p-2"
                       value="{{ old('nickname', $alumnus->nickname ?? '') }}">
            </div>

            <div>
                <label class="block text-sm font-medium">Sex</label>
                <select name="sex" class="w-full border rounded p-2">
                    <option value="">--</option>
                    <option value="male" {{ $sex==='male'?'selected':'' }}>Male</option>
                    <option value="female" {{ $sex==='female'?'selected':'' }}>Female</option>
                    <option value="prefer_not" {{ $sex==='prefer_not'?'selected':'' }}>Prefer not to say</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">Birthdate</label>
                <input type="date" name="birthdate" class="w-full border rounded p-2"
                       value="{{ old('birthdate', $alumnus->birthdate ?? '') }}">
            </div>

            <div>
                <label class="block text-sm font-medium">Age</label>
                <input type="number"
                       name="age"
                       readonly
                       tabindex="-1"
                       class="w-full border rounded p-2 bg-gray-100 text-gray-600 cursor-not-allowed"
                       value="{{ old('age', $alumnus->age ?? '') }}">
            </div>

            <div>
                <label class="block text-sm font-medium">Civil Status</label>
                <select name="civil_status" class="w-full border rounded p-2">
                    <option value="">--</option>
                    <option value="single" {{ $cs==='single'?'selected':'' }}>Single</option>
                    <option value="married" {{ $cs==='married'?'selected':'' }}>Married</option>
                    <option value="widowed" {{ $cs==='widowed'?'selected':'' }}>Widowed</option>
                    <option value="separated" {{ $cs==='separated'?'selected':'' }}>Separated</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">Contact Number</label>
                <input name="contact_number" class="w-full border rounded p-2"
                       value="{{ old('contact_number', $alumnus->contact_number ?? '') }}">
            </div>

            <div>
                <label class="block text-sm font-medium">Email</label>
                <input name="email" class="w-full border rounded p-2"
                   value="{{ old('email', $alumnus->email ?? ($prefill_from_auth ? (auth()->user()->email ?? '') : '')) }}">


            </div>

            <div>
                <label class="block text-sm font-medium">Facebook / Social Media</label>
                <input name="facebook" class="w-full border rounded p-2"
                       value="{{ old('facebook', $alumnus->facebook ?? '') }}">
            </div>

            <div>
                <label class="block text-sm font-medium">Nationality</label>
                <input name="nationality" class="w-full border rounded p-2"
                       value="{{ old('nationality', $alumnus->nationality ?? '') }}">
            </div>

            <div>
                <label class="block text-sm font-medium">Religion</label>
                <input name="religion" class="w-full border rounded p-2"
                       value="{{ old('religion', $alumnus->religion ?? '') }}">
            </div>
        </div>
    </section>

    {{-- II. ADDRESSES --}}
    <section id="addresses" class="scroll-mt-24">
        <div class="flex items-center justify-between mb-3">
            <div class="text-lg font-semibold">II. Addresses</div>
            <a href="#addresses" class="text-xs text-gray-500 hover:text-gray-700">#</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Home Address</label>
                <textarea name="home_address" class="w-full border rounded p-2" rows="2">{{ old('home_address', $alumnus->home_address ?? '') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Current / Present Address</label>
                <textarea name="current_address" class="w-full border rounded p-2" rows="2">{{ old('current_address', $alumnus->current_address ?? '') }}</textarea>
            </div>
        </div>
    </section>

    {{-- III. ACADEMIC BACKGROUND --}}
    <section id="academic" class="scroll-mt-24">
        <div class="flex items-center justify-between mb-3">
            <div class="text-lg font-semibold">III. Academic Background</div>
            <div class="flex items-center gap-2">
                <a href="#academic" class="text-xs text-gray-500 hover:text-gray-700">#</a>
                <button type="button" id="add-education"
                        class="px-3 py-2 bg-gray-800 text-white rounded">
                    + Add Level
                </button>
            </div>
        </div>

        <div class="text-sm text-gray-600 mb-3">
            Add only the level(s) you attended (Elementary / JHS / SHS / College / Grad / Law / Post-NDMU).
        </div>

        <div id="education-wrap" class="space-y-3"></div>
    </section>

    {{-- IV. EMPLOYMENT --}}
    <section id="employment" class="scroll-mt-24">
        <div class="flex items-center justify-between mb-3">
            <div class="text-lg font-semibold">IV. Employment / Professional Information</div>
            <div class="flex items-center gap-2">
                <a href="#employment" class="text-xs text-gray-500 hover:text-gray-700">#</a>
                <button type="button" id="add-employment"
                        class="px-3 py-2 bg-gray-800 text-white rounded">
                    + Add Employment
                </button>
            </div>
        </div>

        <div id="employment-wrap" class="space-y-3"></div>
    </section>

    {{-- V. COMMUNITY --}}
    <section id="community" class="scroll-mt-24">
        <div class="flex items-center justify-between mb-3">
            <div class="text-lg font-semibold">V. Community Involvement & Affiliations</div>
            <div class="flex items-center gap-2">
                <a href="#community" class="text-xs text-gray-500 hover:text-gray-700">#</a>
                <button type="button" id="add-community"
                        class="px-3 py-2 bg-gray-800 text-white rounded">
                    + Add Organization
                </button>
            </div>
        </div>

        <div id="community-wrap" class="space-y-3"></div>
    </section>

    {{-- VI. ENGAGEMENT --}}
    <section id="engagement" class="scroll-mt-24">
        <div class="flex items-center justify-between mb-3">
            <div class="text-lg font-semibold">VI. Alumni Engagement Interests</div>
            <a href="#engagement" class="text-xs text-gray-500 hover:text-gray-700">#</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="engagement[willing_contacted]" value="1"
                    {{ old('engagement.willing_contacted', $pref->willing_contacted ?? false) ? 'checked' : '' }}>
                Willing to be contacted
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="engagement[willing_events]" value="1"
                    {{ old('engagement.willing_events', $pref->willing_events ?? false) ? 'checked' : '' }}>
                Willing to join alumni events
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="engagement[willing_mentor]" value="1"
                    {{ old('engagement.willing_mentor', $pref->willing_mentor ?? false) ? 'checked' : '' }}>
                Willing to mentor / career talks
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="engagement[willing_donation]" value="1"
                    {{ old('engagement.willing_donation', $pref->willing_donation ?? false) ? 'checked' : '' }}>
                Interested in donations/support
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="engagement[willing_scholarship]" value="1"
                    {{ old('engagement.willing_scholarship', $pref->willing_scholarship ?? false) ? 'checked' : '' }}>
                Interested in scholarship support
            </label>
        </div>
    </section>

    {{-- VII. CONSENT --}}
    <section id="consent" class="scroll-mt-24">
        <div class="flex items-center justify-between mb-3">
            <div class="text-lg font-semibold">VII. Consent and Signature</div>
            <a href="#consent" class="text-xs text-gray-500 hover:text-gray-700">#</a>
        </div>

        <label class="block text-sm font-medium">Type your name as signature</label>
        <input name="consent[signature_name]" class="w-full border rounded p-2"
               value="{{ old('consent.signature_name', $consent->signature_name ?? '') }}">

        <div class="text-xs text-gray-600 mt-2">
            By submitting, you consent to the use of the information for alumni tracer purposes
            in accordance with the Data Privacy Act.
        </div>
    </section>

</div>

{{-- Keep full_name in sync with the 3 name fields --}}
<script>
(function () {
    const first = document.getElementById('first_name');
    const middle = document.getElementById('middle_name');
    const last = document.getElementById('last_name');
    const full = document.getElementById('full_name');
    const preview = document.getElementById('full_name_preview');

    if (!first || !last || !full || !preview) return;

    function build() {
        const parts = [
            (first.value || '').trim(),
            (middle?.value || '').trim(),
            (last.value || '').trim()
        ].filter(Boolean);

        const v = parts.join(' ');
        full.value = v;
        preview.textContent = v || '—';
    }

    [first, middle, last].forEach(el => el && el.addEventListener('input', build));
    build();
})();
</script>
