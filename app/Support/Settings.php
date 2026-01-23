<?php

namespace App\Support;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;

class Settings
{
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting:$key", 60, function () use ($key, $default) {
            return optional(AppSetting::where('key', $key)->first())->value ?? $default;
        });
    }

    public static function set(string $key, $value): void
    {
        AppSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        Cache::forget("setting:$key");
    }
}
