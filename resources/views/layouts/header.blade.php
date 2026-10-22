<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>

@php
    // 🌟 BULLETPROOF ROLE CHECK (Forces lowercase, removes spaces) 🌟
    $role = Auth::check() ? strtolower(trim(Auth::user()->role)) : 'guest';
@endphp

<nav class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
            
            <!-- Left Side: Brand & Links -->
            <div class="flex items-center space-x-8 lg:space-x-10">
                
                <!-- Brand / Logo -->
                <a href="{{ route('dtr.index') }}" class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>HR<span class="text-blue-600">Portal</span></span>
                </a>
                
                <!-- Desktop & Tablet Navigation Links (Hidden on Mobile) -->
                <div class="hidden md:flex items-center space-x-4 lg:space-x-6 text-sm font-bold">
                    
                    <a href="{{ route('dtr.index') }}" class="{{ request()->routeIs('dtr.index', 'dtr.show') ? 'text-blue-700' : 'text-slate-500 hover:text-blue-600' }} transition-colors">DTR Records</a>
                    <a href="{{ route('dtr.live') }}" class="{{ request()->routeIs('dtr.live') ? 'text-blue-700' : 'text-slate-500 hover:text-blue-600' }} transition-colors">Live Punch</a>
                    
                    @if(in_array($role, ['hr', 'admin']))
                        <a href="{{ route('hr.dashboard') }}" class="{{ request()->routeIs('hr.dashboard') ? 'text-blue-700' : 'text-slate-500 hover:text-blue-600' }} transition-colors">HR Encoder</a>
                    @endif

                    @if($role === 'admin')
                        <a href="{{ route('device.status') }}" class="{{ request()->routeIs('device.status') ? 'text-blue-700' : 'text-slate-500 hover:text-blue-600' }} transition-colors">Device</a>
                        
                        <a href="{{ route('dtr.upload_form') }}" class="{{ request()->routeIs('dtr.upload_form') ? 'text-blue-700' : 'text-slate-500 hover:text-blue-600' }} transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Sync Data
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right Side: User Name & Logout (Hidden on Mobile) -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <!-- User Name Pill -->
                    <div class="flex items-center text-sm font-bold text-slate-700 bg-slate-50 px-4 py-2 rounded-full border border-slate-200">
                        <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="capitalize">{{ Auth::user()->name }}</span>
                    </div>

                    <!-- Secure Sign Out Button -->
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="text-sm bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold py-2 px-4 rounded-lg transition-all shadow-sm">
                            Sign Out
                        </button>
                    </form>
                @endauth
            </div>

            <!-- Mobile Hamburger Button (Visible ONLY on Mobile) -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-slate-500 hover:text-blue-600 focus:outline-none p-2">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-slate-50 border-t border-slate-200 absolute w-full left-0 shadow-lg">
        <div class="px-4 pt-3 pb-4 space-y-2 font-bold text-slate-600">
            
            <a href="{{ route('dtr.index') }}" class="block px-3 py-2 rounded-md hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('dtr.index', 'dtr.show') ? 'text-blue-700 bg-blue-50' : '' }}">DTR Records</a>
            <a href="{{ route('dtr.live') }}" class="block px-3 py-2 rounded-md hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('dtr.live') ? 'text-blue-700 bg-blue-50' : '' }}">Live Punch</a>
            
            @if(in_array($role, ['hr', 'admin']))
                <a href="{{ route('hr.dashboard') }}" class="block px-3 py-2 rounded-md hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('hr.dashboard') ? 'text-blue-700 bg-blue-50' : '' }}">HR Encoder</a>
            @endif
            
            @if($role === 'admin')
                <a href="{{ route('device.status') }}" class="block px-3 py-2 rounded-md hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('device.status') ? 'text-blue-700 bg-blue-50' : '' }}">Device</a>
                <a href="{{ route('dtr.upload_form') }}" class="block px-3 py-2 rounded-md hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('dtr.upload_form') ? 'text-blue-700 bg-blue-50' : '' }}">Sync Data</a>
            @endif
            
            @auth
                <hr class="my-2 border-slate-200">
                <div class="px-3 py-2 text-sm text-slate-500 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Logged in as <span class="capitalize ml-1 text-slate-700">{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="m-0 block px-3 py-2">
                    @csrf
                    <button type="submit" class="w-full text-left text-red-600 hover:text-red-800 rounded-md">
                        Sign Out
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<!-- JavaScript for Mobile Menu Toggle -->
<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        var menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>