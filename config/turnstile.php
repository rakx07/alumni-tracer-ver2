<?php

return [
    'site_key'   => env('TURNSTILE_SITE_KEY'),
    'secret_key' => env('TURNSTILE_SECRET_KEY'),
    'verify_url' => env('TURNSTILE_VERIFY_URL', 'https://challenges.cloudflare.com/turnstile/v0/siteverify'),

    'disable_on_local' => env('CAPTCHA_DISABLE_ON_LOCAL', true),
    'local_hosts'      => array_filter(array_map('trim', explode(',', env('CAPTCHA_LOCAL_HOSTS', '127.0.0.1,localhost')))),
];
