<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Upload Programs</h2>
            <a href="{{ route('itadmin.programs.index') }}" class="px-4 py-2 bg-gray-800 text-white rounded">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

        <div class="bg-white border rounded p-6 text-sm">
            <div class="font-semibold mb-2">Required CSV/Excel Headers</div>
            <div class="font-mono bg-gray-50 border rounded p-3">category,code,name,is_active</div>
            <div class="text-xs text-gray-600 mt-2">
                category must be: <span class="font-mono">college</span>, <span class="font-mono">grad_school</span>, <span class="font-mono">law</span><br>
                is_active: 1 or 0
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data" action="{{ route('itadmin.programs.upload') }}" class="bg-white border rounded p-6 space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium">Choose file (CSV/XLSX/XLS)</label>
                <input type="file" name="file" class="w-full border rounded p-2" required>
            </div>

            @if ($errors->any())
                <div class="p-3 bg-red-100 border border-red-300 rounded text-sm">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button class="px-5 py-2 bg-gray-800 text-white rounded">Upload</button>
        </form>

    </div>
</x-app-layout>
