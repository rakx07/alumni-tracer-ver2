{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    {{-- NDMU accent bar --}}
    <div class="h-1 w-full" style="background:#E3C77A;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Left: Brand + Links --}}
            <div class="flex items-center gap-8">
                {{-- Brand --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="flex items-center gap-3 group">
                        <img
                            src="{{ asset('images/ndmu-logo.png') }}"
                            alt="NDMU"
                            class="h-10 w-10 rounded-full ring-2 ring-[#E3C77A] bg-white object-contain"
                            onerror="this.style.display='none';"
                        />
                        <div class="leading-tight">
                            <div class="text-xs font-semibold tracking-wide text-gray-500 group-hover:text-gray-700">
                                NOTRE DAME OF MARBEL UNIVERSITY
                            </div>
                            <div class="text-lg font-extrabold" style="color:#0B3D2E;">
                                Alumni Tracer Portal
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Desktop Links --}}
                <div class="hidden lg:flex items-center gap-2">
                    {{-- Public link --}}
                   

                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                            class="rounded-full px-4 py-2 text-sm font-semibold
                                   border border-transparent
                                   hover:border-[#0B3D2E]/20 hover:bg-[#0B3D2E]/5">
                            Dashboard
                        </x-nav-link>

                        @php $role = auth()->user()?->role; @endphp

                        {{-- USER only --}}
                        @if($role === 'user')
                            <x-nav-link :href="route('intake.form')" :active="request()->routeIs('intake.*')"
                                class="rounded-full px-4 py-2 text-sm font-semibold
                                       border border-transparent
                                       hover:border-[#0B3D2E]/20 hover:bg-[#0B3D2E]/5">
                                Intake Form
                            </x-nav-link>
                        @endif

                        {{-- Alumni Officer / IT Admin --}}
                        @if(in_array($role, ['alumni_officer','it_admin'], true))
                            <x-nav-link :href="route('portal.records.index')" :active="request()->routeIs('portal.records.*')"
                                class="rounded-full px-4 py-2 text-sm font-semibold
                                       border border-transparent
                                       hover:border-[#0B3D2E]/20 hover:bg-[#0B3D2E]/5">
                                Manage Records
                            </x-nav-link>
                        @endif

                        {{-- Events management --}}
                        @if(in_array($role, ['alumni_officer','it_admin'], true))
                            <x-nav-link :href="route('portal.events.index')" :active="request()->routeIs('portal.events.*')"
                                class="rounded-full px-4 py-2 text-sm font-semibold
                                       border border-transparent
                                       hover:border-[#0B3D2E]/20 hover:bg-[#0B3D2E]/5">
                                Manage Events
                            </x-nav-link>
                        @endif

                        {{-- Alumni Encoding --}}
                        @if(in_array($role, ['alumni_officer','it_admin','admin'], true))
                            <x-nav-link :href="route('portal.alumni_encoding.index')" :active="request()->routeIs('portal.alumni_encoding.*')"
                                class="rounded-full px-4 py-2 text-sm font-semibold
                                       border border-transparent
                                       hover:border-[#0B3D2E]/20 hover:bg-[#0B3D2E]/5">
                                Encode Alumni
                            </x-nav-link>
                        @endif
                    @endauth
                     <x-nav-link :href="route('events.calendar')" :active="request()->routeIs('events.calendar')"
                        class="rounded-full px-4 py-2 text-sm font-semibold
                               border border-transparent
                               hover:border-[#0B3D2E]/20 hover:bg-[#0B3D2E]/5">
                        Events Calendar
                    </x-nav-link>
                    <x-nav-link :href="route('careers.index')" :active="request()->routeIs('careers.*')"
                        class="rounded-full px-4 py-2 text-sm font-semibold
                            border border-transparent
                            hover:border-[#0B3D2E]/20 hover:bg-[#0B3D2E]/5">
                        Careers
                    </x-nav-link>

                </div>
            </div>

            {{-- Right side --}}
            <div class="hidden sm:flex sm:items-center gap-3">
                @guest
                    <div class="flex items-center gap-2">
                        @if(Route::has('login'))
                            <a href="{{ route('login') }}"
                               class="px-4 py-2 rounded-lg text-sm font-semibold text-white shadow-sm"
                               style="background:#0B3D2E;">
                                Login
                            </a>
                        @endif
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-4 py-2 rounded-lg text-sm font-semibold border shadow-sm"
                               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                                Register
                            </a>
                        @endif
                    </div>
                @endguest

                @auth
                    {{-- little role badge (optional but NDMU-ish) --}}
                    <div class="hidden md:flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold border"
                         style="border-color:#E3C77A; background:#FFFBF0; color:#0B3D2E;">
                        <span class="inline-block h-2 w-2 rounded-full" style="background:#0B3D2E;"></span>
                        {{ strtoupper(str_replace('_',' ', auth()->user()->role)) }}
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold
                                       border shadow-sm transition hover:shadow"
                                style="border-color:#E3C77A; background:#FFFBF0; color:#0B3D2E;">
                                <span class="max-w-[140px] truncate">{{ auth()->user()->name }}</span>
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Account Settings
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            {{-- Hamburger --}}
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-lg border transition"
                        style="border-color:#E3C77A; background:#FFFBF0; color:#0B3D2E;">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Drawer --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden lg:hidden border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 space-y-2 bg-white">
            <div class="text-xs font-semibold tracking-wide text-gray-500">
                MENU
            </div>

            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-responsive-nav-link>

                @php $role = auth()->user()?->role; @endphp

                @if($role === 'user')
                    <x-responsive-nav-link :href="route('intake.form')" :active="request()->routeIs('intake.*')">
                        Intake Form
                    </x-responsive-nav-link>
                @endif

                @if(in_array($role, ['alumni_officer','it_admin'], true))
                    <x-responsive-nav-link :href="route('portal.records.index')" :active="request()->routeIs('portal.records.*')">
                        Manage Records
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('portal.events.index')" :active="request()->routeIs('portal.events.*')">
                        Manage Events
                    </x-responsive-nav-link>
                @endif

                @if(in_array($role, ['alumni_officer','it_admin','admin'], true))
                    <x-responsive-nav-link :href="route('portal.alumni_encoding.index')" :active="request()->routeIs('portal.alumni_encoding.*')">
                        Encode Alumni
                    </x-responsive-nav-link>
                @endif
            @endauth
             <x-responsive-nav-link :href="route('events.calendar')" :active="request()->routeIs('events.calendar')">
                Events Calendar
            </x-responsive-nav-link>
        </div>

        <div class="border-t border-gray-200 bg-[#FFFBF0]">
            @auth
                <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3">
                    <div class="font-semibold" style="color:#0B3D2E;">{{ auth()->user()->name }}</div>
                    <div class="text-sm text-gray-600">{{ auth()->user()->email }}</div>
                    <div class="mt-1 text-xs font-semibold inline-flex items-center gap-2 px-2 py-1 rounded-full border"
                         style="border-color:#E3C77A; color:#0B3D2E;">
                        Role: {{ strtoupper(str_replace('_',' ', auth()->user()->role)) }}
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            Account Settings
                        </x-responsive-nav-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            @endauth

            @guest
                <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex gap-2">
                    @if(Route::has('login'))
                        <a href="{{ route('login') }}"
                           class="flex-1 text-center px-4 py-2 rounded-lg text-sm font-semibold text-white"
                           style="background:#0B3D2E;">
                            Login
                        </a>
                    @endif
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="flex-1 text-center px-4 py-2 rounded-lg text-sm font-semibold border"
                           style="border-color:#E3C77A; color:#0B3D2E; background:white;">
                            Register
                        </a>
                    @endif
                </div>
            @endguest
        </div>
    </div>
</nav>
