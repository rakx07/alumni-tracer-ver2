<?php

namespace App\Http\Controllers\ITAdmin;

use App\Http\Controllers\Controller;
use App\Support\Settings;
use Illuminate\Http\Request;

class CaptchaSettingsController extends Controller
{
    public function edit()
    {
        return view('itadmin.captcha', [
            'captcha_enabled' => Settings::get('captcha_enabled', '1') === '1',
            'captcha_it_admin_bypass' => Settings::get('captcha_it_admin_bypass', '0') === '1',
        ]);
    }

    public function update(Request $request)
    {
        // Use boolean validation (checkbox sends "1" when checked, otherwise missing)
        $validated = $request->validate([
            'captcha_enabled' => ['nullable', 'boolean'],
            'captcha_it_admin_bypass' => ['nullable', 'boolean'],
        ]);

        // Convert to explicit "1"/"0"
        $enabled = $request->boolean('captcha_enabled') ? '1' : '0';
        $bypass  = $request->boolean('captcha_it_admin_bypass') ? '1' : '0';

        // If captcha is OFF, bypass is irrelevant; force it OFF to avoid confusion.
        if ($enabled === '0') {
            $bypass = '0';
        }

        Settings::set('captcha_enabled', $enabled);
        Settings::set('captcha_it_admin_bypass', $bypass);

        return back()->with('success', 'Captcha settings updated.');
    }
}
