<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Travel Order - {{ $travelOrder->employee->name ?? 'Employee' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: portrait; /* Standard portrait paper */
            margin: 0.25in;
        }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none; }
        }
        
        /* Custom checkbox style */
        .box {
            display: inline-flex; align-items: center; justify-content: center; 
            width: 12px; height: 12px; border: 1.5px solid black; 
            margin-right: 4px; vertical-align: middle;
        }
        .box-checked { background-color: transparent; }
        .box-checked::after { content: '✓'; font-weight: bold; font-size: 14px; margin-top: -2px; }
    </style>
</head>
<body class="bg-gray-100 font-serif text-black leading-tight">

    <!-- Print Button (Hidden when printing) -->
    <div class="max-w-[8.5in] mx-auto mt-4 mb-4 no-print text-right">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded font-bold shadow hover:bg-blue-700">
            Print Travel Order
        </button>
    </div>

    <!-- MAIN PAPER CONTAINER (Strictly formatted to Half-Slip Height) -->
    <div class="relative max-w-[8.5in] mx-auto bg-white p-6 shadow-xl border border-gray-400 print:shadow-none print:border-none print:p-2 h-[6.5in]  flex flex-col justify-between">
        
       <!-- Background Watermark (Scattered Pattern) -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0 flex items-center justify-center opacity-[0.08]">
            <div class="w-[150%] h-[150%] flex flex-wrap justify-center content-center -rotate-12">
                @for ($i = 0; $i < 24; $i++)
                    <img src="{{ asset('images/Logo.png') }}" class="w-48 h-48 m-10 object-contain" alt="Watermark">
                @endfor
            </div>
        </div>

        <!-- Foreground Content -->
        <div class="relative z-10 text-[13px] h-full flex flex-col">
            
            <!-- Header -->
            <div class="text-center font-bold text-lg mb-1 tracking-wide">
                TRAVEL ORDER FORM for Executive Department
            </div>
            <div class="text-right mb-3">
                <span class="mr-2">Form No:</span>
                <span class="border-b border-black inline-block w-40 text-center font-bold text-sm">{{ $travelOrder->form_number ?? '---' }}</span>
            </div>

            <!-- Authorization Section -->
            <div class="flex justify-between items-start mb-3">
                <div class="w-1/2 flex items-end">
                    <span class="mr-2 whitespace-nowrap">This is to authorize</span>
                    <div class="flex-1 flex flex-col items-center">
                        <span class="border-b border-black w-full text-center font-bold px-1 uppercase">{{ $travelOrder->employee->name ?? '---' }}</span>
                        <span class="text-[10px] italic">(Name)</span>
                    </div>
                </div>
                <div class="w-5/12 flex flex-col space-y-2">
                    <div class="flex flex-col items-center">
                        <span class="border-b border-black w-full text-center font-bold uppercase">{{ $travelOrder->employee->position ?? '---' }}</span>
                        <span class="text-[10px] italic">(Designation)</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="border-b border-black w-full text-center font-bold uppercase">{{ $travelOrder->employee->department ?? '---' }}</span>
                        <span class="text-[10px] italic">(Department)</span>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                To travel outside of duty station as herein approved:
            </div>

            <!-- Dates -->
            <div class="mb-2">
                <div class="font-bold mb-1">INCLUSIVE DATE OF TRAVEL:</div>
                <div class="flex ml-8 space-x-8">
                    <div class="flex w-2/5 items-end">
                        <span class="font-bold mr-2 w-12">FROM:</span>
                        <span class="border-b border-black flex-1 text-center font-bold">{{ \Carbon\Carbon::parse($travelOrder->start_date ?? now())->format('M d, Y') }}</span>
                    </div>
                    <div class="flex w-2/5 items-end">
                        <span class="font-bold mr-2 w-8">TO:</span>
                        <span class="border-b border-black flex-1 text-center font-bold">{{ \Carbon\Carbon::parse($travelOrder->end_date ?? now())->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Destination -->
            <div class="mb-4">
                <div class="font-bold mb-1">DESTINATION:</div>
                <div class="flex ml-8 space-x-8">
                    <div class="flex w-2/5 items-end">
                        <span class="font-bold mr-2 w-12">FROM:</span>
                        <span class="border-b border-black flex-1 text-center font-bold">{{ $travelOrder->departure_from ?? '---' }}</span>
                    </div>
                    <div class="flex w-2/5 items-end">
                        <span class="font-bold mr-2 w-8">TO:</span>
                        <span class="border-b border-black flex-1 text-center font-bold">{{ $travelOrder->destination ?? '---' }}</span>
                    </div>
                </div>
            </div>

            <!-- Purpose -->
            <div class="mb-4">
                <div class="font-bold mb-1">PURPOSE: <span class="font-normal italic text-[11px]">(specify purpose) and include duties and responsibilities with and in the course of this travel</span></div>
                <div class="ml-8 border-b border-black w-[90%] text-center font-bold text-sm uppercase">
                    {{ $travelOrder->purpose ?? '---' }}
                </div>
            </div>

            <!-- Expenses -->
            <div class="mb-6">
                <div class="font-bold">TRAVELLING EXPENSES AND ALLOWANCES:</div>
                <div class="ml-8">
                    All official expenses incurred in connection with and in the course of this travel are considered
                    <div class="flex items-center justify-center space-x-16 mt-2 font-bold">
                        <label class="flex items-center space-x-2">
                            <span class="box {{ ($travelOrder->expense_type ?? 'Official') == 'Official' ? 'box-checked' : '' }}"></span>
                            <span>Official</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <span class="box {{ ($travelOrder->expense_type ?? 'Official') == 'Personal' ? 'box-checked' : '' }}"></span>
                            <span>Personal</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- 🌟 Signatures & Footer with Digital Timestamps 🌟 -->
            <div class="mt-auto">
                <div class="grid grid-cols-3 gap-4 text-center mb-2">
                    
                    <!-- Recommended By (Dept Head) -->
                    <div class="flex flex-col">
                        <div class="text-left font-bold mb-2">RECOMMENDED BY:</div>
                        
                        <!-- Dept Head Digital Sign Date -->
                        <div class="h-[14px] flex items-end justify-center mb-0.5">
                            @if(!empty($travelOrder->dept_head_approved_at))
                                <span class="text-[9px] font-bold italic text-blue-700">Digital Sign: {{ \Carbon\Carbon::parse($travelOrder->dept_head_approved_at)->format('M d, Y h:i A') }}</span>
                            @endif
                        </div>
                        
                        <div class="font-bold text-xs h-[14px] leading-tight uppercase">
                            {{ $deptHeadName !== '---' ? $deptHeadName : '' }}
                        </div>
                        <div class="border-b border-black w-full"></div>
                        <div class="text-[10px] mt-1">Department Head</div>
                    </div>

                    <!-- Noted By (HRMO) -->
                    <div class="flex flex-col">
                        <div class="text-left font-bold mb-2">NOTED BY:</div>
                        
                        <!-- HRMO Digital Sign Date -->
                        <div class="h-[14px] flex items-end justify-center mb-0.5">
                            @if(!empty($travelOrder->hrmo_approved_at))
                                <span class="text-[9px] font-bold italic text-blue-700">Digital Sign: {{ \Carbon\Carbon::parse($travelOrder->hrmo_approved_at)->format('M d, Y h:i A') }}</span>
                            @endif
                        </div>
                        
                        <div class="font-bold text-xs h-[14px] leading-tight">FELIMON E. ELLORAN JR.</div>
                        <div class="border-b border-black w-full"></div>
                        <div class="text-[10px] mt-1">MGDH I (HRMO)</div>
                    </div>

                    <!-- Approved By (Mayor / Admin) -->
                    <div class="flex flex-col">
                        <div class="text-left font-bold mb-2">APPROVED BY:</div>
                        
                        <!-- Mayor Digital Sign Date -->
                        <div class="h-[14px] flex items-end justify-center mb-0.5">
                            @if(!empty($travelOrder->mayor_approved_at))
                                <span class="text-[9px] font-bold italic text-blue-700">Digital Sign: {{ \Carbon\Carbon::parse($travelOrder->mayor_approved_at)->format('M d, Y h:i A') }}</span>
                            @endif
                        </div>
                        
                        <!-- Mayor -->
                        <div class="font-bold text-xs h-[14px] leading-tight">HON. ANTONIO G. FUNGAN, JR.</div>
                        <div class="border-b border-black w-full"></div>
                        <div class="text-[10px] mt-1 mb-2">Municipal Mayor</div>
                        
                        <!-- Administrator -->
                        <div class="font-bold text-xs h-[14px] leading-tight mt-1">RECHIE T. TAMBUCONG</div>
                        <div class="border-b border-black w-full"></div>
                        <div class="text-[10px] mt-1">Municipal Administrator</div>
                    </div>

                </div>

                <div class="flex justify-between items-end text-[10px] mt-2">
                    <div class="italic text-gray-700 leading-tight">
                        <div class="font-bold not-italic text-black">Note:</div>
                        <div>* Please process this TRAVEL ORDER at least 1 day before your scheduled travel.</div>
                        <div>* For training/seminar, please process TRAVEL ORDER at least 3 days before your scheduled travel.</div>
                    </div>
                    <div class="font-medium pr-4 text-xs">
                        Thank you and travel safely!
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Auto-Print Script -->
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>