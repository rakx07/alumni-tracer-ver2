{{-- resources/views/auth/forgot-password.blade.php --}}
<x-guest-layout>

    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <style>
        :root{
            --ndmu-green:#0B3D2E;
            --ndmu-gold:#E3C77A;
            --paper:#FFFBF0;
            --page:#FAFAF8;
            --line:#EDE7D1;
        }

        .panel{
            background:#fff;
            border:1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
            overflow:hidden;
        }

        .strip-top{
            padding: 16px 18px;
            background: var(--ndmu-green);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }

        .strip-left{
            display:flex;
            align-items:center;
            gap: 12px;
            min-width: 0;
        }

        .gold-bar{
            width: 6px;
            height: 38px;
            background: var(--ndmu-gold);
            border-radius: 999px;
            flex: 0 0 auto;
        }

        .strip-title{
            color:#fff;
            font-weight: 900;
            letter-spacing: .2px;
            line-height: 1.2;
        }

        .strip-sub{
            color: rgba(255,255,255,.78);
            font-size: 12px;
            margin-top: 2px;
        }

        .seal{
            display:flex;
            align-items:center;
            gap:10px;
            padding: 10px 12px;
            border-radius: 14px;
            background: rgba(255,251,240,.95);
            border: 1px solid rgba(227,199,122,.85);
            color: var(--ndmu-green);
            font-weight: 900;
            font-size: 12px;
            white-space: nowrap;
        }

        .seal img{
            width: 28px;
            height: 28px;
            object-fit: contain;
            border-radius: 999px;
            background:#fff;
        }

        .help{
            font-size: 13px;
            color: rgba(15,23,42,.72);
            line-height: 1.6;
        }

        .turnstile-wrap{
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 12px;
        }
    </style>

    <div class="min-h-[60vh] flex items-center justify-center py-10" style="background:var(--page);">
        <div class="w-full max-w-md mx-auto px-4">

            <div class="panel">
                {{-- NDMU Header Strip --}}
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Password Reset</div>
                            <div class="strip-sub">NDMU Alumni Tracer Portal</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex">
                        <div class="seal">
                            <img src="{{ asset('images/ndmu-logo.png') }}"
                                 alt="NDMU"
                                 onerror="this.style.display='none';">
                            NDMU
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div class="help">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>

                    {{-- Session Status --}}
                    <x-auth-session-status class="mb-2" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email"
                                          class="block mt-1 w-full"
                                          type="email"
                                          name="email"
                                          :value="old('email')"
                                          required
                                          autofocus />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Turnstile --}}
                        <div class="turnstile-wrap">
                            <div class="text-xs font-semibold mb-2" style="color:var(--ndmu-green);">
                                Verification
                            </div>

                            <div class="cf-turnstile" data-sitekey="{{ config('turnstile.site_key') }}"></div>
                            <x-input-error :messages="$errors->get('cf-turnstile-response')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="px-5 py-2"
                                style="background:var(--ndmu-green);">
                                {{ __('Email Password Reset Link') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center text-xs text-gray-500 mt-4">
                Notre Dame of Marbel University • Alumni Tracer Portal
            </div>
        </div>
    </div>
</x-guest-layout>
