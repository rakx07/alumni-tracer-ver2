{{-- resources/views/itadmin/programs/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">Edit Program</h2>
                <p class="text-sm text-gray-600">Update program details and status.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('itadmin.programs.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border"
                   style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                    Back to Programs
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
        .panel{
            background:#fff;
            border:1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
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
        .help{ font-size: 12px; color: rgba(15,23,42,.62); }
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Validation errors --}}
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

            {{-- Header strip --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Program Details</div>
                            <div class="strip-sub">Edit fields then click “Update Program”.</div>
                        </div>
                    </div>

                    <span class="text-xs font-extrabold px-3 py-1 rounded-full"
                          style="background:rgba(227,199,122,.18); border:1px solid rgba(227,199,122,.35); color:#fff;">
                        IT Admin
                    </span>
                </div>

                <div class="p-4">
                    <form method="POST"
                          action="{{ route('itadmin.programs.update', $program) }}"
                          class="panel p-6"
                          style="background:var(--paper);">
                        @csrf
                        @method('PUT')

                        {{-- Uses your existing form partial --}}
                        @include('itadmin.programs._form', ['program' => $program])

                        <div class="mt-6 flex flex-wrap items-center gap-2">
                            <button type="submit" class="btn btn-primary">
                                Update Program
                            </button>

                            <a href="{{ route('itadmin.programs.index') }}" class="btn btn-outline">
                                Cancel
                            </a>

                            <div class="help ml-auto">
                                Changes apply immediately and are reflected in Alumni Intake selections.
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
