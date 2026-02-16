<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Edit Network
                </h2>
                <p class="text-sm text-gray-600">
                    Update logo, title, link, and visibility.
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
        .section-card{ border:1px solid rgba(227,199,122,.55); border-radius:18px; background:#fff; padding:18px; box-shadow:0 10px 24px rgba(2,6,23,.06); }
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
        .thumb{ width:110px; height:110px; border-radius:20px; background:var(--paper); border:1px solid rgba(227,199,122,.65); overflow:hidden; display:flex; align-items:center; justify-content:center; }
        .thumb img{ width:100%; height:100%; object-fit:contain; }
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

            <form method="POST"
                  action="{{ route('portal.networks.manage.update', $network) }}"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf
                @method('PUT')

                <div class="section-card">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="label">Title <span class="req">*</span></label>
                            <input name="title" value="{{ old('title', $network->title) }}"
                                   class="input" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">Link <span class="req">*</span></label>
                            <input name="link" value="{{ old('link', $network->link) }}"
                                   class="input" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="label">Description</label>
                            <textarea name="description" class="textarea">{{ old('description', $network->description) }}</textarea>
                        </div>

                        <div>
                            <label class="label">Sort Order</label>
                            <input type="number" name="sort_order"
                                   value="{{ old('sort_order', $network->sort_order) }}"
                                   class="input" min="0" placeholder="Optional">
                            <div class="help">Leave blank for alphabetical ordering.</div>
                        </div>

                        <div class="flex items-center gap-2 mt-7">
                            <input id="is_active" type="checkbox" name="is_active" value="1"
                                   @checked(old('is_active', $network->is_active))>
                            <label for="is_active" class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                                Active (visible on public page)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                        <div>
                            <div class="font-extrabold" style="color:var(--ndmu-green);">Current Logo</div>
                            <div class="help">If you upload a new logo, the old one will be replaced.</div>

                            <div class="mt-3 thumb">
                                @if($network->logo_url)
                                    <img src="{{ $network->logo_url }}" alt="logo" onerror="this.style.display='none';">
                                @else
                                    <span class="text-xs font-bold text-gray-600">NO LOGO</span>
                                @endif
                            </div>

                            @if($network->logo_url)
                                <label class="mt-3 inline-flex items-center gap-2 text-sm font-extrabold" style="color:#991b1b;">
                                    <input type="checkbox" name="remove_logo" value="1">
                                    Remove current logo
                                </label>
                            @endif
                        </div>

                        <div>
                            <label class="label">Upload New Logo</label>
                            <input type="file" name="logo" class="file" accept="image/png,image/jpeg,image/webp">
                            <div class="help">JPG/PNG/WEBP up to 5MB.</div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button class="btn-ndmu btn-primary" type="submit">Save Changes</button>
                    <a href="{{ route('portal.networks.manage.index') }}" class="btn-ndmu btn-outline">Cancel</a>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
