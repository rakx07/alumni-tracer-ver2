<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Alumni ID Request #{{ $request->id }}
                </h2>
                <div class="text-sm text-gray-600">
                    Review details, signature, attachments, and update status.
                </div>
            </div>

            <a href="{{ route('id.officer.requests.index') }}"
               class="inline-flex items-center px-4 py-2 rounded font-semibold border"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto space-y-4 px-4">
        @if(session('success'))
            <div class="p-3 rounded border border-green-200 bg-green-50 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="p-3 rounded border border-red-200 bg-red-50 text-red-800">
                <div class="font-semibold mb-1">Please fix:</div>
                <ul class="list-disc pl-5 text-sm">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Details --}}
            <div class="bg-white border rounded-lg p-4 lg:col-span-2 space-y-3">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-gray-600">Current Status</div>
                        <div class="text-xl font-semibold text-[#0B3D2E]">{{ $request->status }}</div>
                        <div class="text-sm text-gray-600 mt-1">
                            Type: <span class="font-medium">{{ $request->request_type }}</span>
                            <span class="mx-2">•</span>
                            Submitted: <span class="font-medium">{{ optional($request->created_at)->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        Active Request: <span class="font-semibold">{{ $request->is_active_request ? 'YES' : 'NO' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div class="p-3 rounded border bg-gray-50">
                        <div class="text-xs text-gray-500">Name</div>
                        <div class="font-semibold">
                            {{ $request->last_name }}, {{ $request->first_name }} {{ $request->middle_name }}
                        </div>
                    </div>
                    <div class="p-3 rounded border bg-gray-50">
                        <div class="text-xs text-gray-500">School ID (optional)</div>
                        <div class="font-semibold">{{ $request->school_id ?? '—' }}</div>
                    </div>
                    <div class="p-3 rounded border bg-gray-50">
                        <div class="text-xs text-gray-500">Course</div>
                        <div class="font-semibold">{{ $request->course ?? '—' }}</div>
                    </div>
                    <div class="p-3 rounded border bg-gray-50">
                        <div class="text-xs text-gray-500">Graduation Year</div>
                        <div class="font-semibold">{{ $request->grad_year ?? '—' }}</div>
                    </div>
                    <div class="p-3 rounded border bg-gray-50">
                        <div class="text-xs text-gray-500">Birthdate</div>
                        <div class="font-semibold">{{ optional($request->birthdate)->format('M d, Y') ?? '—' }}</div>
                    </div>
                    <div class="p-3 rounded border bg-gray-50">
                        <div class="text-xs text-gray-500">Last Action By</div>
                        <div class="font-semibold">{{ optional($request->lastActor)->name ?? '—' }}</div>
                    </div>
                </div>

                {{-- Update Status --}}
                <div class="border-t pt-4">
                    <div class="font-semibold mb-2">Update Status</div>

                    <form method="POST" action="{{ route('id.officer.requests.updateStatus', $request->id) }}" class="space-y-3">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">New Status</label>
                                @php
                                    $currentStatus = old('status', $request->status);
                                @endphp

                                <select name="status" id="status"
                                        class="mt-1 w-full rounded border-gray-300"
                                        {{ $request->status === 'RELEASED' ? 'disabled' : '' }}>
                                    @foreach(['APPROVED','PROCESSING','DECLINED','READY_FOR_PICKUP','RELEASED'] as $s)
                                        <option value="{{ $s }}" {{ $currentStatus === $s ? 'selected' : '' }}>
                                            {{ $s }}
                                        </option>
                                    @endforeach
                                </select>

                                @if($request->status === 'RELEASED')
                                    <div class="text-xs text-gray-500 mt-1">
                                        This request is already RELEASED and can no longer be changed.
                                    </div>
                                @endif

                                <div class="text-xs text-gray-500 mt-1">
                                    Declined & Released will automatically end the active request.
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Remarks (required if Declined)</label>
                                <textarea name="remarks" rows="3"
                                          class="mt-1 w-full rounded border-gray-300"
                                          placeholder="Add remarks...">{{ old('remarks') }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="px-4 py-2 rounded font-semibold text-white"
                                    style="background:#0B3D2E;">
                                Save Status
                            </button>
                        </div>
                    </form>

                    @if($request->status === 'DECLINED' && $request->remarks)
                        <div class="mt-3 p-3 rounded border border-red-200 bg-red-50 text-red-800 text-sm">
                            <div class="font-semibold mb-1">Decline Remarks</div>
                            <div>{{ $request->remarks }}</div>
                        </div>
                    @endif
                </div>

                {{-- Logs --}}
                <div class="border-t pt-4">
                    <div class="font-semibold mb-2">Activity Logs</div>
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
            </div>

            {{-- Signature & Attachments --}}
            <div class="bg-white border rounded-lg p-4 space-y-3">
                <div class="font-semibold">Signature</div>

                @php
                    $sigUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($request->signature_path);
                    $isPdf = str_ends_with(strtolower($request->signature_path), '.pdf');
                @endphp

                @if($isPdf)
                    <a href="{{ $sigUrl }}" target="_blank" class="underline" style="color:#0B3D2E;">
                        Open Signature PDF
                    </a>
                @else
                    <img src="{{ $sigUrl }}" class="w-full rounded border" alt="Signature">
                    <a href="{{ $sigUrl }}" target="_blank" class="text-sm underline" style="color:#0B3D2E;">Open full</a>
                @endif

                <div class="border-t pt-3">
                    <div class="font-semibold mb-2">Attachments</div>

                    @if($request->attachments->isEmpty())
                        <div class="text-sm text-gray-500">No attachments uploaded.</div>
                    @else
                        <div class="space-y-2">
                            @foreach($request->attachments as $a)
                                @php
                                    $url = \Illuminate\Support\Facades\Storage::disk('public')->url($a->file_path);
                                    $isPdfA = str_ends_with(strtolower($a->file_path), '.pdf');
                                @endphp

                                <div class="p-3 rounded border bg-gray-50">
                                    <div class="text-sm font-semibold">{{ $a->attachment_type }}</div>
                                    <div class="text-xs text-gray-600">
                                        {{ $a->original_name ?? basename($a->file_path) }}
                                    </div>
                                    <a href="{{ $url }}" target="_blank" class="text-sm underline" style="color:#0B3D2E;">
                                        {{ $isPdfA ? 'Open PDF' : 'Open file' }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
