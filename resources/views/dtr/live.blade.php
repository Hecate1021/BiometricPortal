@php
    // Automatically change the Back Button link and text so employees don't get a 403 error
    $backRoute = (auth()->user()->role === 'employee') ? route('employee.forms.index') : route('dtr.index');
    $backText  = (auth()->user()->role === 'employee') ? 'Back to Dashboard' : 'Back to Database';
@endphp

<!-- 🌟 FIXED EMPLOYEE LAYOUT WRAPPER 🌟 -->
<x-employee-layout>
    
    <!-- Responsive padding and vertical spacing -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10 space-y-6 md:space-y-8" 
         x-data="{ 
             logs: [], 
             fetchLogs() {
                 fetch('{{ route('dtr.api.latest_logs') }}')
                     .then(response => response.json())
                     .then(data => this.logs = data);
             }
         }" 
         x-init="fetchLogs(); setInterval(() => fetchLogs(), 3000)">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 md:mb-8">
            <div>
                <h1 class="text-xl md:text-3xl font-black text-slate-800 flex items-center gap-3">
                    <span class="relative flex h-5 w-5 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-5 w-5 bg-rose-500 shadow-sm"></span>
                    </span>
                    Live Punch Monitor
                </h1>
                <p class="text-xs md:text-sm text-slate-500 mt-1.5 font-medium">Monitoring the MB560-VL biometric device for today's scans...</p>
            </div>
            
            <!-- 🌟 SMART BACK BUTTON 🌟 -->
            <a href="{{ $backRoute }}" class="inline-flex items-center text-xs md:text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors bg-blue-50 hover:bg-blue-100 px-4 py-2.5 rounded-xl sm:bg-transparent sm:px-0 sm:py-0 w-fit group shrink-0">
                <svg class="w-4 h-4 mr-1.5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>{{ $backText }}</span>
            </a>
        </div>

        <!-- Live Feed Table Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col" style="max-height: 75vh;">
            
            <!-- Table Header with Live Counter -->
            <div class="p-4 md:p-5 bg-slate-50 border-b border-slate-200 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-3">
                    <span class="text-xs md:text-sm font-bold text-slate-600 uppercase tracking-wider">
                        All Punches Today
                    </span>
                    <!-- Live Counter -->
                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] uppercase font-black rounded-full tracking-wider flex items-center gap-1 shadow-sm border border-emerald-200">
                        <span x-text="logs.length"></span> Total
                    </span>
                </div>
                
                @if(auth()->user()->role === 'employee')
                    <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] uppercase font-black rounded-full tracking-wider shadow-sm border border-blue-200">
                        Personal Feed
                    </span>
                @else
                    <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 text-[10px] uppercase font-black rounded-full tracking-wider shadow-sm border border-indigo-200">
                        Company Feed
                    </span>
                @endif
            </div>
            
            <!-- Table Content (Scrollable) -->
            <div class="overflow-y-auto flex-1 bg-white">
                <table class="w-full text-left border-collapse relative">
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <template x-for="log in logs" :key="log.id">
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="p-4 md:p-5">
                                    <div class="flex items-center gap-3 md:gap-4">
                                        <!-- Avatar Icon -->
                                        <div class="h-10 w-10 md:h-12 md:w-12 shrink-0 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-blue-600 font-bold shadow-sm">
                                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        
                                        <!-- Employee & Database ID Info -->
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm md:text-base" x-text="log.name || (log.employee ? log.employee.name : 'Unknown User')"></p>
                                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                                <span class="text-[10px] font-black tracking-wider text-slate-500 bg-slate-100 px-2 py-0.5 rounded shadow-sm border border-slate-200">
                                                    BIO ID: #<span x-text="log.employee_id || log.biometric_id || 'N/A'"></span>
                                                </span>
                                                <!-- 🌟 DATABASE ID ADDED HERE 🌟 -->
                                                <span class="text-[10px] font-black tracking-wider text-rose-500 bg-rose-50 px-2 py-0.5 rounded shadow-sm border border-rose-200">
                                                    DB LOG ID: <span x-text="log.id"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Time Info -->
                                <td class="p-4 md:p-5 text-right align-middle">
                                    <p class="font-black text-emerald-600 text-base md:text-lg tracking-tight" x-text="new Date(log.punch_time || log.timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'})"></p>
                                    <p class="text-[10px] md:text-xs font-bold text-slate-400 mt-0.5 uppercase tracking-wider" x-text="new Date(log.punch_time || log.timestamp).toLocaleDateString(undefined, {weekday: 'short', month: 'short', day: 'numeric'})"></p>
                                </td>
                            </tr>
                        </template>
                        
                        <!-- Empty State -->
                        <tr x-show="logs.length === 0">
                            <td colspan="2" class="p-8 md:p-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 md:w-12 md:h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-sm md:text-base font-medium text-slate-500">No punches recorded today yet.</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-employee-layout>