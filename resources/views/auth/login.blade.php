{{-- resources/views/auth/login.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'NDMU Alumni Tracer') }} — Login</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('NDMU_logo_icon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('NDMU_logo_icon.ico') }}" type="image/x-icon">

    {{-- Tailwind (Breeze/Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        use App\Support\Settings;

        $localHosts = ['127.0.0.1', 'localhost', '192.168.20.105'];
        $isLocalHost = in_array(request()->getHost(), $localHosts, true);

        // ✅ DB setting switch (from Captcha Settings)
        $captchaEnabled = Settings::get('captcha_enabled', '1') === '1';

        // ✅ Your intended behavior: show captcha only when enabled AND not local host
        $captchaShouldShow = $captchaEnabled && !$isLocalHost;
    @endphp

    @if($captchaShouldShow)
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif

    <style>
        :root{
            --ndmu-green:#0B3D2E;
            --ndmu-gold:#E3C77A;
            --ndmu-paper:#FFFBF0;
        }
        .ndmu-grad { background: linear-gradient(90deg, var(--ndmu-green), var(--ndmu-gold)); }
        .ndmu-ring:focus { outline: none; box-shadow: 0 0 0 3px rgba(227,199,122,.35); border-color: rgba(11,61,46,.35); }
    </style>
</head>

<body class="min-h-screen bg-gray-100">
    {{-- Top Crest --}}
    <div class="pt-8 pb-4 flex items-center justify-center">
        <img src="{{ asset('images/ndmu-logo.png') }}"
             alt="NDMU"
             class="h-20 w-20 object-contain"
             onerror="this.style.display='none';">
    </div>

    <main class="px-4 pb-10">
        <div class="mx-auto w-full max-w-5xl">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    {{-- LEFT HERO --}}
                    <div class="relative min-h-[240px] md:min-h-full">
                        <div class="absolute inset-0"
                             style="
                                background:
                                  linear-gradient(90deg, rgba(11,61,46,.92), rgba(11,61,46,.65) 55%, rgba(0,0,0,.20)),
                                  url('{{ asset('images/ndmu-hero.jpg') }}');
                                background-size:cover;
                                background-position:center;
                             ">
                        </div>

                        <div class="relative h-full p-6 md:p-8 text-white flex flex-col justify-between">
                            <div>
                                <div class="inline-flex items-center gap-2 bg-white/95 text-[var(--ndmu-green)] border border-[var(--ndmu-gold)] rounded-full px-3 py-1 text-xs font-extrabold">
                                    <span class="h-2 w-2 rounded-full bg-[var(--ndmu-green)]"></span>
                                    Secure Sign In
                                </div>

                                <h1 class="mt-4 text-2xl md:text-3xl font-extrabold tracking-wide uppercase">
                                    Welcome back.
                                </h1>

                                <p class="mt-2 text-sm leading-relaxed text-white/90 max-w-md">
                                    Log in to update your alumni tracer profile, view announcements, and access alumni services.
                                </p>

                                <ul class="mt-4 space-y-1 text-sm text-white/90 list-disc pl-5">
                                    <li>Mobile-friendly login</li>
                                    <li>Role-based access</li>
                                    <li>
                                        Security verification:
                                        <span class="font-semibold">
                                            {{ $captchaShouldShow ? 'ON (public host)' : 'OFF (local or disabled)' }}
                                        </span>
                                    </li>
                                </ul>
                            </div>

                            <div class="text-xs text-white/80 leading-relaxed">
                                Data Privacy: Access is role-based and handled responsibly.
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT FORM --}}
                    <div class="p-6 md:p-10">
                        <div class="text-left">
                            <div class="text-xl md:text-2xl font-extrabold text-gray-900">
                                NDMU Alumni Tracer
                            </div>
                            <div class="text-sm text-gray-600 mt-1">
                                Sign in to continue.
                            </div>
                            <div class="mt-3 h-1 w-40 rounded-full ndmu-grad"></div>
                        </div>

                        <div class="mt-6">
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                                @csrf

                                {{-- Email --}}
                                <div>
                                    <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-800" />
                                    <x-text-input id="email"
                                                  class="block mt-1 w-full rounded-xl ndmu-ring"
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
                                    <x-input-label for="password" :value="__('Password')" class="font-semibold text-gray-800" />
                                    <x-text-input id="password"
                                                  class="block mt-1 w-full rounded-xl ndmu-ring"
                                                  type="password"
                                                  name="password"
                                                  required
                                                  autocomplete="current-password"
                                                  placeholder="••••••••" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-between gap-3">
                                    <label for="remember_me" class="inline-flex items-center">
                                        <input id="remember_me" type="checkbox"
                                               class="rounded border-gray-300 text-green-700 shadow-sm focus:ring-green-600"
                                               name="remember">
                                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                    </label>

                                    @if (Route::has('password.request'))
                                        <a class="text-sm font-semibold underline"
                                           style="color:var(--ndmu-green);"
                                           href="{{ route('password.request') }}">
                                            {{ __('Forgot password?') }}
                                        </a>
                                    @endif
                                </div>

                                {{-- Captcha --}}
                                @if($captchaShouldShow)
                                    <div class="pt-1">
                                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
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
                                    <button type="submit"
                                            class="w-full inline-flex justify-center items-center rounded-xl px-4 py-3 font-extrabold tracking-wide text-white"
                                            style="background:var(--ndmu-green);"
                                            onmouseover="this.style.background='#083325'"
                                            onmouseout="this.style.background='var(--ndmu-green)'">
                                        LOG IN
                                    </button>
                                </div>

                                <div class="text-center text-sm text-gray-700 pt-1">
                                    Don’t have an account?
                                    @if (Route::has('register'))
                                        <a class="font-semibold underline" style="color:var(--ndmu-green);" href="{{ route('register') }}">
                                            Register
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>

                        <p class="mt-6 text-xs text-gray-500">
                            © {{ date('Y') }} Notre Dame of Marbel University. Alumni Tracer System.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
