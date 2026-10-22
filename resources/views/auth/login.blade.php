<x-guest-layout>
    <!-- 🌟 Branding & Logo Section (Injects into Breeze's guest layout) 🌟 -->
    <x-slot name="logo">
        <div class="flex flex-col items-center mb-2">
            <!-- Biometric / Fingerprint Icon -->
            <div class="w-16 h-16 bg-gradient-to-tr from-blue-600 to-blue-400 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-blue-500/30">
                <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">BIOMETRIC <span class="text-blue-600">PORTAL</span></h1>
        </div>
    </x-slot>

    <!-- Welcome Text -->
    <div class="text-center mb-8">
        <h2 class="text-lg font-bold text-slate-700">Welcome Back</h2>
        <p class="text-sm text-slate-500 mt-1">Enter your assigned Employee ID to access your dashboard.</p>
    </div>

    <!-- Session Status (Success messages, etc.) -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- The Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Company ID Input -->
        <div>
            <label for="company_id" class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Company ID</label>
            <div class="relative">
                <!-- Input Icon -->
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                </div>
                
                <!-- The Actual Input (Keeps the uppercase typing effect) -->
                <input id="company_id" 
                       type="text" 
                       name="company_id" 
                       value="{{ old('company_id') }}" 
                       required 
                       autofocus 
                       autocomplete="username"
                       placeholder="e.g. AD1234"
                       class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-bold uppercase tracking-wider focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all shadow-sm" />
            </div>
            
            <!-- Error Message Display -->
            <x-input-error :messages="$errors->get('company_id')" class="mt-2 font-medium" />
        </div>
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer">
                <span class="ml-2 text-sm font-medium text-slate-600 group-hover:text-slate-900 transition-colors">Keep me logged in</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md shadow-blue-500/20 font-bold text-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                Secure Login
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </button>
        </div>
    </form>
</x-guest-layout>