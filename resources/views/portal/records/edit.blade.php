{{-- resources/views/portals/records/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Alumni Record #{{ $alumnus->id }}
                </h2>
                <div class="text-sm text-gray-600">
                    Update sections as needed. You can jump directly to a section below.
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('portal.records.show', $alumnus) }}"
                   class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- ERRORS --}}
            @if ($errors->any())
                <div class="p-4 bg-red-100 border border-red-300 rounded">
                    <div class="font-semibold mb-2">Please fix the following:</div>
                    <ul class="list-disc ml-6 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="p-3 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- QUICK NAV (matches show.blade.php anchors) --}}
            <div class="bg-white shadow rounded border border-gray-100 p-4">
                <div class="text-sm font-semibold text-gray-700 mb-2">Jump to section:</div>
                <div class="flex flex-wrap gap-2 text-sm">
                    <a href="#personal" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Personal</a>
                    <a href="#addresses" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Addresses</a>
                    <a href="#academic" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Academic</a>
                    <a href="#employment" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Employment</a>
                    <a href="#community" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Community</a>
                    <a href="#engagement" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Engagement</a>
                    <a href="#consent" class="px-3 py-1.5 bg-gray-100 rounded hover:bg-gray-200">Consent</a>
                </div>
            </div>

            <form method="POST" action="{{ route('portal.records.update', $alumnus) }}">
                @csrf
                @method('PUT')

                {{-- IMPORTANT:
                     Your included form partial should contain these section IDs:
                     personal, addresses, academic, employment, community, engagement, consent
                     so the jump links and highlight feature works.
                --}}

                <div class="bg-white shadow rounded border border-gray-100 p-6">
                    {{-- Reuse the exact intake form UI --}}
                    @include('user._intake_form', ['alumnus' => $alumnus])
                </div>

                <div class="mt-4 flex items-center justify-end gap-2">
                    <a href="{{ route('portal.records.show', $alumnus) }}"
                       class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
                        Cancel
                    </a>

                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Auto-scroll + highlight section when coming from show page ( ?section=addresses ) --}}
    <script>
        (function () {
            const section = new URLSearchParams(window.location.search).get('section');
            if (!section) return;

            const el = document.getElementById(section);
            if (!el) return;

            el.classList.add('ring-2','ring-indigo-500','ring-offset-2');
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });

            setTimeout(() => {
                el.classList.remove('ring-2','ring-indigo-500','ring-offset-2');
            }, 2500);
        })();
    </script>
</x-app-layout>
