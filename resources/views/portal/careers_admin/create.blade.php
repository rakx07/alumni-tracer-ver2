{{-- resources/views/portal/careers_admin/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    New Career Post
                </h2>
                <p class="text-sm text-gray-600">
                    Upload job details and attachments (PDF/images). Optional fields can be left blank.
                </p>
            </div>

            <a href="{{ route('portal.careers.admin.index') }}"
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

        .form-wrap{
            background: #fff;
            border: 1px solid rgba(15,23,42,.10);
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }
        .help{
            font-size: 12px;
            color: rgba(15,23,42,.62);
            margin-top: 6px;
            line-height: 1.5;
        }
        .label{
            display:block;
            font-size: 13px;
            font-weight: 900;
            color: rgba(15,23,42,.88);
        }
        .req{ color:#b91c1c; font-weight: 900; }
        .input, .textarea, .file{
            margin-top: 6px;
            width: 100%;
            border-radius: 12px;
            border: 1px solid rgba(15,23,42,.18);
            background: #fff;
            padding: 10px 12px;
            outline: none;
            transition: .15s ease;
        }
        .input:focus, .textarea:focus{
            border-color: rgba(227,199,122,.95);
            box-shadow: 0 0 0 4px rgba(227,199,122,.22);
        }
        .textarea{ min-height: 120px; }
        .file{ padding: 10px; }

        .soft-card{
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
                    <div class="font-extrabold text-red-900">Please fix the errors:</div>
                    <ul class="list-disc ml-5 text-sm text-red-900 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('portal.careers.admin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="strip">
                    <div class="strip-top">
                        <div class="strip-left">
                            <div class="gold-bar"></div>
                            <div class="min-w-0">
                                <div class="strip-title">Career Post Form</div>
                                <div class="strip-sub">Fill out the required fields and attach PDFs/images.</div>
                            </div>
                        </div>

                        <div class="hidden sm:flex items-center gap-2">
                            <span class="pill">
                                <span class="pill-dot"></span>
                                Alumni Officer / IT Admin
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        {{-- Title --}}
                        <div>
                            <label class="label">Title <span class="req">*</span></label>
                            <input name="title" value="{{ old('title') }}" required class="input">
                        </div>

                        {{-- Company + Location --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label">Company</label>
                                <input name="company" value="{{ old('company') }}" class="input">
                            </div>
                            <div>
                                <label class="label">Location</label>
                                <input name="location" value="{{ old('location') }}" class="input">
                            </div>
                        </div>

                        {{-- Employment Type + Published --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label">Employment Type</label>
                                <input name="employment_type"
                                       value="{{ old('employment_type') }}"
                                       placeholder="Full-time / Part-time / Contract / Internship"
                                       class="input">
                            </div>

                            <div class="soft-card flex items-center gap-3">
                                <input type="checkbox"
                                       name="is_published"
                                       value="1"
                                       {{ old('is_published', '1') ? 'checked' : '' }}
                                       class="rounded border-gray-300">
                                <div class="min-w-0">
                                    <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                                        Published
                                    </div>
                                    <div class="help">
                                        If checked, this post will appear in the public Careers page.
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Summary --}}
                        <div>
                            <label class="label">Short Summary</label>
                            <input name="summary" value="{{ old('summary') }}" maxlength="300" class="input">
                            <div class="help">Shown in list preview. Max 300 characters.</div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="label">Full Description</label>
                            <textarea name="description" rows="6" class="textarea">{{ old('description') }}</textarea>
                        </div>

                        {{-- How to Apply --}}
                        <div>
                            <label class="label">How to Apply</label>
                            <textarea name="how_to_apply" rows="5" class="textarea">{{ old('how_to_apply') }}</textarea>
                        </div>

                        {{-- Apply URL + Email --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label">Application Link (URL)</label>
                                <input name="apply_url" value="{{ old('apply_url') }}" placeholder="https://..." class="input">
                            </div>
                            <div>
                                <label class="label">Application Email</label>
                                <input name="apply_email" value="{{ old('apply_email') }}" placeholder="hr@company.com" class="input">
                            </div>
                        </div>

                        {{-- Date Range --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label">Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" class="input">
                            </div>
                            <div>
                                <label class="label">End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" class="input">
                            </div>
                        </div>

                        {{-- Attachments --}}
                        <div>
                            <label class="label">Attachments (PDF / Images)</label>
                            <input type="file" name="attachments[]" multiple accept=".pdf,image/*" class="file">
                            <div class="help">You can upload 2–3 PDFs and images. Max 10MB per file.</div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-wrap items-center justify-end gap-2 pt-2">
                            <a href="{{ route('portal.careers.admin.index') }}" class="btn-ndmu btn-outline">
                                Cancel
                            </a>
                            <button class="btn-ndmu btn-primary" type="submit">
                                Save
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
