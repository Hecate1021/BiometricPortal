<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pass Slip - {{ $passSlip->employee->name ?? 'Employee' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: portrait;
            margin: 0.25in;
        }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none; }
        }
        
        /* Checkbox Box Style */
        .box {
            display: inline-flex; align-items: center; justify-content: center; 
            width: 12px; height: 12px; border: 1.5px solid black; 
            margin-right: 4px; vertical-align: middle; background-color: transparent;
        }
        .box-checked::after { 
            content: '✓'; font-weight: black; font-size: 14px; margin-top: -3px; color: black;
        }

        /* Form Lines/Boxes matching physical image */
        .input-box {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            min-height: 24px;
            width: 100%;
            display: flex;
            align-items: center;
            background-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-black">

    <!-- Print Button (Hidden when printing) -->
    <div class="max-w-[4.13in] mx-auto mt-6 mb-4 no-print text-center">
        <button onclick="window.print()" class="bg-purple-600 text-white px-4 py-2 rounded-lg font-bold shadow hover:bg-purple-700 w-full">
            Print Pass Slip
        </button>
    </div>

    <!-- MAIN PAPER CONTAINER (Half A4 Lengthwise: 4.13in x 11.69in) -->
    <!-- The min-h-[10.5in] ensures it spans the page vertically while respecting print margins -->
    <div class="w-[4.13in] min-h-[10.5in] mx-auto bg-white p-5 shadow-xl border border-gray-400 print:shadow-none print:border-none print:p-0 flex flex-col relative overflow-hidden">
        
        <!-- Header -->
        <h1 class="text-center font-black text-sm mb-5 tracking-wide shrink-0 px-2 leading-tight">
            OFFICIAL BUSINESS CARD<br>(PASS SLIP)
        </h1>

        <!-- Content Flexbox to evenly distribute space vertically -->
        <div class="flex-1 flex flex-col space-y-3.5">
            
            <div>
                <div class="text-[9px] italic mb-0.5">Department:</div>
                <div class="input-box">{{ $passSlip->employee->department ?? '---' }}</div>
            </div>
            
            <div>
                <div class="text-[9px] italic mb-0.5">Employee's Full Name:</div>
                <div class="input-box">{{ $passSlip->employee->name ?? '---' }}</div>
            </div>

            <div>
                <div class="text-[9px] italic mb-0.5">Full Position Title:</div>
                <div class="input-box">{{ $passSlip->employee->position ?? '---' }}</div>
            </div>

            <div>
                <div class="text-[9px] italic mb-0.5">Specify the OFFICIAL BUSINESS transaction.</div>
                <div class="input-box min-h-[60px] items-start py-1 whitespace-pre-wrap leading-tight">{{ $passSlip->transaction_details ?? '' }}</div>
            </div>

            <div>
                <div class="text-[9px] italic mb-0.5">Date of Official Business Transaction</div>
                <div class="input-box">{{ \Carbon\Carbon::parse($passSlip->transaction_date)->format('F d, Y') }}</div>
            </div>

            <div>
                <div class="text-[9px] italic mb-1">Inclusive Time:</div>
                <div class="flex items-center gap-2 text-xs font-bold">
                    <span class="text-[10px]">From:</span>
                    <div class="border border-black px-2 py-0.5 flex-1 text-center text-xs">
                        {{ \Carbon\Carbon::parse($passSlip->time_from)->format('h:i A') }}
                    </div>
                    <span class="text-[10px] ml-2">To:</span>
                    <div class="border border-black px-2 py-0.5 flex-1 text-center text-xs">
                        {{ \Carbon\Carbon::parse($passSlip->time_to)->format('h:i A') }}
                    </div>
                </div>
            </div>

            <!-- Unlogged Attendance -->
            <div class="pt-2 shrink-0">
                <div class="text-[9px] italic mb-2">Unlogged Attendance in Biometric System.</div>
                
                <div class="grid grid-cols-2 gap-y-2 gap-x-2 pl-1">
                    <label class="flex items-center">
                        <span class="box {{ $passSlip->unlogged_time_in_am ? 'box-checked' : '' }}"></span>
                        <span class="text-[9px] font-bold uppercase tracking-tight">TIME In <span class="font-normal capitalize text-[8px]">(8:00 AM)</span></span>
                    </label>
                    <label class="flex items-center">
                        <span class="box {{ $passSlip->unlogged_time_out_am ? 'box-checked' : '' }}"></span>
                        <span class="text-[9px] font-bold uppercase tracking-tight">TIME Out <span class="font-normal capitalize text-[8px]">(12:00 NN)</span></span>
                    </label>
                    <label class="flex items-center">
                        <span class="box {{ $passSlip->unlogged_time_in_pm ? 'box-checked' : '' }}"></span>
                        <span class="text-[9px] font-bold uppercase tracking-tight">TIME In <span class="font-normal capitalize text-[8px]">(1:00 PM)</span></span>
                    </label>
                    <label class="flex items-center">
                        <span class="box {{ $passSlip->unlogged_time_out_pm ? 'box-checked' : '' }}"></span>
                        <span class="text-[9px] font-bold uppercase tracking-tight">TIME Out <span class="font-normal capitalize text-[8px]">(5:00 PM)</span></span>
                    </label>
                </div>
            </div>

            <div class="pt-2 shrink-0">
                <div class="text-[9px] italic mb-1">Number of unlogged Attendance in Biometric System.</div>
                <div class="border-b border-black w-20 text-center font-bold text-sm">
                    {{ $passSlip->unlogged_count ?? '0' }}
                </div>
            </div>

            <!-- 🌟 UPDATED: Approval Section with Digital Timestamp 🌟 -->
            <div class="pt-8 shrink-0 mt-auto mb-4">
                <div class="text-[10px] font-bold mb-4">Approved By:</div>
                
                <div class="w-[90%] flex flex-col items-start">
                    
                    <!-- Digital Sign Date -->
                    <div class="h-[14px] flex items-end justify-start mb-0.5">
                        @if(!empty($passSlip->dept_head_approved_at))
                            <span class="text-[9px] font-bold italic text-blue-700">Digital Sign: {{ \Carbon\Carbon::parse($passSlip->dept_head_approved_at)->format('M d, Y h:i A') }}</span>
                        @endif
                    </div>

                    <div class="border-b border-black w-full text-left font-black text-[11px] uppercase h-[16px] leading-tight">
                        {{ $deptHeadName }}
                    </div>
                    <div class="text-[8px] font-bold mt-1 leading-tight">
                        @if($isHrmo)
                            Municipal Government Department Head I<br>(Human Resource Management Officer)
                        @else
                            Department Head
                        @endif
                    </div>
                </div>
            </div>

            <!-- Disclaimers -->
            <div class="text-[8px] italic leading-tight space-y-1 mt-4 shrink-0 border-t border-gray-300 pt-2 text-gray-700">
                <p>* Please use this OFFICIAL BUSINESS CARD if transaction is within the Lake Sebu jurisdiction only.</p>
                <p>* Please file this OFFICIAL BUSINESS CARD within your respective Department and submit 1 copy to the Human Resource Management and Development Office (HRMDO).</p>
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