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

            @if(session('warning'))
                <div class="p-3 mb-4 bg-yellow-100 border border-yellow-300 rounded text-yellow-900">
                    {{ session('warning') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 mb-4 bg-red-100 border border-red-300 rounded">
                    <div class="font-semibold mb-2">Please fix the following:</div>
                    <ul class="list-disc ml-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="p-3 mb-4 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
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