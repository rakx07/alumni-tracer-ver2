{{-- resources/views/user/intake.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Alumni Intake Form
            </h2>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-800 text-white rounded">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="p-4 mb-4 bg-red-100 border border-red-300 rounded">
                    <div class="font-semibold mb-2">Please fix the following:</div>
                    <ul class="list-disc ml-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="p-3 mb-4 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('intake.save') }}" novalidate>
                @csrf

                {{-- THIS LOADS YOUR PARTIAL --}}
                @include('user._intake_form', ['alumnus' => $alumnus])

                <div class="mt-6">
                    <button class="px-5 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Save Intake Record
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- SCRIPT MUST BE AFTER PARTIAL SO WRAPPERS EXIST --}}
    <script>
    const PROGRAMS_BY_CAT = @json($programs_by_cat);
    const STRANDS = @json($strands_list);


    document.addEventListener('DOMContentLoaded', () => {

        // =========================
        // Existing rows (create + edit)
        // =========================
        const existingEducations  = @json(old('educations',  $alumnus?->educations?->toArray() ?? []));
        const existingEmployments = @json(old('employments', $alumnus?->employments?->toArray() ?? []));
        const existingCommunity   = @json(old('community',   $alumnus?->communityInvolvements?->toArray() ?? []));

        // =========================
        // DOM WRAPPERS
        // =========================
        const educationWrap  = document.getElementById('education-wrap');
        const employmentWrap = document.getElementById('employment-wrap');
        const communityWrap  = document.getElementById('community-wrap');

        const addEduBtn = document.getElementById('add-education');
        const addEmpBtn = document.getElementById('add-employment');
        const addComBtn = document.getElementById('add-community');

        if (!educationWrap || !employmentWrap || !communityWrap) {
            console.warn('Missing wrapper(s):', { educationWrap, employmentWrap, communityWrap });
            return;
        }

        // =========================
        // AGE AUTO COMPUTE (make age readonly)
        // =========================
        const ageInput = document.querySelector('input[name="age"]');
        if (ageInput) {
            ageInput.readOnly = true;
            ageInput.classList.add('bg-gray-100', 'text-gray-600', 'cursor-not-allowed');
            ageInput.setAttribute('tabindex', '-1');
        }

        function computeAgeFromBirthdate(birthdateStr) {
            if (!birthdateStr) return '';
            const dob = new Date(birthdateStr + 'T00:00:00');
            if (isNaN(dob.getTime())) return '';
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
            if (age < 0) return '';
            return age;
        }

        function syncAgeFromBirthdate() {
            const birthInput = document.querySelector('input[name="birthdate"]');
            if (!birthInput || !ageInput) return;
            ageInput.value = computeAgeFromBirthdate(birthInput.value);
            if (!birthInput.value) ageInput.value = '';
        }

        const birthInput = document.querySelector('input[name="birthdate"]');
        if (birthInput) {
            birthInput.addEventListener('change', syncAgeFromBirthdate);
            birthInput.addEventListener('input', syncAgeFromBirthdate);
        }
        syncAgeFromBirthdate();

        // =========================
        // EDUCATION VISIBILITY RULES (YOUR REQUIREMENTS)
        // =========================
        const EDU_RULES = {
            ndmu_elementary: [
                'did_graduate','year_entered','year_graduated','last_year_attended'
            ],
            ndmu_jhs: [
                'did_graduate','year_entered','year_graduated','last_year_attended'
            ],
            ndmu_shs: [
                'did_graduate','year_entered','year_graduated','last_year_attended','strand_id'
            ],
            ndmu_college: [
                'did_graduate','year_entered','year_graduated','last_year_attended',
                'program_id','specific_program'
            ],
            ndmu_grad_school: [
                'did_graduate','year_entered','year_graduated','last_year_attended',
                'program_id','specific_program'
            ],
            ndmu_law: [
                'did_graduate','year_entered','year_graduated','last_year_attended',
                'program_id','specific_program'
            ],
            post_ndmu: [
                'institution_name','institution_address','course_degree','year_completed','notes'
            ],
        };


        function setVisibility(cardEl, allowedKeys = []) {
            const allowed = new Set(allowedKeys);

            cardEl.querySelectorAll('[data-field]').forEach(wrapper => {
                const key = wrapper.getAttribute('data-field');
                if (key === 'level') return; // always show Level selector

                const show = allowed.has(key);
                wrapper.style.display = show ? '' : 'none';

                // Clear values when hidden
                if (!show) {
                    wrapper.querySelectorAll('input, textarea, select').forEach(el => {
                        if (el.type === 'checkbox' || el.type === 'radio') el.checked = false;
                        else el.value = '';
                    });
                }
            });
        }

        function applyEducationVisibility(cardEl) {
            const levelSel = cardEl.querySelector('select[data-edu-level]');
            const level = levelSel ? levelSel.value : '';
            setVisibility(cardEl, EDU_RULES[level] || []);
        }

        // =========================
        // EDUCATION CARD (with data-field hooks)
        // =========================
       function educationCard(i, data = {}) {
    const div = document.createElement('div');
    div.className = "border rounded p-4 bg-gray-50";

    div.innerHTML = `
        <div class="flex items-center justify-between mb-2">
            <div class="font-semibold">Education Level</div>
            <button type="button" class="text-red-600" data-remove>Remove</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">

            <div data-field="level">
                <label class="block font-medium">Level</label>
                <select data-edu-level name="educations[${i}][level]" class="w-full border rounded p-2" required>
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

            <div data-field="did_graduate">
                <label class="block font-medium">Did you graduate?</label>
                <select name="educations[${i}][did_graduate]" class="w-full border rounded p-2">
                    <option value="">--</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div data-field="year_entered">
                <label class="block font-medium">Year Started</label>
                <input name="educations[${i}][year_entered]" class="w-full border rounded p-2"
                       value="${data.year_entered ?? ''}">
            </div>

            <div data-field="year_graduated">
                <label class="block font-medium">Year Graduated</label>
                <input name="educations[${i}][year_graduated]" class="w-full border rounded p-2"
                       value="${data.year_graduated ?? ''}">
            </div>

            <div data-field="last_year_attended">
                <label class="block font-medium">Last School Year Attended</label>
                <input name="educations[${i}][last_year_attended]" class="w-full border rounded p-2"
                       value="${data.last_year_attended ?? ''}">
            </div>

            <div data-field="strand_id">
                <label class="block font-medium">Strand</label>
                <select name="educations[${i}][strand_id]" class="w-full border rounded p-2">
                    <option value="">-- select --</option>
                    ${STRANDS.map(s =>
                        `<option value="${s.id}">${s.code} — ${s.name}</option>`
                    ).join('')}
                </select>
            </div>

            <div data-field="program_id">
                <label class="block font-medium">Program</label>
                <select name="educations[${i}][program_id]" class="w-full border rounded p-2">
                    <option value="">-- select --</option>
                </select>
            </div>

            <div data-field="specific_program">
                <label class="block font-medium">If Others, specify program</label>
                <input name="educations[${i}][specific_program]" class="w-full border rounded p-2"
                       value="${data.specific_program ?? ''}">
            </div>
        </div>
    `;

    const levelSel = div.querySelector('[data-edu-level]');
    const programSel = div.querySelector(`select[name="educations[${i}][program_id]"]`);

    function populatePrograms() {
        const level = levelSel.value;
        const cat =
            level === 'ndmu_college' ? 'college' :
            level === 'ndmu_grad_school' ? 'grad_school' :
            level === 'ndmu_law' ? 'law' : null;

        if (!cat || !PROGRAMS_BY_CAT[cat]) {
            programSel.innerHTML = `<option value="">-- select --</option>`;
            return;
        }

        programSel.innerHTML =
            `<option value="">-- select --</option>` +
            PROGRAMS_BY_CAT[cat].map(p =>
                `<option value="${p.id}">${p.code ? p.code+' — ' : ''}${p.name}</option>`
            ).join('') +
            `<option value="__other__">Others (Specify)</option>`;
    }

    levelSel.value = data.level ?? '';
    populatePrograms();

    programSel.value = data.program_id ?? (
        data.specific_program ? '__other__' : ''
    );

    applyEducationVisibility(div);
    levelSel.addEventListener('change', () => {
        populatePrograms();
        applyEducationVisibility(div);
    });

    div.querySelector('[data-remove]').onclick = () => div.remove();
    return div;
}


        // =========================
        // EMPLOYMENT CARD (unchanged)
        // =========================
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

        // =========================
        // COMMUNITY CARD (unchanged)
        // =========================
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

        // =========================
        // INIT (renders at least 1 row)
        // =========================
        function init() {
            educationWrap.innerHTML = "";
            employmentWrap.innerHTML = "";
            communityWrap.innerHTML = "";

            const eduRows = (Array.isArray(existingEducations) && existingEducations.length) ? existingEducations : [{}];
            const empRows = (Array.isArray(existingEmployments) && existingEmployments.length) ? existingEmployments : [{}];
            const comRows = (Array.isArray(existingCommunity) && existingCommunity.length) ? existingCommunity : [{}];

            let eIdx = 0;
            eduRows.forEach(row => educationWrap.appendChild(educationCard(eIdx++, row)));

            let empIdx = 0;
            empRows.forEach(row => employmentWrap.appendChild(employmentCard(empIdx++, row)));

            let cIdx = 0;
            comRows.forEach(row => communityWrap.appendChild(communityCard(cIdx++, row)));
        }

        init();

        // Add buttons
        addEduBtn?.addEventListener('click', () => {
            const i = educationWrap.querySelectorAll(':scope > div').length;
            educationWrap.appendChild(educationCard(i, {}));
        });

        addEmpBtn?.addEventListener('click', () => {
            const i = employmentWrap.querySelectorAll(':scope > div').length;
            employmentWrap.appendChild(employmentCard(i, {}));
        });

        addComBtn?.addEventListener('click', () => {
            const i = communityWrap.querySelectorAll(':scope > div').length;
            communityWrap.appendChild(communityCard(i, {}));
        });

    });
    </script>
</x-app-layout>
