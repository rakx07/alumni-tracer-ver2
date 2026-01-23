<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Captcha Settings
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="p-3 mb-4 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('itadmin.captcha.update') }}">
                    @csrf

                    <div class="space-y-4">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="captcha_enabled" value="1"
                                   class="rounded border-gray-300"
                                   @checked($captcha_enabled)>
                            <span class="text-gray-800 font-medium">Enable Captcha (Turnstile)</span>
                        </label>

                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="captcha_it_admin_bypass" value="1"
                                   class="rounded border-gray-300"
                                   @checked($captcha_it_admin_bypass)>
                            <span class="text-gray-800 font-medium">Allow IT Admin to bypass Captcha</span>
                        </label>

                        <div class="pt-2">
                            <button class="px-4 py-2 bg-gray-900 text-white rounded">
                                Save
                            </button>
                        </div>

                        <p class="text-sm text-gray-500">
                            Note: Disabling captcha affects login & registration for all users.
                            Bypass lets IT Admin login/register without solving captcha even if enabled.
                        </p>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
