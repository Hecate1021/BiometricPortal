<x-employee-layout>
    <!-- Load Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 space-y-6 md:space-y-8">

        <!-- 🌟 PAGE HEADER 🌟 -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    System Control Center
                </h1>
                <p class="text-slate-500 mt-1 font-medium">Live ZKTeco Biometrics & IT Administration</p>
            </div>
            <div class="text-sm text-slate-600 font-bold bg-white px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-2.5 transition-all hover:shadow-md">
                <span class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
                System Online
            </div>
        </div>

        <!-- 🌟 ALERTS FOR EMAIL SENDING SUCCESS/ERROR 🌟 -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-700 font-bold rounded-xl border border-emerald-200 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 bg-rose-50 text-rose-700 font-bold rounded-xl border border-rose-200 shadow-sm">
                @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
            </div>
        @endif

        <!-- 🌟 TOP ROW: QUICK STATS 🌟 -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            <!-- Total Employees -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-4 -top-4 text-slate-50 group-hover:text-blue-50 transition-colors duration-500">
                    <svg class="w-28 h-28 transform group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                </div>
                <div class="relative z-10">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Employees</div>
                    <div class="text-3xl sm:text-4xl font-black text-slate-800">{{ $totalEmployees ?? 0 }}</div>
                </div>
            </div>

            <!-- Present Today -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-4 -top-4 text-slate-50 group-hover:text-emerald-50 transition-colors duration-500">
                    <svg class="w-28 h-28 transform group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path></svg>
                </div>
                <div class="relative z-10">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Punched In Today</div>
                    <div class="text-3xl sm:text-4xl font-black text-emerald-600">{{ $punchedInToday ?? 0 }}</div>
                    <div class="text-[10px] font-bold text-emerald-500 mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg> Live ZKTeco Feed
                    </div>
                </div>
            </div>

            <!-- On Leave / OB -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-4 -top-4 text-slate-50 group-hover:text-purple-50 transition-colors duration-500">
                    <svg class="w-28 h-28 transform group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"></path></svg>
                </div>
                <div class="relative z-10">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Leave / Pass Slip</div>
                    <div class="text-3xl sm:text-4xl font-black text-purple-600">{{ $onLeaveOrOb ?? 0 }}</div>
                    <div class="text-[10px] font-bold text-slate-400 mt-1.5">Approved for today</div>
                </div>
            </div>

            <!-- Absent -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-4 -top-4 text-slate-50 group-hover:text-rose-50 transition-colors duration-500">
                    <svg class="w-28 h-28 transform group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"></path></svg>
                </div>
                <div class="relative z-10">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Unlogged / Absent</div>
                    <div class="text-3xl sm:text-4xl font-black text-rose-600">{{ $absentToday ?? 0 }}</div>
                    <div class="text-[10px] font-bold text-slate-400 mt-1.5">No data received</div>
                </div>
            </div>
        </div>

        <!-- 🌟 MIDDLE ROW: LINE CHART & ALERTS (Perfectly Balanced) 🌟 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Attendance Graph -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6 flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        7-Day Attendance Overview
                    </h2>
                </div>
                <div class="relative flex-1 w-full min-h-[250px]">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Recent System Alerts -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6 flex flex-col">
                <h2 class="text-lg font-black text-slate-800 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    System Logs
                </h2>
                <div class="flex-1 space-y-5 overflow-y-auto pr-2">
                    <div class="flex gap-3">
                        <div class="mt-1 w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0 shadow-[0_0_5px_rgba(16,185,129,0.5)]"></div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">System Online</p>
                            <p class="text-xs text-slate-500 mt-0.5">All services are running normally.</p>
                            <span class="text-[9px] text-emerald-600 font-black uppercase mt-1.5 block tracking-wider">Live Monitoring</span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="mt-1 w-2.5 h-2.5 rounded-full bg-blue-500 shrink-0 shadow-[0_0_5px_rgba(59,130,246,0.5)]"></div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">DTR Calculation Engine</p>
                            <p class="text-xs text-slate-500 mt-0.5">Automatically tracking standard 8:00 AM - 5:00 PM shifts.</p>
                            <span class="text-[9px] text-blue-600 font-black uppercase mt-1.5 block tracking-wider">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🌟 FULL WIDTH ROW: FAIR DEPARTMENT LEADERBOARD 🌟 -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Department Attendance Leaderboard
                    </h2>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Average punches per employee</p>
                </div>

                <!-- 🌟 MONTH & YEAR FILTER 🌟 -->
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                    <select name="month" class="text-sm bg-slate-50 border border-slate-300 text-slate-700 font-bold rounded-lg shadow-sm px-3 py-2 focus:ring-2 focus:ring-emerald-500 transition-colors cursor-pointer w-full sm:w-auto">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ ($selectedMonth ?? now()->month) == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>

                    <select name="year" class="text-sm bg-slate-50 border border-slate-300 text-slate-700 font-bold rounded-lg shadow-sm px-3 py-2 focus:ring-2 focus:ring-emerald-500 transition-colors cursor-pointer w-full sm:w-auto">
                        @for($y = now()->year - 2; $y <= now()->year; $y++)
                            <option value="{{ $y }}" {{ ($selectedYear ?? now()->year) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>

                    <button type="submit" class="px-4 py-2 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 font-bold rounded-lg text-sm shadow-sm transition">
                        Filter
                    </button>
                </form>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="deptAttendanceChart"></canvas>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 🖨️ BULK PRINT DTR SECTION                  -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-6">
            <div class="p-5 border-b border-indigo-100 bg-indigo-50 flex items-center justify-between">
                <h2 class="font-bold text-lg text-indigo-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Bulk Print & Email DTRs
                </h2>
            </div>

            <div class="p-6">
                <!-- Using a standard action here, but formaction on the buttons overrides it -->
                <form method="GET" target="_blank" class="flex flex-col md:flex-row md:items-end gap-5">
                    <div class="w-full md:w-1/4">
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Select Month</label>
                        <select name="month" class="w-full text-sm bg-slate-50 border border-slate-300 text-slate-700 font-medium rounded-lg shadow-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 transition-colors cursor-pointer">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="w-full md:w-1/4">
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Select Year</label>
                        <select name="year" class="w-full text-sm bg-slate-50 border border-slate-300 text-slate-700 font-medium rounded-lg shadow-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 transition-colors cursor-pointer">
                            @for($y = now()->year - 1; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="w-full md:flex-1">
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Filter by Department</label>
                        <select name="department" class="w-full text-sm bg-slate-50 border border-slate-300 text-slate-700 font-medium rounded-lg shadow-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 transition-colors cursor-pointer">
                            <option value="All" class="font-bold">All Departments</option>
                            @foreach($departments ?? \App\Models\Department::orderBy('name')->get() as $dept)
                                <option value="{{ $dept->name ?? $dept }}">{{ $dept->name ?? $dept }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 🌟 BUTTONS 🌟 -->
                    <div class="w-full md:w-auto mt-2 md:mt-0 shrink-0 flex flex-col sm:flex-row gap-2">
                        <!-- Print PDF Button -->
                        <button type="submit" formaction="{{ route('dtr.print_bulk') }}" class="w-full md:w-auto px-6 py-2.5 text-sm font-bold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Print
                        </button>

                        <!-- Email PDF Button -->
                        <!-- Note: formtarget="_self" prevents opening a blank tab when sending an email -->
                        <button type="submit" formaction="{{ route('dtr.send_emails') }}" formtarget="_self" class="w-full md:w-auto px-6 py-2.5 text-sm font-bold bg-rose-600 text-white rounded-lg hover:bg-rose-700 shadow-md transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Email DTRs
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 🌟 BOTTOM ROW: SYSTEM CONFIGURATION 🌟 -->
        <h2 class="text-lg font-black text-slate-800 pt-4 border-t border-slate-200 mt-6">System Configuration Modules</h2>

        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <a href="{{ route('dtr.upload_form') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white p-5 rounded-2xl shadow-sm transition group flex flex-col items-center text-center">
                <svg class="w-8 h-8 mb-3 opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <h3 class="text-sm font-bold">Biometric Sync</h3>
            </a>

            <a href="{{ route('admin.users.index') }}" class="bg-white hover:bg-slate-50 border border-slate-200 p-5 rounded-2xl shadow-sm transition group flex flex-col items-center text-center">
                <div class="bg-blue-50 text-blue-600 p-2.5 rounded-xl mb-3 group-hover:scale-110 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></div>
                <h3 class="text-sm font-bold text-slate-800">User Accounts</h3>
            </a>

            <a href="#" class="bg-white hover:bg-slate-50 border border-slate-200 p-5 rounded-2xl shadow-sm transition group flex flex-col items-center text-center">
                <div class="bg-emerald-50 text-emerald-600 p-2.5 rounded-xl mb-3 group-hover:scale-110 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg></div>
                <h3 class="text-sm font-bold text-slate-800">System Roles</h3>
            </a>

            <a href="#" class="bg-white hover:bg-slate-50 border border-slate-200 p-5 rounded-2xl shadow-sm transition group flex flex-col items-center text-center">
                <div class="bg-amber-50 text-amber-600 p-2.5 rounded-xl mb-3 group-hover:scale-110 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg></div>
                <h3 class="text-sm font-bold text-slate-800">Holidays</h3>
            </a>

            <a href="{{ route('departments.index') }}" class="bg-white hover:bg-slate-50 border border-slate-200 p-5 rounded-2xl shadow-sm transition group flex flex-col items-center text-center">
                <div class="bg-rose-50 text-rose-600 p-2.5 rounded-xl mb-3 group-hover:scale-110 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></div>
                <h3 class="text-sm font-bold text-slate-800">Departments</h3>
            </a>
        </div>
    </div>

    <!-- 🌟 CHART.JS INITIALIZATION SCRIPT 🌟 -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // --- 1. Line Chart: 7-Day Overview ---
            const ctx1 = document.getElementById('attendanceChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']) !!},
                    datasets: [{
                        label: 'Employees Present',
                        data: {!! json_encode($chartData ?? [0, 0, 0, 0, 0]) !!},
                        borderColor: '#4f46e5', // Indigo-600
                        borderWidth: 3,
                        tension: 0.4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        backgroundColor: (context) => {
                            const chart = context.chart;
                            const {ctx, chartArea} = chart;
                            if (!chartArea) return null;
                            const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                            gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
                            gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
                            return gradient;
                        }
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b', padding: 10,
                            titleFont: { size: 13 }, bodyFont: { size: 14, weight: 'bold' },
                            displayColors: false
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { color: '#64748b', precision: 0 } },
                        x: { grid: { display: false, drawBorder: false }, ticks: { color: '#64748b', font: { weight: 'bold' } } }
                    }
                }
            });

            // --- 2. Bar Chart: Fair Department Attendance ---
            const ctx2 = document.getElementById('deptAttendanceChart').getContext('2d');
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($deptChartLabels ?? ['HRMO', 'MTO', 'Engineering']) !!},
                    datasets: [{
                        label: 'Avg Punches Per Employee',
                        data: {!! json_encode($deptChartData ?? [0, 0, 0]) !!},
                        borderWidth: 2,
                        borderColor: '#10b981', // Emerald-500
                        borderRadius: 6,
                        borderSkipped: false,
                        maxBarThickness: 50,
                        backgroundColor: (context) => {
                            const chart = context.chart;
                            const {ctx, chartArea} = chart;
                            if (!chartArea) return null;
                            // Create a smooth gradient for the bars
                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)'); // Light Emerald
                            gradient.addColorStop(1, 'rgba(16, 185, 129, 0.8)'); // Solid Emerald
                            return gradient;
                        }
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b', padding: 12,
                            titleFont: { size: 13 }, bodyFont: { size: 14, weight: 'bold' },
                            displayColors: false
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { color: '#64748b' } },
                        x: { grid: { display: false, drawBorder: false }, ticks: { color: '#64748b', font: { weight: 'bold' } } }
                    }
                }
            });
        });
    </script>
</x-employee-layout>
