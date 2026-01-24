{{-- resources/views/portal/events/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900">Edit Event</h2>
                <p class="text-sm text-gray-600">
                    Update event details for the Calendar of Events.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('portal.events.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded font-semibold border"
                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                    Back to Events
                </a>

                @if(Route::has('events.calendar'))
                    <a href="{{ route('events.calendar') }}"
                       class="inline-flex items-center px-4 py-2 rounded font-semibold text-white"
                       style="background:#0B3D2E;">
                        View Calendar
                    </a>
                @endif
            </div>
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

            @if(session('success'))
                <div class="rounded-xl border p-4 bg-green-50" style="border-color:#86EFAC;">
                    <div class="text-green-900 font-semibold">Success</div>
                    <div class="text-green-800 text-sm mt-1">{{ session('success') }}</div>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('portal.events.update', $event) }}"
                  enctype="multipart/form-data"
                  class="bg-white rounded-xl shadow border p-6 space-y-6"
                  style="border-color:#EDE7D1;">
                @csrf
                @method('PUT')

                {{-- Basic Info --}}
                <div class="rounded-xl border p-5" style="border-color:#EDE7D1;">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Basic Information</div>
                            <div class="text-xs text-gray-600 mt-1">Keep details clear and formal for official posting.</div>
                        </div>

                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <input type="checkbox" name="is_published" value="1"
                                   @checked(old('is_published', (bool)($event->is_published ?? true)))>
                            Published
                        </label>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-700">Event Title *</label>
                            <input name="title"
                                   value="{{ old('title', $event->title) }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   placeholder="e.g., NDMU Alumni Homecoming 2026"
                                   required>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Event Type</label>
                            @php $type = old('type', $event->type); @endphp
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
                            <input name="organizer"
                                   value="{{ old('organizer', $event->organizer) }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   placeholder="e.g., Office of Alumni Relations">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Target Group / Batch</label>
                            <input name="target_group"
                                   value="{{ old('target_group', $event->target_group) }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   placeholder="e.g., Batch 2010, CBA Alumni, All Alumni">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Audience</label>
                            @php $aud = old('audience', $event->audience); @endphp
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
                                      placeholder="Short description of the event...">{{ old('description', $event->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Schedule & Venue --}}
                <div class="rounded-xl border p-5" style="border-color:#EDE7D1;">
                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Schedule & Venue</div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            // If you are still using start_date/end_date (DATE), keep these as date inputs.
                            // If you upgraded to start_at/end_at (DATETIME), switch these to datetime-local and map accordingly.
                            $startDate = old('start_date', optional($event->start_date)->format('Y-m-d'));
                            $endDate   = old('end_date', optional($event->end_date)->format('Y-m-d'));
                        @endphp

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Start Date *</label>
                            <input type="date" name="start_date"
                                   value="{{ $startDate }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   required>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">End Date</label>
                            <input type="date" name="end_date"
                                   value="{{ $endDate }}"
                                   class="mt-1 w-full rounded border-gray-300">
                            <div class="text-xs text-gray-600 mt-1">Optional. Use for multi-day events.</div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-700">Location / Venue</label>
                            <input name="location"
                                   value="{{ old('location', $event->location) }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   placeholder="e.g., NDMU Gymnasium / AVR / Online">
                        </div>
                    </div>
                </div>

                {{-- Registration & Contact --}}
                <div class="rounded-xl border p-5" style="border-color:#EDE7D1;">
                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Registration & Contact</div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Registration Link</label>
                            <input name="registration_link"
                                   value="{{ old('registration_link', $event->registration_link) }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   placeholder="https://forms.gle/...">
                            <div class="text-xs text-gray-600 mt-1">Optional. Use Google Forms or official registration page.</div>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Contact Email</label>
                            <input name="contact_email"
                                   value="{{ old('contact_email', $event->contact_email) }}"
                                   class="mt-1 w-full rounded border-gray-300"
                                   placeholder="alumni@ndmu.edu.ph">
                        </div>
                    </div>
                </div>

                {{-- Poster --}}
                <div class="rounded-xl border p-5" style="border-color:#EDE7D1;">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-sm font-semibold" style="color:#0B3D2E;">Event Poster</div>
                            <div class="text-xs text-gray-600 mt-1">Optional. JPG/PNG up to 4MB.</div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Upload New Poster</label>
                            <input type="file" name="poster" class="mt-1 w-full">
                            <div class="text-xs text-gray-600 mt-1">
                                If you upload a new poster, it will replace the current one.
                            </div>

                            {{-- Optional: keep existing poster path if you want to show it later --}}
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Current Poster</label>
                            @if(!empty($event->poster_path))
                                <div class="mt-2 rounded-lg border overflow-hidden" style="border-color:#EDE7D1;">
                                    <img src="{{ asset('storage/'.$event->poster_path) }}"
                                         alt="Event Poster"
                                         style="width:100%;height:auto;display:block;">
                                </div>
                                <div class="mt-2 text-xs text-gray-600">
                                    Stored at: <span class="font-mono">{{ $event->poster_path }}</span>
                                </div>
                            @else
                                <div class="mt-2 text-sm text-gray-600">
                                    No poster uploaded.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="px-5 py-2 rounded font-semibold text-white"
                                style="background:#0B3D2E;">
                            Save Changes
                        </button>

                        <a href="{{ route('portal.events.index') }}"
                           class="px-5 py-2 rounded font-semibold border"
                           style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                            Cancel
                        </a>
                    </div>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('portal.events.destroy', $event) }}"
                          onsubmit="return confirm('Delete this event? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="px-5 py-2 rounded font-semibold text-white"
                                style="background:#991B1B;">
                            Delete Event
                        </button>
                    </form>
                </div>

                {{-- Note --}}
                <div class="rounded-lg p-4 mt-2"
                     style="background:#F6F2E6; border:1px solid #E3C77A;">
                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Posting Reminder</div>
                    <div class="text-xs text-gray-700 mt-1 leading-relaxed">
                        Please ensure event details are accurate before publishing. For official announcements,
                        link to verified registration pages and use proper contact information.
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
