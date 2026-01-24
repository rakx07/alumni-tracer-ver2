{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
    @php
        $localHosts = ['127.0.0.1', 'localhost', '192.168.20.105'];
        $isLocalHost = in_array(request()->getHost(), $localHosts, true);
    @endphp

    @if(!$isLocalHost)
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif

    <div class="w-full max-w-xl">
        {{-- Simple header --}}
        <div class="mb-6 text-left">
            <h1 class="text-2xl font-extrabold text-gray-900">
                NDMU Alumni Tracer
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                Create your account to access alumni records and services.
            </p>
            <div class="mt-3 h-1 w-24 rounded-full" style="background: linear-gradient(90deg,#0b5d2a,#f2c200);"></div>
        </div>

        <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6">
            <form method="POST" action="{{ route('register') }}" class="space-y-4 text-left">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- First Name --}}
                    <div>
                        <x-input-label for="first_name" :value="__('First Name')" class="text-left font-semibold text-gray-800" />
                        <x-text-input id="first_name"
                            class="block mt-1 w-full text-left rounded-lg"
                            type="text"
                            name="first_name"
                            :value="old('first_name')"
                            required
                            autofocus
                            autocomplete="given-name"
                            placeholder="First name" />
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>

                    {{-- Middle Name --}}
                    <div>
                        <x-input-label for="middle_name" :value="__('Middle Name (Optional)')" class="text-left font-semibold text-gray-800" />
                        <x-text-input id="middle_name"
                            class="block mt-1 w-full text-left rounded-lg"
                            type="text"
                            name="middle_name"
                            :value="old('middle_name')"
                            autocomplete="additional-name"
                            placeholder="Middle name" />
                        <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
                    </div>
                </div>

                {{-- Last Name --}}
                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" class="text-left font-semibold text-gray-800" />
                    <x-text-input id="last_name"
                        class="block mt-1 w-full text-left rounded-lg"
                        type="text"
                        name="last_name"
                        :value="old('last_name')"
                        required
                        autocomplete="family-name"
                        placeholder="Last name" />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>

                {{-- Email --}}
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-left font-semibold text-gray-800" />
                    <x-text-input id="email"
                        class="block mt-1 w-full text-left rounded-lg"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autocomplete="username"
                        placeholder="name@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Password --}}
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-left font-semibold text-gray-800" />
                        <x-text-input id="password"
                            class="block mt-1 w-full text-left rounded-lg"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-left font-semibold text-gray-800" />
                        <x-text-input id="password_confirmation"
                            class="block mt-1 w-full text-left rounded-lg"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Confirm password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                {{-- Captcha (public only) --}}
                @if(!$isLocalHost)
                    <div class="pt-1">
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <div class="text-xs font-semibold text-gray-700 mb-2">
                                Security Verification
                            </div>

                            <div class="cf-turnstile" data-sitekey="{{ config('turnstile.site_key') }}"></div>
                            <x-input-error :messages="$errors->get('cf-turnstile-response')" class="mt-2" />
                        </div>
                    </div>
                @endif

                {{-- Submit --}}
                <div class="pt-2">
                    <x-primary-button
                        class="w-full justify-center rounded-lg font-bold tracking-wide focus:ring-2 focus:ring-offset-2"
                        style="background-color:#0b5d2a;"
                        onmouseover="this.style.backgroundColor='#083f1d'"
                        onmouseout="this.style.backgroundColor='#0b5d2a'">
                        {{ __('REGISTER') }}
                    </x-primary-button>
                </div>

                {{-- Login link --}}
                <div class="text-center text-sm text-gray-700 pt-1">
                    Already registered?
                    <a class="font-semibold underline" style="color:#0b5d2a;" href="{{ route('login') }}">
                        Log in
                    </a>
                </div>
            </form>
        </div>

        <p class="mt-4 text-xs text-gray-500 text-left">
            © {{ date('Y') }} Notre Dame of Marbel University. Alumni Tracer System.
        </p>
    </div>
</x-guest-layout>
