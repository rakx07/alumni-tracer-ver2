{{-- resources/views/user/_intake_form.blade.php --}}
@php
    // Make this partial reusable for both Create (intake) and Edit (portal edit)
    // It expects $alumnus to be passed, but it can be null.
    $alumnus = $alumnus ?? null;

    $sex = old('sex', $alumnus->sex ?? '');
    $cs  = old('civil_status', $alumnus->civil_status ?? '');

    $pref    = $alumnus->engagementPreference ?? null;
    $consent = $alumnus->consent ?? null;
@endphp

<div class="bg-white shadow rounded p-6 space-y-8">

    {{-- I. PERSONAL INFO --}}
    <section id="personal" class="scroll-mt-24">
        <div class="flex items-center justify-between mb-3">
            <div class="text-lg font-semibold">I. Personal Information</div>
            <a href="#personal" class="text-xs text-gray-500 hover:text-gray-700">#</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium">Full Name</label>
                <input name="full_name" class="w-full border rounded p-2"
                       value="{{ old('full_name', $alumnus->full_name ?? '') }}" required>
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
                <input type="number" name="age" class="w-full border rounded p-2"
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
                       value="{{ old('email', $alumnus->email ?? '') }}">
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

    {{-- II. ADDRESSES (separate section so Edit Addresses works) --}}
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

    {{-- III. EDUCATIONS --}}
    <section id="academic" class="scroll-mt-24">
        <div class="flex items-center justify-between mb-3">
            <div class="text-lg font-semibold">III. Academic Background (Flexible Levels)</div>
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

        <div class="mt-3 text-sm font-medium">Preferred updates through:</div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm mt-2">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="engagement[prefer_email]" value="1"
                    {{ old('engagement.prefer_email', $pref->prefer_email ?? false) ? 'checked' : '' }}>
                Email
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="engagement[prefer_facebook]" value="1"
                    {{ old('engagement.prefer_facebook', $pref->prefer_facebook ?? false) ? 'checked' : '' }}>
                Facebook
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="engagement[prefer_sms]" value="1"
                    {{ old('engagement.prefer_sms', $pref->prefer_sms ?? false) ? 'checked' : '' }}>
                Text/SMS
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
            By submitting, you consent to the use of the information for alumni tracer purposes in accordance with the Data Privacy Act.
        </div>
    </section>
</div>

