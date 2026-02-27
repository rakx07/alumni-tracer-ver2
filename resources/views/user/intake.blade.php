{{-- resources/views/user/intake.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Alumni Intake Form
            </h2>

            @if(auth()->user()?->intake_completed_at)
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-800 text-white rounded">
                    Back to Dashboard
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

          {{-- =========================
            STANDARDIZED ALERTS
            ========================= --}}

            @if(session('success'))
                <div class="mb-4 p-4 rounded-lg border border-green-300 bg-green-50 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 rounded-lg border border-red-300 bg-red-50 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 p-4 rounded-lg border border-yellow-300 bg-yellow-50 text-yellow-900">
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 p-4 rounded-lg border border-blue-300 bg-blue-50 text-blue-900">
                    {{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 rounded-lg border border-red-300 bg-red-50 text-red-800">
                    <div class="font-semibold mb-2">Please fix the following:</div>
                    <ul class="list-disc ml-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('intake.save') }}" novalidate>
                @csrf

                @include('user._intake_form', ['alumnus' => $alumnus])

                <div class="mt-6">
                    <button class="px-5 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Save Intake Record
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- Datalist (if your JS uses it) --}}
    <datalist id="postNdmuProgramsList">
        @foreach(($programs_by_cat['college'] ?? []) as $p)
            <option value="{{ ($p['code'] ? $p['code'].' — ' : '') . $p['name'] }}"></option>
        @endforeach
        @foreach(($programs_by_cat['grad_school'] ?? []) as $p)
            <option value="{{ ($p['code'] ? $p['code'].' — ' : '') . $p['name'] }}"></option>
        @endforeach
        @foreach(($programs_by_cat['law'] ?? []) as $p)
            <option value="{{ ($p['code'] ? $p['code'].' — ' : '') . $p['name'] }}"></option>
        @endforeach
    </datalist>

    {{-- INCLUDE JS PARTIAL ONCE, AFTER FORM (wrappers exist now) --}}
    @include('user._intake_js', [
        'alumnus' => $alumnus,
        'programs_by_cat' => $programs_by_cat ?? [],
        'strands_list' => $strands_list ?? [],
    ])
</x-app-layout>