<?php

return [
    'site_key'   => env('TURNSTILE_SITE_KEY'),
    'secret_key' => env('TURNSTILE_SECRET_KEY'),
    'verify_url' => env('TURNSTILE_VERIFY_URL', 'https://challenges.cloudflare.com/turnstile/v0/siteverify'),

    // 'disable_on_local' => env('CAPTCHA_DISABLE_ON_LOCAL', true),
    // 'local_hosts'      => array_filter(array_map('trim', explode(',', env('CAPTCHA_LOCAL_HOSTS', '127.0.0.1,localhost')))),
        'disable_on_local' => env('CAPTCHA_DISABLE_ON_LOCAL', true),

'local_hosts' => array_filter(array_map('trim', explode(',', env(
    'CAPTCHA_LOCAL_HOSTS',
    '127.0.0.1,localhost,192.168.20.105'
)))),

// ✅ Only bypass this LAN (change if needed)
'local_subnets' => array_filter(array_map('trim', explode(',', env(
    'CAPTCHA_LOCAL_SUBNETS',
    '192.168.20.0/24,127.0.0.0/8'
)))),


    ];
