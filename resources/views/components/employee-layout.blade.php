<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .h-screen-dynamic {
            height: 100vh;
            height: 100dvh;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased overflow-hidden">

    <!-- Fetch User Role Safely Once -->
    @php
        $role = auth()->check() ? strtolower(trim(auth()->user()->role ?? '')) : '';
    @endphp

    <div class="flex h-screen-dynamic w-full">

        <!-- Mobile Header -->
        <div class="md:hidden fixed top-0 w-full bg-white border-b border-slate-200 z-50 flex items-center justify-between px-4 h-16 shadow-sm">
            <a href="#" class="flex items-center gap-2.5">
                <img src="{{ asset('images/Logo.png') }}" alt="Lake Sebu Logo" class="w-8 h-8 object-contain drop-shadow-sm">
                <div class="flex flex-col justify-center">
                    <span class="text-sm font-black text-slate-800 leading-none tracking-tight">LAKE SEBU</span>
                    <span class="text-[9px] font-bold text-blue-600 leading-none tracking-wider mt-0.5">BIOMETRIC PORTAL</span>
                </div>
            </a>
            <button id="mobile-menu-btn" class="p-2 text-slate-500 hover:text-blue-600 focus:outline-none">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900 bg-opacity-50 z-40 hidden md:hidden"></div>

        <!-- THE SIDEBAR -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out md:static flex flex-col shadow-xl md:shadow-none">

            <!-- Logo Section -->
            <div class="h-20 flex items-center justify-center border-b border-slate-100 hidden md:flex shrink-0 w-full">
                <a href="#" class="flex items-center gap-3">
                    <img src="{{ asset('images/Logo.png') }}" alt="Lake Sebu Logo" class="w-10 h-10 object-contain drop-shadow-sm">
                    <div class="flex flex-col justify-center text-left">
                        <span class="text-lg font-black text-slate-800 leading-none tracking-tight">LAKE SEBU</span>
                        <span class="text-[10px] font-bold text-blue-600 leading-none tracking-widest mt-1">BIOMETRIC PORTAL</span>
                    </div>
                </a>
            </div>

            <!-- Profile Info -->
            @if(auth()->check())
            <div class="px-8 py-8 border-b border-slate-100 flex flex-col items-center text-center mt-16 md:mt-0 shrink-0">
                <div class="h-20 w-20 rounded-full bg-gradient-to-tr from-blue-100 to-blue-50 border-4 border-white shadow-sm flex items-center justify-center text-blue-600 font-black text-2xl mb-4">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <h2 class="font-bold text-slate-800 text-lg leading-tight">{{ auth()->user()->name }}</h2>
                <p class="text-xs font-mono text-slate-500 mt-1 px-3 py-1 bg-slate-100 rounded-full border border-slate-200">ID: {{ auth()->user()->company_id ?? 'Pending' }}</p>
            </div>
            @endif

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto min-h-0">

                <!-- 🌟 ADMIN EXCLUSIVE SECTION 🌟 -->
                @if($role === 'admin')
                    <div class="pb-4 mb-4 border-b border-slate-200 space-y-2">
                        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">System Administrator</p>

                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Dashboard
                        </a>

                        <a href="{{ route('holidays.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('holidays.*') ? 'bg-slate-900 text-white font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                            Holidays & Suspensions
                        </a>

                        <a href="{{ route('hr.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('hr.dashboard') ? 'bg-slate-900 text-white font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            HR Encoder
                        </a>

                        <a href="{{ route('dtr.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dtr.index') ? 'bg-slate-900 text-white font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            All DTR Records
                        </a>

                        <a href="{{ route('employees.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('employees.*') ? 'bg-slate-900 text-white font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Employee Masterlist
                        </a>

                        <a href="{{ route('departments.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('departments.*') ? 'bg-slate-900 text-white font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Departments & Shifts
                        </a>

                        <a href="{{ route('dtr.live') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dtr.live') ? 'bg-slate-900 text-white font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <span class="relative flex h-3 w-3 ml-1 mr-1">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                            </span>
                            Live Punch
                        </a>

                        <a href="{{ route('queue.monitor') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('queue.monitor') ? 'bg-slate-900 text-white font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
    <!-- Database / Server Icon -->
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
    </svg>
    Queue Monitor
</a>

                        <a href="{{ route('dtr.upload_form') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dtr.upload_form') ? 'bg-slate-900 text-white font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Sync Data Device
                        </a>

                        <a href="{{ route('device.status') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('device.status') ? 'bg-slate-900 text-white font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            Network Status
                        </a>
                    </div>
                @endif
                <!-- 🌟 MAYOR EXCLUSIVE SECTION 🌟 -->
                @if($role === 'mayor')
                    <div class="pb-4 mb-4 border-b border-slate-200 space-y-2">
                        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Office of the Mayor</p>

                        <a href="{{ route('mayor.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('mayor.dashboard') ? 'bg-slate-800 text-white font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Final Approvals
                        </a>

                        <a href="{{ route('dtr.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dtr.index') ? 'bg-slate-800 text-white font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Employee DTRs
                        </a>
                    </div>
                @endif

                <!-- 🌟 HRMO EXCLUSIVE SECTION 🌟 -->
                @if($role === 'hrmo')
                    <div class="pb-4 mb-4 border-b border-slate-200 space-y-2">
                        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">HRMO Management</p>

                        <a href="{{ route('hrmo.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('hrmo.dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Approvals Portal
                        </a>

                        <a href="{{ route('dtr.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dtr.index') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Employee DTRs
                        </a>
                    </div>
                @endif

                <!-- 🌟 HR EXCLUSIVE SECTION 🌟 -->
                @if($role === 'hr')
                    <div class="pb-4 mb-4 border-b border-slate-200 space-y-2">
                        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Human Resources</p>

                        <a href="{{ route('hr.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('hr.dashboard') ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            Approved Records
                        </a>

                        <a href="{{ route('dtr.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dtr.index') ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Employee DTRs
                        </a>
                    </div>
                @endif

                <!-- 🌟 DEPARTMENT HEAD SECTION (Visible to Dept Head & HRMO) 🌟 -->
                @if(in_array($role, ['department_head', 'hrmo']))
                    <div class="pb-4 mb-4 border-b border-slate-200 space-y-2">
                        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Department Head</p>

                        <a href="{{ route('dept_head.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dept_head.dashboard') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Dept Head Approvals
                        </a>

                        <a href="{{ route('dept_head.staff') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dept_head.staff') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Department Staff
                        </a>
                    </div>
                @endif

                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2 mt-2">Personal Tools</p>

                <!-- Request Desk (Hidden for Mayor & Admin) -->
                @if(!in_array($role, ['mayor', 'admin']))
                    <a href="{{ route('employee.forms.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('employee.forms.index') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Request Desk
                    </a>
                @endif

                <!-- My DTR (Hidden for Admin) -->
                @if($role !== 'admin' && auth()->check())
                    @php
                        $empRecord = \App\Models\Employee::where('company_id', auth()->user()->company_id)->first();
                    @endphp
                    @if($empRecord)
                        <a href="{{ route('dtr.show', $empRecord->id) }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dtr.show') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-semibold' }} rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            My DTR
                        </a>
                    @endif
                @endif

            </nav>

            <!-- Logout Button -->
            <div class="p-6 pb-24 md:pb-6 border-t border-slate-100 shrink-0 bg-white z-10 relative">
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white border border-slate-200 text-slate-600 hover:bg-rose-50 hover:border-rose-200 hover:text-rose-600 rounded-xl font-bold transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 overflow-y-auto h-full pt-16 md:pt-0 bg-slate-50">
            {{ $slot }}
        </main>
    </div>

    <!-- Layout Scripts (Sidebar Toggle) -->
    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        if(btn) btn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>
