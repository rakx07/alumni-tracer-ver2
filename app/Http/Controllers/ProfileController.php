<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'first_name'  => ['required','string','max:100'],
            'middle_name' => ['nullable','string','max:100'],
            'last_name'   => ['required','string','max:100'],
            'email'       => ['required','string','lowercase','email','max:255','unique:users,email,' . $user->id],
        ]);

        // Recompute display name
        $fullName = trim(
            $data['first_name'] . ' ' .
            ($data['middle_name'] ? $data['middle_name'] . ' ' : '') .
            $data['last_name']
        );

        // If email changed, reset verification
        if ($data['email'] !== $user->email) {
            $user->email_verified_at = null;
        }

        $user->update([
            'first_name' => $data['first_name'],
            'middle_name'=> $data['middle_name'],
            'last_name'  => $data['last_name'],
            'name'       => $fullName,
            'email'      => $data['email'],
        ]);

        return back()->with('status', 'profile-updated');
    }


    public function password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'must_change_password' => false, // ✅ clear flag after change
        ]);

        return back()->with('status', 'password-updated');
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
