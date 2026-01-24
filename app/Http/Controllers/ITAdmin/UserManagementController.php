<?php

namespace App\Http\Controllers\ITAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $users = User::query()
            ->when($q, fn($qry) => $qry->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('itadmin.users.index', compact('users', 'q'));
    }

    public function create()
    {
        return view('itadmin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'  => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name'   => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'        => ['required', 'string', 'max:50'],
            'temp_password' => ['nullable', 'string', 'min:8'],
        ]);

        $tempPassword = $data['temp_password'] ?? \Illuminate\Support\Str::random(12);

        $fullName = trim(
            $data['first_name'] . ' ' .
            ($data['middle_name'] ? $data['middle_name'] . ' ' : '') .
            $data['last_name']
        );

        \App\Models\User::create([
            'first_name' => $data['first_name'],
            'middle_name'=> $data['middle_name'],
            'last_name'  => $data['last_name'],
            'name'       => $fullName, // ✅ computed
            'email'      => $data['email'],
            'role'       => $data['role'],
            'password'   => \Illuminate\Support\Facades\Hash::make($tempPassword),
            'is_active'  => true,
            'must_change_password' => true,
        ]);

        return redirect()
            ->route('itadmin.users.index')
            ->with('success', "User created. Temporary password: {$tempPassword}");
    }


    public function edit(User $user)
    {
        return view('itadmin.users.edit', compact('user'));
    }

    public function update(Request $request, \App\Models\User $user)
    {
        $data = $request->validate([
            'first_name'  => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name'   => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role'        => ['required', 'string', 'max:50'],
        ]);

        $fullName = trim(
            $data['first_name'] . ' ' .
            ($data['middle_name'] ? $data['middle_name'] . ' ' : '') .
            $data['last_name']
        );

        $user->update([
            'first_name' => $data['first_name'],
            'middle_name'=> $data['middle_name'],
            'last_name'  => $data['last_name'],
            'name'       => $fullName, // ✅ recompute
            'email'      => $data['email'],
            'role'       => $data['role'],
        ]);

        return back()->with('success', 'User updated.');
    }


    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => ['nullable','string', Password::defaults()],
        ]);

        $newPassword = $request->new_password ?: Str::random(12);

        $user->update([
            'password' => Hash::make($newPassword),
            'must_change_password' => true,
        ]);

        return back()->with('success', "Password reset. Temporary password: {$newPassword}");
    }

    public function toggleActive(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', $user->is_active ? 'User enabled.' : 'User disabled.');
    }
}
