{{-- resources/views/portals/records/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Alumni Record
                </h2>
                <div class="text-sm text-gray-600">
                    Record ID:
                    <span class="font-medium">#{{ $alumnus->id }}</span>
                    <span class="mx-2">•</span>
                    Last updated:
                    <span class="font-medium">
                        {{ optional($alumnus->updated_at)->format('M d, Y h:i A') }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('portal.records.index') }}"
                   class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">
                    Back to Records
                </a>

                <a href="{{ route('portal.records.edit', $alumnus) }}"
                   class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Edit (All)
                </a>
            </div>
        </div>
    </x-slot>

    @php
        // helpers
        $val  = fn($v, $fallback = '—') => filled($v) ? $v : $fallback;
        $bool = fn($b) => $b ? 'Yes' : 'No';

        $pref    = $alumnus->engagementPreference ?? null;
        $consent = $alumnus->consent ?? null;

        $educationLabels = [
            'ndmu_elementary'  => 'NDMU Elementary',
            'ndmu_jhs'         => 'NDMU Junior High School',
            'ndmu_shs'         => 'NDMU Senior High School',
            'ndmu_college'     => 'NDMU College',
            'ndmu_grad_school' => 'NDMU Graduate School',
            'ndmu_law'         => 'NDMU Law School',
            'post_ndmu'        => 'Education after NDMU',
        ];

        $orgTypeLabels = [
            'government'    => 'Government',
            'private'       => 'Private',
            'ngo'           => 'NGO',
            'self_employed' => 'Self-employed',
            'other'         => 'Other',
        ];

        // ✅ FIXED: explicit closure capture
        $editSectionUrl = function (string $section) use ($alumnus) {
            return route('portal.records.edit', [$alumnus, 'section' => $section]) . "#{$section}";
        };
    @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- QUICK NAV --}}
            <div class="bg-white shadow rounded border p-4">
                <div class="text-sm font-semibold text-gray-700 mb-2">Jump to section:</div>
                <div class="flex flex-wrap gap-2 text-sm">
                    @foreach(['personal','addresses','academic','employment','community','engagement','consent'] as $s)
                        <a href="#{{ $s }}"
                           class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200 capitalize">
                            {{ $s }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- I. PERSONAL --}}
            <div id="personal" class="bg-white shadow rounded border">
                <div class="p-6">
                    <div class="flex justify-between">
                        <h3 class="text-lg font-semibold">I. Personal Information</h3>
                        <a href="{{ $editSectionUrl('personal') }}"
                           class="px-3 py-1.5 bg-indigo-600 text-white rounded text-sm">
                            Edit
                        </a>
                    </div>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <x-show label="Full Name" :value="$alumnus->full_name"/>
                        <x-show label="Nickname" :value="$alumnus->nickname"/>
                        <x-show label="Sex" :value="$alumnus->sex"/>
                        <x-show label="Civil Status" :value="$alumnus->civil_status"/>
                        <x-show label="Birthdate"
                                :value="$alumnus->birthdate
                                    ? \Carbon\Carbon::parse($alumnus->birthdate)->format('M d, Y')
                                    : null"/>
                        <x-show label="Age" :value="$alumnus->age"/>
                        <x-show label="Contact Number" :value="$alumnus->contact_number"/>
                        <x-show label="Email" :value="$alumnus->email"/>
                        <x-show label="Facebook / Social Media" :value="$alumnus->facebook"/>
                        <x-show label="Nationality" :value="$alumnus->nationality"/>
                        <x-show label="Religion" :value="$alumnus->religion"/>
                    </div>
                </div>
            </div>

            {{-- II. ADDRESSES --}}
            <div id="addresses" class="bg-white shadow rounded border">
                <div class="p-6">
                    <div class="flex justify-between">
                        <h3 class="text-lg font-semibold">II. Addresses</h3>
                        <a href="{{ $editSectionUrl('addresses') }}"
                           class="px-3 py-1.5 bg-indigo-600 text-white rounded text-sm">
                            Edit
                        </a>
                    </div>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <x-show label="Home Address" :value="$alumnus->home_address" block/>
                        <x-show label="Current Address" :value="$alumnus->current_address" block/>
                    </div>
                </div>
            </div>

            {{-- III. ACADEMIC --}}
            <div id="academic" class="bg-white shadow rounded border">
                <div class="p-6">
                    <div class="flex justify-between">
                        <h3 class="text-lg font-semibold">
                            III. Academic Background ({{ $alumnus->educations->count() }})
                        </h3>
                        <a href="{{ $editSectionUrl('academic') }}"
                           class="px-3 py-1.5 bg-indigo-600 text-white rounded text-sm">
                            Edit
                        </a>
                    </div>

                    <div class="mt-5 space-y-4">
                        @forelse($alumnus->educations as $e)
                            @php
                                $level  = $e->level;
                                $isSHS  = $level === 'ndmu_shs';
                                $isHigh = in_array($level, ['ndmu_college','ndmu_grad_school','ndmu_law'], true);
                                $isPost = $level === 'post_ndmu';
                            @endphp

                            <div class="border rounded bg-gray-50 p-4">
                                <div class="font-semibold text-gray-900">
                                    {{ $educationLabels[$level] ?? $level }}
                                </div>

                                <div class="text-sm mt-1">
                                    Student No.: {{ $val($e->student_number) }}
                                </div>

                                <div class="mt-2 text-sm grid grid-cols-1 md:grid-cols-3 gap-2">
                                    <div>Entered: {{ $val($e->year_entered) }}</div>
                                    <div>Graduated: {{ $val($e->year_graduated) }}</div>
                                    <div>Last Attended: {{ $val($e->last_year_attended) }}</div>
                                </div>

                                @if($isSHS || $isHigh)
                                    <div class="mt-3 text-sm">
                                        <strong>Strand / Track:</strong>
                                        {{ $val($e->strand_track) }}
                                    </div>
                                @endif

                                @if($isHigh)
                                    <div class="mt-2 text-sm">
                                        <strong>Degree / Program:</strong>
                                        {{ $val($e->degree_program) }}
                                    </div>
                                @endif

                                @if($isPost)
                                    <div class="mt-2 text-sm">
                                        <strong>Institution:</strong>
                                        {{ $val($e->institution_name) }}<br>
                                        <strong>Course:</strong>
                                        {{ $val($e->course_degree) }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">
                                No education records.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- IV. EMPLOYMENT --}}
            <div id="employment" class="bg-white shadow rounded border">
                <div class="p-6">
                    <div class="flex justify-between">
                        <h3 class="text-lg font-semibold">
                            IV. Employment ({{ $alumnus->employments->count() }})
                        </h3>
                        <a href="{{ $editSectionUrl('employment') }}"
                           class="px-3 py-1.5 bg-indigo-600 text-white rounded text-sm">
                            Edit
                        </a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse($alumnus->employments as $emp)
                            <div class="border rounded bg-gray-50 p-4 text-sm">
                                <div class="font-semibold">
                                    {{ $val($emp->occupation_position) }}
                                </div>
                                <div>{{ $val($emp->company_name) }}</div>
                                <div>Status: {{ $val($emp->current_status) }}</div>
                                <div>
                                    Org Type:
                                    {{ $orgTypeLabels[$emp->org_type] ?? $val($emp->org_type) }}
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">
                                No employment records.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- V. COMMUNITY --}}
            <div id="community" class="bg-white shadow rounded border">
                <div class="p-6">
                    <div class="flex justify-between">
                        <h3 class="text-lg font-semibold">
                            V. Community Involvement ({{ $alumnus->communityInvolvements->count() }})
                        </h3>
                        <a href="{{ $editSectionUrl('community') }}"
                           class="px-3 py-1.5 bg-indigo-600 text-white rounded text-sm">
                            Edit
                        </a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse($alumnus->communityInvolvements as $c)
                            <div class="border rounded bg-gray-50 p-4 text-sm">
                                <div class="font-semibold">{{ $val($c->organization) }}</div>
                                <div>Role: {{ $val($c->role_position) }}</div>
                                <div>Years: {{ $val($c->years_involved) }}</div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">
                                No community involvement records.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- VI. ENGAGEMENT --}}
            <div id="engagement" class="bg-white shadow rounded border">
                <div class="p-6">
                    <div class="flex justify-between">
                        <h3 class="text-lg font-semibold">VI. Alumni Engagement</h3>
                        <a href="{{ $editSectionUrl('engagement') }}"
                           class="px-3 py-1.5 bg-indigo-600 text-white rounded text-sm">
                            Edit
                        </a>
                    </div>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <x-show label="Willing to be contacted" :value="$bool($pref->willing_contacted ?? false)"/>
                        <x-show label="Join alumni events" :value="$bool($pref->willing_events ?? false)"/>
                        <x-show label="Mentor / talks" :value="$bool($pref->willing_mentor ?? false)"/>
                        <x-show label="Donations / support" :value="$bool($pref->willing_donation ?? false)"/>
                        <x-show label="Scholarship support" :value="$bool($pref->willing_scholarship ?? false)"/>
                        <x-show label="Prefer Email" :value="$bool($pref->prefer_email ?? false)"/>
                        <x-show label="Prefer Facebook" :value="$bool($pref->prefer_facebook ?? false)"/>
                        <x-show label="Prefer SMS" :value="$bool($pref->prefer_sms ?? false)"/>
                    </div>
                </div>
            </div>

            {{-- VII. CONSENT --}}
            <div id="consent" class="bg-white shadow rounded border">
                <div class="p-6">
                    <div class="flex justify-between">
                        <h3 class="text-lg font-semibold">VII. Consent</h3>
                        <a href="{{ $editSectionUrl('consent') }}"
                           class="px-3 py-1.5 bg-indigo-600 text-white rounded text-sm">
                            Edit
                        </a>
                    </div>

                    <div class="mt-5 text-sm">
                        <div>
                            <strong>Signature:</strong>
                            {{ $val($consent->signature_name ?? null) }}
                        </div>
                        <div class="mt-2 text-xs text-gray-600">
                            Data Privacy Act consent acknowledged.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
