{{-- resources/views/user/intake.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Alumni Intake Form
            </h2>

            {{-- Hide Back to Dashboard if intake not completed --}}
            @if(auth()->user()?->intake_completed_at)
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-800 text-white rounded">
                    Back to Dashboard
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Warning --}}
            @if(session('warning'))
                <div class="p-3 mb-4 bg-yellow-100 border border-yellow-300 rounded text-yellow-900">
                    {{ session('warning') }}
                </div>
            @endif

            {{-- Errors --}}
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

            {{-- Success --}}
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

    {{-- ✅ Datalist for typed program/course suggestions --}}
    <datalist id="postNdmuProgramsList">
        @foreach(($programs_by_cat['college'] ?? []) as $p)
            <option value="{{ ($p['code'] ? $p['code'].' — ' : '') . $p['name'] }}"></option>
        @endforeach
        @foreach(($programs_by_cat['grad_school'] ?? []) as $p)
            <option value="{{ ($p['code'] ? $p['code'].' — ' : '') . $p['name'] }}"></option>
        @endforeach
        @foreach(($programs_by_cat['law'] ?? []) as $p)
            <option value="{{ ($p['code'] ? $p['code'].' — ' : '') . $p['name'] }}"></option>
        @endforeach
    </datalist>

    {{-- SCRIPT MUST BE AFTER PARTIAL SO WRAPPERS EXIST --}}
    <script>
        // SAFE JSON (controller already supplies these as plain arrays)
        const PROGRAMS_BY_CAT = @json($programs_by_cat ?? []);
        const STRANDS         = @json($strands_list ?? []);
        const RELIGIONS       = @json($religions_list ?? []);
        const NATIONALITIES   = @json($nationalities_list ?? []);

        document.addEventListener('DOMContentLoaded', () => {

            // ========= EXISTING ROWS =========
            const existingEducations  = @json(old('educations',  $alumnus?->educations?->toArray() ?? []));
            const existingEmployments = @json(old('employments', $alumnus?->employments?->toArray() ?? []));
            const existingCommunity   = @json(old('community',   $alumnus?->communityInvolvements?->toArray() ?? []));

            // ========= DOM WRAPPERS =========
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

            // ========= AGE AUTO COMPUTE =========
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

            // ==============================
            // CAPSLOCK / AUTO-UPPERCASE (SAFE)
            // ==============================
            function shouldUppercase(el) {
                if (!el) return false;
                if (el.dataset && el.dataset.noCaps === '1') return false;
                if (el.name === 'email') return false;
                if (el.type === 'date' || el.type === 'number' || el.type === 'checkbox' || el.type === 'radio' || el.type === 'file' || el.type === 'password') return false;

                const tag = (el.tagName || '').toLowerCase();
                if (tag === 'textarea') return true;
                if (tag === 'input') {
                    const t = (el.getAttribute('type') || 'text').toLowerCase();
                    return (t === 'text' || t === '' || t === 'search' || t === 'tel');
                }
                return false;
            }

            function forceUppercase(el) {
                if (!shouldUppercase(el)) return;

                el.style.textTransform = 'uppercase';

                if (el.dataset && el.dataset.capsBound === '1') return;
                if (el.dataset) el.dataset.capsBound = '1';

                const handler = () => {
                    const start = el.selectionStart;
                    const end   = el.selectionEnd;

                    const upper = (el.value || '').toUpperCase();
                    if (el.value !== upper) {
                        el.value = upper;
                        if (typeof start === 'number' && typeof end === 'number') {
                            try { el.setSelectionRange(start, end); } catch (e) {}
                        }
                    }
                };

                el.addEventListener('input', handler);
                el.addEventListener('blur', handler);
                el.addEventListener('change', handler);
                handler();
            }

            function applyUppercaseToContainer(root) {
                if (!root || !root.querySelectorAll) return;
                root.querySelectorAll('input, textarea').forEach(forceUppercase);
            }

            applyUppercaseToContainer(document);

            // ========= VISIBILITY RULES =========
            // ✅ Minimal change: remove program_id + specific_program for post_ndmu
            const EDU_RULES = {
                ndmu_elementary:  ['did_graduate','year_entered','year_graduated','last_year_attended'],
                ndmu_jhs:         ['did_graduate','year_entered','year_graduated','last_year_attended'],
                ndmu_shs:         ['did_graduate','year_entered','year_graduated','last_year_attended','strand_id'],
                ndmu_college:     ['did_graduate','year_entered','year_graduated','last_year_attended','program_id','specific_program'],
                ndmu_grad_school: ['did_graduate','year_entered','year_graduated','last_year_attended','program_id','specific_program'],
                ndmu_law:         ['did_graduate','year_entered','year_graduated','last_year_attended','program_id','specific_program'],

                // ✅ Post-NDMU: no Program dropdown. Only typed course + institution + year + notes
                post_ndmu:        ['institution_name','institution_address','course_degree','year_completed','notes'],
            };

            function setVisibility(cardEl, allowedKeys = []) {
                const allowed = new Set(allowedKeys);

                cardEl.querySelectorAll('[data-field]').forEach(wrapper => {
                    const key = wrapper.getAttribute('data-field');
                    if (key === 'level') return;

                    const show = allowed.has(key);
                    wrapper.style.display = show ? '' : 'none';

                    if (!show) {
                        wrapper.querySelectorAll('input, textarea, select').forEach(el => {
                            if (el.type === 'checkbox' || el.type === 'radio') el.checked = false;
                            else el.value = '';
                        });
                    }
                });
            }

            function applyGraduateLogic(cardEl) {
                const levelSel = cardEl.querySelector('select[data-edu-level]');
                const level = levelSel ? levelSel.value : '';
                if (level === 'post_ndmu') return; // don't affect post_ndmu fields

                const gradSel = cardEl.querySelector('select[data-did-graduate]');
                const gradVal = gradSel ? gradSel.value : '';

                const yg = cardEl.querySelector('[data-field="year_graduated"]');
                const la = cardEl.querySelector('[data-field="last_year_attended"]');
                if (!yg || !la) return;

                if (gradVal === '1') {
                    yg.style.display = '';
                    la.style.display = 'none';
                    la.querySelectorAll('input').forEach(i => i.value = '');
                } else if (gradVal === '0') {
                    yg.style.display = 'none';
                    la.style.display = '';
                    yg.querySelectorAll('input').forEach(i => i.value = '');
                } else {
                    yg.style.display = '';
                    la.style.display = '';
                }
            }

            function applyEducationVisibility(cardEl) {
                const levelSel = cardEl.querySelector('select[data-edu-level]');
                const level = levelSel ? levelSel.value : '';
                setVisibility(cardEl, EDU_RULES[level] || []);
                applyGraduateLogic(cardEl);
            }

            // ========= EDUCATION CARD =========
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
                            <select data-did-graduate name="educations[${i}][did_graduate]" class="w-full border rounded p-2">
                                <option value="">--</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div data-field="year_entered">
                            <label class="block font-medium">Year Started</label>
                            <input name="educations[${i}][year_entered]" class="w-full border rounded p-2"
                                   placeholder="YYYY" inputmode="numeric"
                                   value="${data.year_entered ?? ''}">
                        </div>

                        <div data-field="year_graduated">
                            <label class="block font-medium">Year Graduated</label>
                            <input name="educations[${i}][year_graduated]" class="w-full border rounded p-2"
                                   placeholder="YYYY" inputmode="numeric"
                                   value="${data.year_graduated ?? ''}">
                        </div>

                        <div data-field="last_year_attended">
                            <label class="block font-medium">Last School Year Attended</label>
                            <input name="educations[${i}][last_year_attended]" class="w-full border rounded p-2"
                                   placeholder="YYYY" inputmode="numeric"
                                   value="${data.last_year_attended ?? ''}">
                        </div>

                        <div data-field="strand_id">
                            <label class="block font-medium">Strand</label>
                            <select name="educations[${i}][strand_id]" class="w-full border rounded p-2">
                                <option value="">-- select --</option>
                                ${STRANDS.map(s => `<option value="${s.id}">${s.code} — ${s.name}</option>`).join('')}
                            </select>
                        </div>

                        <div data-field="program_id">
                            <label class="block font-medium">Program</label>
                            <select data-program name="educations[${i}][program_id]" class="w-full border rounded p-2">
                                <option value="">-- select --</option>
                                <option value="__other__">Others (Specify)</option>
                            </select>
                            <div class="text-xs text-gray-500 mt-1">
                                Choose from list, or select <b>Others (Specify)</b>.
                            </div>
                        </div>

                        <div data-field="specific_program">
                            <label class="block font-medium">If Others, specify program</label>
                            <input name="educations[${i}][specific_program]" class="w-full border rounded p-2"
                                   value="${data.specific_program ?? ''}">
                        </div>

                        <div class="md:col-span-2" data-field="institution_name">
                            <label class="block font-medium">Institution Name</label>
                            <input name="educations[${i}][institution_name]" class="w-full border rounded p-2"
                                   value="${data.institution_name ?? ''}">
                        </div>

                        <div class="md:col-span-2" data-field="institution_address">
                            <label class="block font-medium">Institution Address</label>
                            <input name="educations[${i}][institution_address]" class="w-full border rounded p-2"
                                   value="${data.institution_address ?? ''}">
                        </div>

                        {{-- ✅ Minimal change: label text removed "(Post-NDMU)" --}}
                        <div class="md:col-span-2" data-field="course_degree">
                            <label class="block font-medium">Program / Course Taken</label>
                            <input
                                list="postNdmuProgramsList"
                                name="educations[${i}][course_degree]"
                                class="w-full border rounded p-2"
                                placeholder="Type to search (e.g., Bachelor of …) — or type your own"
                                value="${data.course_degree ?? ''}"
                            >
                            <div class="text-xs text-gray-500 mt-1">
                                Start typing to see suggestions. If not listed, you may type freely.
                            </div>
                        </div>

                        <div data-field="year_completed">
                            <label class="block font-medium">Year Completed</label>
                            <input name="educations[${i}][year_completed]" class="w-full border rounded p-2"
                                   placeholder="YYYY" inputmode="numeric"
                                   value="${data.year_completed ?? ''}">
                        </div>

                        <div class="md:col-span-2" data-field="notes">
                            <label class="block font-medium">Notes</label>
                            <textarea name="educations[${i}][notes]" class="w-full border rounded p-2" rows="2">${data.notes ?? ''}</textarea>
                        </div>
                    </div>
                `;

                const levelSel = div.querySelector('select[data-edu-level]');
                const gradSel  = div.querySelector('select[data-did-graduate]');
                const progSel  = div.querySelector('select[data-program]');
                const otherWrap = div.querySelector('[data-field="specific_program"]');

                // Set saved values
                levelSel.value = data.level ?? '';

                const dg = data.did_graduate;
                if (dg === true || dg === 1 || dg === "1") gradSel.value = "1";
                else if (dg === false || dg === 0 || dg === "0") gradSel.value = "0";
                else gradSel.value = "";

                // Populate program dropdown for NDMU levels only
                function categoryForLevel(level) {
                    if (level === 'ndmu_college') return 'college';
                    if (level === 'ndmu_grad_school') return 'grad_school';
                    if (level === 'ndmu_law') return 'law';
                    return null;
                }

                function populatePrograms(programSelectEl, level) {
                    const cat = categoryForLevel(level);
                    const list = (cat && PROGRAMS_BY_CAT[cat]) ? PROGRAMS_BY_CAT[cat] : [];

                    let html = `<option value="">-- select --</option>`;
                    list.forEach(p => {
                        html += `<option value="${p.id}">${p.code ? (p.code + ' — ') : ''}${p.name}</option>`;
                    });
                    html += `<option value="__other__">Others (Specify)</option>`;
                    programSelectEl.innerHTML = html;
                }

                function syncOtherProgram() {
                    if (!progSel || !otherWrap) return;
                    const input = otherWrap.querySelector('input, textarea');
                    const isOther = progSel.value === '__other__';
                    otherWrap.style.display = isOther ? '' : 'none';
                    if (input) {
                        input.disabled = !isOther;
                        if (!isOther) input.value = '';
                    }
                }

                // Initial populate + restore program values
                populatePrograms(progSel, levelSel.value);

                if (data.program_id) {
                    progSel.value = String(data.program_id);
                } else if (data.specific_program) {
                    progSel.value = '__other__';
                } else {
                    progSel.value = '';
                }

                // Apply visibility rules (this will HIDE program fields for post_ndmu)
                applyEducationVisibility(div);
                syncOtherProgram();

                // Events
                levelSel.addEventListener('change', () => {
                    populatePrograms(progSel, levelSel.value);
                    progSel.value = '';
                    applyEducationVisibility(div);
                    syncOtherProgram();
                });

                gradSel.addEventListener('change', () => applyEducationVisibility(div));

                progSel.addEventListener('change', () => {
                    syncOtherProgram();
                });

                div.querySelector('[data-remove]').addEventListener('click', () => div.remove());

                // uppercase on dynamic fields
                applyUppercaseToContainer(div);

                return div;
            }

            // ========= EMPLOYMENT CARD =========
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
                            <label class="block font-medium">Company/Organization</label>
                            <input name="employments[${i}][company_name]" class="w-full border rounded p-2"
                                value="${data.company_name ?? ''}">
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

                        <div>
                            <label class="block font-medium">Occupation/Position</label>
                            <input name="employments[${i}][occupation_position]" class="w-full border rounded p-2"
                                value="${data.occupation_position ?? ''}">
                        </div>

                        <div>
                            <label class="block font-medium">Current Status</label>
                            <input name="employments[${i}][current_status]" class="w-full border rounded p-2"
                                value="${data.current_status ?? ''}">
                        </div>

                        <div>
                            <label class="block font-medium">Years of Service / Start</label>
                            <input name="employments[${i}][years_of_service_or_start]" class="w-full border rounded p-2"
                                value="${data.years_of_service_or_start ?? ''}">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-medium">Work Address</label>
                            <textarea name="employments[${i}][work_address]" class="w-full border rounded p-2" rows="2">${data.work_address ?? ''}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-medium">Contact Info</label>
                            <input name="employments[${i}][contact_info]" class="w-full border rounded p-2"
                                value="${data.contact_info ?? ''}">
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
                if (sel) sel.value = data.org_type ?? '';

                div.querySelector('[data-remove]').addEventListener('click', () => div.remove());
                applyUppercaseToContainer(div);

                return div;
            }

            // ========= COMMUNITY CARD =========
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
                applyUppercaseToContainer(div);

                return div;
            }

            // ========= INIT =========
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

            // ========= ADD BUTTONS =========
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

            // ========= FLASH MODAL (Success/Warning) =========
            (function () {
                const modal = document.getElementById('flashModal');
                const backdrop = document.getElementById('flashBackdrop');
                const closeBtn = document.getElementById('flashClose');
                const okBtn = document.getElementById('flashOk');
                const titleEl = document.getElementById('flashTitle');
                const msgEl = document.getElementById('flashMessage');

                if (!modal || !backdrop || !closeBtn || !okBtn || !titleEl || !msgEl) return;

                const successMsg = @json(session('success'));
                const warningMsg = @json(session('warning'));

                const message = successMsg || warningMsg;
                if (!message) return;

                titleEl.textContent = successMsg ? 'Success' : 'Notice';
                msgEl.textContent = message;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');

                function close() {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    modal.setAttribute('aria-hidden', 'true');
                }

                backdrop.addEventListener('click', close);
                closeBtn.addEventListener('click', close);
                okBtn.addEventListener('click', close);

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') close();
                });
            })();

        });
    </script>

    {{-- ✅ Success/Warning Modal --}}
    <div id="flashModal"
         class="fixed inset-0 z-50 hidden items-center justify-center"
         aria-hidden="true">
        {{-- Backdrop --}}
        <div id="flashBackdrop" class="absolute inset-0 bg-black/50"></div>

        {{-- Modal --}}
        <div class="relative w-full max-w-md mx-4 rounded-lg bg-white shadow-lg border">
            <div class="flex items-start justify-between px-5 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900" id="flashTitle">Notice</h3>
                <button type="button" id="flashClose"
                        class="text-gray-500 hover:text-gray-800 text-2xl leading-none">
                    &times;
                </button>
            </div>

            <div class="px-5 py-4">
                <div id="flashMessage" class="text-sm text-gray-700">
                    {{-- message injected by JS --}}
                </div>
            </div>

            <div class="px-5 py-4 border-t flex justify-end gap-2">
                <button type="button" id="flashOk"
                        class="px-4 py-2 rounded bg-gray-800 text-white hover:bg-gray-900">
                    OK
                </button>
            </div>
        </div>
    </div>

</x-app-layout>