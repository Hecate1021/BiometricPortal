@php
    $role = strtolower(trim(auth()->user()->role ?? ''));
    
    // Choose Back Button Destination based on their exact role
    if ($role === 'employee') {
        $backRoute = route('employee.forms.index');
        $backText = 'Back to Request Desk';
    } elseif ($role === 'department_head') {
        $backRoute = route('dept_head.dashboard');
        $backText = 'Back to Approvals Portal';
    } elseif ($role === 'hrmo') {
        $backRoute = route('hrmo.dashboard');
        $backText = 'Back to HRMO Portal';
    } else {
        // Admin, HR, and Mayor will get sent back to the main list
        $backRoute = route('dtr.index');
        $backText = 'Back to Employee List';
    }
@endphp

<!-- 🌟 FIXED EMPLOYEE LAYOUT WRAPPER 🌟 -->
<x-employee-layout>
    
    <!-- Print Styles (Ensures Ctrl+P still works perfectly even inside a layout) -->
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: white !important; }
            @page { size: portrait; margin: 0.25in; }
            /* Hide the sidebars and navigation when printing */
            aside, header, nav, #sidebar, .md\:hidden { display: none !important; }
            main { padding: 0 !important; margin: 0 !important; overflow: visible !important; }
        }
    </style>

    <!-- ========================================== -->
    <!-- WEB VIEW (Visible on screen, Hidden on paper) -->
    <!-- ========================================== -->
    <div class="print:hidden max-w-5xl mx-auto space-y-6 px-4 py-6 md:py-8">

        <!-- Top Navigation & Profile Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <!-- Dynamic Back Button -->
                <a href="{{ $backRoute }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center gap-1 mb-2">
                    &larr; {{ $backText }}
                </a>
                <h1 class="text-2xl font-bold text-slate-900">{{ $employee->name }}</h1>
                <p class="text-sm text-slate-500">Biometric Terminal ID: <span class="font-mono font-semibold text-slate-700">#{{ $employee->biometric_id }}</span></p>
            </div>

            <!-- Action Buttons Container -->
            <div class="flex items-center gap-3">
                <!-- 🌟 LIVE Month & Year Filter 🌟 -->
                <form method="GET" class="flex items-center gap-2 m-0">
                    <select name="month" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer hover:bg-slate-50 transition">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m)->format('F') }}
                            </option>
                        @endfor
                    </select>

                    <select name="year" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer hover:bg-slate-50 transition">
                        @for($y = now()->year; $y >= now()->year - 2; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </form>

                <!-- THE PRINT BUTTON -->
                <a href="{{ route('dtr.print', $employee->id) }}?month={{ request('month', $month) }}&year={{ request('year', $year) }}" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print DTR
                </a>
            </div>
        </div>

        <!-- 4-Column Civil Service DTR Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 bg-slate-100/75 border-b border-slate-200 text-center font-bold text-slate-700 uppercase tracking-wider text-sm">
                Daily Time Record — {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse text-sm min-w-[700px]">
                
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase">
                            <th rowspan="2" class="p-3 border-r border-slate-200 w-16">Day</th>
                            <th colspan="2" class="p-2 border-r border-slate-200 bg-blue-50/50 text-blue-900">Morning (A.M.)</th>
                            <th colspan="2" class="p-2 bg-indigo-50/50 text-indigo-900 border-r border-slate-200">Afternoon (P.M.)</th>
                            <th colspan="3" class="p-2 bg-slate-50"></th> <!-- Updated Colspan to 3 -->
                        </tr>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold text-xs">
                            <th class="p-2 border-r border-slate-200">Arrival</th>
                            <th class="p-2 border-r border-slate-200">Departure</th>
                            <th class="p-2 border-r border-slate-200">Arrival</th>
                            <th class="p-2 border-r border-slate-200">Departure</th>
                            <th class="px-2 py-3 border-r border-slate-200 text-center text-[10px] font-bold text-slate-700 uppercase tracking-wider">Late</th>
                            <th class="px-2 py-3 border-r border-slate-200 text-center text-[10px] font-bold text-slate-700 uppercase tracking-wider">Early Out</th> <!-- NEW COLUMN -->
                            <th class="px-3 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">Total Hrs</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($dtr as $row)
                        <tr class="border-b text-center hover:bg-slate-50 transition-colors">
                            <td class="px-2 py-2 border-r">{{ $row['day_num'] }} <br> <span class="text-xs text-slate-400 font-medium">({{ $row['day_name'] }})</span></td>
                            
                            @if($row['remark'])
                                @if(!$row['hide_remark'])
                                    <!-- Updated colspan to 7 to stretch across the new Early Out column -->
                                    <td colspan="7" rowspan="{{ $row['rowspan'] }}" class="px-2 py-3 bg-blue-50/50 align-middle">
                                        <div class="font-bold tracking-widest text-blue-700">{{ $row['remark'] }}</div>
                                        @if($row['remark_details'])
                                            <div class="text-xs text-blue-800 mt-1 font-semibold">Executive : {{ $row['remark_details']['form_number'] }}</div>
                                            <div class="text-xs text-slate-700 italic">{{ $row['remark_details']['purpose'] }}</div>
                                            <div class="text-xs text-slate-500 uppercase">{{ $row['remark_details']['route'] }}</div>
                                        @endif
                                    </td>
                                @endif
                            @else
                                <!-- AM IN -->
                                <td class="px-2 py-2 border-r font-medium {{ isset($row['pass_slip']) && $row['pass_slip']['am_in'] ? 'bg-purple-100 text-purple-700 text-[10px] font-bold tracking-wider' : 'text-blue-600' }}">
                                    @if(isset($row['pass_slip']) && $row['pass_slip']['am_in']) PASS SLIP @else {{ $row['am_in'] ?: '--' }} @endif
                                </td>

                                <!-- AM OUT -->
                                <td class="px-2 py-2 border-r font-medium {{ isset($row['pass_slip']) && $row['pass_slip']['am_out'] ? 'bg-purple-100 text-purple-700 text-[10px] font-bold tracking-wider' : 'text-slate-700' }}">
                                    @if(isset($row['pass_slip']) && $row['pass_slip']['am_out']) PASS SLIP @else {{ $row['am_out'] ?: '--' }} @endif
                                </td>

                                <!-- PM IN -->
                                <td class="px-2 py-2 border-r font-medium {{ isset($row['pass_slip']) && $row['pass_slip']['pm_in'] ? 'bg-purple-100 text-purple-700 text-[10px] font-bold tracking-wider' : 'text-slate-700' }}">
                                    @if(isset($row['pass_slip']) && $row['pass_slip']['pm_in']) PASS SLIP @else {{ $row['pm_in'] ?: '--' }} @endif
                                </td>

                                <!-- PM OUT -->
                                <td class="px-2 py-2 border-r font-medium {{ isset($row['pass_slip']) && $row['pass_slip']['pm_out'] ? 'bg-purple-100 text-purple-700 text-[10px] font-bold tracking-wider' : 'text-blue-600' }}">
                                    @if(isset($row['pass_slip']) && $row['pass_slip']['pm_out']) PASS SLIP @else {{ $row['pm_out'] ?: '--' }} @endif
                                </td>

                                <!-- LATE -->
                                <td class="px-2 py-2 border-r text-rose-500 font-bold">{{ $row['late'] }}</td>
                                
                                <!-- EARLY OUT -->
                                <td class="px-2 py-2 border-r text-amber-500 font-bold">{{ $row['early'] }}</td>
                                
                                <!-- TOTAL HRS -->
                                <td class="px-2 py-2 font-black text-slate-800">
                                    @if(isset($row['pass_slip']))
                                        <span class="text-[9px] text-purple-600 block leading-tight font-bold whitespace-nowrap mb-0.5">
                                            OB: {{ $row['pass_slip']['time_from'] }} - {{ $row['pass_slip']['time_to'] }}
                                        </span>
                                    @endif
                                    {{ $row['total_hrs'] }}
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- PRINT VIEW (Hidden on screen, Visible on paper) -->
    <!-- ========================================== -->
    <div class="hidden print:block w-[8.27in] mx-auto bg-white text-black">
        <!-- This seamlessly injects your print layout right here if they hit Ctrl+P! -->
        @include('dtr.print')
    </div>

</x-employee-layout>