<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <span class="font-bold text-lg text-gray-800">Alumni Tracer</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- Public link (works for guests and logged-in) --}}
                    <x-nav-link :href="route('events.calendar')" :active="request()->routeIs('events.calendar')">
                        Events Calendar
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-nav-link>

                        @php $role = auth()->user()?->role; @endphp

                        {{-- USER only --}}
                        @if($role === 'user')
                            <x-nav-link :href="route('intake.form')" :active="request()->routeIs('intake.*')">
                                Intake Form
                            </x-nav-link>
                        @endif

                        {{-- Alumni Officer / IT Admin --}}
                        @if(in_array($role, ['alumni_officer','it_admin'], true))
                            <x-nav-link :href="route('portal.records.index')" :active="request()->routeIs('portal.records.*')">
                                Manage Records
                            </x-nav-link>
                        @endif

                        {{-- Events management (officer/admin) --}}
                        @if(in_array($role, ['alumni_officer','it_admin'], true))
                            <x-nav-link :href="route('portal.events.index')" :active="request()->routeIs('portal.events.*')">
                                Manage Events
                            </x-nav-link>
                        @endif

                        {{-- Alumni Encoding (officer / IT admin / admin) --}}
                        @if(in_array($role, ['alumni_officer','it_admin','admin'], true))
                            <x-nav-link :href="route('portal.alumni_encoding.index')"
                                        :active="request()->routeIs('portal.alumni_encoding.*')">
                                Encode Alumni
                            </x-nav-link>
                        @endif

                    @endauth
                </div>
            </div>

            {{-- Right side --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @guest
                    <div class="flex items-center gap-2">
                        @if(Route::has('login'))
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded bg-gray-900 text-white text-sm">
                                Login
                            </a>
                        @endif
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded border text-sm">
                                Register
                            </a>
                        @endif
                    </div>
                @endguest

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                <div>{{ auth()->user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
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
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Responsive --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('events.calendar')" :active="request()->routeIs('events.calendar')">
                Events Calendar
            </x-responsive-nav-link>

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
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                    <div class="mt-1 text-xs text-gray-500">Role: {{ auth()->user()->role }}</div>
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
            @endauth

            @guest
                <div class="px-4 py-3 flex gap-2">
                    @if(Route::has('login'))
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded bg-gray-900 text-white text-sm">Login</a>
                    @endif
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded border text-sm">Register</a>
                    @endif
                </div>
            @endguest
        </div>
    </div>
</nav>
