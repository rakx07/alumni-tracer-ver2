{{-- resources/views/itadmin/users/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Edit User Account
                </h2>
                <div class="text-sm text-gray-600">
                    Update user details, role, status, and reset password if needed.
                </div>
            </div>

            <a href="{{ route('itadmin.users.index') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                Back to Users
            </a>
        </div>
    </x-slot>

    <style>
        :root{
            --ndmu-green:#0B3D2E;
            --ndmu-gold:#E3C77A;
            --paper:#FFFBF0;
            --page:#FAFAF8;
            --line:#EDE7D1;
        }

        .strip{
            border:1px solid var(--line);
            border-radius: 18px;
            overflow:hidden;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }
        .strip-top{
            padding: 16px 18px;
            background: var(--ndmu-green);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }
        .strip-left{
            display:flex;
            align-items:center;
            gap: 12px;
            min-width: 0;
        }
        .gold-bar{
            width: 6px;
            height: 38px;
            background: var(--ndmu-gold);
            border-radius: 999px;
            flex: 0 0 auto;
        }
        .strip-title{
            color:#fff;
            font-weight: 900;
            letter-spacing: .2px;
        }
        .strip-sub{
            color: rgba(255,255,255,.78);
            font-size: 12px;
            margin-top: 2px;
        }

        .pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,251,240,.95);
            border: 1px solid rgba(227,199,122,.85);
            color: var(--ndmu-green);
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
        }
        .pill-dot{
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--ndmu-green);
        }

        .section-card{
            border: 1px solid rgba(227,199,122,.55);
            border-radius: 18px;
            background:#fff;
            padding: 18px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }

        .subhead{
            display:flex;
            align-items:center;
            gap:10px;
            margin-bottom: 12px;
        }
        .subhead .badge{
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight: 900;
            border: 1px solid rgba(227,199,122,.65);
            background: rgba(227,199,122,.16);
            color: var(--ndmu-green);
            flex: 0 0 34px;
        }
        .subhead strong{
            display:block;
            font-size: 14px;
            font-weight: 900;
            color: var(--ndmu-green);
            letter-spacing: .2px;
        }
        .subhead span{
            display:block;
            font-size: 12px;
            color: rgba(15,23,42,.62);
            margin-top: 2px;
        }

        .label{
            display:block;
            font-size: 13px;
            font-weight: 900;
            color: rgba(15,23,42,.86);
        }
        .req{ color:#b91c1c; font-weight: 900; }

        .input, .select{
            margin-top: 6px;
            width: 100%;
            border-radius: 12px;
            border: 1px solid rgba(15,23,42,.18);
            background: #fff;
            padding: 10px 12px;
            outline: none;
            transition: .15s ease;
        }
        .input:focus, .select:focus{
            border-color: rgba(227,199,122,.95);
            box-shadow: 0 0 0 4px rgba(227,199,122,.22);
        }

        .help{
            font-size: 12px;
            color: rgba(15,23,42,.60);
            margin-top: 6px;
            line-height: 1.5;
        }

        .btn-ndmu{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 900;
            transition: .15s ease;
            white-space: nowrap;
            box-shadow: 0 6px 14px rgba(2,6,23,.06);
        }
        .btn-primary{ background: var(--ndmu-green); color:#fff; }
        .btn-primary:hover{ filter: brightness(.95); }
        .btn-outline{
            background: var(--paper);
            color: var(--ndmu-green);
            border: 1px solid var(--ndmu-gold);
        }
        .btn-outline:hover{ filter: brightness(.98); }
        .btn-danger{
            background:#991B1B;
            color:#fff;
        }
        .btn-danger:hover{ filter: brightness(.95); }

        .soft-note{
            border: 1px solid var(--line);
            background: var(--paper);
            border-radius: 14px;
            padding: 12px;
        }
        .chip{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid rgba(227,199,122,.75);
            background: rgba(227,199,122,.18);
            color: var(--ndmu-green);
            font-size: 12px;
            font-weight: 900;
        }

        .state{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            border: 1px solid var(--line);
            background:#fff;
            color: rgba(15,23,42,.78);
        }
        .dot{ width: 8px; height: 8px; border-radius: 999px; }

        .alert-ok{
            border-color: rgba(134,239,172,.55);
            background: rgba(236,253,245,.85);
            color:#065F46;
        }
        .alert-bad{
            border-color: rgba(239,68,68,.25);
            background: rgba(239,68,68,.07);
            color:#7f1d1d;
        }

        @media (max-width: 640px){
            .strip-top{ padding: 14px 14px; }
        }
    </style>

    <div class="py-10" style="background:var(--page);">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-xl border p-4 alert-ok">
                    <div class="font-extrabold">Success</div>
                    <div class="text-sm mt-1">{{ session('success') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-xl border p-4 alert-bad">
                    <div class="font-extrabold">Please fix the following:</div>
                    <ul class="list-disc ml-5 text-sm mt-2 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Top strip --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div class="min-w-0">
                            <div class="strip-title">User Account Management</div>
                            <div class="strip-sub">Edit account details, toggle status, and manage password resets.</div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            IT Admin Console
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="soft-note flex items-center gap-3">
                        <img src="{{ asset('images/ndmu-logo.png') }}"
                             alt="NDMU Logo"
                             class="h-10 w-10 rounded-full ring-2"
                             style="--tw-ring-color: var(--ndmu-gold);"
                             onerror="this.style.display='none';">
                        <div class="min-w-0">
                            <div class="text-xs font-semibold tracking-wide text-gray-600">
                                NOTRE DAME OF MARBEL UNIVERSITY
                            </div>
                            <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                                Editing: {{ $user->name ?? 'User' }}
                            </div>
                            <div class="help" style="margin-top:4px;">
                                Tip: Keep roles minimal, and disable accounts instead of deleting.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- MAIN: Update Info --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Account Details --}}
                    <div class="section-card">
                        <div class="subhead">
                            <div class="badge">1</div>
                            <div class="min-w-0">
                                <strong>Account Details</strong>
                                <span>Update the user’s name, email, and role assignment.</span>
                            </div>
                        </div>

                        {{-- Current quick info --}}
                        <div class="rounded-xl border p-4"
                             style="border-color: rgba(227,199,122,.75); background: var(--paper);">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">
                                        Current Record
                                    </div>
                                    <div class="mt-1 text-sm text-gray-900 font-semibold break-words">
                                        {!! nl2br(e($user->vertical_name ?? ($user->name ?? ''))) !!}
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1 break-words">
                                        {{ $user->email }} • Role: {{ $user->role_label ?? $user->role }}
                                    </div>
                                </div>

                                <div class="shrink-0 flex flex-wrap gap-2">
                                    <span class="chip">ID: #{{ $user->id }}</span>
                                    <span class="chip">
                                        {{ $user->created_at?->format('M d, Y') ? 'Created '.$user->created_at->format('M d, Y') : 'Created —' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('itadmin.users.update', $user) }}" class="mt-5 space-y-6">
                            @csrf
                            @method('PUT')

                            {{-- Name fields --}}
                            <div>
                                <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">Full Name</div>
                                <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="label">Last Name <span class="req">*</span></label>
                                        <input name="last_name"
                                               value="{{ old('last_name', $user->last_name) }}"
                                               class="input"
                                               required>
                                    </div>

                                    <div>
                                        <label class="label">First Name <span class="req">*</span></label>
                                        <input name="first_name"
                                               value="{{ old('first_name', $user->first_name) }}"
                                               class="input"
                                               required>
                                    </div>

                                    <div>
                                        <label class="label">Middle Name</label>
                                        <input name="middle_name"
                                               value="{{ old('middle_name', $user->middle_name) }}"
                                               class="input"
                                               placeholder="Optional">
                                    </div>
                                </div>
                                <div class="help">
                                    Display name will be updated based on these fields (if your controller rebuilds it).
                                </div>
                            </div>

                            {{-- Email + role --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="label">Email Address <span class="req">*</span></label>
                                    <input type="email" name="email"
                                           value="{{ old('email', $user->email) }}"
                                           class="input"
                                           required>
                                </div>

                                <div>
                                    <label class="label">Role <span class="req">*</span></label>
                                    <select name="role" class="select" required>
                                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="alumni_officer" {{ old('role', $user->role) === 'alumni_officer' ? 'selected' : '' }}>Alumni Officer</option>
                                        <option value="it_admin" {{ old('role', $user->role) === 'it_admin' ? 'selected' : '' }}>IT Admin</option>
                                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                    <div class="help">Assign only necessary access based on responsibility.</div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-1">
                                <button class="btn-ndmu btn-primary w-full sm:w-auto" type="submit">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                {{-- SIDE: Status + Password --}}
                <div class="space-y-6">

                    {{-- Status --}}
                    <div class="section-card">
                        <div class="subhead">
                            <div class="badge">2</div>
                            <div class="min-w-0">
                                <strong>Account Status</strong>
                                <span>Enable/disable access and review password policy.</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <div class="text-sm text-gray-800">
                                <span class="font-extrabold" style="color:var(--ndmu-green);">Status:</span>
                                @if($user->is_active)
                                    <span class="state">
                                        <span class="dot" style="background:#16a34a;"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="state">
                                        <span class="dot" style="background:#dc2626;"></span>
                                        Disabled
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm text-gray-800">
                                <span class="font-extrabold" style="color:var(--ndmu-green);">Password policy:</span>
                                @if($user->must_change_password)
                                    <span class="state">
                                        <span class="dot" style="background:#f59e0b;"></span>
                                        Must change password
                                    </span>
                                @else
                                    <span class="state">
                                        <span class="dot" style="background:#64748b;"></span>
                                        Normal
                                    </span>
                                @endif
                            </div>
                        </div>

                        <form method="POST" action="{{ route('itadmin.users.toggle_active', $user) }}" class="mt-4">
                            @csrf
                            <button class="btn-ndmu btn-outline w-full" type="submit">
                                {{ $user->is_active ? 'Disable Account' : 'Enable Account' }}
                            </button>
                        </form>

                        <div class="help mt-3">
                            Disabling prevents login without deleting history.
                        </div>
                    </div>

                    {{-- Reset Password --}}
                    <div class="section-card">
                        <div class="subhead">
                            <div class="badge">3</div>
                            <div class="min-w-0">
                                <strong>Reset Password</strong>
                                <span>Set a temporary password (or auto-generate).</span>
                            </div>
                        </div>

                        <div class="rounded-xl border p-4"
                             style="border-color: rgba(227,199,122,.75); background: var(--paper);">
                            <div class="text-xs text-gray-700 leading-relaxed">
                                The user will be required to change the password on next login.
                                Share temporary passwords securely.
                            </div>
                        </div>

                        <form method="POST" action="{{ route('itadmin.users.reset_password', $user) }}" class="mt-4 space-y-3">
                            @csrf

                            <div>
                                <label class="label">New Temporary Password</label>
                                <input name="new_password"
                                       class="input"
                                       placeholder="Leave blank to auto-generate">
                                <div class="help">Leave empty if you want the system to generate a strong password.</div>
                            </div>

                            <button class="btn-ndmu btn-primary w-full" type="submit">
                                Reset Password
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
