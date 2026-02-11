{{-- resources/views/id/user/request/status.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Alumni ID Request Status
                </h2>
                <div class="text-sm text-gray-600">
                    Track your Alumni ID processing request.
                </div>
            </div>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 rounded font-semibold border"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div class="rounded-xl border p-4 bg-green-50" style="border-color:#86EFAC;">
                    <div class="font-semibold text-green-900">Success</div>
                    <div class="text-sm text-green-800 mt-1">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('warning'))
                <div class="rounded-xl border p-4 bg-yellow-50" style="border-color:#FDE68A;">
                    <div class="font-semibold text-yellow-900">Notice</div>
                    <div class="text-sm text-yellow-800 mt-1">{{ session('warning') }}</div>
                </div>
            @endif

            {{-- NDMU HERO --}}
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
                                Alumni ID Processing Status
                            </h3>

                            <p class="mt-1 text-sm text-white/90 leading-relaxed">
                                View your latest request status and activity log updates from the Alumni Office.
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('dashboard') }}"
                                   class="inline-flex items-center px-4 py-2 rounded font-semibold"
                                   style="background:#E3C77A; color:#0B3D2E;">
                                    Back to Dashboard
                                </a>

                                @if(Route::has('intake.form'))
                                    <a href="{{ route('intake.form') }}"
                                       class="inline-flex items-center px-4 py-2 rounded font-semibold border"
                                       style="border-color:rgba(255,255,255,.45); color:#fff;">
                                        Open Intake Form
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="w-full lg:w-[380px]">
                            <div class="rounded-lg p-4"
                                 style="background:rgba(255,255,255,.08); border:1px solid rgba(227,199,122,.25);">
                                <div class="text-sm font-semibold" style="color:#E3C77A;">How it works</div>
                                <ul class="mt-2 text-sm text-white/90 space-y-2">
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        Requests are reviewed by the Alumni Officer.
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        You can submit again only after Declined or Released.
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full" style="background:#E3C77A;"></span>
                                        Check the activity log for updates.
                                    </li>
                                </ul>
                                <div class="mt-3 text-xs text-white/80">
                                    Need to correct information? Update your intake form first.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT --}}
            @if(!$alumnus)
                <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                    <div class="text-lg font-semibold" style="color:#0B3D2E;">No intake record found</div>
                    <div class="text-sm text-gray-600 mt-1">
                        Please complete the intake form first before requesting an Alumni ID.
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @if(Route::has('intake.form'))
                            <a href="{{ route('intake.form') }}"
                               class="inline-flex items-center px-4 py-2 rounded font-semibold text-white"
                               style="background:#0B3D2E;">
                                Complete Intake Form
                            </a>
                        @endif
                    </div>
                </div>
            @else

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- LEFT: STATUS CARD --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow border p-5 space-y-3" style="border-color:#EDE7D1;">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Current Status</div>
                                    <div class="text-xs text-gray-500">Your latest Alumni ID request</div>
                                </div>

                                <div class="text-xs font-semibold px-3 py-1 rounded-full"
                                     style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                                    Track
                                </div>
                            </div>

                            @if(!$request)
                                <div class="rounded-lg p-3 border bg-gray-50">
                                    <div class="font-semibold text-gray-900">No request yet</div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        You can submit a new Alumni ID request.
                                    </div>
                                </div>

                                <a href="{{ route('id.user.request.create') }}"
                                   class="inline-flex items-center justify-center w-full px-4 py-2 rounded font-semibold text-white"
                                   style="background:#0B3D2E;">
                                    + Request Alumni ID
                                </a>

                                <div class="text-xs text-gray-500">
                                    Note: one active request at a time.
                                </div>
                            @else
                                @php
                                    $status = $request->status ?? '';
                                    // small badge colors
                                    $badge = match($status) {
                                        'APPROVED' => ['bg' => 'rgba(134,239,172,.18)', 'bd' => 'rgba(134,239,172,.35)', 'tx' => '#D1FAE5'],
                                        'PROCESSING' => ['bg' => 'rgba(253,230,138,.18)', 'bd' => 'rgba(253,230,138,.35)', 'tx' => '#000000'],
                                        'READY_FOR_PICKUP' => ['bg' => 'rgba(147,197,253,.18)', 'bd' => 'rgba(147,197,253,.35)', 'tx' => '#000000'],
                                       'DECLINED' => ['bg' => '#FEE2E2','bd' => '#FCA5A5','tx' => '#991B1B'],
                                        'RELEASED' => ['bg' => 'rgba(229,231,235,.25)', 'bd' => 'rgba(229,231,235,.35)', 'tx' => '#000000'],
                                        default => ['bg' => 'rgba(255,255,255,.10)', 'bd' => 'rgba(227,199,122,.25)', 'tx' => '#FFFFFF'],
                                    };
                                @endphp

                                <div class="rounded-lg p-4"
                                     style="background:#FFFBF0; border:1px solid #E3C77A;">
                                    <div class="text-xs text-gray-600">Latest Request</div>

                                    <div class="mt-1 text-2xl font-bold" style="color:#0B3D2E;">
                                        {{ $status }}
                                    </div>

                                    <div class="mt-2 text-sm text-gray-700">
                                        Type: <span class="font-semibold">{{ $request->request_type }}</span>
                                        <span class="mx-2">•</span>
                                        Submitted: <span class="font-semibold">{{ optional($request->created_at)->format('M d, Y h:i A') }}</span>
                                    </div>

                                    <div class="mt-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                              style="background:{{ $badge['bg'] }}; border:1px solid {{ $badge['bd'] }}; color:{{ $badge['tx'] }};">
                                            Status: {{ $status }}
                                        </span>
                                    </div>
                                </div>

                                @if($status === 'DECLINED' && $request->remarks)
                                    <div class="mt-3 p-3 rounded-lg border bg-red-50 text-red-800 text-sm"
                                         style="border-color:#FCA5A5;">
                                        <div class="font-semibold mb-1">Declined Remarks</div>
                                        <div>{{ $request->remarks }}</div>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    @if(in_array($status, ['DECLINED','RELEASED'], true))
                                        <a href="{{ route('id.user.request.create') }}"
                                           class="inline-flex items-center justify-center w-full px-4 py-2 rounded font-semibold text-white"
                                           style="background:#0B3D2E;">
                                            Request Again
                                        </a>
                                    @else
                                        <div class="text-sm text-gray-500">
                                            You can request again only after Declined or Released.
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- RIGHT: ACTIVITY LOG --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow border p-6" style="border-color:#EDE7D1;">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="text-lg font-semibold" style="color:#0B3D2E;">Activity Log</div>
                                    <div class="text-sm text-gray-600">
                                        Updates recorded by the Alumni Office during processing.
                                    </div>
                                </div>
                                <div class="text-xs font-semibold px-3 py-1 rounded-full"
                                     style="background:#F6F2E6; color:#0B3D2E; border:1px solid #E3C77A;">
                                    History
                                </div>
                            </div>

                            @if(!$request)
                                <div class="mt-4 p-4 rounded-lg border bg-gray-50 text-sm text-gray-700">
                                    No activity yet. Submit a request to start tracking updates.
                                </div>
                            @else
                                <div class="mt-5 space-y-3">
                                    @forelse($request->logs as $log)
                                        <div class="p-4 rounded-lg border bg-gray-50">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="font-semibold text-gray-900">
                                                    {{ $log->action }}
                                                </div>
                                                <div class="text-xs text-gray-500 whitespace-nowrap">
                                                    {{ optional($log->created_at)->format('M d, Y h:i A') }}
                                                </div>
                                            </div>

                                            <div class="text-xs text-gray-600 mt-1">
                                                By: {{ optional($log->actor)->name ?? 'System' }}
                                            </div>

                                            @if($log->remarks)
                                                <div class="mt-2 text-sm text-gray-700">
                                                    {{ $log->remarks }}
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="p-4 rounded-lg border bg-gray-50 text-sm text-gray-700">
                                            No logs yet.
                                        </div>
                                    @endforelse
                                </div>
                            @endif

                            <div class="mt-5 rounded-lg p-4"
                                 style="background:#FFFBF0; border:1px solid #E3C77A;">
                                <div class="text-sm font-semibold" style="color:#0B3D2E;">Need help?</div>
                                <div class="text-xs text-gray-700 mt-1 leading-relaxed">
                                    If your request is declined, please review the remarks and submit again with corrected details.
                                    For changes to personal information, update the intake form first.
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            @endif

        </div>
    </div>
</x-app-layout>
