<?php

namespace App\Rules;

use App\Support\Settings;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TurnstileValid implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // ✅ Global enable/disable (default: enabled)
        $enabled = Settings::get('captcha_enabled', '1') === '1';

        if (! $enabled) {
            return; // captcha OFF => skip validation
        }

        // ✅ Optional bypass for IT Admin even when captcha is ON
        $bypass = Settings::get('captcha_it_admin_bypass', '0') === '1';
        $role = Auth::user()?->role;

        if ($bypass && $role === 'it_admin') {
            return;
        }

        $secret = config('turnstile.secret_key');
        $url    = config('turnstile.verify_url', 'https://challenges.cloudflare.com/turnstile/v0/siteverify');

        if (!$secret) {
            $fail('Captcha is not configured (missing secret key).');
            return;
        }

        if (!$value) {
            $fail('Please complete the captcha.');
            return;
        }

        try {
            $response = Http::asForm()->post($url, [
                'secret'   => $secret,
                'response' => $value,
            ]);

            if (! $response->ok()) {
                $fail('Captcha verification failed. Please try again.');
                return;
            }

            $data = $response->json();

            if (!($data['success'] ?? false)) {
                $fail('Captcha verification failed. Please try again.');
            }
        } catch (\Throwable $e) {
            $fail('Captcha verification error. Please try again.');
        }
    }
}
