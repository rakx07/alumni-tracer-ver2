<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\TurnstileValid;
use App\Support\Settings;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // Match your Blade local-host behavior
        $localHosts = ['127.0.0.1', 'localhost', '192.168.20.105'];
        $isLocalHost = in_array($request->getHost(), $localHosts, true);

        // DB settings
        $captchaEnabled = Settings::get('captcha_enabled', '1') === '1';
        $captchaBypassEnabled = Settings::get('captcha_it_admin_bypass', '0') === '1';

        // IT Admin bypass (only if already authenticated as it_admin)
        $isItAdmin = Auth::check() && (Auth::user()->role ?? null) === 'it_admin';

        // Final rule: require captcha only if enabled, not local, and not bypassed
        $shouldRequireCaptcha = $captchaEnabled && !$isLocalHost && !($captchaBypassEnabled && $isItAdmin);

        $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],

            // ✅ Turnstile rule aligned with settings
            'cf-turnstile-response' => $shouldRequireCaptcha
                ? ['required', new TurnstileValid()]
                : ['nullable'],
        ]);

        // Duplicate protection (case-insensitive)
        $exists = User::whereRaw('LOWER(first_name) = ?', [strtolower($request->first_name)])
            ->whereRaw('LOWER(last_name) = ?', [strtolower($request->last_name)])
            ->whereRaw('LOWER(email) = ?', [strtolower($request->email)])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['email' => 'A user with the same name and email already exists.'])
                ->withInput();
        }

        $fullName = trim(
            $request->first_name . ' ' .
            ($request->middle_name ? $request->middle_name . ' ' : '') .
            $request->last_name
        );

        $user = User::create([
            'name'        => $fullName,
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'email'       => strtolower($request->email),
            'password'    => Hash::make($request->password),

            // default role
            'role'        => 'user',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
