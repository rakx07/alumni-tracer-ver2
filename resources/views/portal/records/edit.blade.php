{{-- resources/views/portal/records/edit.blade.php --}}
<x-app-layout>

    {{-- ✅ IMPORTANT:
        - user._intake_js is ONLY for dynamic sections (education/employment/community/age, etc.)
        - the Nationality/Religion suggestions are rendered by user._intake_form (via <datalist>),
          so we must PASS religions_list + nationalities_list into _intake_form include.
        - also, user._intake_js should be included AFTER the form so wrappers exist.
    --}}

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Edit Alumni Record #{{ $alumnus->id }}
                </h2>
                <div class="text-sm text-gray-600">
                    Update sections as needed. You can jump directly to a section below.
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('portal.records.show', $alumnus) }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                    Back
                </a>
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
            padding: 14px 16px;
            background: var(--ndmu-green);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }
        .strip-left{
            display:flex;
            align-items:center;
            gap: 10px;
            min-width: 0;
        }
        .gold-bar{
            width: 6px;
            height: 28px;
            background: var(--ndmu-gold);
            border-radius: 999px;
            flex: 0 0 auto;
        }
        .strip-title{
            color:#fff;
            font-weight: 900;
            letter-spacing: .2px;
            line-height: 1.2;
        }
        .strip-sub{
            color: rgba(255,255,255,.78);
            font-size: 12px;
            margin-top: 2px;
        }

        .panel{
            background:#fff;
            border:1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }

        .jump a{
            padding: 8px 10px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: #fff;
            font-weight: 800;
            font-size: 12px;
            color: rgba(15,23,42,.78);
            transition: .15s ease;
        }
        .jump a:hover{
            background: var(--paper);
            border-color: rgba(227,199,122,.85);
            color: var(--ndmu-green);
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

        /* gentle highlight when auto-scrolling */
        .ndmu-highlight{
            box-shadow: 0 0 0 3px rgba(227,199,122,.55), 0 10px 24px rgba(2,6,23,.06);
            border-radius: 18px;
        }
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- ERRORS --}}
            @if ($errors->any())
                <div class="panel p-4" style="border-color:rgba(248,113,113,.45); background:rgba(254,242,242,1);">
                    <div class="font-extrabold text-red-800 mb-2">Please fix the following:</div>
                    <ul class="list-disc ml-6 text-sm text-red-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="panel p-4" style="border-color:rgba(34,197,94,.30); background:rgba(34,197,94,.06);">
                    <div class="font-semibold" style="color:rgba(22,101,52,1);">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- QUICK NAV --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div>
                            <div class="strip-title">Jump to Section</div>
                            <div class="strip-sub">Click a section to scroll inside the form.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-extrabold"
                              style="background:rgba(255,251,240,.95); border:1px solid rgba(227,199,122,.85); color:var(--ndmu-green);">
                            <span class="inline-block h-2 w-2 rounded-full" style="background:var(--ndmu-green);"></span>
                            Edit Mode
                        </span>
                    </div>
                </div>

                <div class="p-4">
                    <div class="jump flex flex-wrap gap-2 text-sm">
                        <a href="#personal">Personal</a>
                        <a href="#addresses">Addresses</a>
                        <a href="#academic">Academic</a>
                        <a href="#employment">Employment</a>
                        <a href="#community">Community</a>
                        <a href="#engagement">Engagement</a>
                        <a href="#consent">Consent</a>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('portal.records.update', $alumnus) }}">
                @csrf
                @method('PUT')

                <div class="panel p-6">
                    {{-- Reuse intake form UI --}}
                    @php($alumnusLocal = $alumnus)

                    {{-- ✅ PASS datalist arrays into the form partial --}}
                    @include('user._intake_form', [
                        'alumnus' => $alumnusLocal,
                        'prefill_from_auth' => false,

                        // ✅ these populate <datalist> options
                        'religions_list' => $religions_list ?? [],
                        'nationalities_list' => $nationalities_list ?? [],
                    ])
                </div>

                <div class="mt-4 flex items-center justify-end gap-2">
                    <a href="{{ route('portal.records.show', $alumnus) }}"
                       class="btn-ndmu btn-outline">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn-ndmu btn-primary">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Auto-scroll + highlight section when coming from show page (?section=addresses) --}}
    <script>
        (function () {
            const section = new URLSearchParams(window.location.search).get('section');
            if (!section) return;

            const el = document.getElementById(section);
            if (!el) return;

            el.classList.add('ndmu-highlight');
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });

            setTimeout(() => el.classList.remove('ndmu-highlight'), 2500);
        })();
    </script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function shouldUppercase(el) {
        if (!el) return false;

        // allow opt-out
        if (el.dataset && el.dataset.noCaps === '1') return false;

        const tag = (el.tagName || '').toLowerCase();
        if (tag !== 'input' && tag !== 'textarea') return false;

        const type = (el.getAttribute('type') || 'text').toLowerCase();

        // ✅ NEVER touch Laravel CSRF/method spoofing and hidden fields
        if (type === 'hidden' || el.name === '_token' || el.name === '_method') return false;

        // ❌ do NOT uppercase these types
        if (type === 'email' ||
            type === 'date' ||
            type === 'number' ||
            type === 'checkbox' ||
            type === 'radio' ||
            type === 'file' ||
            type === 'password') {
            return false;
        }

        // also skip buttons
        if (type === 'submit' || type === 'button' || type === 'reset') return false;

        return true;
    }

    function forceUppercase(el) {
        if (!shouldUppercase(el)) return;

        el.style.textTransform = 'uppercase';

        if (el.dataset && el.dataset.capsBound === '1') return;
        if (el.dataset) el.dataset.capsBound = '1';

        const handler = () => {
            const start = el.selectionStart;
            const end   = el.selectionEnd;

            const upper = (el.value || '').toUpperCase();
            if (el.value !== upper) {
                el.value = upper;
                if (typeof start === 'number' && typeof end === 'number') {
                    try { el.setSelectionRange(start, end); } catch (e) {}
                }
            }
        };

        el.addEventListener('input', handler);
        el.addEventListener('blur', handler);
        el.addEventListener('change', handler);
        handler();
    }

    document.querySelectorAll('input, textarea').forEach(forceUppercase);
});
</script>

  
    {{-- ✅ Include JS AFTER the form so wrappers exist --}}
    @include('user._intake_js', [
        'alumnus' => $alumnusLocal,
        'programs_by_cat' => $programs_by_cat ?? [],
        'strands_list' => $strands_list ?? [],
    ])

</x-app-layout>