{{-- resources/views/portal/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Portal Dashboard
                </h2>
                <div class="text-sm text-gray-600">
                    NDMU Alumni Tracer & Directory — Admin / Officer Portal
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('portal.records.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded text-white"
                   style="background:#0B3D2E;">
                    Manage Records
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $user = auth()->user();
        $role = $user->role ?? 'user';

        $roleLabel = match($role) {
            'it_admin' => 'IT Admin',
            'admin' => 'Administrator',
            'alumni_officer' => 'Alumni Officer',
            default => ucfirst($role),
        };

        $isOfficer = in_array($role, ['alumni_officer','admin','it_admin'], true);

        $stats = $stats ?? [
            'total_records' => '—',
            'new_this_month' => '—',
            'with_email' => '—',
            'with_contact' => '—',
        ];
    @endphp

    <style>
        :root{
            --ndmu-green:#0B3D2E;
            --ndmu-gold:#E3C77A;
            --paper:#FFFBF0;
            --page:#FAFAF8;
            --line:#EDE7D1;
            --text:#0f172a;
        }

        .ndmu-hero{
            border:1px solid rgba(227,199,122,.95);
            border-radius: 18px;
            overflow:hidden;
            box-shadow: 0 12px 28px rgba(2,6,23,.08);
            background: linear-gradient(135deg,#0B3D2E 0%, #0F5A41 55%, #0B3D2E 100%);
        }
        .hero-top{
            padding: 18px 20px;
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap: 12px;
        }
        .hero-pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(227,199,122,.18);
            border: 1px solid rgba(227,199,122,.35);
            font-size: 12px;
            font-weight: 800;
            color: #fff;
            white-space: nowrap;
        }
        .hero-pill .dot{
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: var(--ndmu-gold);
        }

        .hero-actions{
            padding: 0 20px 18px 20px;
        }

        .btn-hero{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 10px 14px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 13px;
            transition: .15s ease;
            white-space: nowrap;
        }
        .btn-gold{
            background: var(--ndmu-gold);
            color: var(--ndmu-green);
        }
        .btn-outline{
            border: 1px solid rgba(255,255,255,.35);
            color:#fff;
            background: rgba(255,255,255,.10);
        }
        .btn-outline:hover{ background: rgba(255,255,255,.14); }

        .panel{
            background:#fff;
            border:1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }
        .panel-hd{
            padding: 14px 16px;
            border-bottom:1px solid var(--line);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap: 10px;
        }
        .panel-title{
            font-weight: 900;
            color: var(--ndmu-green);
        }
        .tag{
            font-size: 11px;
            font-weight: 900;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(227,199,122,.20);
            border: 1px solid rgba(227,199,122,.55);
            color: var(--ndmu-green);
            white-space: nowrap;
        }

        .stat{
            background:#fff;
            border:1px solid var(--line);
            border-radius: 16px;
            padding: 14px 16px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }
        .stat .k{ font-size: 12px; font-weight: 800; color: rgba(15,23,42,.62); }
        .stat .v{ margin-top: 6px; font-size: 24px; font-weight: 900; color: var(--ndmu-green); }
        .stat .h{ margin-top: 2px; font-size: 12px; color: rgba(15,23,42,.55); }

        .action-grid a{
            display:block;
            border:1px solid rgba(227,199,122,.75);
            border-radius: 14px;
            padding: 14px;
            background: #fff;
            transition: .15s ease;
        }
        .action-grid a:hover{
            transform: translateY(-1px);
            box-shadow: 0 12px 26px rgba(2,6,23,.08);
        }
        .action-title{ font-weight: 900; color: var(--ndmu-green); font-size: 13px; }
        .action-sub{ margin-top: 4px; font-size: 12px; color: rgba(15,23,42,.62); line-height: 1.45; }

        .section-label{
            font-size: 12px;
            font-weight: 900;
            color: rgba(15,23,42,.70);
            margin-bottom: 8px;
            display:flex;
            align-items:center;
            gap:8px;
        }
        .section-label .bar{
            width: 10px; height: 10px; border-radius: 4px;
            background: var(--ndmu-gold);
        }
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- HERO (cleaner) --}}
            <div class="ndmu-hero text-white">
                <div class="hero-top">
                    <div class="min-w-0">
                        <div class="hero-pill">
                            <span class="dot"></span>
                            NDMU Alumni Tracer Portal
                        </div>

                        <div class="mt-3 text-2xl font-extrabold tracking-tight">
                            Welcome, {{ $user->name }}
                        </div>

                        <div class="mt-1 text-sm text-white/90">
                            Role:
                            <span class="font-extrabold" style="color:var(--ndmu-gold);">{{ $roleLabel }}</span>
                            <span class="mx-2">•</span>
                            Manage alumni profiles and engagement records.
                        </div>
                    </div>

                    <div class="hidden lg:block w-[340px]">
                        <div class="rounded-xl p-4"
                             style="background:rgba(255,255,255,.08); border:1px solid rgba(227,199,122,.25);">
                            <div class="text-sm font-extrabold" style="color:var(--ndmu-gold);">Portal Note</div>
                            <div class="mt-2 text-sm text-white/90 leading-relaxed">
                                Keep alumni records updated for engagement, reporting, and program feedback.
                            </div>
                            <div class="mt-3 text-xs text-white/80">
                                Data privacy: access is role-based. Handle exports responsibly.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hero action buttons grouped --}}
                <div class="hero-actions">
                    <div class="section-label text-white/90">
                        <span class="bar"></span> Quick Links
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('portal.records.index') }}" class="btn-hero btn-gold">
                            📋 Manage Alumni Records
                        </a>

                        <a href="{{ route('intake.form') }}" class="btn-hero btn-outline">
                            📝 Alumni Intake Form
                        </a>

                        <a href="{{ route('profile.edit') }}" class="btn-hero btn-outline">
                            ⚙️ Account Settings
                        </a>

                        @if(Route::has('id.user.request.status'))
                            <a href="{{ route('id.user.request.status') }}" class="btn-hero btn-outline">
                            🆔 Alumni ID Request
                            </a>
                        @endif

                        @if(Route::has('networks.index'))
                            <a href="{{ route('networks.index') }}" class="btn-hero btn-outline">
                                🌐 Alumni Network Page
                            </a>
                        @endif

                        {{-- Officer/Admin tools --}}
                        @if($isOfficer)
                            @if(Route::has('events.calendar'))
                                <a href="{{ route('events.calendar') }}" class="btn-hero btn-outline">
                                    📅 Calendar of Events
                                </a>
                            @endif

                            @if(Route::has('portal.events.index'))
                                <a href="{{ route('portal.events.index') }}" class="btn-hero btn-outline">
                                    🎫 Manage Events
                                </a>
                            @endif

                            @if(in_array($role, ['alumni_officer','it_admin'], true) && Route::has('portal.careers.admin.index'))
                                <a href="{{ route('portal.careers.admin.index') }}" class="btn-hero btn-outline">
                                    💼 Manage Careers
                                </a>
                            @endif

                            @if(in_array($role, ['alumni_officer','it_admin'], true) && Route::has('portal.networks.manage.index'))
                                <a href="{{ route('portal.networks.manage.index') }}" class="btn-hero btn-outline">
                                    🧩 Manage Networks
                                </a>
                            @endif

                            @if(in_array($role, ['alumni_officer','it_admin'], true) && Route::has('id.officer.requests.index'))
                                <a href="{{ route('id.officer.requests.index') }}" class="btn-hero btn-outline">
                                    ✅ Manage Alumni ID Requests
                                </a>
                            @endif

                            @if(in_array($role, ['alumni_officer','it_admin'], true) && Route::has('id.officer.requests.assisted.create'))
                                <a href="{{ route('id.officer.requests.assisted.create') }}" class="btn-hero btn-outline">
                                    🤝 Assisted Alumni ID Request
                                </a>
                            @endif

                            {{-- ✅ ADDED: Assisted Alumni Record Creation (PWD/Senior) --}}
                            @if(in_array($role, ['alumni_officer','it_admin','admin'], true) && Route::has('portal.alumni_encoding.create'))
                                <a href="{{ route('portal.alumni_encoding.create') }}" class="btn-hero btn-outline">
                                    🧾 Assisted Alumni Record Creation
                                </a>
                            @endif
                        @endif

                        {{-- IT Admin tools --}}
                        @if(($role ?? null) === 'it_admin')
                            <a href="{{ route('itadmin.users.index') }}" class="btn-hero btn-outline">
                                👤 User Management
                            </a>
                            <a href="{{ route('itadmin.programs.index') }}" class="btn-hero btn-outline">
                                🎓 Programs
                            </a>
                            <a href="{{ route('itadmin.strands.index') }}" class="btn-hero btn-outline">
                                🧾 Strands
                            </a>
                            <a href="{{ route('itadmin.captcha.edit') }}"
                            class="inline-flex items-center px-4 py-2 rounded font-semibold"
                            style="background:rgba(255,255,255,.18); border:1px solid rgba(227,199,122,.35); color:#fff;">
                                🔐 Captcha Settings
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- STATS (now powered by controller) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat">
                    <div class="k">Total Alumni Records</div>
                    <div class="v">{{ $stats['total_records'] ?? '—' }}</div>
                    <div class="h">All saved intake profiles</div>
                </div>

                <div class="stat">
                    <div class="k">New This Month</div>
                    <div class="v">{{ $stats['new_this_month'] ?? '—' }}</div>
                    <div class="h">Recently added/updated entries</div>
                </div>

                <div class="stat">
                    <div class="k">With Email</div>
                    <div class="v">{{ $stats['with_email'] ?? '—' }}</div>
                    <div class="h">Reachable for announcements</div>
                </div>

                <div class="stat">
                    <div class="k">With Contact No.</div>
                    <div class="v">{{ $stats['with_contact'] ?? '—' }}</div>
                    <div class="h">Useful for SMS updates</div>
                </div>
            </div>

            {{-- ORGANIZED QUICK ACTIONS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                <div class="panel lg:col-span-2">
                    <div class="panel-hd">
                        <div>
                            <div class="panel-title">Quick Actions</div>
                            <div class="text-xs text-gray-600">Grouped tools for faster navigation.</div>
                        </div>
                        <span class="tag">Admin Tools</span>
                    </div>

                    <div class="p-4 space-y-4">

                        <div>
                            <div class="section-label">
                                <span class="bar"></span> General
                            </div>
                            <div class="action-grid grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <a href="{{ route('portal.records.index') }}">
                                    <div class="action-title">Manage Records</div>
                                    <div class="action-sub">Search, filter, edit, and export.</div>
                                </a>

                                <a href="{{ route('intake.form') }}">
                                    <div class="action-title">Open Intake Form</div>
                                    <div class="action-sub">Create or update an alumni profile.</div>
                                </a>

                                @if(Route::has('networks.index'))
                                    <a href="{{ route('networks.index') }}">
                                        <div class="action-title">Alumni Network Page</div>
                                        <div class="action-sub">View associations and official alumni groups.</div>
                                    </a>
                                @endif

                                @if(Route::has('id.user.request.status'))
                                    <a href="{{ route('id.user.request.status') }}">
                                        <div class="action-title">Alumni ID Request</div>
                                        <div class="action-sub">Submit or track your Alumni ID request.</div>
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if($isOfficer)
                            <div>
                                <div class="section-label">
                                    <span class="bar"></span> Officer / Admin
                                </div>
                                <div class="action-grid grid grid-cols-1 sm:grid-cols-2 gap-3">

                                    @if(Route::has('portal.events.index'))
                                        <a href="{{ route('portal.events.index') }}">
                                            <div class="action-title">Manage Events</div>
                                            <div class="action-sub">Create, edit, publish, and organize alumni events.</div>
                                        </a>
                                    @endif

                                    @if(Route::has('events.calendar'))
                                        <a href="{{ route('events.calendar') }}">
                                            <div class="action-title">Calendar of Events</div>
                                            <div class="action-sub">View public event listing as alumni users see it.</div>
                                        </a>
                                    @endif

                                    @if(in_array($role, ['alumni_officer','it_admin'], true) && Route::has('portal.careers.admin.index'))
                                        <a href="{{ route('portal.careers.admin.index') }}">
                                            <div class="action-title">Manage Careers</div>
                                            <div class="action-sub">Create job posts and manage visibility.</div>
                                        </a>
                                    @endif

                                    @if(in_array($role, ['alumni_officer','it_admin'], true) && Route::has('portal.networks.manage.index'))
                                        <a href="{{ route('portal.networks.manage.index') }}">
                                            <div class="action-title">Manage Networks</div>
                                            <div class="action-sub">Add logos, links, and toggle visibility.</div>
                                        </a>
                                    @endif

                                    @if(in_array($role, ['alumni_officer','it_admin'], true) && Route::has('id.officer.requests.index'))
                                        <a href="{{ route('id.officer.requests.index') }}">
                                            <div class="action-title">Manage Alumni ID Requests</div>
                                            <div class="action-sub">Approve, process, mark ready, and release IDs.</div>
                                        </a>
                                    @endif

                                    @if(in_array($role, ['alumni_officer','it_admin'], true) && Route::has('id.officer.requests.assisted.create'))
                                        <a href="{{ route('id.officer.requests.assisted.create') }}">
                                            <div class="action-title">Assisted ID Request</div>
                                            <div class="action-sub">Create a request on behalf of PWD/Senior alumni.</div>
                                        </a>
                                    @endif

                                    {{-- ✅ ADDED: Assisted Alumni Record Creation (PWD/Senior) --}}
                                    @if(in_array($role, ['alumni_officer','it_admin','admin'], true) && Route::has('portal.alumni_encoding.create'))
                                        <a href="{{ route('portal.alumni_encoding.create') }}">
                                            <div class="action-title">Assisted Alumni Record Creation</div>
                                            <div class="action-sub">Create a record on behalf of PWD/Senior alumni and link to email.</div>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if(($role ?? null) === 'it_admin')
                            <div>
                                <div class="section-label">
                                    <span class="bar"></span> IT Admin
                                </div>
                                <div class="action-grid grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <a href="{{ route('itadmin.users.index') }}">
                                        <div class="action-title">User Management</div>
                                        <div class="action-sub">Create users, reset passwords, toggle active.</div>
                                    </a>
                                    <a href="{{ route('itadmin.programs.index') }}">
                                        <div class="action-title">Programs</div>
                                        <div class="action-sub">Maintain academic programs list.</div>
                                    </a>
                                    <a href="{{ route('itadmin.strands.index') }}">
                                        <div class="action-title">Strands</div>
                                        <div class="action-sub">Maintain SHS strands list.</div>
                                    </a>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Right column --}}
                <div class="panel">
                    <div class="panel-hd">
                        <div class="panel-title">NDMU Alumni Tracer</div>
                        <span class="tag">Info</span>
                    </div>

                    <div class="p-4 text-sm text-gray-700 leading-relaxed">
                        The Alumni Tracer system helps NDMU maintain updated alumni records for engagement,
                        institutional reporting, and program improvement.

                        <div class="mt-4 rounded-lg p-4"
                             style="background: var(--paper); border:1px solid rgba(227,199,122,.75);">
                            <div class="font-extrabold" style="color:var(--ndmu-green);">Data Privacy</div>
                            <div class="mt-1 text-xs text-gray-700">
                                Handle exports responsibly. Access and changes should remain within authorized roles
                                in compliance with the Data Privacy Act.
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="font-extrabold" style="color:var(--ndmu-green);">Suggested Next Modules</div>
                            <ul class="mt-2 text-xs text-gray-700 space-y-2">
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-2 w-2 rounded-full" style="background:var(--ndmu-gold);"></span>
                                    Alumni Events & Attendance Tracking
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-2 w-2 rounded-full" style="background:var(--ndmu-gold);"></span>
                                    Engagement Campaigns (Email/SMS)
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-2 w-2 rounded-full" style="background:var(--ndmu-gold);"></span>
                                    Employment Analytics & Reports
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-2 w-2 rounded-full" style="background:var(--ndmu-gold);"></span>
                                    QR-based Verification for Alumni ID / Records
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
