{{-- resources/views/portals/records/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Alumni Record
                </h2>
                <div class="text-sm text-gray-600">
                    Record ID: <span class="font-semibold">#{{ $alumnus->id }}</span>
                    <span class="mx-2">•</span>
                    Last updated:
                    <span class="font-semibold">
                        {{ optional($alumnus->updated_at)->format('M d, Y h:i A') }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('portal.records.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                    Back to Records
                </a>

                <a href="{{ route('portal.records.edit', $alumnus) }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white shadow-sm"
                   style="background:#0B3D2E;">
                    Edit (All)
                </a>
            </div>
        </div>
    </x-slot>

    @php
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

        $editSectionUrl = function (string $section) use ($alumnus) {
            return route('portal.records.edit', [$alumnus, 'section' => $section]) . "#{$section}";
        };
    @endphp

    <style>
        :root{
            --ndmu-green:#0B3D2E;
            --ndmu-gold:#E3C77A;
            --paper:#FFFBF0;
            --page:#FAFAF8;
            --line:#EDE7D1;
        }

        .strip{
            border:1px solid var(--line);
            border-radius: 18px;
            overflow:hidden;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }
        .strip-top{
            padding: 14px 16px;
            background: var(--ndmu-green);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }
        .strip-left{
            display:flex;
            align-items:center;
            gap: 10px;
            min-width: 0;
        }
        .gold-bar{
            width: 6px;
            height: 28px;
            background: var(--ndmu-gold);
            border-radius: 999px;
            flex: 0 0 auto;
        }
        .strip-title{
            color:#fff;
            font-weight: 900;
            letter-spacing: .2px;
            line-height: 1.2;
        }
        .strip-sub{
            color: rgba(255,255,255,.78);
            font-size: 12px;
            margin-top: 2px;
        }

        .panel{
            background:#fff;
            border:1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }

        .jump a{
            padding: 8px 10px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: #fff;
            font-weight: 800;
            font-size: 12px;
            color: rgba(15,23,42,.78);
            text-transform: capitalize;
            transition: .15s ease;
        }
        .jump a:hover{ background: var(--paper); border-color: rgba(227,199,122,.85); color: var(--ndmu-green); }

        .section-head{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap: 10px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--line);
            background: var(--paper);
        }
        .section-head h3{
            font-weight: 900;
            color: var(--ndmu-green);
        }
        .btn-edit{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding: 8px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 900;
            color: #fff;
            background: var(--ndmu-green);
            box-shadow: 0 6px 14px rgba(2,6,23,.06);
        }
        .btn-edit:hover{ filter: brightness(.95); }

        .soft-card{
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #fff;
        }
        .soft-card.pad{ padding: 16px; }

        .mini{
            font-size: 12px;
            color: rgba(15,23,42,.62);
        }
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- QUICK NAV --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Jump to Section</div>
                            <div class="strip-sub">Quick navigation for faster viewing.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-extrabold"
                              style="background:rgba(255,251,240,.95); border:1px solid rgba(227,199,122,.85); color:var(--ndmu-green);">
                            <span class="inline-block h-2 w-2 rounded-full" style="background:var(--ndmu-green);"></span>
                            Record View
                        </span>
                    </div>
                </div>

                <div class="p-4">
                    <div class="jump flex flex-wrap gap-2">
                        @foreach(['personal','addresses','academic','employment','community','engagement','consent'] as $s)
                            <a href="#{{ $s }}">{{ $s }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- I. PERSONAL --}}
            <div id="personal" class="panel overflow-hidden">
                <div class="section-head">
                    <div>
                        <h3 class="text-base">I. Personal Information</h3>
                        <div class="mini">Basic identity and contact details.</div>
                    </div>
                    <a href="{{ $editSectionUrl('personal') }}" class="btn-edit">Edit</a>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
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
            <div id="addresses" class="panel overflow-hidden">
                <div class="section-head">
                    <div>
                        <h3 class="text-base">II. Addresses</h3>
                        <div class="mini">Home and current addresses.</div>
                    </div>
                    <a href="{{ $editSectionUrl('addresses') }}" class="btn-edit">Edit</a>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <x-show label="Home Address" :value="$alumnus->home_address" block/>
                        <x-show label="Current Address" :value="$alumnus->current_address" block/>
                    </div>
                </div>
            </div>

            {{-- III. ACADEMIC --}}
            <div id="academic" class="panel overflow-hidden">
                <div class="section-head">
                    <div>
                        <h3 class="text-base">III. Academic Background ({{ $alumnus->educations->count() }})</h3>
                        <div class="mini">NDMU levels and post-NDMU education history.</div>
                    </div>
                    <a href="{{ $editSectionUrl('academic') }}" class="btn-edit">Edit</a>
                </div>

                <div class="p-6 space-y-4">
                    @forelse($alumnus->educations as $e)
                        @php
                            $level   = $e->level;
                            $isShs   = $level === 'ndmu_shs';
                            $isHigh  = in_array($level, ['ndmu_college','ndmu_grad_school','ndmu_law'], true);
                            $isPost  = $level === 'post_ndmu';

                            $gradLabel = is_null($e->did_graduate) ? '—' : ($e->did_graduate ? 'Yes' : 'No');

                            $yearGraduated = $e->year_graduated;
                            $lastAttended  = $e->last_year_attended;

                            $strandLabel = null;
                            if ($e->strand) {
                                $strandLabel = trim($e->strand->code.' — '.$e->strand->name);
                            } elseif (!empty($e->strand_track)) {
                                $strandLabel = $e->strand_track;
                            }

                            $programLabel = null;
                            if ($e->program) {
                                $programLabel = trim(($e->program->code ? $e->program->code.' — ' : '').$e->program->name);
                            } elseif (!empty($e->specific_program)) {
                                $programLabel = $e->specific_program.' (Others)';
                            } elseif (!empty($e->degree_program)) {
                                $programLabel = $e->degree_program;
                            }
                        @endphp

                        <div class="soft-card pad">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="font-extrabold" style="color:var(--ndmu-green);">
                                    {{ $educationLabels[$level] ?? $level }}
                                </div>
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-extrabold"
                                      style="background:rgba(227,199,122,.25); border:1px solid rgba(227,199,122,.70); color:var(--ndmu-green);">
                                    Graduated: {{ $gradLabel }}
                                </span>
                            </div>

                            <div class="mt-3 text-sm grid grid-cols-1 md:grid-cols-3 gap-2">
                                <div><strong>Year Started:</strong> {{ $val($e->year_entered) }}</div>

                                @if($e->did_graduate === 1 || $e->did_graduate === true)
                                    <div><strong>Year Graduated:</strong> {{ $val($yearGraduated) }}</div>
                                    <div><strong>Last Attended:</strong> —</div>
                                @elseif($e->did_graduate === 0 || $e->did_graduate === false)
                                    <div><strong>Year Graduated:</strong> —</div>
                                    <div><strong>Last School Year Attended:</strong> {{ $val($lastAttended) }}</div>
                                @else
                                    <div><strong>Year Graduated:</strong> {{ $val($yearGraduated) }}</div>
                                    <div><strong>Last School Year Attended:</strong> {{ $val($lastAttended) }}</div>
                                @endif
                            </div>

                            @if($isShs)
                                <div class="mt-3 text-sm">
                                    <strong>Strand:</strong> {{ $val($strandLabel) }}
                                </div>
                            @endif

                            @if($isHigh)
                                <div class="mt-3 text-sm">
                                    <strong>Program:</strong> {{ $val($programLabel) }}
                                </div>
                            @endif

                            @if($isPost)
                                <div class="mt-3 text-sm">
                                    <strong>Institution:</strong> {{ $val($e->institution_name) }}<br>
                                    <strong>Course/Degree:</strong> {{ $val($e->course_degree) }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-sm text-gray-600">No education records.</div>
                    @endforelse
                </div>
            </div>

            {{-- IV. EMPLOYMENT --}}
            <div id="employment" class="panel overflow-hidden">
                <div class="section-head">
                    <div>
                        <h3 class="text-base">IV. Employment ({{ $alumnus->employments->count() }})</h3>
                        <div class="mini">Employment history and organization details.</div>
                    </div>
                    <a href="{{ $editSectionUrl('employment') }}" class="btn-edit">Edit</a>
                </div>

                <div class="p-6 space-y-3">
                    @forelse($alumnus->employments as $emp)
                        <div class="soft-card pad text-sm">
                            <div class="font-extrabold" style="color:var(--ndmu-green);">
                                {{ $val($emp->occupation_position) }}
                            </div>
                            <div>{{ $val($emp->company_name) }}</div>
                            <div class="mt-1 text-gray-700">Status: {{ $val($emp->current_status) }}</div>
                            <div class="text-gray-700">
                                Org Type: {{ $orgTypeLabels[$emp->org_type] ?? $val($emp->org_type) }}
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-600">No employment records.</div>
                    @endforelse
                </div>
            </div>

            {{-- V. COMMUNITY --}}
            <div id="community" class="panel overflow-hidden">
                <div class="section-head">
                    <div>
                        <h3 class="text-base">V. Community Involvement ({{ $alumnus->communityInvolvements->count() }})</h3>
                        <div class="mini">Organizations and roles outside work.</div>
                    </div>
                    <a href="{{ $editSectionUrl('community') }}" class="btn-edit">Edit</a>
                </div>

                <div class="p-6 space-y-3">
                    @forelse($alumnus->communityInvolvements as $c)
                        <div class="soft-card pad text-sm">
                            <div class="font-extrabold" style="color:var(--ndmu-green);">
                                {{ $val($c->organization) }}
                            </div>
                            <div class="mt-1">Role: {{ $val($c->role_position) }}</div>
                            <div>Years: {{ $val($c->years_involved) }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-600">No community involvement records.</div>
                    @endforelse
                </div>
            </div>

            {{-- VI. ENGAGEMENT --}}
            <div id="engagement" class="panel overflow-hidden">
                <div class="section-head">
                    <div>
                        <h3 class="text-base">VI. Alumni Engagement</h3>
                        <div class="mini">Preferred ways to engage with alumni programs.</div>
                    </div>
                    <a href="{{ $editSectionUrl('engagement') }}" class="btn-edit">Edit</a>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
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
            <div id="consent" class="panel overflow-hidden">
                <div class="section-head">
                    <div>
                        <h3 class="text-base">VII. Consent</h3>
                        <div class="mini">Data Privacy Act consent acknowledgement.</div>
                    </div>
                    <a href="{{ $editSectionUrl('consent') }}" class="btn-edit">Edit</a>
                </div>

                <div class="p-6 text-sm">
                    <div>
                        <strong>Signature:</strong> {{ $val($consent->signature_name ?? null) }}
                    </div>
                    <div class="mt-2 text-xs text-gray-600">
                        Data Privacy Act consent acknowledged.
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
