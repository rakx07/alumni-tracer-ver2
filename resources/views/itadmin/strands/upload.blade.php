{{-- resources/views/itadmin/strands/upload.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">Upload Strands</h2>
                <p class="text-sm text-gray-600">Import SHS strand list via CSV/XLSX and keep Intake dropdowns updated.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('itadmin.strands.template') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border"
                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                    Download Template
                </a>

                <a href="{{ route('itadmin.strands.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white"
                   style="background:#0B3D2E;">
                    Back to Strands
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
            padding: 14px 18px;
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

        .badge{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            border: 1px solid rgba(227,199,122,.65);
            background: rgba(227,199,122,.18);
            color: #fff;
        }

        .input{
            width:100%;
            border-radius: 12px;
            border: 1px solid rgba(15,23,42,.18);
            padding: 10px 12px;
            font-size: 14px;
            outline: none;
            background:#fff;
        }
        .input:focus{
            box-shadow: 0 0 0 3px rgba(227,199,122,.35);
            border-color: rgba(227,199,122,.85);
        }

        .btn{
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

        .help{
            font-size: 12px;
            color: rgba(15,23,42,.62);
            line-height: 1.4;
        }

        .mono{
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono","Courier New", monospace;
        }
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Flash --}}
            @if(session('success'))
                <div class="panel p-4" style="border-color:rgba(34,197,94,.35); background:rgba(34,197,94,.10);">
                    <div class="font-semibold" style="color:rgba(20,83,45,1);">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="panel p-4" style="border-color:rgba(234,179,8,.35); background:rgba(234,179,8,.10);">
                    <div class="font-semibold" style="color:rgba(133,77,14,1);">
                        {{ session('warning') }}
                    </div>
                </div>
            @endif

            {{-- Instructions strip --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Import Instructions</div>
                            <div class="strip-sub">Prepare your file using the exact headers below.</div>
                        </div>
                    </div>
                    <span class="badge">IT Admin</span>
                </div>

                <div class="p-4 space-y-3">
                    <div class="panel p-4" style="background:var(--paper);">
                        <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                            Required CSV/Excel Headers
                        </div>

                        <div class="mt-2 mono text-sm bg-white border rounded-xl p-3 overflow-x-auto"
                             style="border-color:var(--line);">
                            code,name,is_active
                        </div>

                        <div class="help mt-2">
                            <div><span class="font-semibold">is_active</span>: 1 or 0</div>
                            <div class="mt-2">
                                Notes:
                                <ul class="list-disc ml-5 mt-1">
                                    <li><span class="mono">code</span> and <span class="mono">name</span> are required.</li>
                                    <li>Rows missing required fields will be skipped.</li>
                                    <li>Recommended file size: up to ~10MB.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Upload Form --}}
                    <form method="POST"
                          enctype="multipart/form-data"
                          action="{{ route('itadmin.strands.upload') }}"
                          class="panel p-4">
                        @csrf

                        <div class="space-y-2">
                            <label class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                                Choose file (CSV/XLSX/XLS)
                            </label>

                            <input type="file"
                                   name="file"
                                   class="input"
                                   accept=".csv,.xlsx,.xls"
                                   required>

                            <div class="help">
                                Tip: Use the “Download Template” button to avoid header mistakes.
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="mt-3 p-4 rounded-xl text-sm"
                                 style="border:1px solid rgba(248,113,113,.45); background:rgba(254,242,242,1);">
                                <div class="font-extrabold text-red-800 mb-2">Please fix the following:</div>
                                <ul class="list-disc ml-5 text-red-800">
                                    @foreach($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <button type="submit" class="btn btn-primary">
                                Upload Strands
                            </button>

                            <a href="{{ route('itadmin.strands.index') }}" class="btn btn-outline">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
