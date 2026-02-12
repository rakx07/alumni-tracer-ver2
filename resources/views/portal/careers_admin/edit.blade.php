{{-- resources/views/portal/careers_admin/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Edit Career Post
                </h2>
                <p class="text-sm text-gray-600">
                    Update details, add new files, or remove existing attachments.
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

        .status-badge{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 7px 10px;
            border-radius: 999px;
            border:1px solid rgba(15,23,42,.14);
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
        }
        .dot{ width:8px;height:8px;border-radius:999px;display:inline-block; }
        .badge-active{ background: rgba(16,185,129,.10); color:#065f46; border-color: rgba(16,185,129,.25); }
        .badge-upcoming{ background: rgba(59,130,246,.10); color:#1e3a8a; border-color: rgba(59,130,246,.25); }
        .badge-expired{ background: rgba(107,114,128,.10); color:#111827; border-color: rgba(107,114,128,.22); }
        .badge-hidden{ background: rgba(234,179,8,.14); color:#854d0e; border-color: rgba(234,179,8,.35); }

        .attach-row{
            border: 1px solid rgba(227,199,122,.55);
            border-radius: 14px;
            background: #fff;
            padding: 12px;
            box-shadow: 0 8px 18px rgba(2,6,23,.05);
        }
        .attach-name{
            font-weight: 900;
            color: #0f172a;
            font-size: 13px;
        }
        .attach-meta{
            font-size: 12px;
            color: rgba(15,23,42,.60);
            margin-top: 2px;
        }
    </style>

    <div class="py-10" style="background:var(--page);">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-xl border p-4"
                     style="border-color: rgba(16,185,129,.25); background: rgba(16,185,129,.07);">
                    <div class="font-extrabold text-green-900">{{ session('success') }}</div>
                </div>
            @endif

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

            <form action="{{ route('portal.careers.admin.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @php
                    $status = $post->statusLabel();
                    $badgeClass = $status === 'Active'
                        ? 'badge-active'
                        : ($status === 'Upcoming'
                            ? 'badge-upcoming'
                            : ($status === 'Expired' ? 'badge-expired' : 'badge-hidden'));
                    $dotColor = $status === 'Active'
                        ? 'background:#10b981;'
                        : ($status === 'Upcoming'
                            ? 'background:#3b82f6;'
                            : ($status === 'Expired' ? 'background:#6b7280;' : 'background:#eab308;'));
                @endphp

                <div class="strip">
                    <div class="strip-top">
                        <div class="strip-left">
                            <div class="gold-bar"></div>
                            <div class="min-w-0">
                                <div class="strip-title">Edit Career Post</div>
                                <div class="strip-sub">Manage publishing, content, date range, and attachments.</div>
                            </div>
                        </div>

                        <div class="hidden sm:flex items-center gap-2">
                            <span class="pill">
                                <span class="pill-dot"></span>
                                Admin
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">

                        {{-- Status + Published --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="status-badge {{ $badgeClass }}">
                                    <span class="dot" style="{{ $dotColor }}"></span>
                                    {{ $status }}
                                </span>
                                <span class="text-xs text-gray-600">
                                    Based on date range + published flag
                                </span>
                            </div>

                            <div class="soft-card flex items-center gap-3">
                                <input type="checkbox"
                                       name="is_published"
                                       value="1"
                                       {{ old('is_published', $post->is_published) ? 'checked' : '' }}
                                       class="rounded border-gray-300">
                                <div class="min-w-0">
                                    <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                                        Published
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        Visible on public Careers page when enabled.
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Title --}}
                        <div>
                            <label class="label">Title <span class="req">*</span></label>
                            <input name="title" value="{{ old('title', $post->title) }}" required class="input">
                        </div>

                        {{-- Company + Location --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label">Company</label>
                                <input name="company" value="{{ old('company', $post->company) }}" class="input">
                            </div>
                            <div>
                                <label class="label">Location</label>
                                <input name="location" value="{{ old('location', $post->location) }}" class="input">
                            </div>
                        </div>

                        {{-- Employment Type --}}
                        <div>
                            <label class="label">Employment Type</label>
                            <input name="employment_type" value="{{ old('employment_type', $post->employment_type) }}" class="input">
                        </div>

                        {{-- Summary --}}
                        <div>
                            <label class="label">Short Summary</label>
                            <input name="summary" maxlength="300" value="{{ old('summary', $post->summary) }}" class="input">
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="label">Full Description</label>
                            <textarea name="description" rows="6" class="textarea">{{ old('description', $post->description) }}</textarea>
                        </div>

                        {{-- How to Apply --}}
                        <div>
                            <label class="label">How to Apply</label>
                            <textarea name="how_to_apply" rows="5" class="textarea">{{ old('how_to_apply', $post->how_to_apply) }}</textarea>
                        </div>

                        {{-- Apply URL + Email --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label">Application Link (URL)</label>
                                <input name="apply_url" value="{{ old('apply_url', $post->apply_url) }}" class="input">
                            </div>
                            <div>
                                <label class="label">Application Email</label>
                                <input name="apply_email" value="{{ old('apply_email', $post->apply_email) }}" class="input">
                            </div>
                        </div>

                        {{-- Date Range --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label">Start Date</label>
                                <input type="date" name="start_date"
                                       value="{{ old('start_date', optional($post->start_date)->format('Y-m-d')) }}"
                                       class="input">
                            </div>
                            <div>
                                <label class="label">End Date</label>
                                <input type="date" name="end_date"
                                       value="{{ old('end_date', optional($post->end_date)->format('Y-m-d')) }}"
                                       class="input">
                            </div>
                        </div>

                        {{-- Existing attachments --}}
                        <div class="border-t pt-4">
                            <div class="font-extrabold text-sm text-gray-900 mb-2">
                                Existing Attachments
                            </div>

                            @if($post->attachments->count())
                                <div class="space-y-3">
                                    @foreach($post->attachments as $att)
                                        <div class="attach-row flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                            <div class="min-w-0">
                                                <div class="attach-name truncate">{{ $att->original_name }}</div>
                                                <div class="attach-meta">
                                                    {{ $att->mime_type ?: '—' }}
                                                    •
                                                    {{ $att->size ? number_format($att->size/1024, 1).' KB' : '—' }}
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <a class="btn-ndmu btn-outline" target="_blank"
                                                   href="{{ asset('storage/'.$att->path) }}">
                                                    View
                                                </a>

                                                <label class="inline-flex items-center gap-2 text-sm font-extrabold text-red-700">
                                                    <input type="checkbox" name="delete_attachments[]" value="{{ $att->id }}">
                                                    Remove
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="text-xs text-gray-600 mt-2">
                                    Tick “Remove” then click <strong>Save Changes</strong> to delete selected files.
                                </div>
                            @else
                                <div class="rounded-xl border p-4"
                                     style="border-color:var(--line); background:var(--paper);">
                                    <div class="font-extrabold" style="color:var(--ndmu-green);">No attachments</div>
                                    <div class="text-sm text-gray-600 mt-1">You can upload files below.</div>
                                </div>
                            @endif
                        </div>

                        {{-- Add new files --}}
                        <div>
                            <label class="label">Add More Attachments</label>
                            <input type="file" name="attachments[]" multiple accept=".pdf,image/*" class="file">
                            <div class="text-xs text-gray-600 mt-1">Max 10MB per file.</div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-wrap items-center justify-end gap-2 pt-2">
                            <button class="btn-ndmu btn-primary" type="submit">
                                Save Changes
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
