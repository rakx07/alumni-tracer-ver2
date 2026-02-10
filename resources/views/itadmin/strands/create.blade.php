{{-- resources/views/itadmin/strands/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">Add Strand</h2>
                <p class="text-sm text-gray-600">Create a new SHS strand entry for the intake dropdown.</p>
            </div>

            <a href="{{ route('itadmin.strands.index') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to Strands
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
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

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

            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">New Strand</div>
                            <div class="strip-sub">Add a strand code and name (you can disable it later).</div>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <form method="POST"
                          action="{{ route('itadmin.strands.store') }}"
                          class="space-y-4">
                        @csrf

                        {{-- Form partial --}}
                        @include('itadmin.strands._form', ['strand' => null])

                        <div class="flex flex-wrap items-center gap-2 pt-2">
                            <button type="submit" class="btn btn-primary">
                                Save Strand
                            </button>

                            <a href="{{ route('itadmin.strands.index') }}" class="btn btn-outline">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-xs text-gray-500">
                Note: Code will be normalized to uppercase in the controller.
            </div>

        </div>
    </div>
</x-app-layout>
