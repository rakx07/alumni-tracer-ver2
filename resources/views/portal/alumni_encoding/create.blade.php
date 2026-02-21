{{-- resources/views/portal/alumni_encoding/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">Encode Alumni</h2>
                <p class="text-sm text-gray-600">Create assisted record (Mode A or B).</p>
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

        .panel{
            background:#fff;
            border:1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
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

        .input{
            width:100%;
            border-radius: 12px;
            border: 1px solid rgba(15,23,42,.18);
            padding: 10px 12px;
            font-size: 14px;
            outline: none;
        }
        .input:focus{
            box-shadow: 0 0 0 3px rgba(227,199,122,.35);
            border-color: rgba(227,199,122,.85);
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

        .match-card{
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 12px;
            background:#fff;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap: 12px;
        }

        .badge{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            border: 1px solid transparent;
            white-space: nowrap;
        }
        .badge-gold{
            background: rgba(227,199,122,.25);
            border-color: rgba(227,199,122,.70);
            color: var(--ndmu-green);
        }
        .badge-plain{
            background:#fff;
            border-color: var(--line);
            color: rgba(15,23,42,.78);
            font-weight: 800;
        }

        .help{
            font-size: 12px;
            color: rgba(15,23,42,.62);
        }
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- DUPLICATE CHECK --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Duplicate Check</div>
                            <div class="strip-sub">Search existing alumni records to avoid duplicates.</div>
                        </div>
                    </div>
                </div>

                <div class="p-4 space-y-3">
                    <form method="GET" class="flex flex-col sm:flex-row gap-2">
                        <input name="search"
                               value="{{ $search }}"
                               class="input"
                               placeholder="Search possible existing alumni (avoid duplicates)...">
                        <button class="btn-ndmu btn-primary" type="submit">Search</button>
                        <a class="btn-ndmu btn-outline" href="{{ route('portal.alumni_encoding.create') }}">Reset</a>
                    </form>

                    @if($matches->count())
                        <div class="panel p-4" style="background:var(--paper);">
                            <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                                Possible matches
                            </div>
                            <div class="help mt-1">
                                If the record already exists, open it instead of creating a duplicate.
                            </div>

                            <div class="mt-3 space-y-2">
                                @foreach($matches as $m)
                                    <div class="match-card">
                                        <div class="min-w-0">
                                            <div class="font-semibold text-gray-900">{{ $m->full_name }}</div>
                                            <div class="text-xs text-gray-600 mt-1">
                                                {{ $m->email ?? '—' }}
                                                <span class="mx-2 text-gray-300">•</span>
                                                <span class="inline-flex px-2 py-1 rounded-full border text-[11px] font-semibold"
                                                      style="border-color:var(--line); background:#fff;">
                                                    {{ $m->record_status }}
                                                </span>
                                            </div>
                                        </div>

                                        <a class="btn-ndmu btn-outline"
                                           href="{{ route('portal.alumni_encoding.edit', $m) }}">
                                            Open
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- CREATE ASSISTED RECORD --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Create Assisted Record</div>
                            <div class="strip-sub">Mode A creates a user; Mode B creates record only.</div>
                        </div>
                    </div>
                </div>

                <div class="p-4 space-y-4">
                    @if(session('warning'))
                        <div class="panel p-4" style="border-color:rgba(234,179,8,.35); background:rgba(234,179,8,.10);">
                            <div class="font-semibold" style="color:rgba(133,77,14,1);">
                                {{ session('warning') }}
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="panel p-4" style="border-color:rgba(248,113,113,.45); background:rgba(254,242,242,1);">
                            <div class="font-semibold text-red-800 mb-2">Please fix the following:</div>
                            <ul class="list-disc ml-5 text-sm text-red-800">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- NOTE: after creating, you will proceed to the full intake/encoding form in the edit screen --}}
                    <div class="panel p-4" style="background:rgba(11,61,46,.04); border-color:rgba(11,61,46,.18);">
                        <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">Next Step</div>
                        <div class="help mt-1">
                            After you click <b>Create Record</b>, you will be redirected to the full encoding form to complete Academic, Employment, and other details.
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('portal.alumni_encoding.store') }}"
                          class="space-y-4">
                        @csrf

                        {{-- Name fields --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="text-sm font-semibold text-gray-700">
                                    First Name <span class="text-red-600">*</span>
                                </label>
                                <input name="first_name"
                                       value="{{ old('first_name') }}"
                                       class="input mt-1"
                                       required>
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-gray-700">Middle Name</label>
                                <input name="middle_name"
                                       value="{{ old('middle_name') }}"
                                       class="input mt-1">
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-gray-700">
                                    Last Name <span class="text-red-600">*</span>
                                </label>
                                <input name="last_name"
                                       value="{{ old('last_name') }}"
                                       class="input mt-1"
                                       required>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Alumni Email (optional)</label>
                            <input name="email"
                                   value="{{ old('email') }}"
                                   class="input mt-1">
                            <div class="help mt-1">Used for record reference. Can be blank for seniors without email.</div>
                        </div>

                        {{-- Mode selection --}}
                        <div class="panel p-4" style="background:var(--paper);">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                                    Mode Selection
                                </div>
                                <span class="badge badge-gold">Mode A / Mode B</span>
                            </div>

                            <div class="mt-3 space-y-3">
                                <label class="flex items-start gap-3 text-sm">
                                    <input type="radio"
                                           name="create_user"
                                           value="1"
                                           class="mt-1"
                                           @checked(old('create_user','0') === '1')>
                                    <div>
                                        <div class="font-semibold text-gray-900">
                                            Mode A — Create User + Temporary Password
                                        </div>
                                        <div class="help">
                                            Creates a login account for the alumnus using the user email below.
                                        </div>
                                    </div>
                                </label>

                                <div class="pl-7">
                                    <label class="text-sm font-semibold text-gray-700">
                                        User Email (required for Mode A)
                                    </label>
                                    <input name="user_email"
                                           value="{{ old('user_email') }}"
                                           class="input mt-1"
                                           placeholder="ex: alumnus@ndmu.edu.ph">
                                </div>

                                <hr style="border-color:var(--line);">

                                <label class="flex items-start gap-3 text-sm">
                                    <input type="radio"
                                           name="create_user"
                                           value="0"
                                           class="mt-1"
                                           @checked(old('create_user','0') === '0')>
                                    <div>
                                        <div class="font-semibold text-gray-900">
                                            Mode B — Record Only (link user later)
                                        </div>
                                        <div class="help">
                                            Creates the alumni record without creating a login account.
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <button class="btn-ndmu btn-primary" type="submit">
                                Create Record
                            </button>

                            <a href="{{ route('portal.alumni_encoding.index') }}" class="btn-ndmu btn-outline">
                                Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Apply Intake-style auto-uppercase here too (names, but NOT email fields) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            function shouldUppercase(el) {
                if (!el) return false;

                // allow opt-out: data-no-caps="1"
                if (el.dataset && el.dataset.noCaps === '1') return false;

                // exclude emails + non-text inputs
                if (el.name === 'email' || el.name === 'user_email') return false;
                if (el.type === 'email' || el.type === 'date' || el.type === 'number' || el.type === 'checkbox' || el.type === 'radio' || el.type === 'file' || el.type === 'password') return false;

                const tag = (el.tagName || '').toLowerCase();
                if (tag === 'textarea') return true;
                if (tag === 'input') {
                    const t = (el.getAttribute('type') || 'text').toLowerCase();
                    return (t === 'text' || t === '' || t === 'search' || t === 'tel');
                }
                return false;
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
</x-app-layout>