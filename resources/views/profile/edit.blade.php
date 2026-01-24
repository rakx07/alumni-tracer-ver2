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
