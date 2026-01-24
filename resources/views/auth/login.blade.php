<x-guest-layout>
    @php
        // Hosts where captcha should be hidden (LAN/local access)
        $localHosts = ['127.0.0.1', 'localhost', '192.168.20.105'];
        $isLocalHost = in_array(request()->getHost(), $localHosts, true);
    @endphp

    @if(!$isLocalHost)
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif

    <style>
        :root{
            --ndmu-green:#0b5d2a;
            --ndmu-green-2:#06401d;
            --ndmu-gold:#f2c200;
            --ink:#0f172a;
            --muted:#64748b;
            --card:#ffffff;
            --line:rgba(15,23,42,.10);
        }
        .ndmu-wrap{
            min-height: calc(100vh - 0px);
            display:flex;
            align-items:center;
            justify-content:center;
            padding: 2rem 1rem;
            background:
                radial-gradient(1200px 500px at 20% 0%, rgba(11,93,42,.12), transparent 60%),
                radial-gradient(900px 450px at 100% 20%, rgba(242,194,0,.10), transparent 55%),
                linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        }
        .ndmu-card{
            width:100%;
            max-width: 460px;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(2,6,23,.08);
            overflow:hidden;
        }
        .ndmu-top{
            background: linear-gradient(135deg, var(--ndmu-green) 0%, var(--ndmu-green-2) 100%);
            padding: 20px 22px;
            color:#fff;
            position:relative;
        }
        .ndmu-top:after{
            content:"";
            position:absolute;
            right:-40px;
            top:-40px;
            width:160px;
            height:160px;
            border-radius:50%;
            background: radial-gradient(circle at 30% 30%, rgba(242,194,0,.55), rgba(242,194,0,0) 60%);
            pointer-events:none;
        }
        .ndmu-brand{
            display:flex;
            gap:12px;
            align-items:center;
        }
        .ndmu-badge{
            width:40px;
            height:40px;
            border-radius:12px;
            background: rgba(242,194,0,.18);
            border: 1px solid rgba(242,194,0,.35);
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
            color: var(--ndmu-gold);
            letter-spacing:.5px;
        }
        .ndmu-title{
            line-height:1.1;
        }
        .ndmu-title h1{
            margin:0;
            font-size: 1.05rem;
            font-weight: 800;
        }
        .ndmu-title p{
            margin:.15rem 0 0;
            font-size: .85rem;
            opacity:.92;
        }
        .ndmu-body{
            padding: 22px;
        }
        .ndmu-label{
            font-weight: 700 !important;
            color: #0f172a !important;
        }
        .ndmu-input{
            border-radius: 12px !important;
        }
        .ndmu-muted{
            color: var(--muted);
            font-size: .9rem;
        }
        .ndmu-link{
            color: var(--ndmu-green);
            font-weight: 700;
        }
        .ndmu-link:hover{ color: var(--ndmu-green-2); }
        .ndmu-btn{
            background: var(--ndmu-green) !important;
            border-radius: 12px !important;
            padding: .65rem 1rem !important;
        }
        .ndmu-btn:hover{
            background: var(--ndmu-green-2) !important;
        }
        .ndmu-divider{
            margin: 16px 0 0;
            padding-top: 16px;
            border-top: 1px dashed rgba(2,6,23,.12);
        }
        .ndmu-note{
            margin-top: 10px;
            font-size: .82rem;
            color: rgba(15,23,42,.7);
        }
        .ndmu-chip{
            display:inline-flex;
            align-items:center;
            gap:.35rem;
            padding: .25rem .55rem;
            border-radius: 999px;
            border: 1px solid rgba(242,194,0,.35);
            background: rgba(242,194,0,.12);
            color: #6b4f00;
            font-weight: 700;
            font-size:.78rem;
        }
        .turnstile-wrap{
            border: 1px solid rgba(2,6,23,.10);
            border-radius: 14px;
            padding: 12px;
            background: #fff;
        }
    </style>

    <div class="ndmu-wrap">
        <div class="ndmu-card">
            <div class="ndmu-top">
                <div class="ndmu-brand">
                    <div class="ndmu-badge">ND</div>
                    <div class="ndmu-title">
                        <h1>{{ config('app.name', 'NDMU Alumni Portal') }}</h1>
                        <p>Sign in to continue</p>
                    </div>
                </div>
            </div>

            <div class="ndmu-body">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label class="ndmu-label" for="email" :value="__('Email')" />
                        <x-text-input
                            id="email"
                            class="ndmu-input block mt-1 w-full"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="name@ndmu.edu.ph"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label class="ndmu-label" for="password" :value="__('Password')" />
                        <x-text-input
                            id="password"
                            class="ndmu-input block mt-1 w-full"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                   class="rounded border-gray-300 text-[var(--ndmu-green)] shadow-sm focus:ring-[var(--ndmu-green)]"
                                   name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="underline text-sm ndmu-link rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--ndmu-green)]"
                               href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Captcha -->
                    @if(!$isLocalHost)
                        <div class="mt-4 turnstile-wrap">
                            <div class="flex items-center justify-between mb-2">
                                <span class="ndmu-chip">Security Check</span>
                                <span class="ndmu-muted">Required for public access</span>
                            </div>
                            <div class="cf-turnstile" data-sitekey="{{ config('turnstile.site_key') }}"></div>
                            <x-input-error :messages="$errors->get('cf-turnstile-response')" class="mt-2" />
                        </div>
                    @else
                        <div class="ndmu-note">
                            Captcha is disabled on local network access.
                        </div>
                    @endif

                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button class="ndmu-btn">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>

                    <div class="ndmu-divider">
                        <p class="text-sm text-gray-600">
                            Don’t have an account?
                            <a class="ndmu-link underline" href="{{ route('register') }}">Create one</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
