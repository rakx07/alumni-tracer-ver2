{{-- resources/views/portals/records/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Alumni Record
                </h2>
                <div class="text-sm text-gray-600">
                    Record ID: <span class="font-medium">#{{ $alumnus->id }}</span>
                    <span class="mx-2">•</span>
                    Last updated:
                    <span class="font-medium">{{ optional($alumnus->updated_at)->format('M d, Y h:i A') }}</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('portal.records.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">
                    Back to Records
                </a>

                <a href="{{ route('portal.records.edit', $alumnus) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Edit (All)
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $val = fn($v, $fallback = '—') => filled($v) ? $v : $fallback;
        $boolText = fn($b) => $b ? 'Yes' : 'No';

        $pref = $alumnus->engagementPreference ?? null;
        $consent = $alumnus->consent ?? null;

        $educationLabels = [
            'ndmu_elementary' => 'NDMU Elementary',
            'ndmu_jhs' => 'NDMU Junior High School',
            'ndmu_shs' => 'NDMU Senior High School',
            'ndmu_college' => 'NDMU College',
            'ndmu_grad_school' => 'NDMU Graduate School',
            'ndmu_law' => 'NDMU Law School',
            'post_ndmu' => 'Education after NDMU',
        ];

        $orgTypeLabels = [
            'government' => 'Government',
            'private' => 'Private',
            'ngo' => 'NGO',
            'self_employed' => 'Self-employed',
            'other' => 'Other',
        ];

        // helper for section edit url
        $editSectionUrl = function(string $section) use ($alumnus) {
            return route('portal.records.edit', [$alumnus, 'section' => $section]) . "#{$section}";
        };
    @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- QUICK NAV --}}
            <div class="bg-white shadow rounded border border-gray-100 p-4">
                <div class="text-sm font-semibold text-gray-700 mb-2">Jump to section:</div>
                <div class="flex flex-wrap gap-2 text-sm">
                    <a href="#personal" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Personal</a>
                    <a href="#addresses" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Addresses</a>
                    <a href="#academic" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Academic</a>
                    <a href="#employment" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Employment</a>
                    <a href="#community" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Community</a>
                    <a href="#engagement" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Engagement</a>
                    <a href="#consent" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Consent</a>
                </div>
            </div>

            {{-- PERSONAL --}}
            <div id="personal" class="bg-white shadow rounded border border-gray-100">
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">I. Personal Information</h3>
                            <div class="text-sm text-gray-600 mt-1">
                                {{ $val($alumnus->full_name) }} • {{ $val($alumnus->email) }}
                            </div>
                        </div>

                        <a href="{{ $editSectionUrl('personal') }}"
                           class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                            Edit Personal
                        </a>
                    </div>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="bg-gray-50 border rounded p-4">
                            <div class="text-gray-500">Full Name</div>
                            <div class="font-semibold text-gray-900">{{ $val($alumnus->full_name) }}</div>
                        </div>
                        <div class="bg-gray-50 border rounded p-4">
                            <div class="text-gray-500">Nickname</div>
                            <div class="font-semibold text-gray-900">{{ $val($alumnus->nickname) }}</div>
                        </div>

                        <div class="bg-gray-50 border rounded p-4">
                            <div class="text-gray-500">Sex</div>
                            <div class="font-semibold text-gray-900">{{ $val($alumnus->sex) }}</div>
                        </div>
                        <div class="bg-gray-50 border rounded p-4">
                            <div class="text-gray-500">Civil Status</div>
                            <div class="font-semibold text-gray-900">{{ $val($alumnus->civil_status) }}</div>
                        </div>

                        <div class="bg-gray-50 border rounded p-4">
                            <div class="text-gray-500">Birthdate</div>
                            <div class="font-semibold text-gray-900">
                                {{ $alumnus->birthdate ? \Carbon\Carbon::parse($alumnus->birthdate)->format('M d, Y') : '—' }}
                            </div>
                        </div>
                        <div class="bg-gray-50 border rounded p-4">
                            <div class="text-gray-500">Age</div>
                            <div class="font-semibold text-gray-900">{{ $val($alumnus->age) }}</div>
                        </div>

                        <div class="bg-gray-50 border rounded p-4">
                            <div class="text-gray-500">Contact Number</div>
                            <div class="font-semibold text-gray-900">{{ $val($alumnus->contact_number) }}</div>
                        </div>
                        <div class="bg-gray-50 border rounded p-4">
                            <div class="text-gray-500">Facebook / Social Media</div>
                            <div class="font-semibold text-gray-900 break-all">{{ $val($alumnus->facebook) }}</div>
                        </div>

                        <div class="bg-gray-50 border rounded p-4">
                            <div class="text-gray-500">Nationality</div>
                            <div class="font-semibold text-gray-900">{{ $val($alumnus->nationality) }}</div>
                        </div>
                        <div class="bg-gray-50 border rounded p-4">
                            <div class="text-gray-500">Religion</div>
                            <div class="font-semibold text-gray-900">{{ $val($alumnus->religion) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ADDRESSES --}}
            <div id="addresses" class="bg-white shadow rounded border border-gray-100">
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">II. Addresses</h3>
                            <div class="text-sm text-gray-600 mt-1">Home and current/present address</div>
                        </div>

                        <a href="{{ $editSectionUrl('addresses') }}"
                           class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                            Edit Addresses
                        </a>
                    </div>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="bg-gray-50 border rounded p-4">
                            <div class="text-gray-500 font-medium">Home Address</div>
                            <div class="mt-2 text-gray-900 whitespace-pre-line">{{ $val($alumnus->home_address) }}</div>
                        </div>
                        <div class="bg-gray-50 border rounded p-4">
                            <div class="text-gray-500 font-medium">Current / Present Address</div>
                            <div class="mt-2 text-gray-900 whitespace-pre-line">{{ $val($alumnus->current_address) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACADEMIC --}}
            <div id="academic" class="bg-white shadow rounded border border-gray-100">
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">III. Academic Background</h3>
                            <div class="text-sm text-gray-600 mt-1">
                                Total: <span class="font-semibold text-gray-800">{{ $alumnus->educations->count() }}</span>
                            </div>
                        </div>

                        <a href="{{ $editSectionUrl('academic') }}"
                           class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                            Edit Academic
                        </a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse($alumnus->educations as $e)
                            <div class="border rounded p-4 bg-gray-50">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2">
                                    <div>
                                        <div class="font-semibold text-gray-900">
                                            {{ $educationLabels[$e->level] ?? $val($e->level) }}
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            Student No.: <span class="font-medium text-gray-800">{{ $val($e->student_number) }}</span>
                                        </div>
                                    </div>

                                    <div class="text-sm text-gray-700">
                                        <div><span class="font-medium">Year Entered:</span> {{ $val($e->year_entered) }}</div>
                                        <div><span class="font-medium">Year Graduated:</span> {{ $val($e->year_graduated) }}</div>
                                        <div><span class="font-medium">Last Year Attended:</span> {{ $val($e->last_year_attended) }}</div>
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <div class="text-gray-500 font-medium">Strand / Track</div>
                                        <div class="text-gray-900 mt-1">{{ $val($e->strand_track) }}</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-500 font-medium">Degree / Program</div>
                                        <div class="text-gray-900 mt-1">{{ $val($e->degree_program) }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-600 text-sm bg-gray-50 border rounded p-4">
                                No education records available.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- EMPLOYMENT --}}
            <div id="employment" class="bg-white shadow rounded border border-gray-100">
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">IV. Employment / Professional Information</h3>
                            <div class="text-sm text-gray-600 mt-1">
                                Total: <span class="font-semibold text-gray-800">{{ $alumnus->employments->count() }}</span>
                            </div>
                        </div>

                        <a href="{{ $editSectionUrl('employment') }}"
                           class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                            Edit Employment
                        </a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse($alumnus->employments as $emp)
                            <div class="border rounded p-4 bg-gray-50">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $val($emp->occupation_position) }}</div>
                                        <div class="text-sm text-gray-600 mt-1">{{ $val($emp->company_name) }}</div>
                                    </div>

                                    <div class="text-sm text-gray-700">
                                        <div><span class="font-medium">Status:</span> {{ $val($emp->current_status) }}</div>
                                        <div>
                                            <span class="font-medium">Org Type:</span>
                                            {{ $orgTypeLabels[$emp->org_type] ?? $val($emp->org_type) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-600 text-sm bg-gray-50 border rounded p-4">
                                No employment records available.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- COMMUNITY --}}
            <div id="community" class="bg-white shadow rounded border border-gray-100">
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">V. Community Involvement & Affiliations</h3>
                            <div class="text-sm text-gray-600 mt-1">
                                Total: <span class="font-semibold text-gray-800">{{ $alumnus->communityInvolvements->count() }}</span>
                            </div>
                        </div>

                        <a href="{{ $editSectionUrl('community') }}"
                           class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                            Edit Community
                        </a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse($alumnus->communityInvolvements as $c)
                            <div class="border rounded p-4 bg-gray-50">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                    <div class="md:col-span-2">
                                        <div class="text-gray-500 font-medium">Organization</div>
                                        <div class="text-gray-900 mt-1">{{ $val($c->organization) }}</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-500 font-medium">Role / Position</div>
                                        <div class="text-gray-900 mt-1">{{ $val($c->role_position) }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-600 text-sm bg-gray-50 border rounded p-4">
                                No community involvement records available.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ENGAGEMENT --}}
            <div id="engagement" class="bg-white shadow rounded border border-gray-100">
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">VI. Alumni Engagement Preferences</h3>
                            <div class="text-sm text-gray-600 mt-1">Participation and preferred updates</div>
                        </div>

                        <a href="{{ $editSectionUrl('engagement') }}"
                           class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                            Edit Engagement
                        </a>
                    </div>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="border rounded p-3 bg-gray-50">
                            <div class="text-gray-500">Willing to be contacted</div>
                            <div class="font-semibold text-gray-900">{{ $boolText((bool)($pref->willing_contacted ?? false)) }}</div>
                        </div>
                        <div class="border rounded p-3 bg-gray-50">
                            <div class="text-gray-500">Join alumni events</div>
                            <div class="font-semibold text-gray-900">{{ $boolText((bool)($pref->willing_events ?? false)) }}</div>
                        </div>
                        <div class="border rounded p-3 bg-gray-50">
                            <div class="text-gray-500">Mentor / career talks</div>
                            <div class="font-semibold text-gray-900">{{ $boolText((bool)($pref->willing_mentor ?? false)) }}</div>
                        </div>
                        <div class="border rounded p-3 bg-gray-50">
                            <div class="text-gray-500">Donations / support</div>
                            <div class="font-semibold text-gray-900">{{ $boolText((bool)($pref->willing_donation ?? false)) }}</div>
                        </div>
                        <div class="border rounded p-3 bg-gray-50 sm:col-span-2">
                            <div class="text-gray-500">Scholarship support</div>
                            <div class="font-semibold text-gray-900">{{ $boolText((bool)($pref->willing_scholarship ?? false)) }}</div>
                        </div>
                    </div>

                    <div class="mt-6 text-sm font-medium text-gray-700">Preferred updates through:</div>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                        <div class="border rounded p-3 bg-gray-50">
                            <div class="text-gray-500">Email</div>
                            <div class="font-semibold text-gray-900">{{ $boolText((bool)($pref->prefer_email ?? false)) }}</div>
                        </div>
                        <div class="border rounded p-3 bg-gray-50">
                            <div class="text-gray-500">Facebook</div>
                            <div class="font-semibold text-gray-900">{{ $boolText((bool)($pref->prefer_facebook ?? false)) }}</div>
                        </div>
                        <div class="border rounded p-3 bg-gray-50">
                            <div class="text-gray-500">Text / SMS</div>
                            <div class="font-semibold text-gray-900">{{ $boolText((bool)($pref->prefer_sms ?? false)) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONSENT --}}
            <div id="consent" class="bg-white shadow rounded border border-gray-100">
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">VII. Consent & Signature</h3>
                            <div class="text-sm text-gray-600 mt-1">Data Privacy consent</div>
                        </div>

                        <a href="{{ $editSectionUrl('consent') }}"
                           class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                            Edit Consent
                        </a>
                    </div>

                    <div class="mt-5 text-sm">
                        <div class="text-gray-500 font-medium">Signature Name</div>
                        <div class="mt-1 text-gray-900 font-semibold">{{ $val($consent->signature_name ?? null) }}</div>

                        <div class="mt-4 text-xs text-gray-600 leading-relaxed">
                            By submitting, the alumnus consented to the use of the information for alumni tracer purposes
                            in accordance with the Data Privacy Act.
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOT ACTIONS --}}
            <div class="flex flex-wrap items-center justify-end gap-2">
                <a href="{{ route('portal.records.index') }}"
                   class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
                    Back
                </a>
                <a href="{{ route('portal.records.edit', $alumnus) }}"
                   class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Edit (All)
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
