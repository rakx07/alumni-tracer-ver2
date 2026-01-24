<?php

namespace App\Rules;

use App\Support\Settings; // If you have Settings helper/module; safe to remove if you don't use it
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TurnstileValid implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // ✅ 1) Skip captcha on local/LAN access (host/subnet rules)
        if ($this->shouldBypassForLocalAccess()) {
            return;
        }

        // ✅ 2) Optional DB toggle (captcha_enabled) + IT Admin bypass
        // If you don't use Settings at all, you can remove this whole block + import.
        if (class_exists(\App\Support\Settings::class)) {
            $enabled = Settings::get('captcha_enabled', '1') === '1';
            if (! $enabled) {
                return;
            }

            $bypass = Settings::get('captcha_it_admin_bypass', '0') === '1';
            if ($bypass && Auth::user()?->role === 'it_admin') {
                return;
            }
        }

        // ✅ 3) Public access => require token
        if (empty($value)) {
            $fail('Please complete the captcha.');
            return;
        }

        $secret = config('turnstile.secret_key');
        $url    = config('turnstile.verify_url', 'https://challenges.cloudflare.com/turnstile/v0/siteverify');

        if (!$secret) {
            $fail('Captcha is not configured (missing secret key).');
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

    /**
     * Bypass rules for LAN/local.
     * - Uses config/turnstile.php keys:
     *   - disable_on_local (bool)
     *   - local_hosts (array)
     *   - local_subnets (array of CIDR strings; optional)
     */
    private function shouldBypassForLocalAccess(): bool
    {
        if (! config('turnstile.disable_on_local', true)) {
            return false;
        }

        // ✅ Host-based bypass (best for: 192.168.20.105, localhost)
        $host = request()->getHost();
        $localHosts = config('turnstile.local_hosts', []);

        if (is_array($localHosts) && in_array($host, $localHosts, true)) {
            return true;
        }

        // ✅ Subnet-based bypass (recommended for LAN like 192.168.20.0/24)
        // If you didn't define this config, it defaults to only 192.168.20.0/24
        $subnets = config('turnstile.local_subnets', ['192.168.20.0/24', '127.0.0.0/8']);

        $ip = request()->ip();

        if ($this->ipMatchesAnyCidr($ip, $subnets)) {
            return true;
        }

        return false;
    }

    private function ipMatchesAnyCidr(?string $ip, array $cidrs): bool
    {
        if (!$ip) return false;

        // IPv4 only for simplicity
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return false;
        }

        foreach ($cidrs as $cidr) {
            $cidr = trim((string) $cidr);
            if ($cidr === '') continue;

            if ($this->inCidr($ip, $cidr)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check IPv4 in CIDR. Example: inCidr("192.168.20.10", "192.168.20.0/24")
     */
    private function inCidr(string $ip, string $cidr): bool
    {
        if (!str_contains($cidr, '/')) {
            return false;
        }

        [$network, $prefix] = explode('/', $cidr, 2);

        if (!filter_var($network, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return false;
        }

        $prefix = (int) $prefix;
        if ($prefix < 0 || $prefix > 32) {
            return false;
        }

        $ipLong  = ip2long($ip);
        $netLong = ip2long($network);

        if ($ipLong === false || $netLong === false) {
            return false;
        }

        $mask = -1 << (32 - $prefix);

        return (($ipLong & $mask) === ($netLong & $mask));
    }
}
