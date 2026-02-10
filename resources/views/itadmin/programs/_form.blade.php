@php
    // SAFE: works for create (null) and edit (Program model)
    $p = $program ?? null;

    $valCategory = old('category', $p?->category ?? 'college');
    $valCode     = old('code', $p?->code ?? '');
    $valName     = old('name', $p?->name ?? '');
    $valActive   = old('is_active', $p?->is_active ?? true);
@endphp

<style>
    :root{
        --ndmu-green:#0B3D2E;
        --ndmu-gold:#E3C77A;
        --paper:#FFFBF0;
        --page:#FAFAF8;
        --line:#EDE7D1;
    }

    .ndmu-field{
        width:100%;
        border-radius: 12px;
        border: 1px solid rgba(15,23,42,.18);
        padding: 10px 12px;
        font-size: 14px;
        outline: none;
        background:#fff;
    }
    .ndmu-field:focus{
        box-shadow: 0 0 0 3px rgba(227,199,122,.35);
        border-color: rgba(227,199,122,.85);
    }

    .ndmu-label{
        font-size: 13px;
        font-weight: 800;
        color: var(--ndmu-green);
    }

    .ndmu-help{
        font-size: 12px;
        color: rgba(15,23,42,.62);
    }

    .ndmu-chip{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding: 7px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        background: rgba(227,199,122,.18);
        border:1px solid rgba(227,199,122,.45);
        color: var(--ndmu-green);
    }

    .ndmu-check{
        width: 18px;
        height: 18px;
        border-radius: 6px;
        border: 1px solid rgba(15,23,42,.25);
    }

    .ndmu-errors{
        border-radius: 14px;
        border: 1px solid rgba(239,68,68,.25);
        background: rgba(239,68,68,.08);
        padding: 14px;
        color: rgba(127,29,29,1);
        font-size: 13px;
    }
</style>

<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                Program Information
            </div>
            <div class="ndmu-help mt-1">
                This list appears in Alumni Intake (College / Grad School / Law).
            </div>
        </div>

        <span class="ndmu-chip">
            NDMU • Catalog
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="ndmu-label">Category <span class="text-red-600">*</span></label>
            <select name="category" class="ndmu-field mt-1" required>
                <option value="college" {{ $valCategory==='college' ? 'selected' : '' }}>College</option>
                <option value="grad_school" {{ $valCategory==='grad_school' ? 'selected' : '' }}>Graduate School</option>
                <option value="law" {{ $valCategory==='law' ? 'selected' : '' }}>Law</option>
            </select>
            <div class="ndmu-help mt-1">Used to group programs inside the intake dropdown.</div>
        </div>

        <div>
            <label class="ndmu-label">Code (optional)</label>
            <input name="code"
                   class="ndmu-field mt-1"
                   value="{{ $valCode }}"
                   placeholder="e.g. BSIT, MBA, JD">
            <div class="ndmu-help mt-1">Short code shown in lists (if available).</div>
        </div>

        <div class="md:col-span-2">
            <label class="ndmu-label">Program Name <span class="text-red-600">*</span></label>
            <input name="name"
                   class="ndmu-field mt-1"
                   value="{{ $valName }}"
                   placeholder="e.g. Bachelor of Science in Information Technology"
                   required>
        </div>

        <div class="md:col-span-2">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       class="ndmu-check"
                       {{ $valActive ? 'checked' : '' }}>
                <span class="text-sm font-semibold" style="color:var(--ndmu-green);">Active</span>
            </label>
            <div class="ndmu-help mt-1">
                Disable instead of deleting, so historical records remain valid.
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="ndmu-errors">
            <div class="font-extrabold mb-2">Please fix the following:</div>
            <ul class="list-disc ml-5 space-y-1">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
