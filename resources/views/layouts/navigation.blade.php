<nav x-data="{ open: false }" class="border-b" style="background:#ffffff; border-color:#EDE7D1;">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT: Brand + links --}}
            <div class="flex">
                <!-- Logo / Brand -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center h-9 w-9 rounded-lg text-white font-bold"
                              style="background:#0B3D2E;">
                            N
                        </span>
                        <div class="leading-tight">
                            <div class="font-bold text-gray-900">NDMU Alumni Tracer</div>
                            <div class="text-[11px] text-gray-500 -mt-0.5">Portal</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-8 sm:flex">

                    {{-- Dashboard --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    {{-- USER only --}}
                    @if(auth()->user()->role === 'user')
                        <x-nav-link :href="route('intake.form')" :active="request()->routeIs('intake.*')">
                            Intake Form
                        </x-nav-link>
                    @endif

                    {{-- Alumni Officer + IT Admin --}}
                    @if(in_array(auth()->user()->role, ['alumni_officer','it_admin','admin']))
                        <x-nav-link :href="route('portal.records.index')" :active="request()->routeIs('portal.records.*')">
                            Manage Records
                        </x-nav-link>
                    @endif

                    {{-- IT Admin only --}}
                    @if(auth()->user()->role === 'it_admin')
                        <x-nav-link :href="route('itadmin.users.index')" :active="request()->routeIs('itadmin.users.*')">
                            User Management
                        </x-nav-link>
                    @endif

                </div>
            </div>

            {{-- RIGHT: User dropdown --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @php
                    $u = auth()->user();
                    $roleLabel = $u->role_label ?? match(($u->role ?? 'user')) {
                        'admin' => 'Admin',
                        'alumni_officer' => 'Alumni Officer',
                        'it_admin' => 'IT Admin',
                        default => 'User',
                    };
                    $rolePill = match(($u->role ?? 'user')) {
                        'it_admin' => 'background:rgba(11,61,46,.10); border:1px solid rgba(11,61,46,.18); color:#0B3D2E;',
                        'admin' => 'background:rgba(227,199,122,.20); border:1px solid rgba(227,199,122,.45); color:#0B3D2E;',
                        'alumni_officer' => 'background:rgba(79,70,229,.10); border:1px solid rgba(79,70,229,.18); color:#1F2A7A;',
                        default => 'background:rgba(148,163,184,.18); border:1px solid rgba(148,163,184,.35); color:#334155;',
                    };
                @endphp

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-3 px-3 py-2 rounded-lg border text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition"
                            style="border-color:#EDE7D1;"
                        >
                            <div class="text-left">
                                <div class="flex items-center gap-2">
                                    <div class="font-semibold text-gray-900 leading-tight">
                                        {{ $u->name }}
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold"
                                          style="{{ $rolePill }}">
                                        {{ $roleLabel }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 leading-tight">
                                    {{ $u->email }}
                                </div>
                            </div>

                            <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Profile / Account settings (only show if route exists) --}}
                        @if(Route::has('profile.edit'))
                            <x-dropdown-link :href="route('profile.edit')">
                                Account Settings
                            </x-dropdown-link>
                        @endif

                        {{-- IT Admin quick link --}}
                        @if(auth()->user()->role === 'it_admin')
                            <x-dropdown-link :href="route('itadmin.users.index')">
                                User Management
                            </x-dropdown-link>
                        @endif

                        <div class="border-t my-1" style="border-color:#EDE7D1;"></div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t" style="border-color:#EDE7D1;">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>

            @if(auth()->user()->role === 'user')
                <x-responsive-nav-link :href="route('intake.form')" :active="request()->routeIs('intake.*')">
                    Intake Form
                </x-responsive-nav-link>
            @endif

            @if(in_array(auth()->user()->role, ['alumni_officer','it_admin','admin']))
                <x-responsive-nav-link :href="route('portal.records.index')" :active="request()->routeIs('portal.records.*')">
                    Manage Records
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->role === 'it_admin')
                <x-responsive-nav-link :href="route('itadmin.users.index')" :active="request()->routeIs('itadmin.users.*')">
                    User Management
                </x-responsive-nav-link>
            @endif

            @if(Route::has('profile.edit'))
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                    Account Settings
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t" style="border-color:#EDE7D1;">
            <div class="px-4">
                <div class="font-semibold text-base text-gray-900">{{ auth()->user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                <div class="mt-1 text-xs inline-flex items-center px-2 py-0.5 rounded-full font-semibold"
                     style="{{ $rolePill }}">
                    Role: {{ $roleLabel }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