<script>
    // Existing rows (works for create + edit)
    const existingEducations = @json(old('educations', $alumnus?->educations?->toArray() ?? []));
    const existingEmployments = @json(old('employments', $alumnus?->employments?->toArray() ?? []));
    const existingCommunity = @json(old('community', $alumnus?->communityInvolvements?->toArray() ?? []));

    const educationWrap = document.getElementById('education-wrap');
    const employmentWrap = document.getElementById('employment-wrap');
    const communityWrap = document.getElementById('community-wrap');

    function educationCard(i, data = {}) {
        const div = document.createElement('div');
        div.className = "border rounded p-4 bg-gray-50";
        div.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <div class="font-semibold">Education Level</div>
                <button type="button" class="text-red-600" data-remove>Remove</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                <div>
                    <label class="block font-medium">Level</label>
                    <select name="educations[${i}][level]" class="w-full border rounded p-2" required>
                        <option value="">-- select --</option>
                        <option value="ndmu_elementary">NDMU Elementary</option>
                        <option value="ndmu_jhs">NDMU Junior High School</option>
                        <option value="ndmu_shs">NDMU Senior High School</option>
                        <option value="ndmu_college">NDMU College</option>
                        <option value="ndmu_grad_school">NDMU Graduate School</option>
                        <option value="ndmu_law">NDMU Law School</option>
                        <option value="post_ndmu">Education after NDMU</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium">Student Number (if known)</label>
                    <input name="educations[${i}][student_number]" class="w-full border rounded p-2" value="${data.student_number ?? ''}">
                </div>

                <div>
                    <label class="block font-medium">Year Entered</label>
                    <input name="educations[${i}][year_entered]" class="w-full border rounded p-2" value="${data.year_entered ?? ''}">
                </div>

                <div>
                    <label class="block font-medium">Year Graduated</label>
                    <input name="educations[${i}][year_graduated]" class="w-full border rounded p-2" value="${data.year_graduated ?? ''}">
                </div>

                <div>
                    <label class="block font-medium">Last Year Attended</label>
                    <input name="educations[${i}][last_year_attended]" class="w-full border rounded p-2" value="${data.last_year_attended ?? ''}">
                </div>

                <div>
                    <label class="block font-medium">Strand/Track (SHS)</label>
                    <input name="educations[${i}][strand_track]" class="w-full border rounded p-2" value="${data.strand_track ?? ''}">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium">Degree/Program (College/Grad/Law) / Course (Post-NDMU)</label>
                    <input name="educations[${i}][degree_program]" class="w-full border rounded p-2" value="${data.degree_program ?? ''}">
                    <input name="educations[${i}][course_degree]" class="w-full border rounded p-2 mt-2" placeholder="(optional) Course/Degree for Post-NDMU" value="${data.course_degree ?? ''}">
                </div>

                <div>
                    <label class="block font-medium">Specific Program (Grad/Law)</label>
                    <input name="educations[${i}][specific_program]" class="w-full border rounded p-2" value="${data.specific_program ?? ''}">
                </div>

                <div>
                    <label class="block font-medium">Research Title (Grad/Law)</label>
                    <input name="educations[${i}][research_title]" class="w-full border rounded p-2" value="${data.research_title ?? ''}">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium">Thesis Title (if applicable)</label>
                    <input name="educations[${i}][thesis_title]" class="w-full border rounded p-2" value="${data.thesis_title ?? ''}">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium">Honors/Awards</label>
                    <textarea name="educations[${i}][honors_awards]" class="w-full border rounded p-2" rows="2">${data.honors_awards ?? ''}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium">Extracurricular Activities</label>
                    <textarea name="educations[${i}][extracurricular_activities]" class="w-full border rounded p-2" rows="2">${data.extracurricular_activities ?? ''}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium">Clubs/Organizations Joined</label>
                    <textarea name="educations[${i}][clubs_organizations]" class="w-full border rounded p-2" rows="2">${data.clubs_organizations ?? ''}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium">Post-NDMU Institution Name / Address</label>
                    <input name="educations[${i}][institution_name]" class="w-full border rounded p-2" placeholder="Institution Name" value="${data.institution_name ?? ''}">
                    <input name="educations[${i}][institution_address]" class="w-full border rounded p-2 mt-2" placeholder="Institution Address" value="${data.institution_address ?? ''}">
                </div>

                <div>
                    <label class="block font-medium">Year Completed</label>
                    <input name="educations[${i}][year_completed]" class="w-full border rounded p-2" value="${data.year_completed ?? ''}">
                </div>

                <div>
                    <label class="block font-medium">Scholarship/Award</label>
                    <input name="educations[${i}][scholarship_award]" class="w-full border rounded p-2" value="${data.scholarship_award ?? ''}">
                </div>
            </div>
        `;

        const sel = div.querySelector(`select[name="educations[${i}][level]"]`);
        sel.value = data.level ?? '';

        div.querySelector('[data-remove]').addEventListener('click', () => div.remove());
        return div;
    }

    function employmentCard(i, data = {}) {
        const div = document.createElement('div');
        div.className = "border rounded p-4 bg-gray-50";
        div.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <div class="font-semibold">Employment</div>
                <button type="button" class="text-red-600" data-remove>Remove</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                <div>
                    <label class="block font-medium">Current Status</label>
                    <input name="employments[${i}][current_status]" class="w-full border rounded p-2" value="${data.current_status ?? ''}">
                </div>
                <div>
                    <label class="block font-medium">Occupation/Position</label>
                    <input name="employments[${i}][occupation_position]" class="w-full border rounded p-2" value="${data.occupation_position ?? ''}">
                </div>
                <div>
                    <label class="block font-medium">Company/Organization</label>
                    <input name="employments[${i}][company_name]" class="w-full border rounded p-2" value="${data.company_name ?? ''}">
                </div>
                <div>
                    <label class="block font-medium">Type of Organization</label>
                    <select name="employments[${i}][org_type]" class="w-full border rounded p-2">
                        <option value="">--</option>
                        <option value="government">Government</option>
                        <option value="private">Private</option>
                        <option value="ngo">NGO</option>
                        <option value="self_employed">Self-employed</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium">Work Address</label>
                    <textarea name="employments[${i}][work_address]" class="w-full border rounded p-2" rows="2">${data.work_address ?? ''}</textarea>
                </div>
                <div>
                    <label class="block font-medium">Contact Info</label>
                    <input name="employments[${i}][contact_info]" class="w-full border rounded p-2" value="${data.contact_info ?? ''}">
                </div>
                <div>
                    <label class="block font-medium">Years of Service / Start</label>
                    <input name="employments[${i}][years_of_service_or_start]" class="w-full border rounded p-2" value="${data.years_of_service_or_start ?? ''}">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium">Licenses/Certifications</label>
                    <textarea name="employments[${i}][licenses_certifications]" class="w-full border rounded p-2" rows="2">${data.licenses_certifications ?? ''}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium">Achievements/Awards</label>
                    <textarea name="employments[${i}][achievements_awards]" class="w-full border rounded p-2" rows="2">${data.achievements_awards ?? ''}</textarea>
                </div>
            </div>
        `;

        const sel = div.querySelector(`select[name="employments[${i}][org_type]"]`);
        sel.value = data.org_type ?? '';

        div.querySelector('[data-remove]').addEventListener('click', () => div.remove());
        return div;
    }

    function communityCard(i, data = {}) {
        const div = document.createElement('div');
        div.className = "border rounded p-4 bg-gray-50";
        div.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <div class="font-semibold">Organization / Association</div>
                <button type="button" class="text-red-600" data-remove>Remove</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                <div>
                    <label class="block font-medium">Organization</label>
                    <input name="community[${i}][organization]" class="w-full border rounded p-2" value="${data.organization ?? ''}">
                </div>
                <div>
                    <label class="block font-medium">Role/Position</label>
                    <input name="community[${i}][role_position]" class="w-full border rounded p-2" value="${data.role_position ?? ''}">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium">Years Involved</label>
                    <input name="community[${i}][years_involved]" class="w-full border rounded p-2" value="${data.years_involved ?? ''}">
                </div>
            </div>
        `;
        div.querySelector('[data-remove]').addEventListener('click', () => div.remove());
        return div;
    }

    function init() {
        educationWrap.innerHTML = "";
        employmentWrap.innerHTML = "";
        communityWrap.innerHTML = "";

        let eIdx = 0;
        (existingEducations.length ? existingEducations : [{}])
            .forEach(row => educationWrap.appendChild(educationCard(eIdx++, row)));

        let empIdx = 0;
        (existingEmployments.length ? existingEmployments : [{}])
            .forEach(row => employmentWrap.appendChild(employmentCard(empIdx++, row)));

        let cIdx = 0;
        (existingCommunity.length ? existingCommunity : [{}])
            .forEach(row => communityWrap.appendChild(communityCard(cIdx++, row)));
    }

    init();

    document.getElementById('add-education')?.addEventListener('click', () => {
        const i = educationWrap.querySelectorAll(':scope > div').length;
        educationWrap.appendChild(educationCard(i, {}));
    });

    document.getElementById('add-employment')?.addEventListener('click', () => {
        const i = employmentWrap.querySelectorAll(':scope > div').length;
        employmentWrap.appendChild(employmentCard(i, {}));
    });

    document.getElementById('add-community')?.addEventListener('click', () => {
        const i = communityWrap.querySelectorAll(':scope > div').length;
        communityWrap.appendChild(communityCard(i, {}));
    });
</script>
