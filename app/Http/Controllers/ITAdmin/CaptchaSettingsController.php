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
        $data = $request->validate([
            'captcha_enabled' => ['nullable', 'in:1'],
            'captcha_it_admin_bypass' => ['nullable', 'in:1'],
        ]);

        Settings::set('captcha_enabled', isset($data['captcha_enabled']) ? '1' : '0');
        Settings::set('captcha_it_admin_bypass', isset($data['captcha_it_admin_bypass']) ? '1' : '0');

        return back()->with('success', 'Captcha settings updated.');
    }
}
