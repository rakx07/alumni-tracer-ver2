{{-- resources/views/careers/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    {{ $post->title }}
                </h2>
                <p class="text-sm text-gray-600">
                    {{ $post->company ?: '—' }}
                    @if($post->location) • {{ $post->location }} @endif
                    @if($post->employment_type) • {{ $post->employment_type }} @endif
                </p>
            </div>

            <a href="{{ route('careers.index') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to Careers
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

        .link-card{
            border: 1px solid rgba(227,199,122,.80);
            border-radius: 16px;
            background:#fff;
            padding: 14px 16px;
            transition: .15s ease;
            box-shadow: 0 8px 18px rgba(2,6,23,.05);
        }
        .link-card:hover{
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(2,6,23,.08);
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

        .block-title{
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 8px;
        }
        .text-body{
            font-size: 14px;
            line-height: 1.75;
            color: rgba(15,23,42,.78);
            white-space: pre-line;
        }
        .meta-line{
            font-size: 12px;
            color: rgba(15,23,42,.65);
            margin-top: 2px;
            line-height: 1.5;
        }
        .kv{
            font-size: 13px;
            color: rgba(15,23,42,.75);
            line-height: 1.7;
        }
        .kv strong{ color:#0f172a; }
        .divider{
            border-top: 1px solid rgba(15,23,42,.10);
            margin: 14px 0 0;
            padding-top: 14px;
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
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @php
                $status = $post->statusLabel();
                $badgeClass = $status === 'Active'
                    ? 'badge-active'
                    : ($status === 'Upcoming' ? 'badge-upcoming' : 'badge-expired');

                $dotColor = $status === 'Active'
                    ? 'background:#10b981;'
                    : ($status === 'Upcoming' ? 'background:#3b82f6;' : 'background:#6b7280;');
            @endphp

            {{-- OVERVIEW --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Career Posting</div>
                            <div class="strip-sub">Full details and attachments for this opportunity.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            Careers
                        </span>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="status-badge {{ $badgeClass }}">
                            <span class="dot" style="{{ $dotColor }}"></span>
                            {{ $status }}
                        </span>

                        <span class="meta-line">
                            @if($post->start_date || $post->end_date)
                                {{ $post->start_date ? $post->start_date->format('M d, Y') : '—' }}
                                —
                                {{ $post->end_date ? $post->end_date->format('M d, Y') : '—' }}
                            @else
                                Date range not specified
                            @endif
                        </span>
                    </div>

                    @if($post->summary)
                        <div class="kv">
                            <strong>Summary:</strong> {{ $post->summary }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="rounded-xl border p-4" style="border-color:var(--line); background:var(--paper);">
                            <div class="meta-line">Company</div>
                            <div class="font-extrabold" style="color:var(--ndmu-green);">
                                {{ $post->company ?: '—' }}
                            </div>
                        </div>

                        <div class="rounded-xl border p-4" style="border-color:var(--line); background:var(--paper);">
                            <div class="meta-line">Location</div>
                            <div class="font-extrabold" style="color:var(--ndmu-green);">
                                {{ $post->location ?: '—' }}
                            </div>
                        </div>

                        <div class="rounded-xl border p-4" style="border-color:var(--line); background:var(--paper);">
                            <div class="meta-line">Employment Type</div>
                            <div class="font-extrabold" style="color:var(--ndmu-green);">
                                {{ $post->employment_type ?: '—' }}
                            </div>
                        </div>
                    </div>

                    @if($post->apply_url || $post->apply_email)
                        <div class="divider">
                            <div class="block-title">Application Details</div>

                            <div class="kv space-y-1">
                                @if($post->apply_url)
                                    <div>
                                        <strong>Link:</strong>
                                        <a class="underline" style="color:var(--ndmu-green);"
                                           href="{{ $post->apply_url }}" target="_blank" rel="noopener">
                                            {{ $post->apply_url }}
                                        </a>
                                    </div>
                                @endif

                                @if($post->apply_email)
                                    <div><strong>Email:</strong> {{ $post->apply_email }}</div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        <a href="{{ route('careers.index') }}" class="btn-ndmu btn-outline">
                            Back to Careers
                        </a>

                        @auth
                            @if (in_array(auth()->user()->role, ['it_admin','alumni_officer'], true))
                                <a href="{{ route('portal.careers.admin.edit', $post) }}" class="btn-ndmu btn-primary">
                                    Edit (Admin)
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            {{-- DESCRIPTION --}}
            @if($post->description)
                <div class="strip">
                    <div class="strip-top">
                        <div class="strip-left">
                            <div class="gold-bar"></div>
                            <div class="min-w-0">
                                <div class="strip-title">Description</div>
                                <div class="strip-sub">Job overview and responsibilities (if provided).</div>
                            </div>
                        </div>

                        <div class="hidden sm:flex items-center gap-2">
                            <span class="pill">
                                <span class="pill-dot"></span>
                                Details
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="text-body">{{ $post->description }}</div>
                    </div>
                </div>
            @endif

            {{-- HOW TO APPLY --}}
            @if($post->how_to_apply)
                <div class="strip">
                    <div class="strip-top">
                        <div class="strip-left">
                            <div class="gold-bar"></div>
                            <div class="min-w-0">
                                <div class="strip-title">How to Apply</div>
                                <div class="strip-sub">Instructions provided by the posting office/company.</div>
                            </div>
                        </div>

                        <div class="hidden sm:flex items-center gap-2">
                            <span class="pill">
                                <span class="pill-dot"></span>
                                Application
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="text-body">{{ $post->how_to_apply }}</div>
                    </div>
                </div>
            @endif

  {{-- ATTACHMENTS --}}
<div class="strip">
    <div class="strip-top">
        <div class="strip-left">
            <div class="gold-bar"></div>
            <div class="min-w-0">
                <div class="strip-title">Attachments</div>
                <div class="strip-sub">Click a file to preview. Use Next/Prev in the viewer.</div>
            </div>
        </div>

        <div class="hidden sm:flex items-center gap-2">
            <span class="pill">
                <span class="pill-dot"></span>
                Files
            </span>
        </div>
    </div>

    <div class="p-6">
        @if($post->attachments->count())
            @php
                // Build a JS-friendly array for the modal viewer
                $viewerItems = $post->attachments->map(function($att) {
                    $url  = asset('storage/'.$att->path);
                    $mime = strtolower($att->mime_type ?? '');
                    $isImage = str_starts_with($mime, 'image/');
                    $isPdf   = str_contains($mime, 'pdf') || str_ends_with(strtolower($att->original_name ?? ''), '.pdf');

                    return [
                        'id'   => $att->id,
                        'name' => $att->original_name ?? 'Attachment',
                        'url'  => $url,
                        'type' => $isImage ? 'image' : ($isPdf ? 'pdf' : 'file'),
                        'mime' => $mime,
                    ];
                })->values();
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($viewerItems as $i => $item)
                    @php
                        $type = $item['type'];
                        $name = $item['name'];
                        $url  = $item['url'];
                    @endphp

                    <button type="button"
                        class="group text-left link-card p-0 overflow-hidden"
                        onclick="openAttachmentModal({{ $i }})"
                        aria-label="Open attachment: {{ $name }}"
                    >
                        {{-- Thumbnail area --}}
                        <div class="relative aspect-[4/3] bg-gray-50 overflow-hidden">
                            @if($type === 'image')
                                <img
                                    src="{{ $url }}"
                                    alt="{{ $name }}"
                                    class="h-full w-full object-cover transition duration-150 group-hover:scale-[1.02]"
                                    loading="lazy"
                                >
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></div>
                                <div class="absolute bottom-2 left-2 inline-flex items-center gap-2 text-xs font-extrabold px-2 py-1 rounded-full"
                                     style="background: rgba(255,251,240,.95); border:1px solid rgba(227,199,122,.85); color: var(--ndmu-green);">
                                    <span class="inline-block h-2 w-2 rounded-full" style="background:#10b981;"></span>
                                    IMAGE
                                </div>
                            @elseif($type === 'pdf')
                                <div class="h-full w-full flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-4xl">📄</div>
                                        <div class="mt-2 inline-flex items-center gap-2 text-xs font-extrabold px-2 py-1 rounded-full"
                                             style="background: rgba(255,251,240,.95); border:1px solid rgba(227,199,122,.85); color: var(--ndmu-green);">
                                            <span class="inline-block h-2 w-2 rounded-full" style="background:#ef4444;"></span>
                                            PDF
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition"></div>
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-4xl">📎</div>
                                        <div class="mt-2 inline-flex items-center gap-2 text-xs font-extrabold px-2 py-1 rounded-full"
                                             style="background: rgba(255,251,240,.95); border:1px solid rgba(227,199,122,.85); color: var(--ndmu-green);">
                                            FILE
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Caption area --}}
                        <div class="p-3">
                            <div class="attach-name truncate">{{ $name }}</div>
                            <div class="attach-meta">
                                {{ strtoupper($type) }}
                                <span class="mx-1">•</span>
                                Click to preview
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>

            {{-- Modal (Lightbox) --}}
            <div id="attachModal"
                 class="fixed inset-0 hidden"
                 style="z-index:9999;"
                 aria-hidden="true">
                {{-- Backdrop --}}
                <div id="attachBackdrop"
                     class="absolute inset-0"
                     style="background: rgba(2,6,23,.72);"></div>

                {{-- Modal panel --}}
                <div class="relative min-h-screen flex items-center justify-center p-4">
                    <div class="w-full max-w-5xl rounded-2xl overflow-hidden shadow-2xl bg-white border"
                         style="border-color:rgba(227,199,122,.55);">

                        {{-- Top bar --}}
                        <div class="flex items-center justify-between gap-3 px-4 py-3"
                             style="background: var(--ndmu-green);">
                            <div class="min-w-0">
                                <div id="attachTitle" class="text-white font-extrabold truncate">Attachment</div>
                                <div id="attachMeta" class="text-xs" style="color: rgba(255,255,255,.78);">
                                    —
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button"
                                        class="btn-ndmu btn-outline"
                                        onclick="prevAttachment()">
                                    ← Prev
                                </button>
                                <button type="button"
                                        class="btn-ndmu btn-outline"
                                        onclick="nextAttachment()">
                                    Next →
                                </button>
                                <button type="button"
                                        class="btn-ndmu btn-primary"
                                        onclick="closeAttachmentModal()">
                                    Close ✕
                                </button>
                            </div>
                        </div>

                        {{-- Viewer area --}}
                        <div class="p-4" style="background: var(--page);">
                            <div id="attachViewer" class="rounded-xl overflow-hidden border bg-white"
                                 style="border-color: var(--line);">
                                {{-- JS injects content here --}}
                            </div>

                            <div class="mt-3 flex items-center justify-between gap-2 flex-wrap">
                                <div class="text-xs text-gray-600">
                                    Tip: Use keyboard <strong>←</strong> / <strong>→</strong> for prev/next, <strong>Esc</strong> to close.
                                </div>
                                <a id="attachOpenNewTab"
                                   href="#"
                                   target="_blank"
                                   rel="noopener"
                                   class="btn-ndmu btn-outline">
                                    Open in new tab
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <script>
                // Attachments data (from PHP)
                const ATTACH_ITEMS = @json($viewerItems);

                let attachIndex = 0;

                const modalEl = document.getElementById('attachModal');
                const backdropEl = document.getElementById('attachBackdrop');
                const titleEl = document.getElementById('attachTitle');
                const metaEl = document.getElementById('attachMeta');
                const viewerEl = document.getElementById('attachViewer');
                const openNewTabEl = document.getElementById('attachOpenNewTab');

                function openAttachmentModal(index){
                    attachIndex = index;
                    renderAttachment();
                    modalEl.classList.remove('hidden');
                    modalEl.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }

                function closeAttachmentModal(){
                    modalEl.classList.add('hidden');
                    modalEl.setAttribute('aria-hidden', 'true');
                    viewerEl.innerHTML = '';
                    document.body.style.overflow = '';
                }

                function prevAttachment(){
                    attachIndex = (attachIndex - 1 + ATTACH_ITEMS.length) % ATTACH_ITEMS.length;
                    renderAttachment();
                }

                function nextAttachment(){
                    attachIndex = (attachIndex + 1) % ATTACH_ITEMS.length;
                    renderAttachment();
                }

                function renderAttachment(){
                    const item = ATTACH_ITEMS[attachIndex];
                    if(!item) return;

                    titleEl.textContent = item.name || 'Attachment';
                    metaEl.textContent = (item.type || 'file').toUpperCase() + ' • ' + (attachIndex + 1) + ' of ' + ATTACH_ITEMS.length;

                    openNewTabEl.href = item.url;

                    // Render viewer
                    if(item.type === 'image'){
                        viewerEl.innerHTML = `
                            <div class="relative bg-black">
                                <img src="${item.url}"
                                     alt="${escapeHtml(item.name)}"
                                     class="w-full"
                                     style="max-height: 75vh; object-fit: contain; display:block; margin:auto;">
                            </div>
                        `;
                    } else if(item.type === 'pdf'){
                        // iframe pdf preview
                        viewerEl.innerHTML = `
                            <div class="bg-white">
                                <iframe src="${item.url}"
                                        style="width:100%; height: 75vh; border:0;"
                                        title="${escapeHtml(item.name)}"></iframe>
                            </div>
                        `;
                    } else {
                        viewerEl.innerHTML = `
                            <div class="p-6">
                                <div class="text-sm text-gray-700">
                                    Preview not available for this file type.
                                </div>
                                <div class="mt-3">
                                    <a class="underline font-extrabold" style="color: var(--ndmu-green);" href="${item.url}" target="_blank" rel="noopener">
                                        Open in new tab →
                                    </a>
                                </div>
                            </div>
                        `;
                    }
                }

                function escapeHtml(str){
                    return String(str || '')
                        .replaceAll('&','&amp;')
                        .replaceAll('<','&lt;')
                        .replaceAll('>','&gt;')
                        .replaceAll('"','&quot;')
                        .replaceAll("'","&#039;");
                }

                // Close on backdrop click
                backdropEl.addEventListener('click', closeAttachmentModal);

                // Keyboard controls
                document.addEventListener('keydown', function(e){
                    if(modalEl.classList.contains('hidden')) return;

                    if(e.key === 'Escape') closeAttachmentModal();
                    if(e.key === 'ArrowLeft') prevAttachment();
                    if(e.key === 'ArrowRight') nextAttachment();
                });
            </script>

        @else
            <div class="rounded-xl border p-4"
                 style="border-color:var(--line); background:var(--paper);">
                <div class="font-extrabold" style="color:var(--ndmu-green);">No attachments</div>
                <div class="text-sm text-gray-600 mt-1">
                    This posting has no uploaded files.
                </div>
            </div>
        @endif
    </div>
</div>


        </div>
    </div>
</x-app-layout>
