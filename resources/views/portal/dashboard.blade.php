{{-- resources/views/portal/dashboard.blade.php (or your current dashboard view) --}}
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
            'total_records' => null,
            'new_this_month' => null,
            'with_email' => null,
            'with_contact' => null,
        ];
    @endphp


    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- TOP HERO --}}
            <div class="rounded-xl shadow border overflow-hidden"
                 style="border-color:#E3C77A;">
                <div class="p-6 sm:p-8 text-white"
                     style="background:linear-gradient(135deg,#0B3D2E 0%, #0F5A41 55%, #0B3D2E 100%);">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold px-3 py-1 rounded-full"
                                 style="background:rgba(227,199,122,.18); border:1px solid rgba(227,199,122,.35);">
                                <span class="h-2.5 w-2.5 rounded-full" style="background:#E3C77A;"></span>
                                NDMU Alumni Tracer Portal
                            </div>

                            <h3 class="mt-3 text-2xl font-bold tracking-tight">
                                Welcome, {{ $user->name }}
                            </h3>
                            <p class="mt-1 text-sm text-white/90">
                                Role: <span class="font-semibold" style="color:#E3C77A;">{{ $roleLabel }}</span>
                                <span class="mx-2">•</span>
                                Manage alumni intake records, academic background, employment, community involvement, and engagement preferences.
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('portal.records.index') }}"
                            class="inline-flex items-center px-4 py-2 rounded font-semibold"
                            style="background:#E3C77A; color:#0B3D2E;">
                                Manage Alumni Records
                            </a>

                            <a href="{{ route('intake.form') }}"
                            class="inline-flex items-center px-4 py-2 rounded font-semibold border"
                            style="border-color:rgba(227,199,122,.65); color:#fff;">
                                Alumni Intake Form
                            </a>

                            {{-- ✅ NEW: Account Settings --}}
                            <a href="{{ route('profile.edit') }}"
                            class="inline-flex items-center px-4 py-2 rounded font-semibold border"
                            style="border-color:rgba(255,255,255,.45); color:#fff;">
                                Account Settings
                            </a>

                             @if($isOfficer)
                            @if(Route::has('events.calendar'))
                                <a href="{{ route('events.calendar') }}"
                                class="inline-flex items-center px-4 py-2 rounded font-semibold"
                                style="background:rgba(227,199,122,.18); border:1px solid rgba(227,199,122,.55); color:#fff;">
                                    Calendar of Events
                                </a>
                            @endif

                            @if(Route::has('portal.events.index'))
                                <a href="{{ route('portal.events.index') }}"
                                class="inline-flex items-center px-4 py-2 rounded font-semibold"
                                style="background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.25); color:#fff;">
                                    Manage Events
                                </a>
                            @endif
                        @endif       


                            {{-- ✅ NEW: User Management (IT Admin only) --}}
                            @if(($role ?? null) === 'it_admin')
                                <a href="{{ route('itadmin.users.index') }}"
                                class="inline-flex items-center px-4 py-2 rounded font-semibold"
                                style="background:rgba(255,255,255,.18); border:1px solid rgba(227,199,122,.35); color:#fff;">
                                    User Management
                                </a>
                            @endif
                        </div>

                        </div>

                        {{-- Quick status card --}}
                        <div class="w-full lg:w-[360px]">
                            <div class="rounded-lg p-4"
                                 style="background:rgba(255,255,255,.08); border:1px solid rgba(227,199,122,.25);">
                                <div class="text-sm font-semibold" style="color:#E3C77A;">Portal Overview</div>
                                <div class="mt-2 text-sm text-white/90 leading-relaxed">
                                    This portal supports NDMU’s Alumni Tracer initiative: maintaining updated alumni profiles to strengthen engagement,
                                    improve program feedback, and support institutional reporting.
                                </div>
                                <div class="mt-3 text-xs text-white/80">
                                    Data privacy note: access is role-based. Exporting (PDF/Excel) should be handled responsibly.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- STAT CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow border p-5" style="border-color:#EDE7D1;">
                    <div class="text-xs font-semibold text-gray-600">Total Alumni Records</div>
                    <div class="mt-2 text-2xl font-bold" style="color:#0B3D2E;">
                        {{ $stats['total_records'] ?? '—' }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">All saved intake profiles</div>
                </div>

                <div class="bg-white rounded-xl shadow border p-5" style="border-color:#EDE7D1;">
                    <div class="text-xs font-semibold text-gray-600">New This Month</div>
                    <div class="mt-2 text-2xl font-bold" style="color:#0B3D2E;">
                        {{ $stats['new_this_month'] ?? '—' }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">Recently submitted updates</div>
                </div>

                <div class="bg-white rounded-xl shadow border p-5" style="border-color:#EDE7D1;">
                    <div class="text-xs font-semibold text-gray-600">With Email</div>
                    <div class="mt-2 text-2xl font-bold" style="color:#0B3D2E;">
                        {{ $stats['with_email'] ?? '—' }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">Reachable for announcements</div>
                </div>

                <div class="bg-white rounded-xl shadow border p-5" style="border-color:#EDE7D1;">
                    <div class="text-xs font-semibold text-gray-600">With Contact No.</div>
                    <div class="mt-2 text-2xl font-bold" style="color:#0B3D2E;">
                        {{ $stats['with_contact'] ?? '—' }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">Useful for SMS updates</div>
                </div>
            </div>

            {{-- QUICK ACTIONS + INFO --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- Actions --}}
                <div class="bg-white rounded-xl shadow border p-6 lg:col-span-2" style="border-color:#EDE7D1;">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-semibold" style="color:#0B3D2E;">Quick Actions</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Common tasks for managing alumni data and exports.
                            </p>
                        </div>
                        <div class="text-xs font-semibold px-3 py-1 rounded-full"
                             style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                            Admin Tools
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="{{ route('portal.records.index') }}"
                           class="p-4 rounded-lg border hover:shadow-sm transition"
                           style="border-color:#E3C77A;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Manage Records</div>
                            <div class="text-xs text-gray-600 mt-1">Search, filter, edit, and export.</div>
                        </a>

                        <a href="{{ route('intake.form') }}"
                           class="p-4 rounded-lg border hover:shadow-sm transition"
                           style="border-color:#E3C77A;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Open Intake Form</div>
                            <div class="text-xs text-gray-600 mt-1">Create or update an alumni profile.</div>
                        </a>

                        <a href="{{ route('portal.records.index', ['field' => 'email']) }}"
                           class="p-4 rounded-lg border hover:shadow-sm transition"
                           style="border-color:#E3C77A;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Email-focused Search</div>
                            <div class="text-xs text-gray-600 mt-1">Quickly find records by email.</div>
                        </a>

                        <a href="{{ route('portal.records.index', ['per_page' => 25]) }}"
                           class="p-4 rounded-lg border hover:shadow-sm transition"
                           style="border-color:#E3C77A;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Review New Entries</div>
                            <div class="text-xs text-gray-600 mt-1">Browse recent records efficiently.</div>
                        </a>
                        {{-- ✅ NEW: Events cards (Alumni Officer/Admin/IT Admin) --}}
                @if($isOfficer)
                    @if(Route::has('portal.events.index'))
                        <a href="{{ route('portal.events.index') }}"
                        class="p-4 rounded-lg border hover:shadow-sm transition"
                        style="border-color:#E3C77A;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Manage Events</div>
                            <div class="text-xs text-gray-600 mt-1">Create, edit, publish, and organize alumni events.</div>
                        </a>
                    @endif

                    @if(Route::has('events.calendar'))
                        <a href="{{ route('events.calendar') }}"
                        class="p-4 rounded-lg border hover:shadow-sm transition"
                        style="border-color:#E3C77A;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Calendar of Events</div>
                            <div class="text-xs text-gray-600 mt-1">View upcoming events as seen by alumni users.</div>
                        </a>
                    @endif
                @endif

                    </div>
                </div>

                {{-- About / Future Features --}}
                <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                    <h4 class="text-lg font-semibold" style="color:#0B3D2E;">NDMU Alumni Tracer</h4>
                    <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                        The Alumni Tracer system helps NDMU maintain updated alumni records for engagement,
                        institutional reporting, and program improvement.
                    </p>

                    <div class="mt-4">
                        <div class="text-sm font-semibold" style="color:#0B3D2E;">Future modules (suggested)</div>
                        <ul class="mt-2 text-sm text-gray-700 space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                Alumni Events & Attendance Tracking
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                Engagement Campaigns (Email/SMS)
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                Employment Analytics & Reports
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                QR-based Verification for Alumni ID / Records
                            </li>
                        </ul>
                    </div>

                    <div class="mt-5 rounded-lg p-4"
                         style="background:#F6F2E6; border:1px solid #E3C77A;">
                        <div class="text-sm font-semibold" style="color:#0B3D2E;">Data Privacy</div>
                        <div class="text-xs text-gray-700 mt-1 leading-relaxed">
                            Handle exports responsibly. Access and changes should remain within authorized roles
                            in compliance with the Data Privacy Act.
                        </div>
                    </div>
                </div>
                {{-- ✅ NEW: Events cards (Alumni Officer/Admin/IT Admin)
                @if($isOfficer)
                    @if(Route::has('portal.events.index'))
                        <a href="{{ route('portal.events.index') }}"
                        class="p-4 rounded-lg border hover:shadow-sm transition"
                        style="border-color:#E3C77A;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Manage Events</div>
                            <div class="text-xs text-gray-600 mt-1">Create, edit, publish, and organize alumni events.</div>
                        </a>
                    @endif

                    @if(Route::has('events.calendar'))
                        <a href="{{ route('events.calendar') }}"
                        class="p-4 rounded-lg border hover:shadow-sm transition"
                        style="border-color:#E3C77A;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Calendar of Events</div>
                            <div class="text-xs text-gray-600 mt-1">View upcoming events as seen by alumni users.</div>
                        </a>
                    @endif
                @endif --}}

            </div>

        </div>
    </div>
</x-app-layout>
