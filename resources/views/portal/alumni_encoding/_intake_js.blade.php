{{-- resources/views/user/_intake_form_js.blade.php --}}
<script>
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
        ndmu_elementary: ['student_number','year_entered','year_graduated','last_year_attended'],
        ndmu_jhs: ['student_number','year_entered','year_graduated','last_year_attended'],
        ndmu_shs: ['student_number','year_entered','year_graduated','last_year_attended','strand_track'],
        ndmu_college: [
            'student_number','year_entered','year_graduated','last_year_attended',
            'strand_track','degree_program','thesis_title',
            'honors_awards','extracurricular_activities','clubs_organizations',
            'year_completed','scholarship_award'
        ],
        ndmu_grad_school: [
            'student_number','year_entered','year_graduated','last_year_attended',
            'strand_track','degree_program','thesis_title',
            'honors_awards','extracurricular_activities','clubs_organizations',
            'year_completed','scholarship_award'
        ],
        ndmu_law: [
            'student_number','year_entered','year_graduated','last_year_attended',
            'strand_track','degree_program','thesis_title',
            'honors_awards','extracurricular_activities','clubs_organizations',
            'year_completed','scholarship_award'
        ],
        post_ndmu: ['institution_name','institution_address','course_degree','year_completed','scholarship_award','notes'],
    };

    function setVisibility(cardEl, allowedKeys = []) {
        const allowed = new Set(allowedKeys);

        cardEl.querySelectorAll('[data-field]').forEach(wrapper => {
            const key = wrapper.getAttribute('data-field');
            if (key === 'level') return;

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
    // EDUCATION CARD
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

                <div data-field="student_number">
                    <label class="block font-medium">Student Number / LRN (if known)</label>
                    <input name="educations[${i}][student_number]" class="w-full border rounded p-2" value="${data.student_number ?? ''}">
                </div>

                <div data-field="year_entered">
                    <label class="block font-medium">Year Entered</label>
                    <input name="educations[${i}][year_entered]" class="w-full border rounded p-2" placeholder="YYYY" inputmode="numeric" value="${data.year_entered ?? ''}">
                </div>

                <div data-field="year_graduated">
                    <label class="block font-medium">Year Graduated</label>
                    <input name="educations[${i}][year_graduated]" class="w-full border rounded p-2" placeholder="YYYY" inputmode="numeric" value="${data.year_graduated ?? ''}">
                </div>

                <div data-field="last_year_attended">
                    <label class="block font-medium">Last Year Attended</label>
                    <input name="educations[${i}][last_year_attended]" class="w-full border rounded p-2" placeholder="YYYY" inputmode="numeric" value="${data.last_year_attended ?? ''}">
                </div>

                <div data-field="strand_track">
                    <label class="block font-medium">Strand/Track (SHS)</label>
                    <input name="educations[${i}][strand_track]" class="w-full border rounded p-2" value="${data.strand_track ?? ''}">
                    <div class="text-xs text-gray-500 mt-1">Optional for College/Grad/Law.</div>
                </div>

                <div class="md:col-span-2" data-field="degree_program">
                    <label class="block font-medium">Degree/Program</label>
                    <input name="educations[${i}][degree_program]" class="w-full border rounded p-2" value="${data.degree_program ?? ''}">
                </div>

                <div class="md:col-span-2" data-field="thesis_title">
                    <label class="block font-medium">Thesis Title (if applicable)</label>
                    <input name="educations[${i}][thesis_title]" class="w-full border rounded p-2" value="${data.thesis_title ?? ''}">
                </div>

                <div class="md:col-span-2" data-field="honors_awards">
                    <label class="block font-medium">Honors/Awards</label>
                    <textarea name="educations[${i}][honors_awards]" class="w-full border rounded p-2" rows="2">${data.honors_awards ?? ''}</textarea>
                </div>

                <div class="md:col-span-2" data-field="extracurricular_activities">
                    <label class="block font-medium">Extracurricular Activities</label>
                    <textarea name="educations[${i}][extracurricular_activities]" class="w-full border rounded p-2" rows="2">${data.extracurricular_activities ?? ''}</textarea>
                </div>

                <div class="md:col-span-2" data-field="clubs_organizations">
                    <label class="block font-medium">Clubs/Organizations Joined</label>
                    <textarea name="educations[${i}][clubs_organizations]" class="w-full border rounded p-2" rows="2">${data.clubs_organizations ?? ''}</textarea>
                </div>

                <div data-field="year_completed">
                    <label class="block font-medium">Year Completed</label>
                    <input name="educations[${i}][year_completed]" class="w-full border rounded p-2" placeholder="YYYY" inputmode="numeric" value="${data.year_completed ?? ''}">
                </div>

                <div data-field="scholarship_award">
                    <label class="block font-medium">Scholarship/Award</label>
                    <input name="educations[${i}][scholarship_award]" class="w-full border rounded p-2" value="${data.scholarship_award ?? ''}">
                </div>

                <div class="md:col-span-2" data-field="institution_name">
                    <label class="block font-medium">Institution Name</label>
                    <input name="educations[${i}][institution_name]" class="w-full border rounded p-2" value="${data.institution_name ?? ''}">
                </div>

                <div class="md:col-span-2" data-field="institution_address">
                    <label class="block font-medium">Institution Address</label>
                    <input name="educations[${i}][institution_address]" class="w-full border rounded p-2" value="${data.institution_address ?? ''}">
                </div>

                <div class="md:col-span-2" data-field="course_degree">
                    <label class="block font-medium">Course / Degree (Post-NDMU)</label>
                    <input name="educations[${i}][course_degree]" class="w-full border rounded p-2" value="${data.course_degree ?? ''}">
                </div>

                <div class="md:col-span-2" data-field="notes">
                    <label class="block font-medium">Notes</label>
                    <textarea name="educations[${i}][notes]" class="w-full border rounded p-2" rows="2">${data.notes ?? ''}</textarea>
                </div>

            </div>
        `;

        const sel = div.querySelector('select[data-edu-level]');
        sel.value = data.level ?? '';

        applyEducationVisibility(div);
        sel.addEventListener('change', () => applyEducationVisibility(div));

        div.querySelector('[data-remove]').addEventListener('click', () => div.remove());

        return div;
    }

    // =========================
    // EMPLOYMENT CARD
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
    // COMMUNITY CARD
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
    // INIT
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
