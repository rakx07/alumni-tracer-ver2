{{-- resources/views/itadmin/programs/_form.blade.php --}}
@php
    $p = $program ?? null;

    $ndmuGreen = '#0B3D2E';
    $ndmuGold  = '#E3C77A';
    $paper     = '#FFFBF0';
    $line      = '#EDE7D1';

    $valCategory = old('category', $p->category ?? 'college');
    $valCode     = old('code', $p->code ?? '');
    $valName     = old('name', $p->name ?? '');
    $valActive   = old('is_active', $p->is_active ?? true);
@endphp

<style>
    :root{
        --ndmu-green: {{ $ndmuGreen }};
        --ndmu-gold: {{ $ndmuGold }};
        --paper: {{ $paper }};
        --line: {{ $line }};
    }

    .ndmu-field label{
        font-size: 12px;
        font-weight: 800;
        color: rgba(15,23,42,.78);
    }

    .ndmu-input, .ndmu-select{
        width: 100%;
        border-radius: 12px;
        border: 1px solid rgba(15,23,42,.18);
        padding: 10px 12px;
        font-size: 14px;
        outline: none;
        background: #fff;
    }

    .ndmu-input:focus, .ndmu-select:focus{
        box-shadow: 0 0 0 3px rgba(227,199,122,.35);
        border-color: rgba(227,199,122,.85);
    }

    .ndmu-help{
        font-size: 12px;
        color: rgba(15,23,42,.62);
        margin-top: 6px;
        line-height: 1.3;
    }

    .ndmu-card{
        border: 1px solid var(--line);
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
        border: 1px solid rgba(227,199,122,.65);
        background: rgba(227,199,122,.20);
        color: var(--ndmu-green);
    }
</style>

<div class="space-y-4">

    {{-- Small header card --}}
    <div class="ndmu-card">
        <div class="flex items-start justify-between gap-3">
            <div>
                <div class="font-extrabold" style="color:var(--ndmu-green);">
                    Program Information
                </div>
                <div class="ndmu-help">
                    Programs appear in the Alumni Intake “College/Grad/Law” selections (and assisted encoding).
                </div>
            </div>
            <span class="ndmu-badge">NDMU</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        {{-- Category --}}
        <div class="ndmu-field">
            <label class="block mb-1">
                Category <span class="text-red-600">*</span>
            </label>
            <select name="category" class="ndmu-select" required>
                <option value="college" @selected($valCategory === 'college')>College</option>
                <option value="grad_school" @selected($valCategory === 'grad_school')>Graduate School</option>
                <option value="law" @selected($valCategory === 'law')>Law</option>
            </select>
            <div class="ndmu-help">
                Used for grouping and filtering in the admin list.
            </div>
        </div>

        {{-- Code --}}
        <div class="ndmu-field">
            <label class="block mb-1">Code (optional)</label>
            <input name="code"
                   class="ndmu-input"
                   value="{{ $valCode }}"
                   placeholder="e.g. BSIT, MBA, JD">
            <div class="ndmu-help">
                Short identifier. If blank, searches will rely on the name.
            </div>
        </div>

        {{-- Name --}}
        <div class="ndmu-field md:col-span-2">
            <label class="block mb-1">
                Program Name <span class="text-red-600">*</span>
            </label>
            <input name="name"
                   class="ndmu-input"
                   value="{{ $valName }}"
                   placeholder="e.g. Bachelor of Science in Information Technology"
                   required>
            <div class="ndmu-help">
                This is what users/officers will see in dropdowns.
            </div>
        </div>

        {{-- Active --}}
        <div class="ndmu-field md:col-span-2">
            <div class="flex items-center justify-between p-3 rounded-xl bg-white"
                 style="border:1px solid var(--line);">
                <div>
                    <div class="font-extrabold" style="color:var(--ndmu-green); font-size:13px;">
                        Active Status
                    </div>
                    <div class="ndmu-help">
                        If disabled, it can be hidden from selections (depending on your intake query).
                    </div>
                </div>

                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="rounded"
                           @checked((bool)$valActive)>
                    <span class="text-sm font-semibold" style="color:rgba(15,23,42,.78);">Active</span>
                </label>
            </div>
        </div>
    </div>

    {{-- Errors (keep inside partial for create/edit convenience) --}}
    @if ($errors->any())
        <div class="p-4 rounded-xl text-sm"
             style="border:1px solid rgba(248,113,113,.45); background:rgba(254,242,242,1);">
            <div class="font-extrabold text-red-800 mb-2">Please fix the following:</div>
            <ul class="list-disc ml-5 text-red-800">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

</div>
