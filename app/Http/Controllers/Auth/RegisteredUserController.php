<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Duplicate protection (case-insensitive)
        // Note: email unique already blocks duplicates; this is an extra guard
        $exists = User::whereRaw('LOWER(first_name) = ?', [strtolower($request->first_name)])
            ->whereRaw('LOWER(last_name) = ?', [strtolower($request->last_name)])
            ->whereRaw('LOWER(email) = ?', [strtolower($request->email)])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['email' => 'A user with the same name and email already exists.'])
                ->withInput();
        }

        $fullName = trim(
            $request->first_name . ' ' .
            ($request->middle_name ? $request->middle_name . ' ' : '') .
            $request->last_name
        );

        $user = User::create([
            // keep Breeze's original name column filled
            'name'        => $fullName,

            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,

            'email'       => strtolower($request->email),
            'password'    => Hash::make($request->password),

            // force default role
            'role'        => 'user',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
