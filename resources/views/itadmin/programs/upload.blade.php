{{-- resources/views/itadmin/programs/upload.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Upload Programs</h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('itadmin.programs.template') }}"
                   class="px-4 py-2 bg-white border border-gray-300 rounded text-gray-800 hover:bg-gray-50">
                    Download Template
                </a>

                <a href="{{ route('itadmin.programs.index') }}"
                   class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

        {{-- Flash --}}
        @if(session('success'))
            <div class="p-3 rounded bg-green-50 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="p-3 rounded bg-yellow-50 border border-yellow-200 text-yellow-800">
                {{ session('warning') }}
            </div>
        @endif

        {{-- Instructions --}}
        <div class="bg-white border rounded p-6 text-sm">
            <div class="font-semibold mb-2">Required CSV/Excel Headers</div>

            <div class="font-mono bg-gray-50 border rounded p-3 overflow-x-auto">
                category,code,name,is_active
            </div>

            <div class="text-xs text-gray-600 mt-2 leading-relaxed">
                <div>
                    <span class="font-semibold">category</span> must be:
                    <span class="font-mono">college</span>,
                    <span class="font-mono">grad_school</span>,
                    <span class="font-mono">law</span>
                </div>
                <div>
                    <span class="font-semibold">is_active</span>: 1 or 0
                </div>
                <div class="mt-2">
                    Notes:
                    <ul class="list-disc ml-5">
                        <li><span class="font-mono">code</span> is optional (can be blank).</li>
                        <li>Rows missing required fields will be skipped.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Upload Form --}}
        <form method="POST"
              enctype="multipart/form-data"
              action="{{ route('itadmin.programs.upload') }}"
              class="bg-white border rounded p-6 space-y-3">
            @csrf

            <div>
                <label class="block text-sm font-medium">Choose file (CSV/XLSX/XLS)</label>
                <input type="file"
                       name="file"
                       class="w-full border rounded p-2 mt-1"
                       accept=".csv,.xlsx,.xls"
                       required>
                <div class="text-xs text-gray-500 mt-1">
                    Maximum 10MB recommended.
                </div>
            </div>

            @if ($errors->any())
                <div class="p-3 bg-red-50 border border-red-200 rounded text-sm text-red-800">
                    <div class="font-semibold mb-1">Please fix the following:</div>
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit"
                    class="px-5 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">
                Upload
            </button>
        </form>

    </div>
</x-app-layout>
