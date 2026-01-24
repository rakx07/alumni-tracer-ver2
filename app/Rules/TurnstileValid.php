<?php

namespace App\Rules;

use App\Support\Settings; // keep if you already use DB toggle; safe to remove if not
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TurnstileValid implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // ✅ 1) Skip captcha on local/LAN access
        if ($this->shouldBypassForLocalAccess()) {
            return;
        }

        // ✅ 2) If you also have a DB toggle (optional)
        // If you DON'T use Settings, you can remove these lines.
        if (class_exists(\App\Support\Settings::class)) {
            $enabled = Settings::get('captcha_enabled', '1') === '1';
            if (! $enabled) return;

            $bypass = Settings::get('captcha_it_admin_bypass', '0') === '1';
            if ($bypass && Auth::user()?->role === 'it_admin') return;
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
                // 'remoteip' => request()->ip(), // optional
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

    private function shouldBypassForLocalAccess(): bool
    {
        if (!config('turnstile.disable_on_local', true)) {
            return false;
        }

        // Host-based bypass (e.g., 192.168.20.105, localhost)
        $host = request()->getHost();
        $localHosts = config('turnstile.local_hosts', []);

        if (in_array($host, $localHosts, true)) {
            return true;
        }

        // IP-based bypass (private ranges)
        $ip = request()->ip();
        return $this->isPrivateIp($ip);
    }

    private function isPrivateIp(?string $ip): bool
    {
        if (!$ip) return false;

        // Validate basic format
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return false;
        }

        $long = ip2long($ip);
        if ($long === false) return false;

        // 10.0.0.0/8
        if ($this->inCidr($long, '10.0.0.0', 8)) return true;

        // 172.16.0.0/12
        if ($this->inCidr($long, '172.16.0.0', 12)) return true;

        // 192.168.0.0/16
        if ($this->inCidr($long, '192.168.0.0', 16)) return true;

        // 127.0.0.0/8 (loopback)
        if ($this->inCidr($long, '127.0.0.0', 8)) return true;

        return false;
    }

    private function inCidr(int $ipLong, string $network, int $prefix): bool
    {
        $netLong = ip2long($network);
        if ($netLong === false) return false;

        $mask = -1 << (32 - $prefix);
        return (($ipLong & $mask) === ($netLong & $mask));
    }
}
