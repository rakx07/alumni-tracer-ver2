{{-- resources/views/user/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        @php
            $user = auth()->user();

            // Optional: if you added role_label accessor
            $roleLabel = $user->role_label ?? match(($user->role ?? 'user')) {
                'it_admin' => 'IT Admin',
                'admin' => 'Admin',
                'alumni_officer' => 'Alumni Officer',
                default => 'User',
            };

            $submitted = (bool) $alumnus;
        @endphp

        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Alumni Dashboard
                </h2>
                <div class="text-sm text-gray-600">
                    NDMU Alumni Tracer — Your profile and intake status
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center px-4 py-2 rounded font-semibold border"
                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                    Account Settings
                </a>

                <a href="{{ route('intake.form') }}"
                   class="inline-flex items-center px-4 py-2 rounded font-semibold text-white"
                   style="background:#0B3D2E;">
                    {{ $submitted ? 'Update Intake' : 'Fill Intake' }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="rounded-xl border p-4 bg-green-50" style="border-color:#86EFAC;">
                    <div class="font-semibold text-green-800">Success</div>
                    <div class="text-sm text-green-700 mt-1">{{ session('success') }}</div>
                </div>
            @endif

            {{-- HERO CARD --}}
            <div class="rounded-xl shadow border overflow-hidden" style="border-color:#E3C77A;">
                <div class="p-6 sm:p-8 text-white"
                     style="background:linear-gradient(135deg,#0B3D2E 0%, #0F5A41 55%, #0B3D2E 100%);">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                        <div>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold px-3 py-1 rounded-full"
                                 style="background:rgba(227,199,122,.18); border:1px solid rgba(227,199,122,.35);">
                                <span class="h-2.5 w-2.5 rounded-full" style="background:#E3C77A;"></span>
                                NDMU Alumni Tracer
                            </div>

                            <h3 class="mt-3 text-2xl font-bold tracking-tight">
                                Welcome, {{ $user->name }}
                            </h3>

                            <p class="mt-1 text-sm text-white/90 leading-relaxed">
                                Please keep your alumni profile updated for engagement initiatives and institutional reporting.
                                <span class="mx-2">•</span>
                                Role: <span class="font-semibold" style="color:#E3C77A;">{{ $roleLabel }}</span>
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('intake.form') }}"
                                   class="inline-flex items-center px-4 py-2 rounded font-semibold"
                                   style="background:#E3C77A; color:#0B3D2E;">
                                    {{ $submitted ? 'Update Intake Form' : 'Complete Intake Form' }}
                                </a>

                                <a href="{{ route('profile.edit') }}"
                                   class="inline-flex items-center px-4 py-2 rounded font-semibold border"
                                   style="border-color:rgba(255,255,255,.45); color:#fff;">
                                    Manage Password & Email
                                </a>
                            </div>
                        </div>

                        {{-- Status Summary --}}
                        <div class="w-full lg:w-[360px]">
                            <div class="rounded-lg p-4"
                                 style="background:rgba(255,255,255,.08); border:1px solid rgba(227,199,122,.25);">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-semibold" style="color:#E3C77A;">Intake Status</div>

                                    @if($submitted)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                              style="background:rgba(134,239,172,.18); border:1px solid rgba(134,239,172,.35); color:#D1FAE5;">
                                            Submitted
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                              style="background:rgba(252,165,165,.18); border:1px solid rgba(252,165,165,.35); color:#FEE2E2;">
                                            Not Submitted
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-3 text-sm text-white/90 leading-relaxed">
                                    @if($submitted)
                                        Your intake information is on file. You may update it anytime to keep your records current.
                                    @else
                                        You have not submitted your intake form yet. Please complete it to be included in the alumni directory.
                                    @endif
                                </div>

                                <div class="mt-3 text-xs text-white/80">
                                    Data Privacy: Access is protected and used only for authorized institutional purposes.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- STAT CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow border p-5" style="border-color:#EDE7D1;">
                    <div class="text-xs font-semibold text-gray-600">Status</div>
                    <div class="mt-2 text-2xl font-bold" style="color:#0B3D2E;">
                        {{ $submitted ? 'Submitted' : 'Not Submitted' }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">
                        Intake form completion status
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow border p-5" style="border-color:#EDE7D1;">
                    <div class="text-xs font-semibold text-gray-600">Name on Record</div>
                    <div class="mt-2 text-lg font-bold" style="color:#0B3D2E;">
                        {{ $submitted ? ($alumnus->full_name ?? '—') : '—' }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">
                        Based on your intake submission
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow border p-5" style="border-color:#EDE7D1;">
                    <div class="text-xs font-semibold text-gray-600">Last Updated</div>
                    <div class="mt-2 text-lg font-bold" style="color:#0B3D2E;">
                        {{ $submitted ? optional($alumnus->updated_at)->format('F d, Y h:i A') : '—' }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">
                        Most recent profile update
                    </div>
                </div>
            </div>

            {{-- QUICK ACTIONS + INFO --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- Quick Actions --}}
                <div class="bg-white rounded-xl shadow border p-6 lg:col-span-2" style="border-color:#EDE7D1;">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-semibold" style="color:#0B3D2E;">Quick Actions</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Manage your intake submission and account settings.
                            </p>
                        </div>
                        <div class="text-xs font-semibold px-3 py-1 rounded-full"
                             style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                            Alumni Tools
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="{{ route('intake.form') }}"
                           class="p-4 rounded-lg border hover:shadow-sm transition"
                           style="border-color:#E3C77A;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">
                                {{ $submitted ? 'Update Intake Form' : 'Complete Intake Form' }}
                            </div>
                            <div class="text-xs text-gray-600 mt-1">
                                Keep your alumni profile updated.
                            </div>
                        </a>

                        <a href="{{ route('profile.edit') }}"
                           class="p-4 rounded-lg border hover:shadow-sm transition"
                           style="border-color:#E3C77A;">
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Account Settings</div>
                            <div class="text-xs text-gray-600 mt-1">Change password, update email, and manage account.</div>
                        </a>
                    </div>

                    <div class="mt-5 rounded-lg p-4"
                         style="background:#FFFBF0; border:1px solid #E3C77A;">
                        <div class="text-sm font-semibold" style="color:#0B3D2E;">Reminder</div>
                        <div class="text-xs text-gray-700 mt-1 leading-relaxed">
                            Please ensure that your contact information and employment details are accurate.
                            This helps NDMU improve alumni engagement and reporting.
                        </div>
                    </div>
                </div>

                {{-- Info Panel --}}
                <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                    <h4 class="text-lg font-semibold" style="color:#0B3D2E;">About the Alumni Tracer</h4>
                    <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                        The Alumni Tracer system helps NDMU maintain updated alumni records to strengthen engagement,
                        support institutional reporting, and improve program feedback.
                    </p>

                    <div class="mt-5 rounded-lg p-4"
                         style="background:#F6F2E6; border:1px solid #E3C77A;">
                        <div class="text-sm font-semibold" style="color:#0B3D2E;">Data Privacy</div>
                        <div class="text-xs text-gray-700 mt-1 leading-relaxed">
                            Your information is handled responsibly and accessed only by authorized personnel
                            in compliance with data protection guidelines.
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
