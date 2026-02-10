{{-- resources/views/itadmin/strands/_form.blade.php --}}
@php
    $s = $strand ?? null;

    // Keep old() values, but normalize display a bit
    $codeVal = strtoupper(trim((string) old('code', $s->code ?? '')));
    $nameVal = old('name', $s->name ?? '');
    $activeVal = old('is_active', $s->is_active ?? true);
@endphp

<style>
    :root{
        --ndmu-green:#0B3D2E;
        --ndmu-gold:#E3C77A;
        --paper:#FFFBF0;
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
    .ndmu-card{
        border:1px solid var(--line);
        border-radius: 16px;
        background: var(--paper);
        padding: 14px;
    }
    .ndmu-badge{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        background: rgba(227,199,122,.25);
        border: 1px solid rgba(227,199,122,.70);
        color: var(--ndmu-green);
        white-space: nowrap;
    }
    .ndmu-help{
        font-size: 12px;
        color: rgba(15,23,42,.62);
        margin-top: 6px;
        line-height: 1.35;
    }
</style>

<div class="space-y-4">

    {{-- Errors --}}
    @if ($errors->any())
        <div class="p-4 rounded-lg border text-sm"
             style="border-color:rgba(248,113,113,.45); background:rgba(254,242,242,1); color:rgba(153,27,27,1);">
            <div class="font-extrabold mb-2">Please fix the following:</div>
            <ul class="list-disc ml-5 space-y-1">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Strand fields --}}
    <div class="ndmu-card">
        <div class="flex items-start justify-between gap-3">
            <div>
                <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                    Strand Details
                </div>
                <div class="ndmu-help">
                    This will appear in the SHS Strand dropdown (for NDMU Senior High School).
                </div>
            </div>
            <span class="ndmu-badge">NDMU • SHS</span>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-semibold text-gray-700">
                    Code <span class="text-red-600">*</span>
                </label>
                <input name="code"
                       value="{{ $codeVal }}"
                       class="ndmu-field mt-1"
                       placeholder="e.g. STEM, HUMSS, ABM"
                       maxlength="50"
                       required>
                <div class="ndmu-help">We’ll store it in uppercase (e.g., <span class="font-mono">STEM</span>).</div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">
                    Name <span class="text-red-600">*</span>
                </label>
                <input name="name"
                       value="{{ $nameVal }}"
                       class="ndmu-field mt-1"
                       placeholder="Full strand name"
                       maxlength="255"
                       required>
                <div class="ndmu-help">Example: “Science, Technology, Engineering and Mathematics”.</div>
            </div>

            <div class="md:col-span-2">
                <label class="inline-flex items-center gap-2 select-none">
                    <input type="checkbox" name="is_active" value="1"
                           @checked((bool)$activeVal)>
                    <span class="text-sm font-semibold text-gray-700">Active</span>
                </label>
                <div class="ndmu-help">
                    If unchecked, the strand stays in the database but won’t show up in dropdown selections.
                </div>
            </div>
        </div>
    </div>

</div>
