{{-- resources/views/itadmin/users/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
                    Create User Account
                </h2>
                <div class="text-sm text-gray-600">
                    IT Admin — Add a portal user and assign an access role.
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
            --text:#0f172a;
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

        .soft-note{
            border: 1px solid var(--line);
            background: var(--paper);
            border-radius: 14px;
            padding: 12px;
        }
        .side-card{
            border: 1px solid var(--line);
            border-radius: 18px;
            background:#fff;
            padding: 16px;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
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

        .callout{
            border: 1px solid rgba(227,199,122,.75);
            background: var(--paper);
            border-radius: 16px;
            padding: 14px;
        }

        @media (max-width: 640px){
            .strip-top{ padding: 14px 14px; }
        }
    </style>

    <div class="py-10" style="background:var(--page);">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Errors --}}
            @if ($errors->any())
                <div class="rounded-xl border p-4"
                     style="border-color: rgba(239,68,68,.25); background: rgba(239,68,68,.07);">
                    <div class="font-extrabold text-red-900">Please fix the following:</div>
                    <ul class="list-disc ml-5 text-sm text-red-900 mt-2">
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
                            <div class="strip-title">User Account Creation</div>
                            <div class="strip-sub">Create accounts for Officers/Admins and assign the correct access level.</div>
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
                                Create New Portal User
                            </div>
                            <div class="help" style="margin-top:4px;">
                                Tip: Use official emails; assign the minimum role needed.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main Form --}}
                <div class="lg:col-span-2">
                    <form method="POST" action="{{ route('itadmin.users.store') }}" class="space-y-6">
                        @csrf

                        {{-- 1) Name --}}
                        <div class="section-card">
                            <div class="subhead">
                                <div class="badge">1</div>
                                <div class="min-w-0">
                                    <strong>User Information</strong>
                                    <span>Enter the user’s name details and email address.</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="label">Last Name <span class="req">*</span></label>
                                    <input name="last_name" value="{{ old('last_name') }}"
                                           class="input"
                                           placeholder="e.g., Dela Cruz"
                                           required>
                                </div>

                                <div>
                                    <label class="label">First Name <span class="req">*</span></label>
                                    <input name="first_name" value="{{ old('first_name') }}"
                                           class="input"
                                           placeholder="e.g., Juan"
                                           required>
                                </div>

                                <div>
                                    <label class="label">Middle Name</label>
                                    <input name="middle_name" value="{{ old('middle_name') }}"
                                           class="input"
                                           placeholder="Optional">
                                </div>
                            </div>

                            <div class="help">
                                The system will automatically build the display name from Last / First / Middle.
                            </div>

                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="label">Email Address <span class="req">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           class="input"
                                           placeholder="name@ndmu.edu.ph"
                                           required>
                                    <div class="help">Prefer official university email whenever possible.</div>
                                </div>

                                <div>
                                    <label class="label">Role <span class="req">*</span></label>
                                    <select name="role" class="select" required>
                                        <option value="">— Select Role —</option>
                                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="alumni_officer" {{ old('role') === 'alumni_officer' ? 'selected' : '' }}>Alumni Officer</option>
                                        <option value="it_admin" {{ old('role') === 'it_admin' ? 'selected' : '' }}>IT Admin</option>
                                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                    <div class="help">Assign only what is needed for the user’s responsibility.</div>
                                </div>
                            </div>
                        </div>

                        {{-- 2) Temporary Password --}}
                        <div class="section-card">
                            <div class="subhead">
                                <div class="badge">2</div>
                                <div class="min-w-0">
                                    <strong>Temporary Password</strong>
                                    <span>Optional. Leave blank to auto-generate a secure temporary password.</span>
                                </div>
                            </div>

                            <div class="callout">
                                <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">Important</div>
                                <div class="text-xs text-gray-700 mt-1 leading-relaxed">
                                    Users created by IT Admin will be required to change the password upon first login.
                                    Share temporary passwords securely (avoid public group chats).
                                </div>

                                <div class="mt-3">
                                    <label class="label">Set Temporary Password</label>
                                    <input name="temp_password"
                                           class="input"
                                           placeholder="Leave blank to auto-generate">
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                            <a href="{{ route('itadmin.users.index') }}" class="btn-ndmu btn-outline w-full sm:w-auto">
                                Cancel
                            </a>
                            <button class="btn-ndmu btn-primary w-full sm:w-auto" type="submit">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Side Guidance --}}
                <div class="space-y-4">
                    <div class="side-card">
                        <div class="flex items-center justify-between gap-3">
                            <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">Guidelines</div>
                            <span class="chip">IT Admin</span>
                        </div>

                        <ul class="mt-4 text-sm text-gray-700 space-y-3">
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full" style="background:var(--ndmu-gold);"></span>
                                Use an official email address whenever possible.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full" style="background:var(--ndmu-gold);"></span>
                                Assign only necessary access based on role and responsibility.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full" style="background:var(--ndmu-gold);"></span>
                                Temporary passwords should be shared securely (avoid public messages).
                            </li>
                        </ul>
                    </div>

                    <div class="side-card" style="background:#fff;">
                        <div class="text-sm font-extrabold" style="color:var(--ndmu-green);">Data Privacy</div>
                        <div class="text-xs text-gray-700 mt-2 leading-relaxed">
                            User accounts provide access to sensitive alumni information.
                            Ensure account creation complies with NDMU data protection guidelines.
                        </div>

                        <div class="mt-4 callout" style="background:#F6F2E6;">
                            <div class="text-xs font-semibold text-gray-800">
                                Best practice
                            </div>
                            <div class="text-xs text-gray-700 mt-1 leading-relaxed">
                                Keep role assignments minimal and review access when staff roles change.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
