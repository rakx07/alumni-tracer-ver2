{{-- resources/views/portal/events/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900">Create Event</h2>
                <p class="text-sm text-gray-600">Add an alumni event to the calendar.</p>
            </div>
            <a href="{{ route('portal.events.index') }}"
               class="inline-flex items-center px-4 py-2 rounded font-semibold border"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div class="rounded-xl border p-4 bg-red-50" style="border-color:#FCA5A5;">
                    <div class="font-semibold text-red-800">Please fix the following:</div>
                    <ul class="list-disc ml-6 text-sm text-red-700 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('portal.events.store') }}" enctype="multipart/form-data"
                  class="bg-white rounded-xl shadow border p-6 space-y-6"
                  style="border-color:#EDE7D1;">
                @csrf

                {{-- Basic Info --}}
                <div class="rounded-xl border p-5" style="border-color:#EDE7D1;">
                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Basic Information</div>
                    <div class="text-xs text-gray-600 mt-1">Use clear, formal details suitable for official posting.</div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-700">Event Title <span class="text-red-600">*</span></label>
                            <input name="title" value="{{ old('title') }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   placeholder="e.g., NDMU Alumni Homecoming 2026" required>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Event Type</label>
                            @php $type = old('type'); @endphp
                            <select name="type" class="mt-1 w-full rounded border-gray-300">
                                <option value="">— Select —</option>
                                <option value="homecoming" @selected($type==='homecoming')>Homecoming</option>
                                <option value="reunion" @selected($type==='reunion')>Reunion</option>
                                <option value="webinar" @selected($type==='webinar')>Webinar / Talk</option>
                                <option value="outreach" @selected($type==='outreach')>Outreach / Service</option>
                                <option value="training" @selected($type==='training')>Training</option>
                                <option value="sports" @selected($type==='sports')>Sports / Competition</option>
                                <option value="other" @selected($type==='other')>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Organizer</label>
                            <input name="organizer" value="{{ old('organizer', 'Office of Alumni Relations') }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   placeholder="e.g., Office of Alumni Relations">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Target Group / Batch</label>
                            <input name="target_group" value="{{ old('target_group') }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   placeholder="e.g., Batch 2010, CBA Alumni, All Alumni">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Audience</label>
                            @php $aud = old('audience'); @endphp
                            <select name="audience" class="mt-1 w-full rounded border-gray-300">
                                <option value="">— Select —</option>
                                <option value="Alumni Only" @selected($aud==='Alumni Only')>Alumni Only</option>
                                <option value="Alumni & Students" @selected($aud==='Alumni & Students')>Alumni & Students</option>
                                <option value="Open to Public" @selected($aud==='Open to Public')>Open to Public</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-700">Description</label>
                            <textarea name="description" rows="4"
                                      class="mt-1 w-full rounded border-gray-300"
                                      placeholder="Short description of the event...">{{ old('description') }}</textarea>
                            <div class="text-xs text-gray-600 mt-1">
                                Tip: If you need time details (e.g., 8:00 AM – 5:00 PM), include it here.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Schedule & Venue --}}
                <div class="rounded-xl border p-5" style="border-color:#EDE7D1;">
                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Schedule & Venue</div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Start Date <span class="text-red-600">*</span></label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}"
                                   class="mt-1 w-full rounded border-gray-300" required>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">End Date</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}"
                                   class="mt-1 w-full rounded border-gray-300">
                            <div class="text-xs text-gray-600 mt-1">Optional. Use for multi-day events.</div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-700">Location / Venue</label>
                            <input name="location" value="{{ old('location') }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   placeholder="e.g., NDMU Gymnasium / AVR / Online">
                        </div>
                    </div>
                </div>

                {{-- Registration --}}
                <div class="rounded-xl border p-5" style="border-color:#EDE7D1;">
                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Registration</div>
                    <div class="text-xs text-gray-600 mt-1">Optional. Provide a link only if registration is required.</div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Registration Link</label>
                            <input name="registration_link" value="{{ old('registration_link') }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   placeholder="https://forms.gle/...">
                            <div class="text-xs text-gray-600 mt-1">Leave blank if not applicable.</div>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Contact Email</label>
                            <input name="contact_email" value="{{ old('contact_email', 'alumni@ndmu.edu.ph') }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   placeholder="alumni@ndmu.edu.ph">
                        </div>
                    </div>
                </div>

                {{-- Poster (optional) --}}
                <div class="rounded-xl border p-5" style="border-color:#EDE7D1;">
                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Event Poster (Optional)</div>

                    <div class="mt-4">
                        <input type="file" name="poster" class="w-full">
                        <div class="text-xs text-gray-600 mt-1">JPG/PNG up to 10MB.</div>
                    </div>
                </div>

                {{-- Publishing --}}
                <div class="rounded-xl border p-5" style="border-color:#EDE7D1;">
                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Publishing</div>

                    <div class="mt-3 flex flex-col sm:flex-row gap-4">
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', true))>
                            Published (visible on calendar)
                        </label>
                    </div>

                    <div class="mt-2 text-xs text-gray-600">
                        Uncheck if you want to save as draft (not visible publicly).
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button class="px-5 py-2 rounded font-semibold text-white"
                            style="background:#0B3D2E;">
                        Save Event
                    </button>

                    <a href="{{ route('portal.events.index') }}"
                       class="px-5 py-2 rounded font-semibold border"
                       style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
