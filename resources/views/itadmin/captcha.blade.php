{{-- resources/views/itadmin/captcha.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Captcha Settings
                </h2>
                <div class="text-sm text-gray-600">
                    Control Turnstile protection for login & registration.
                </div>
            </div>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <style>
        :root{
            --ndmu-green:#0B3D2E;
            --ndmu-gold:#E3C77A;
            --paper:#FFFBF0;
            --page:#FAFAF8;
            --line:#EDE7D1;
        }

        .strip{
            border:1px solid var(--line);
            border-radius: 18px;
            overflow:hidden;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
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
        }
        .strip-sub{
            color: rgba(255,255,255,.78);
            font-size: 12px;
            margin-top: 2px;
        }

        .pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,251,240,.95);
            border: 1px solid rgba(227,199,122,.85);
            color: var(--ndmu-green);
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
        }
        .pill-dot{
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--ndmu-green);
        }

        .section-card{
            border: 1px solid rgba(227,199,122,.55);
            border-radius: 18px;
            background:#fff;
            padding: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }

        .toggle{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap: 14px;
            padding: 14px;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: #fff;
        }
        .toggle-left{
            min-width: 0;
        }
        .toggle-title{
            font-weight: 900;
            color: var(--ndmu-green);
        }
        .toggle-desc{
            margin-top: 4px;
            font-size: 12px;
            color: rgba(15,23,42,.62);
            line-height: 1.5;
        }
        .switch{
            width: 48px;
            height: 28px;
            border-radius: 999px;
            border: 1px solid rgba(15,23,42,.18);
            background: rgba(15,23,42,.06);
            position: relative;
            flex: 0 0 auto;
            cursor: pointer;
        }
        .switch input{
            opacity:0;
            width: 1px;
            height: 1px;
            position:absolute;
        }
        .knob{
            position:absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 3px;
            width: 22px;
            height: 22px;
            border-radius: 999px;
            background: #fff;
            box-shadow: 0 8px 16px rgba(2,6,23,.15);
            transition: .15s ease;
            border: 1px solid rgba(15,23,42,.12);
        }
        .switch.on{
            background: rgba(11,61,46,.16);
            border-color: rgba(11,61,46,.25);
        }
        .switch.on .knob{
            left: 22px;
            background: var(--ndmu-gold);
            border-color: rgba(227,199,122,.8);
        }

        .btn-ndmu{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 900;
            transition: .15s ease;
            white-space: nowrap;
            box-shadow: 0 6px 14px rgba(2,6,23,.06);
        }
        .btn-primary{ background: var(--ndmu-green); color:#fff; }
        .btn-primary:hover{ filter: brightness(.95); }
        .btn-outline{
            background: var(--paper);
            color: var(--ndmu-green);
            border: 1px solid var(--ndmu-gold);
        }
        .btn-outline:hover{ filter: brightness(.98); }

        .note{
            border: 1px solid var(--line);
            background: var(--paper);
            border-radius: 14px;
            padding: 12px;
            font-size: 12px;
            color: rgba(15,23,42,.70);
            line-height: 1.6;
        }
        .danger{
            border-color: rgba(239,68,68,.28);
            background: rgba(239,68,68,.06);
            color: rgba(127,29,29,.92);
        }
    </style>

    <div class="py-10" style="background:var(--page);">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Errors --}}
            @if ($errors->any())
                <div class="rounded-xl border p-4"
                     style="border-color: rgba(239,68,68,.25); background: rgba(239,68,68,.07);">
                    <div class="font-extrabold text-red-900">Please fix the following:</div>
                    <ul class="list-disc ml-5 text-sm text-red-900 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Success --}}
            @if(session('success'))
                <div class="rounded-xl border p-4 text-sm"
                     style="border-color:#BBF7D0; background:#ECFDF5; color:#065F46;">
                    <div class="font-extrabold">Saved</div>
                    <div class="mt-1">{{ session('success') }}</div>
                </div>
            @endif

            {{-- Header Strip --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">Security Controls</div>
                            <div class="strip-sub">Turnstile protection for authentication pages.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            IT Admin Only
                        </span>
                    </div>
                </div>

                <div class="p-5 text-sm text-gray-600">
                    These settings affect **login and registration** pages. Keep captcha enabled in production.
                </div>
            </div>

            <form method="POST" action="{{ route('itadmin.captcha.update') }}" class="space-y-6" id="captchaForm">
                @csrf

                <div class="section-card space-y-3">
                    {{-- Captcha Enabled --}}
                    <div class="toggle">
                        <div class="toggle-left">
                            <div class="toggle-title">Enable Captcha (Cloudflare Turnstile)</div>
                            <div class="toggle-desc">
                                Recommended ON for production to reduce bots and abuse on Login/Register.
                            </div>
                        </div>

                        <label class="switch {{ $captcha_enabled ? 'on' : '' }}" id="swEnabled">
                            <input type="checkbox" name="captcha_enabled" value="1" @checked($captcha_enabled)>
                            <span class="knob"></span>
                        </label>
                    </div>

                    {{-- IT Admin Bypass --}}
                    <div class="toggle" id="bypassRow">
                        <div class="toggle-left">
                            <div class="toggle-title">Allow IT Admin Bypass</div>
                            <div class="toggle-desc">
                                If enabled, IT Admin can log in/register without solving captcha even when captcha is ON.
                            </div>
                        </div>

                        <label class="switch {{ ($captcha_enabled && $captcha_it_admin_bypass) ? 'on' : '' }}" id="swBypass">
                            <input type="checkbox" name="captcha_it_admin_bypass" value="1"
                                   @checked($captcha_it_admin_bypass) {{ !$captcha_enabled ? 'disabled' : '' }}>
                            <span class="knob"></span>
                        </label>
                    </div>

                    <div class="note danger" id="disableWarning" style="{{ $captcha_enabled ? 'display:none;' : '' }}">
                        <strong>Warning:</strong> Captcha is currently OFF. This reduces protection against bots on authentication pages.
                        Turn this ON for public deployments.
                    </div>

                    <div class="note">
                        <strong>Security note:</strong> These settings should be restricted to IT Admin only (already protected by route middleware).
                        If you are testing locally, bypass can be useful. For production, keep captcha enabled.
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button class="btn-ndmu btn-primary" type="submit">
                        Save Settings
                    </button>

                    <a href="{{ route('dashboard') }}" class="btn-ndmu btn-outline">
                        Cancel
                    </a>
                </div>
            </form>

            <script>
                (function(){
                    const form = document.getElementById('captchaForm');
                    const swEnabled = document.getElementById('swEnabled');
                    const swBypass  = document.getElementById('swBypass');
                    const bypassRow = document.getElementById('bypassRow');
                    const disableWarning = document.getElementById('disableWarning');

                    function applySwitchUI(labelEl){
                        if(!labelEl) return;
                        const input = labelEl.querySelector('input[type="checkbox"]');
                        if(!input) return;
                        labelEl.classList.toggle('on', !!input.checked);
                    }

                    function sync(){
                        applySwitchUI(swEnabled);
                        applySwitchUI(swBypass);

                        const enabledInput = swEnabled?.querySelector('input[type="checkbox"]');
                        const bypassInput  = swBypass?.querySelector('input[type="checkbox"]');

                        const enabled = !!enabledInput?.checked;

                        if (bypassInput){
                            bypassInput.disabled = !enabled;
                            if(!enabled){
                                bypassInput.checked = false; // keep consistent (and controller also enforces)
                            }
                        }

                        applySwitchUI(swBypass);

                        if(disableWarning){
                            disableWarning.style.display = enabled ? 'none' : 'block';
                        }
                    }

                    // Confirm when turning OFF captcha
                    swEnabled?.addEventListener('click', function(e){
                        const input = swEnabled.querySelector('input[type="checkbox"]');
                        if(!input) return;

                        // Click happens before checked toggles visually; we check intention by simulating next state
                        const willBeEnabled = !input.checked; // because click toggles
                        if(!willBeEnabled){
                            const ok = confirm('Disable Captcha? This reduces protection on login/register. Continue?');
                            if(!ok){
                                e.preventDefault();
                                e.stopPropagation();
                                return false;
                            }
                        }
                        // allow toggle then sync after a tick
                        setTimeout(sync, 0);
                    });

                    swBypass?.addEventListener('click', function(){
                        setTimeout(sync, 0);
                    });

                    // Initial sync
                    sync();
                })();
            </script>
        </div>
    </div>
</x-app-layout>
