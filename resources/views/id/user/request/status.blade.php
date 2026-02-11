<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
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

    <div class="py-6 max-w-4xl mx-auto space-y-4 px-4">

        @if(session('success'))
            <div class="p-3 rounded border border-green-200 bg-green-50 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="p-3 rounded border border-yellow-200 bg-yellow-50 text-yellow-800">
                {{ session('warning') }}
            </div>
        @endif

        @if(!$alumnus)
            <div class="p-4 bg-white border rounded">
                <div class="font-semibold text-gray-900">No intake record found.</div>
                <div class="text-sm text-gray-600 mt-1">
                    Please complete the intake form first before requesting an Alumni ID.
                </div>
            </div>
        @else

            <div class="bg-white border rounded p-5">
                @if(!$request)
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-semibold text-gray-900">No request yet</div>
                            <div class="text-sm text-gray-600">You can submit a new Alumni ID request.</div>
                        </div>

                        <a href="{{ route('id.user.request.create') }}"
                           class="px-4 py-2 rounded font-semibold text-white"
                           style="background:#0B3D2E;">
                            + Request Alumni ID
                        </a>
                    </div>
                @else
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <div class="text-sm text-gray-600">Latest Request</div>
                            <div class="text-xl font-semibold">
                                Status: <span class="text-[#0B3D2E]">{{ $request->status }}</span>
                            </div>
                            <div class="text-sm text-gray-600 mt-1">
                                Type: <span class="font-medium">{{ $request->request_type }}</span>
                                <span class="mx-2">•</span>
                                Submitted: <span class="font-medium">{{ optional($request->created_at)->format('M d, Y h:i A') }}</span>
                            </div>

                            @if($request->status === 'DECLINED' && $request->remarks)
                                <div class="mt-3 p-3 rounded border border-red-200 bg-red-50 text-red-800 text-sm">
                                    <div class="font-semibold mb-1">Declined Remarks</div>
                                    <div>{{ $request->remarks }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            @if(in_array($request->status, ['DECLINED','RELEASED'], true))
                                <a href="{{ route('id.user.request.create') }}"
                                   class="px-4 py-2 rounded font-semibold text-white"
                                   style="background:#0B3D2E;">
                                    Request Again
                                </a>
                            @else
                                <div class="text-sm text-gray-500">
                                    You can request again only after Declined or Released.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-5 border-t pt-4">
                        <div class="font-semibold text-gray-900 mb-2">Activity Log</div>
                        <div class="space-y-2 text-sm">
                            @foreach($request->logs as $log)
                                <div class="p-3 rounded border bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="font-semibold">{{ $log->action }}</div>
                                        <div class="text-xs text-gray-500">{{ optional($log->created_at)->format('M d, Y h:i A') }}</div>
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        By: {{ optional($log->actor)->name ?? 'System' }}
                                    </div>
                                    @if($log->remarks)
                                        <div class="mt-2 text-gray-700">{{ $log->remarks }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        @endif
    </div>
</x-app-layout>
