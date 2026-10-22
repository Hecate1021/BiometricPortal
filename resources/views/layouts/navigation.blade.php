<nav x-data="{ open: false }" class="bg-white border-b border-slate-100 shadow-sm sticky top-0 z-50">
    
    @php
        // 🌟 THE BULLETPROOF ROLE CHECK 🌟
        // Forces lowercase and removes accidental spaces (e.g., " Admin " becomes "admin")
        $role = Auth::check() ? strtolower(trim(Auth::user()->role)) : 'guest';
    @endphp

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- ========================================== -->
            <!-- LEFT SIDE: LOGO & DESKTOP LINKS -->
            <!-- ========================================== -->
            <div class="flex items-center space-x-10">
                
                <!-- Brand / Logo -->
                <a href="{{ route('dtr.index') }}" class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2 shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>HR<span class="text-blue-600">Portal</span></span>
                    
                    <!-- DEBUG BADGE: Tells you exactly what the database says! -->
                    <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full uppercase tracking-wider ml-2">
                        ROLE: {{ $role }}
                    </span>
                </a>
                
                <!-- Desktop Navigation Links (Hides on Mobile) -->
                <div class="hidden sm:flex space-x-6 text-sm font-bold items-center">
                    
                    <!-- 🟢 EVERYONE (HR & Admin) -->
                    <a href="{{ route('dtr.index') }}" class="{{ request()->routeIs('dtr.index', 'dtr.show') ? 'text-blue-700' : 'text-slate-500 hover:text-blue-600' }} transition-colors">
                        DTR Records
                    </a>
                    
                    <a href="{{ route('dtr.live') }}" class="{{ request()->routeIs('dtr.live') ? 'text-blue-700' : 'text-slate-500 hover:text-blue-600' }} transition-colors">
                        Live Punch
                    </a>
                    
                    <!-- 🟠 HR ENCODER (HR & Admin) -->
                    @if(in_array($role, ['hr', 'admin']))
                        <a href="{{ route('hr.dashboard') }}" class="{{ request()->routeIs('hr.dashboard') ? 'text-blue-700' : 'text-slate-500 hover:text-blue-600' }} transition-colors">
                            HR Encoder
                        </a>
                    @endif
                    
                    <!-- 🔴 SYSTEM TOOLS (Strictly ADMIN ONLY) -->
                    @if($role === 'admin')
                        <a href="{{ route('device.status') }}" class="{{ request()->routeIs('device.status') ? 'text-blue-700' : 'text-slate-500 hover:text-blue-600' }} transition-colors">
                            Device
                        </a>
                        
                        <a href="{{ route('dtr.upload_form') }}" class="{{ request()->routeIs('dtr.upload_form') ? 'text-blue-700' : 'text-slate-500 hover:text-blue-600' }} transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Sync Data
                        </a>
                    @endif
                    
                </div>
            </div>

            <!-- ========================================== -->
            <!-- RIGHT SIDE: USER PROFILE DROPDOWN -->
            <!-- ========================================== -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-500 bg-white hover:text-slate-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- ========================================== -->
            <!-- HAMBURGER BUTTON (MOBILE) -->
            <!-- ========================================== -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 focus:text-slate-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- RESPONSIVE MOBILE MENU -->
    <!-- ========================================== -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-50 border-b border-slate-200">
        <div class="pt-2 pb-3 space-y-1">
            
            <x-responsive-nav-link :href="route('dtr.index')" :active="request()->routeIs('dtr.index')">
                DTR Records
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('dtr.live')" :active="request()->routeIs('dtr.live')">
                Live Punch
            </x-responsive-nav-link>

            @if(in_array($role, ['hr', 'admin']))
                <x-responsive-nav-link :href="route('hr.dashboard')" :active="request()->routeIs('hr.dashboard')">
                    HR Encoder
                </x-responsive-nav-link>
            @endif

            @if($role === 'admin')
                <x-responsive-nav-link :href="route('device.status')" :active="request()->routeIs('device.status')">
                    Device Monitor
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('dtr.upload_form')" :active="request()->routeIs('dtr.upload_form')">
                    Sync Data (USB Upload)
                </x-responsive-nav-link>
            @endif

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-200 bg-white">
            <div class="px-4">
                <div class="font-medium text-base text-slate-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>