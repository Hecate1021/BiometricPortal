<x-employee-layout>
    
    <!-- Auto-refresh page every 10 seconds for live monitoring -->
    <script>
        setTimeout(function(){
            window.location.reload(1);
        }, 10000);
    </script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-800">Biometric Terminals</h1>
                <p class="text-slate-500 mt-1">Live connection monitor for all network devices.</p>
            </div>
            <div class="text-sm font-bold text-slate-600 bg-white px-4 py-2 rounded-lg shadow-sm border border-slate-200">
                Total Devices: <span class="text-blue-600">{{ count($devices) }}</span>
            </div>
        </div>

        <!-- Devices List -->
        @if(empty($devices))
            <div class="bg-white rounded-2xl shadow-sm p-8 md:p-12 text-center border border-slate-200 border-t-4 border-t-amber-400">
                <svg class="w-12 h-12 text-amber-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h2 class="text-xl font-black text-slate-800">No Devices Found</h2>
                <p class="text-slate-500 mt-2 font-medium">Waiting for terminals to ping the server...</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($devices as $device)
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-shadow border border-slate-200">
                        
                        <!-- Status Bar -->
                        <div class="p-4 {{ $device['is_online'] ? 'bg-emerald-500' : 'bg-rose-500' }} flex items-center justify-between text-white">
                            <div class="flex items-center gap-2">
                                @if($device['is_online'])
                                    <span class="relative flex h-3 w-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-200"></span>
                                    </span>
                                    <span class="font-black text-sm tracking-wide uppercase">Online</span>
                                @else
                                    <span class="h-3 w-3 rounded-full bg-rose-200"></span>
                                    <span class="font-black text-sm tracking-wide uppercase">Offline</span>
                                @endif
                            </div>
                            <span class="text-xs font-bold opacity-90">{{ \Carbon\Carbon::parse($device['last_seen'])->diffForHumans() }}</span>
                        </div>

                        <!-- Device Details -->
                        <div class="p-5 space-y-4">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Serial Number</p>
                                <p class="text-lg font-black text-slate-800">{{ $device['sn'] }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-0.5">IP Address</p>
                                <p class="text-sm font-mono font-bold text-slate-600 bg-slate-50 px-3 py-1.5 rounded-lg inline-block border border-slate-100">{{ $device['ip'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-employee-layout>