<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulk Print DTRs</title>
    <!-- Ensure Tailwind is loaded -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            /* 🌟 Removed hardcoded paper size so the printer dialog decides (A4, Letter, Legal) 🌟 */
            @page { margin: 0.3in; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: white !important; }
            .print-hidden { display: none !important; }

            /* THIS IS THE MAGIC LINE FOR BULK PRINTING */
            .page-break { break-after: page; }
            /* Ensures the very last page doesn't spit out a blank sheet */
            .page-break:last-child { break-after: auto; }

            /* Force the container to take up the full printable area of the chosen paper */
            .print-container { width: 100% !important; max-width: none !important; box-shadow: none !important; margin: 0 !important; }
        }
        .td-border { border: 1px solid black; padding: 2px 4px; }
        .th-border { border: 1px solid black; padding: 2px 4px; font-weight: bold; text-align: center; }
    </style>
</head>
<body class="bg-gray-200">

    <!-- Print Buttons (Hides when actually printing) -->
    <div class="max-w-4xl mx-auto mt-4 mb-4 flex justify-end gap-3 print-hidden px-4">
        <a href="{{ url()->previous() }}" class="bg-slate-300 text-slate-800 px-4 py-2 rounded shadow hover:bg-slate-400 font-bold">
            Go Back
        </a>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 flex items-center gap-2 font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print {{ count($allDtrs) }} DTRs
        </button>
    </div>

    <!-- Loop through every employee's DTR dataset -->
    @foreach($allDtrs as $data)

    <!-- 🌟 Replaced fixed inches with flexible w-full and max-w-4xl 🌟 -->
    <div class="page-break print-container w-full max-w-4xl mx-auto bg-white p-4 sm:p-6 text-[10px] text-black shadow-lg mb-8 flex flex-col justify-between">

        <!-- Top Header -->
        <div class="flex justify-between items-end mb-2 shrink-0">
            <div>
                <div class="font-bold text-sm uppercase">{{ $data['employee']->name }}</div>
                <div class="italic text-[9px] uppercase">{{ $data['employee']->position ?? 'ADMINISTRATIVE ASSISTANT I' }}</div>
            </div>
            <div class="w-1/2 text-[9px] italic text-right leading-tight text-gray-700">
                Recorded official business transactions based on approved Travel Orders and Official Business Cards (Pass Slips), as well as Leave Applications (CS Form No. 6, Rev. 2020) and Biometric Attendance Suspension.
            </div>
        </div>

        <!-- Main Two-Column Table Area -->
        <div class="flex border-t border-l border-r border-black flex-1">

            <!-- LEFT SIDE (DTR Logs) -->
            <div class="w-[60%] border-r border-black flex flex-col">
                <table class="w-full text-center border-collapse h-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th rowspan="2" class="th-border border-l-0 border-t-0 w-[20%] text-[8px]">Date</th>
                            <th rowspan="2" class="th-border border-t-0 text-[7px]">Timetable</th>
                            <th colspan="2" class="th-border border-t-0 text-[8px]">AM</th>
                            <th colspan="2" class="th-border border-t-0 text-[8px]">PM</th>
                            <th rowspan="2" class="th-border border-t-0 text-[7px]">Late</th>
                            <th rowspan="2" class="th-border border-t-0 text-[7px]">Early <br>Out</th>
                            <th rowspan="2" class="th-border border-r-0 border-t-0 text-[7px]">Total<br>Hrs</th>
                        </tr>
                        <tr class="bg-gray-100">
                            <th class="th-border text-[7px]">In</th>
                            <th class="th-border text-[7px]">Out</th>
                            <th class="th-border text-[7px]">In</th>
                            <th class="th-border text-[7px]">Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['dtr'] as $row)
                        <tr>
                            <!-- 🌟 HIGHLIGHT LOGIC APPLIED TO DATE 🌟 -->
                            <td class="td-border border-l-0 text-left text-[8px] whitespace-nowrap {{ !empty($row['remark']) ? 'bg-yellow-100 font-bold' : '' }}">
                                {{ $row['day_name'] }}, {{ \Carbon\Carbon::create(null, $data['month'])->format('M') }} {{ str_pad($row['day_num'], 2, '0', STR_PAD_LEFT) }}
                            </td>

                            <!-- 🌟 DYNAMIC ROW SPAN LOGIC FOR TRAVEL & LEAVES 🌟 -->
                            @if(!empty($row['remark']) && empty($row['hide_remark']))

                                <!-- Merged Middle Columns (Highlighted) -->
                                <td colspan="7" rowspan="{{ $row['rowspan'] }}" class="td-border bg-yellow-100 text-center align-middle">
                                    <span class="font-black text-[10px] uppercase tracking-wide text-blue-900">
                                        {{ $row['remark'] === 'Executive' ? 'OFFICIAL BUSINESS' : $row['remark'] }}
                                    </span>
                                    @if(!empty($row['remark_details']['form_number']))
                                        <br><span class="text-[8px] font-bold text-slate-800">{{ $row['remark_details']['form_number'] }}</span>
                                    @endif
                                </td>

                                <!-- Empty Total Hrs Column -->
                                <td rowspan="{{ $row['rowspan'] }}" class="td-border border-r-0 bg-yellow-100 text-center font-bold text-gray-500 align-middle">
                                    --
                                </td>

                            @elseif(empty($row['hide_remark']))
                               <!-- NORMAL TIME RENDERING (With Pass Slips and Exemptions) -->
                                <td class="td-border text-[7px]">{{ (!empty($row['pass_slip'])) ? 'OB/LV' : 'REG' }}</td>

                                <td class="td-border text-[8px] {{ isset($row['pass_slip']) && $row['pass_slip']['am_in'] ? 'font-bold text-[6px]' : '' }} {{ $row['am_in'] === 'EXEMPT' ? 'font-bold text-blue-700 text-[6px] bg-blue-50/50' : '' }}">
                                    @if(isset($row['pass_slip']) && $row['pass_slip']['am_in']) PASS SLIP @else {{ $row['am_in'] ?: '' }} @endif
                                </td>
                                <td class="td-border text-[8px] {{ isset($row['pass_slip']) && $row['pass_slip']['am_out'] ? 'font-bold text-[6px] text-black' : 'text-gray-500' }} {{ $row['am_out'] === 'EXEMPT' ? 'font-bold text-blue-700 text-[6px] bg-blue-50/50' : '' }}">
                                    @if(isset($row['pass_slip']) && $row['pass_slip']['am_out']) PASS SLIP @else {{ $row['am_out'] ?: '' }} @endif
                                </td>
                                <td class="td-border text-[8px] {{ isset($row['pass_slip']) && $row['pass_slip']['pm_in'] ? 'font-bold text-[6px] text-black' : 'text-gray-500' }} {{ $row['pm_in'] === 'EXEMPT' ? 'font-bold text-blue-700 text-[6px] bg-blue-50/50' : '' }}">
                                    @if(isset($row['pass_slip']) && $row['pass_slip']['pm_in']) PASS SLIP @else {{ $row['pm_in'] ?: '' }} @endif
                                </td>
                                <td class="td-border text-[8px] {{ isset($row['pass_slip']) && $row['pass_slip']['pm_out'] ? 'font-bold text-[6px]' : '' }} {{ $row['pm_out'] === 'EXEMPT' ? 'font-bold text-blue-700 text-[6px] bg-blue-50/50' : '' }}">
                                    @if(isset($row['pass_slip']) && $row['pass_slip']['pm_out']) PASS SLIP @else {{ $row['pm_out'] ?: '' }} @endif
                                </td>

                                <td class="td-border text-[8px] text-red-600 font-bold">{{ $row['late'] }}</td>
                                <td class="td-border text-[8px] text-orange-600 font-bold">{{ $row['early'] }}</td>

                                <td class="td-border text-[8px] border-r-0 font-bold text-blue-800">
                                    @if(isset($row['pass_slip']))
                                        <span class="text-[6px] text-black block leading-tight font-bold whitespace-nowrap mb-0.5">
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

            <!-- RIGHT SIDE (Transactions / Travel Orders) -->
            <div class="w-[40%] flex flex-col">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="th-border border-l-0 border-t-0 border-r-0 w-[35%]">Date</th>
                            <th class="th-border border-t-0 border-r-0">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['dtr'] as $row)
                            @if((!empty($row['remark']) || !empty($row['pass_slip'])) && empty($row['hide_remark']))
                            <tr>
                                <td class="td-border border-l-0 border-r-0 text-center align-top font-bold text-[9px]">
                                    @if(isset($row['rowspan']) && $row['rowspan'] > 1)
                                        {{ sprintf('%02d/%02d/%04d', $data['month'], $row['day_num'], $data['year']) }} - <br>
                                        {{ sprintf('%02d/%02d/%04d', $data['month'], $row['day_num'] + $row['rowspan'] - 1, $data['year']) }}
                                    @else
                                        {{ sprintf('%02d/%02d/%04d', $data['month'], $row['day_num'], $data['year']) }}
                                    @endif
                                </td>
                                <td class="td-border border-r-0 leading-tight pb-3">
                                    @if(!empty($row['remark']))
                                        <span class="font-bold">{{ $row['remark'] }} : {{ $row['remark_details']['form_number'] ?? '' }}</span><br>
                                        {{ $row['remark_details']['purpose'] ?? '' }} <br>
                                        <span class="text-[8px] uppercase text-gray-600">{{ $row['remark_details']['route'] ?? '' }}</span>
                                    @elseif(!empty($row['pass_slip']))
                                        <span class="font-bold">PASS SLIP (Official Business)</span><br>
                                        <span class="text-[8px] font-bold text-gray-800">Time: {{ $row['pass_slip']['time_from'] }} - {{ $row['pass_slip']['time_to'] }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Summary Area -->
        <!-- 🌟 Removed h-40, replaced with h-auto to allow stretching based on paper size 🌟 -->
        <div class="flex border-b border-l border-r border-black h-auto min-h-[10rem] shrink-0 mt-auto">
            <!-- Left Summary -->
            <div class="w-[60%] border-r border-black p-2 flex">
                <div class="w-[70%] pr-3 border-r border-black flex flex-col justify-between">
                    <div>
                        <div class="flex justify-end gap-2 items-end">
                            <span class="font-bold text-[9px]">Required WORKDAY/s:</span>
                            <span class="border-b border-black w-16 text-center text-sm font-bold">{{ $data['required_workdays'] ?? '19.0' }}</span>
                        </div>
                        <div class="text-right mt-1 leading-tight text-[8px] text-gray-700">
                            Recorded OFFICIAL BUSINESS transactions, {{ $data['leave_ob_text'] ?? 'LEAVE 0 D | 0 H' }}<br>
                            and Biometric Attendance Suspension.
                        </div>
                        <div class="flex justify-end gap-2 items-end mt-1">
                            <span class="font-bold text-[9px]">Rendered WORKDAY/s:</span>
                            <div class="text-center">
                                <div class="text-[9px] font-bold text-blue-700">{{ $data['rendered_days'] ?? '0 D | 0 H | 0 m' }}</div>
                                <div class="border-b-2 border-black w-24"></div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center relative mt-6">
                        <div class="border-b border-black w-48 mx-auto"></div>
                        <div class="font-bold mt-1 uppercase">{{ $data['employee']->name }}</div>
                        <div class="text-[8px]">Employee's Signature</div>
                    </div>
                </div>

                <!-- Right Side (Lates, Undertime, Absences) -->
                <div class="w-[30%] pl-2 pt-1 flex flex-col justify-between">
                    <div class="flex justify-between font-bold text-red-600 text-center text-xs">
                        <div>{{ $data['total_late'] ?? '0:00' }}<br><span class="text-black font-normal text-[8px]">Late</span></div>
                        <div>{{ $data['total_undertime'] ?? '0:00' }}<br><span class="text-black font-normal text-[8px]">Under<br>Time</span></div>
                        <div class="text-blue-700">{{ $data['total_workhour'] ?? '0:00' }}<br><span class="text-black font-normal text-[8px]">Rendered<br>WORKHOUR</span></div>
                    </div>
                    <div class="text-center mt-6">
                        <div class="text-red-600 font-bold tracking-widest text-[9px]">ABSENCES</div>
                        <div class="text-red-600 font-bold text-xs mt-1">{{ $data['total_absences'] ?? '0 D | 0 H | 0 m' }}</div>
                    </div>
                </div>
            </div>

            <!-- 🌟 DYNAMIC DATABASE DEPARTMENT HEAD SIGNATURE 🌟 -->
            @php
                $headCompanyIds = \App\Models\User::where('role', 'department_head')->pluck('company_id');

                $deptHead = \App\Models\Employee::where('department', $data['employee']->department)
                    ->whereIn('company_id', $headCompanyIds)
                    ->first();

                $dynamicHeadName = $deptHead ? $deptHead->name : 'ACTING DEPARTMENT HEAD';
                $dynamicDepartment = $data['employee']->department ? $data['employee']->department : "MAYOR'S OFFICE";
            @endphp

            <div class="w-[40%] p-2 relative flex flex-col justify-between">
                <div class="text-[8px] text-blue-800 italic leading-tight">
                    Note: Attach supporting documents for any additional hours rendered under Official Business, Leave, or Biometric Attendance Suspension that are not listed above.
                </div>
                <div class="text-center mt-auto mb-2">
                    <div class="border-b border-black w-11/12 mx-auto font-bold text-[11px] uppercase">
                        {{ $dynamicHeadName }}
                    </div>
                    <div class="font-bold text-[8px] leading-tight mt-1 uppercase">
                        MUNICIPAL GOVERNMENT DEPARTMENT HEAD<br>
                        ({{ $dynamicDepartment }})
                    </div>
                </div>
            </div>
        </div>

    </div>

    @endforeach

</body>
</html>
