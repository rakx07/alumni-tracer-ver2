<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Edit Strand</h2>
            <a href="{{ route('itadmin.strands.index') }}" class="px-4 py-2 bg-gray-800 text-white rounded">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('itadmin.strands.update', $strand) }}" class="bg-white border rounded p-6">
            @csrf
            @method('PUT')

            @include('itadmin.strands._form', ['strand' => $strand])

            <div class="mt-6">
                <button class="px-5 py-2 bg-gray-800 text-white rounded">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
