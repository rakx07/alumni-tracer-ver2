{{-- resources/views/portal/events/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Create Event
                </h2>
                <p class="text-sm text-gray-600">
                    Add an alumni event to the calendar.
                </p>
            </div>

            <a href="{{ route('portal.events.index') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back
            </a>
        </div>
    </x-slot>

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
            padding: 16px 18px;
            background: var(--ndmu-green);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }
        .strip-left{
            display:flex;
            align-items:center;
            gap: 12px;
            min-width: 0;
        }
        .gold-bar{
            width: 6px;
            height: 38px;
            background: var(--ndmu-gold);
            border-radius: 999px;
            flex: 0 0 auto;
        }
        .strip-title{
            color:#fff;
            font-weight: 900;
            letter-spacing: .2px;
        }
        .strip-sub{
            color: rgba(255,255,255,.78);
            font-size: 12px;
            margin-top: 2px;
        }

        .pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,251,240,.95);
            border: 1px solid rgba(227,199,122,.85);
            color: var(--ndmu-green);
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
        }
        .pill-dot{
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--ndmu-green);
        }

        .section-card{
            border: 1px solid rgba(227,199,122,.55);
            border-radius: 18px;
            background:#fff;
            padding: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }

        .subhead{
            display:flex;
            align-items:center;
            gap:10px;
            margin-bottom: 12px;
        }
        .subhead .badge{
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight: 900;
            border: 1px solid rgba(227,199,122,.65);
            background: rgba(227,199,122,.16);
            color: var(--ndmu-green);
            flex: 0 0 34px;
        }
        .subhead strong{
            display:block;
            font-size: 14px;
            font-weight: 900;
            color: var(--ndmu-green);
            letter-spacing: .2px;
        }
        .subhead span{
            display:block;
            font-size: 12px;
            color: rgba(15,23,42,.62);
            margin-top: 2px;
        }

        .label{
            display:block;
            font-size: 13px;
            font-weight: 900;
            color: rgba(15,23,42,.86);
        }
        .req{ color:#b91c1c; font-weight: 900; }

        .input, .textarea, .select, .file{
            margin-top: 6px;
            width: 100%;
            border-radius: 12px;
            border: 1px solid rgba(15,23,42,.18);
            background: #fff;
            padding: 10px 12px;
            outline: none;
            transition: .15s ease;
        }
        .textarea{ min-height: 120px; }
        .file{ padding: 10px; }

        .input:focus, .textarea:focus, .select:focus{
            border-color: rgba(227,199,122,.95);
            box-shadow: 0 0 0 4px rgba(227,199,122,.22);
        }

        .help{
            font-size: 12px;
            color: rgba(15,23,42,.60);
            margin-top: 6px;
            line-height: 1.5;
        }

        .btn-ndmu{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 900;
            transition: .15s ease;
            white-space: nowrap;
            box-shadow: 0 6px 14px rgba(2,6,23,.06);
        }
        .btn-primary{ background: var(--ndmu-green); color:#fff; }
        .btn-primary:hover{ filter: brightness(.95); }
        .btn-outline{
            background: var(--paper);
            color: var(--ndmu-green);
            border: 1px solid var(--ndmu-gold);
        }
        .btn-outline:hover{ filter: brightness(.98); }

        .soft-note{
            border: 1px solid var(--line);
            background: var(--paper);
            border-radius: 14px;
            padding: 12px;
        }
    </style>

    <div class="py-10" style="background:var(--page);">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div class="rounded-xl border p-4"
                     style="border-color: rgba(239,68,68,.25); background: rgba(239,68,68,.07);">
                    <div class="font-extrabold text-red-900">Please fix the following:</div>
                    <ul class="list-disc ml-5 text-sm text-red-900 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Top strip --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Event Creation</div>
                            <div class="strip-sub">NDMU-branded posting layout for the Calendar of Events.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            Office of Alumni Relations
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="soft-note flex items-center gap-3">
                        <img src="{{ asset('images/ndmu-logo.png') }}"
                             alt="NDMU Logo"
                             class="h-10 w-10 rounded-full ring-2"
                             style="--tw-ring-color: var(--ndmu-gold);"
                             onerror="this.style.display='none';">
                        <div class="min-w-0">
                            <div class="text-xs font-semibold tracking-wide text-gray-600">
                                NOTRE DAME OF MARBEL UNIVERSITY
                            </div>
                            <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                                Create New Event
                            </div>
                            <div class="help" style="margin-top:4px;">
                                Tip: Put the time schedule (e.g., 8:00 AM – 5:00 PM) inside the description.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST"
                  action="{{ route('portal.events.store') }}"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf

                {{-- Basic Info --}}
                <div class="section-card">
                    <div class="subhead">
                        <div class="badge">1</div>
                        <div class="min-w-0">
                            <strong>Basic Information</strong>
                            <span>Use clear, formal details suitable for official posting.</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="label">Event Title <span class="req">*</span></label>
                            <input name="title" value="{{ old('title') }}"
                                   class="input"
                                   placeholder="e.g., NDMU Alumni Homecoming 2026" required>
                        </div>

                        <div>
                            <label class="label">Event Type</label>
                            @php $type = old('type'); @endphp
                            <select name="type" class="select">
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
                            <label class="label">Organizer</label>
                            <input name="organizer" value="{{ old('organizer', 'Office of Alumni Relations') }}"
                                   class="input"
                                   placeholder="e.g., Office of Alumni Relations">
                        </div>

                        <div>
                            <label class="label">Target Group / Batch</label>
                            <input name="target_group" value="{{ old('target_group') }}"
                                   class="input"
                                   placeholder="e.g., Batch 2010, CBA Alumni, All Alumni">
                        </div>

                        <div>
                            <label class="label">Audience</label>
                            @php $aud = old('audience'); @endphp
                            <select name="audience" class="select">
                                <option value="">— Select —</option>
                                <option value="Alumni Only" @selected($aud==='Alumni Only')>Alumni Only</option>
                                <option value="Alumni & Students" @selected($aud==='Alumni & Students')>Alumni & Students</option>
                                <option value="Open to Public" @selected($aud==='Open to Public')>Open to Public</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">Description</label>
                            <textarea name="description" rows="5" class="textarea"
                                      placeholder="Short description of the event...">{{ old('description') }}</textarea>
                            <div class="help">
                                Tip: If you need time details (e.g., 8:00 AM – 5:00 PM), include it here.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Schedule & Venue --}}
                <div class="section-card">
                    <div class="subhead">
                        <div class="badge">2</div>
                        <div class="min-w-0">
                            <strong>Schedule & Venue</strong>
                            <span>Set start/end dates and the venue. End date is optional.</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">Start Date <span class="req">*</span></label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}"
                                   class="input" required>
                        </div>

                        <div>
                            <label class="label">End Date</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}"
                                   class="input">
                            <div class="help">Optional. Use for multi-day events.</div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">Location / Venue</label>
                            <input name="location" value="{{ old('location') }}"
                                   class="input"
                                   placeholder="e.g., NDMU Gymnasium / AVR / Online">
                        </div>
                    </div>
                </div>

                {{-- Registration --}}
                <div class="section-card">
                    <div class="subhead">
                        <div class="badge">3</div>
                        <div class="min-w-0">
                            <strong>Registration</strong>
                            <span>Optional. Provide a link only if registration is required.</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">Registration Link</label>
                            <input name="registration_link" value="{{ old('registration_link') }}"
                                   class="input"
                                   placeholder="https://forms.gle/...">
                            <div class="help">Leave blank if not applicable.</div>
                        </div>

                        <div>
                            <label class="label">Contact Email</label>
                            <input name="contact_email" value="{{ old('contact_email', 'alumni@ndmu.edu.ph') }}"
                                   class="input"
                                   placeholder="alumni@ndmu.edu.ph">
                        </div>
                    </div>
                </div>

                {{-- Poster --}}
                <div class="section-card">
                    <div class="subhead">
                        <div class="badge">4</div>
                        <div class="min-w-0">
                            <strong>Event Poster</strong>
                            <span>Optional upload. JPG/PNG/PDF up to 10MB.</span>
                        </div>
                    </div>

                    <input type="file" name="poster" class="file"
                           accept="image/png,image/jpeg,application/pdf">
                    <div class="help">Recommended: upload a clean poster with readable details.</div>
                </div>

                {{-- Publishing --}}
                <div class="section-card">
                    <div class="subhead">
                        <div class="badge">5</div>
                        <div class="min-w-0">
                            <strong>Publishing</strong>
                            <span>Save as published or draft.</span>
                        </div>
                    </div>

                    <label class="inline-flex items-center gap-2 text-sm font-extrabold" style="color:var(--ndmu-green);">
                        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', true))>
                        Published (visible on calendar)
                    </label>

                    <div class="help">Uncheck if you want to save as draft (not visible publicly).</div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-wrap items-center gap-3">
                    <button class="btn-ndmu btn-primary" type="submit">
                        Save Event
                    </button>

                    <a href="{{ route('portal.events.index') }}" class="btn-ndmu btn-outline">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
