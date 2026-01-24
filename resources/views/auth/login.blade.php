{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    @php
        $localHosts = ['127.0.0.1', 'localhost', '192.168.20.105'];
        $isLocalHost = in_array(request()->getHost(), $localHosts, true);
    @endphp

    @if(!$isLocalHost)
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif

    <div class="w-full max-w-md">
        {{-- Simple header --}}
        <div class="mb-6 text-left">
            <h1 class="text-2xl font-extrabold text-gray-900">
                NDMU Alumni Tracer
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                Sign in to continue.
            </p>
            <div class="mt-3 h-1 w-24 rounded-full" style="background: linear-gradient(90deg,#0b5d2a,#f2c200);"></div>
        </div>

        <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6">
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4 text-left">
                @csrf

                {{-- Email --}}
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-left font-semibold text-gray-800" />
                    <x-text-input id="email"
                        class="block mt-1 w-full text-left rounded-lg"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="name@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Password --}}
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-left font-semibold text-gray-800" />
                    <x-text-input id="password"
                        class="block mt-1 w-full text-left rounded-lg"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Remember --}}
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                               class="rounded border-gray-300 text-green-700 shadow-sm focus:ring-green-600"
                               name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm font-semibold underline"
                           style="color:#0b5d2a;"
                           href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
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
                        {{ __('LOG IN') }}
                    </x-primary-button>
                </div>

                {{-- Register link --}}
                <div class="text-center text-sm text-gray-700 pt-1">
                    Don’t have an account?
                    <a class="font-semibold underline" style="color:#0b5d2a;" href="{{ route('register') }}">
                        Register
                    </a>
                </div>
            </form>
        </div>

        <p class="mt-4 text-xs text-gray-500 text-left">
            © {{ date('Y') }} Notre Dame of Marbel University. Alumni Tracer System.
        </p>
    </div>
</x-guest-layout>
