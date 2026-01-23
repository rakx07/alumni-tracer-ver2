<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class TurnstileValid implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('turnstile.secret_key');
        $url    = config('turnstile.verify_url');

        if (!$secret) {
            $fail('Turnstile secret key is not configured.');
            return;
        }

        if (!$value) {
            $fail('Please complete the captcha.');
            return;
        }

        try {
            $resp = Http::asForm()->post($url, [
                'secret'   => $secret,
                'response' => $value,
                // 'remoteip' => request()->ip(), // optional
            ]);

            if (!$resp->ok()) {
                $fail('Captcha verification failed. Please try again.');
                return;
            }

            $data = $resp->json();

            // Turnstile returns: { "success": true/false, ... }
            if (!($data['success'] ?? false)) {
                $fail('Captcha verification failed. Please try again.');
            }
        } catch (\Throwable $e) {
            $fail('Captcha verification error. Please try again.');
        }
    }
}
