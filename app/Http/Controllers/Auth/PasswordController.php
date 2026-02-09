<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class PasswordController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $userId = $request->user()->id;

        // ✅ Write directly to DB (bypasses fillable/events/observers)
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'password' => Hash::make($validated['password']),
                'must_change_password' => 0,
                'updated_at' => now(),
            ]);

        return back()->with('status', 'password-updated');
    }
}
