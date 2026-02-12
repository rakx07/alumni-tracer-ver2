{{-- resources/views/portal/events/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">Edit Event</h2>
                <p class="text-sm text-gray-600">Update event details for the Calendar of Events.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('portal.events.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                    Back to Events
                </a>

                @if(Route::has('events.calendar'))
                    <a href="{{ route('events.calendar') }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white shadow-sm"
                       style="background:#0B3D2E;">
                        View Calendar
                    </a>
                @endif
            </div>
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
        .textarea{ min-height: 110px; }
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
        .btn-danger{ background:#991B1B; color:#fff; }
        .btn-danger:hover{ filter: brightness(.95); }

        .soft-note{
            border: 1px solid var(--line);
            background: var(--paper);
            border-radius: 14px;
            padding: 12px;
        }

        /* Poster preview button */
        .preview-btn{
            display:inline-flex; align-items:center; justify-content:center;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 900;
            background: rgba(11,61,46,.10);
            color: var(--ndmu-green);
            border: 1px solid rgba(227,199,122,.70);
        }
        .preview-btn:hover{ filter: brightness(.98); }

        /* Modal */
        .modal-overlay{
            position: fixed;
            inset: 0;
            z-index: 60;
            display:none;
            align-items:center;
            justify-content:center;
            background: rgba(0,0,0,.70);
        }
        .modal-overlay.show{ display:flex; }
        .modal-card{
            position: relative;
            background:#fff;
            border-radius: 18px;
            width: min(1100px, calc(100% - 24px));
            box-shadow: 0 20px 60px rgba(0,0,0,.30);
            overflow:hidden;
            border: 1px solid rgba(227,199,122,.55);
        }
        .modal-top{
            padding: 12px 14px;
            background: var(--ndmu-green);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
        }
        .modal-top .title{
            color:#fff;
            font-weight: 900;
            font-size: 13px;
            letter-spacing: .2px;
        }
        .modal-close{
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: rgba(255,255,255,.14);
            border: 1px solid rgba(255,255,255,.18);
            color:#fff;
            font-weight: 900;
            font-size: 18px;
            line-height: 1;
            display:flex;
            align-items:center;
            justify-content:center;
        }
        .modal-close:hover{ background: rgba(255,255,255,.20); }
        .modal-body{
            padding: 14px;
            max-height: 82vh;
            overflow:auto;
            background: #fff;
        }
    </style>

    @php
        $posterUrl = !empty($event->poster_path) ? asset('storage/'.$event->poster_path) : null;

        $ext = $event->poster_path ? strtolower(pathinfo($event->poster_path, PATHINFO_EXTENSION)) : null;

        $isPdf = ($ext === 'pdf');
    @endphp

    <div class="py-10" style="background:var(--page);">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Top strip --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Update Event Details</div>
                            <div class="strip-sub">Keep details accurate and publish only when verified.</div>
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
                                Editing: {{ $event->title }}
                            </div>
                            <div class="help" style="margin-top:4px;">
                                Reminder: confirm dates/venue and attachments before publishing.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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

            @if(session('success'))
                <div class="rounded-xl border p-4"
                     style="border-color: rgba(34,197,94,.25); background: rgba(34,197,94,.07);">
                    <div class="font-extrabold text-green-900">Success</div>
                    <div class="text-sm text-green-900 mt-1">{{ session('success') }}</div>
                </div>
            @endif

            {{-- MAIN UPDATE FORM --}}
            <form method="POST"
                  action="{{ route('portal.events.update', $event) }}"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Ensure checkbox false is sent when unchecked --}}
                <input type="hidden" name="is_published" value="0">

                {{-- 1) Basic --}}
                <div class="section-card">
                    <div class="subhead">
                        <div class="badge">1</div>
                        <div class="min-w-0">
                            <strong>Basic Information</strong>
                            <span>Keep details clear and formal for official posting.</span>
                        </div>

                        <div class="ml-auto">
                            <label class="inline-flex items-center gap-2 text-sm font-extrabold" style="color:var(--ndmu-green);">
                                <input type="checkbox" name="is_published" value="1"
                                       @checked(old('is_published', (bool)($event->is_published ?? true)))>
                                Published
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="label">Event Title <span class="req">*</span></label>
                            <input name="title"
                                   value="{{ old('title', $event->title) }}"
                                   class="input"
                                   placeholder="e.g., NDMU Alumni Homecoming 2026"
                                   required>
                        </div>

                        <div>
                            <label class="label">Event Type</label>
                            @php $type = old('type', $event->type); @endphp
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
                            <input name="organizer"
                                   value="{{ old('organizer', $event->organizer) }}"
                                   class="input"
                                   placeholder="e.g., Office of Alumni Relations">
                        </div>

                        <div>
                            <label class="label">Target Group / Batch</label>
                            <input name="target_group"
                                   value="{{ old('target_group', $event->target_group) }}"
                                   class="input"
                                   placeholder="e.g., Batch 2010, CBA Alumni, All Alumni">
                        </div>

                        <div>
                            <label class="label">Audience</label>
                            @php $aud = old('audience', $event->audience); @endphp
                            <select name="audience" class="select">
                                <option value="">— Select —</option>
                                <option value="Alumni Only" @selected($aud==='Alumni Only')>Alumni Only</option>
                                <option value="Alumni & Students" @selected($aud==='Alumni & Students')>Alumni & Students</option>
                                <option value="Open to Public" @selected($aud==='Open to Public')>Open to Public</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">Description</label>
                            <textarea name="description" rows="5"
                                      class="textarea"
                                      placeholder="Short description of the event...">{{ old('description', $event->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- 2) Schedule --}}
                <div class="section-card">
                    <div class="subhead">
                        <div class="badge">2</div>
                        <div class="min-w-0">
                            <strong>Schedule & Venue</strong>
                            <span>Set start/end dates and the venue. End date is optional.</span>
                        </div>
                    </div>

                    @php
                        $startDate = old('start_date', optional($event->start_date)->format('Y-m-d'));
                        $endDate   = old('end_date', optional($event->end_date)->format('Y-m-d'));
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">Start Date <span class="req">*</span></label>
                            <input type="date" name="start_date"
                                   value="{{ $startDate }}"
                                   class="input"
                                   required>
                        </div>

                        <div>
                            <label class="label">End Date</label>
                            <input type="date" name="end_date"
                                   value="{{ $endDate }}"
                                   class="input">
                            <div class="help">Optional. Use for multi-day events.</div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">Location / Venue</label>
                            <input name="location"
                                   value="{{ old('location', $event->location) }}"
                                   class="input"
                                   placeholder="e.g., NDMU Gymnasium / AVR / Online">
                        </div>
                    </div>
                </div>

                {{-- 3) Registration --}}
                <div class="section-card">
                    <div class="subhead">
                        <div class="badge">3</div>
                        <div class="min-w-0">
                            <strong>Registration & Contact</strong>
                            <span>Optional. Use official links and proper contact details.</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">Registration Link</label>
                            <input name="registration_link"
                                   value="{{ old('registration_link', $event->registration_link) }}"
                                   class="input"
                                   placeholder="https://forms.gle/...">
                            <div class="help">Optional. Use Google Forms or official registration page.</div>
                        </div>

                        <div>
                            <label class="label">Contact Email</label>
                            <input name="contact_email"
                                   value="{{ old('contact_email', $event->contact_email) }}"
                                   class="input"
                                   placeholder="alumni@ndmu.edu.ph">
                        </div>
                    </div>
                </div>

                {{-- 4) Poster --}}
                <div class="section-card">
                    <div class="subhead">
                        <div class="badge">4</div>
                        <div class="min-w-0">
                            <strong>Event Poster</strong>
                            <span>Optional. JPG / PNG / PDF up to 10MB.</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                        <div>
                            <label class="label">Upload New Poster</label>
                            <input type="file" name="poster" class="file"
                                   accept="image/png,image/jpeg,application/pdf">
                            <div class="help">
                                If you upload a new file, it will replace the current one.
                            </div>
                        </div>

                        <div>
                            <label class="label">Current Poster</label>

                            @if($posterUrl)
                                @if($isPdf)
                                    <button type="button"
                                            onclick="openPosterModal('{{ $posterUrl }}','pdf')"
                                            class="preview-btn mt-2">
                                        View Current Poster (PDF)
                                    </button>
                                    <div class="help">
                                        Stored at: <span class="font-mono">{{ $event->poster_path }}</span>
                                    </div>
                                @else
                                    <img src="{{ $posterUrl }}"
                                         alt="Event Poster"
                                         class="mt-2 rounded-lg border cursor-pointer"
                                         style="border-color:rgba(227,199,122,.55); width:100%; height:auto; display:block;"
                                         onclick="openPosterModal('{{ $posterUrl }}','image')">
                                    <div class="help">
                                        Click image to preview • <span class="font-mono">{{ $event->poster_path }}</span>
                                    </div>
                                @endif
                            @else
                                <div class="help mt-2">No poster uploaded.</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" class="btn-ndmu btn-primary">
                            Save Changes
                        </button>

                        <a href="{{ route('portal.events.index') }}" class="btn-ndmu btn-outline">
                            Cancel
                        </a>
                    </div>

                    <button type="submit"
                            form="deleteEventForm"
                            class="btn-ndmu btn-danger"
                            onclick="return confirm('Delete this event? This action cannot be undone.');">
                        Delete Event
                    </button>
                </div>

                <div class="soft-note">
                    <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">Posting Reminder</div>
                    <div class="text-xs text-gray-700 mt-1 leading-relaxed">
                        Please ensure event details are accurate before publishing. For official announcements,
                        link to verified registration pages and use proper contact information.
                    </div>
                </div>
            </form>

            <form id="deleteEventForm" method="POST" action="{{ route('portal.events.destroy', $event) }}">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div>

    {{-- POP-OUT MODAL --}}
    <div id="posterModal" class="modal-overlay" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-label="Poster preview">
            <div class="modal-top">
                <div class="title">Poster Preview</div>
                <button type="button" class="modal-close" onclick="closePosterModal()" aria-label="Close">
                    ✕
                </button>
            </div>
            <div id="posterModalContent" class="modal-body"></div>
        </div>
    </div>

    <script>
        function openPosterModal(url, type) {
            const modal = document.getElementById('posterModal');
            const content = document.getElementById('posterModalContent');

            content.innerHTML = '';

            if (type === 'pdf') {
                content.innerHTML = `<iframe src="${url}" style="width:100%; height:75vh;" frameborder="0"></iframe>`;
            } else {
                content.innerHTML = `<img src="${url}" style="width:100%; height:auto; display:block; margin:auto;">`;
            }

            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closePosterModal() {
            const modal = document.getElementById('posterModal');
            const content = document.getElementById('posterModalContent');

            content.innerHTML = '';
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        // Click outside closes
        document.getElementById('posterModal').addEventListener('click', function (e) {
            if (e.target === this) closePosterModal();
        });

        // Escape closes
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closePosterModal();
        });
    </script>
</x-app-layout>
