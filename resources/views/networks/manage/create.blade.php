<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Create Network
                </h2>
                <p class="text-sm text-gray-600">
                    Upload a logo and add a link for an Alumni Association / Network.
                </p>
            </div>

            <a href="{{ route('portal.networks.manage.index') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back
            </a>
        </div>
    </x-slot>

    <style>
        :root{ --ndmu-green:#0B3D2E; --ndmu-gold:#E3C77A; --paper:#FFFBF0; --page:#FAFAF8; --line:#EDE7D1; }
        .strip{ border:1px solid var(--line); border-radius:18px; overflow:hidden; background:#fff; box-shadow:0 10px 24px rgba(2,6,23,.06); }
        .strip-top{ padding:16px 18px; background:var(--ndmu-green); display:flex; align-items:center; justify-content:space-between; gap:12px; }
        .strip-left{ display:flex; align-items:center; gap:12px; min-width:0; }
        .gold-bar{ width:6px; height:38px; background:var(--ndmu-gold); border-radius:999px; flex:0 0 auto; }
        .strip-title{ color:#fff; font-weight:900; letter-spacing:.2px; }
        .strip-sub{ color:rgba(255,255,255,.78); font-size:12px; margin-top:2px; }
        .section-card{ border:1px solid rgba(227,199,122,.55); border-radius:18px; background:#fff; padding:18px; box-shadow:0 10px 24px rgba(2,6,23,.06); }
        .subhead{ display:flex; align-items:center; gap:10px; margin-bottom:12px; }
        .subhead .badge{ width:34px; height:34px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:900;
            border:1px solid rgba(227,199,122,.65); background:rgba(227,199,122,.16); color:var(--ndmu-green); }
        .subhead strong{ display:block; font-size:14px; font-weight:900; color:var(--ndmu-green); }
        .subhead span{ display:block; font-size:12px; color:rgba(15,23,42,.62); margin-top:2px; }
        .label{ display:block; font-size:13px; font-weight:900; color:rgba(15,23,42,.86); }
        .req{ color:#b91c1c; font-weight:900; }
        .input, .textarea, .file{
            margin-top:6px; width:100%; border-radius:12px; border:1px solid rgba(15,23,42,.18);
            background:#fff; padding:10px 12px; outline:none; transition:.15s ease;
        }
        .textarea{ min-height: 120px; }
        .input:focus, .textarea:focus{
            border-color: rgba(227,199,122,.95);
            box-shadow: 0 0 0 4px rgba(227,199,122,.22);
        }
        .help{ font-size:12px; color:rgba(15,23,42,.60); margin-top:6px; line-height:1.5; }
        .btn-ndmu{ display:inline-flex; align-items:center; justify-content:center; padding:10px 14px; border-radius:12px; font-size:13px; font-weight:900; transition:.15s ease; white-space:nowrap; box-shadow:0 6px 14px rgba(2,6,23,.06); }
        .btn-primary{ background: var(--ndmu-green); color:#fff; }
        .btn-outline{ background: var(--paper); color: var(--ndmu-green); border: 1px solid var(--ndmu-gold); }
        .soft-note{ border:1px solid var(--line); background:var(--paper); border-radius:14px; padding:12px; }
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

            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Network Posting</div>
                            <div class="strip-sub">NDMU-branded layout for Alumni Networks.</div>
                        </div>
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
                                Create New Network
                            </div>
                            <div class="help" style="margin-top:4px;">
                                Tip: Use official links (Facebook Page / Website).
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST"
                  action="{{ route('portal.networks.manage.store') }}"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf

                <div class="section-card">
                    <div class="subhead">
                        <div class="badge">1</div>
                        <div class="min-w-0">
                            <strong>Network Details</strong>
                            <span>Title and link are required. Description is optional.</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="label">Title <span class="req">*</span></label>
                            <input name="title" value="{{ old('title') }}"
                                   class="input" required
                                   placeholder="e.g., CICS Alumni Association, Inc.">
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">Link <span class="req">*</span></label>
                            <input name="link" value="{{ old('link') }}"
                                   class="input" required
                                   placeholder="https://facebook.com/... or https://...">
                            <div class="help">Will open in a new tab.</div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">Description</label>
                            <textarea name="description" class="textarea"
                                      placeholder="Optional short description...">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="label">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order') }}"
                                   class="input" min="0" placeholder="Optional">
                            <div class="help">Leave blank for alphabetical ordering.</div>
                        </div>

                        <div class="flex items-center gap-2 mt-7">
                            <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                            <label for="is_active" class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                                Active (visible on public page)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="subhead">
                        <div class="badge">2</div>
                        <div class="min-w-0">
                            <strong>Logo Upload</strong>
                            <span>Optional. JPG/PNG/WEBP up to 5MB.</span>
                        </div>
                    </div>

                    <input type="file" name="logo" class="file" accept="image/png,image/jpeg,image/webp">
                    <div class="help">Recommended: square logo with transparent background if possible.</div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button class="btn-ndmu btn-primary" type="submit">Save Network</button>
                    <a href="{{ route('portal.networks.manage.index') }}" class="btn-ndmu btn-outline">Cancel</a>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
