{{-- resources/views/profile/edit.blade.php --}}
<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    Account Settings
                </h2>
                <div class="text-sm text-gray-600">
                    Update your profile details and secure your account.
                </div>
            </div>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 rounded font-semibold border"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    {{-- ✅ STATUS MODAL (with dimmed background) --}}
    @php
        $status = session('status');

        $statusMap = [
            'password-change-required' => [
                'title'   => 'Password Change Required',
                'message' => 'You must change your password before continuing.',
                'type'    => 'warning',
            ],
            'password-updated' => [
                'title'   => 'Success',
                'message' => 'Password changed successfully.',
                'type'    => 'success',
            ],
            'profile-updated' => [
                'title'   => 'Success',
                'message' => 'Profile updated successfully.',
                'type'    => 'success',
            ],
        ];

        $modal = $statusMap[$status] ?? null;

        $typeClasses = [
            'success' => 'border-green-200 bg-green-50 text-green-900',
            'warning' => 'border-yellow-200 bg-yellow-50 text-yellow-900',
            'error'   => 'border-red-200 bg-red-50 text-red-900',
            'info'    => 'border-blue-200 bg-blue-50 text-blue-900',
        ];
    @endphp

    @if($modal)
        <div
            x-data="{
                open: true,
                init() {
                    // lock scroll
                    document.documentElement.classList.add('overflow-hidden');

                    // auto-close only success
                    @if(($modal['type'] ?? '') === 'success')
                        setTimeout(() => { this.open = false }, 2000);
                    @endif
                }
            }"
            x-show="open"
            x-cloak
            x-on:keydown.escape.window="open = false"
            x-effect="
                if(!open){
                    document.documentElement.classList.remove('overflow-hidden');
                }
            "
            class="fixed inset-0 z-50 flex items-center justify-center px-4"
            aria-modal="true"
            role="dialog"
        >
            {{-- ✅ DIM BACKGROUND / BACKDROP --}}
            <div
                class="fixed inset-0 bg-black/60"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="open = false"
            ></div>

            {{-- Modal box --}}
            <div
                class="relative z-10 w-full max-w-md rounded-xl border shadow-lg p-5 {{ $typeClasses[$modal['type']] ?? 'border-gray-200 bg-white text-gray-900' }}"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-base font-semibold">
                            {{ $modal['title'] }}
                        </div>
                        <div class="mt-1 text-sm opacity-90">
                            {{ $modal['message'] }}
                        </div>
                    </div>

                    <button
                        type="button"
                        class="shrink-0 rounded-md px-2 py-1 text-sm font-semibold hover:bg-black/5"
                        @click="open = false"
                        aria-label="Close"
                    >
                        ✕
                    </button>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button
                        type="button"
                        class="px-4 py-2 rounded font-semibold text-white"
                        style="background:#0B3D2E;"
                        @click="open = false"
                    >
                        OK
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Profile Information --}}
            <div class="bg-white rounded-xl shadow border overflow-hidden" style="border-color:#EDE7D1;">
                <div class="px-6 py-4 border-b" style="border-color:#EDE7D1; background:#F6F2E6;">
                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Profile</div>
                    <div class="text-xs text-gray-600 mt-1">Name details and email address</div>
                </div>
                <div class="p-6">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- Update Password --}}
            <div class="bg-white rounded-xl shadow border overflow-hidden" style="border-color:#EDE7D1;">
                <div class="px-6 py-4 border-b" style="border-color:#EDE7D1; background:#F6F2E6;">
                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Security</div>
                    <div class="text-xs text-gray-600 mt-1">Change your password regularly</div>
                </div>
                <div class="p-6">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Delete User --}}
            <div class="bg-white rounded-xl shadow border overflow-hidden" style="border-color:#EDE7D1;">
                <div class="px-6 py-4 border-b" style="border-color:#EDE7D1; background:#F6F2E6;">
                    <div class="text-sm font-semibold" style="color:#0B3D2E;">Account</div>
                    <div class="text-xs text-gray-600 mt-1">Deactivate / permanently delete your account</div>
                </div>
                <div class="p-6">
                    <div class="max-w-2xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
