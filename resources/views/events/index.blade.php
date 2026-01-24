{{-- resources/views/events/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900">
                    Events & Updates
                </h2>
                <p class="text-sm text-gray-600">
                    Official sources and calendar of alumni events
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 rounded font-semibold border"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Official External Sources --}}
        <div class="bg-white rounded-xl shadow border p-6"
             style="border-color:#EDE7D1;">
            <h3 class="text-lg font-semibold mb-3" style="color:#0B3D2E;">
                Official University Updates
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <a href="https://www.ndmu.edu.ph/news-and-updates"
                   target="_blank" rel="noopener"
                   class="p-5 rounded-lg border hover:shadow-sm transition"
                   style="border-color:#E3C77A;">
                    <div class="font-semibold text-gray-900">
                        🌐 NDMU News & Updates
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        Official announcements from the NDMU website.
                    </div>
                </a>

                <a href="https://www.facebook.com/ndmuofficial/"
                   target="_blank" rel="noopener"
                   class="p-5 rounded-lg border hover:shadow-sm transition"
                   style="border-color:#E3C77A;">
                    <div class="font-semibold text-gray-900">
                        📘 NDMU Official Facebook Page
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        Real-time updates, posters, and announcements.
                    </div>
                </a>

            </div>
        </div>

        {{-- Internal Calendar --}}
        <div class="bg-white rounded-xl shadow border p-6"
             style="border-color:#EDE7D1;">
            <h3 class="text-lg font-semibold mb-3" style="color:#0B3D2E;">
                Calendar of Events
            </h3>

            <p class="text-sm text-gray-600 mb-4">
                View upcoming alumni activities and university-related events managed by the Office of Alumni Relations.
            </p>

            <a href="{{ route('events.calendar') }}"
               class="inline-flex items-center px-5 py-2 rounded font-semibold text-white"
               style="background:#0B3D2E;">
                View Calendar of Events
            </a>
        </div>

    </div>
</x-app-layout>
