<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Leave Form - {{ $leave->employee->name ?? 'Employee' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: legal; /* Legal size paper (8.5 x 14) */
            margin: 0.25in;
        }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none; }
            
            /* Forces the document to scale to 108% automatically */
            .auto-scale {
                zoom: 1.08;
                /* Fallback for Firefox */
                -moz-transform: scale(1.08); 
                -moz-transform-origin: top center;
            }
        }
        
        /* Custom checkbox style for printing */
        .box {
            display: inline-flex; align-items: center; justify-content: center; 
            width: 12px; height: 12px; border: 1px solid black; 
            margin-right: 4px; vertical-align: middle;
        }
        .box-checked { background-color: transparent; }
        .box-checked::after { content: '✓'; font-weight: bold; font-size: 12px; margin-top: -1px; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-black text-[10px] leading-tight">

    @php
        // 🌟 SAFETY CHECK: Prevents PHP Fatal Errors by safely handling both Arrays and Strings
        $typeStr = is_array($leave->leave_type) ? implode(',', $leave->leave_type) : (string)($leave->leave_type ?? '');
        $detailsStr = is_array($leave->leave_details) ? implode(',', $leave->leave_details) : (string)($leave->leave_details ?? '');
    @endphp

    <!-- Print Button (Hides when actually printing) -->
    <div class="max-w-[8.5in] mx-auto mt-4 mb-4 no-print text-right">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded font-bold shadow hover:bg-blue-700">
            Print Document
        </button>
    </div>

    <!-- MAIN PAPER CONTAINER -->
    <div class="max-w-[8.5in] mx-auto bg-white p-6 shadow-xl border border-gray-300 print:shadow-none print:border-none print:p-0 auto-scale">
        
        <!-- Header Section -->
        <div class="relative h-28 mb-2">
            <!-- Left: Form Info -->
            <div class="absolute top-0 left-0">
                <p class="italic text-[9px]">Civil Service Form No. 6</p>
                <p class="italic text-[9px]">Revised 2020</p>
            </div>
            
            <!-- Logo (Moved perfectly to the red circle position) -->
            <div class="absolute top-0 left-[18%] w-16 h-16 rounded-full border-1 border-black flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="w-full h-full object-cover" onerror="this.outerHTML='<span class=\'font-bold text-gray-400 text-center text-[8px] leading-tight\'>OFFICIAL<br>SEAL</span>'">
            </div>
            
            <!-- Center: Header Text (Perfectly centered on the paper) -->
            <div class="absolute top-0 left-0 w-full flex flex-col items-center pt-1">
                <p class="uppercase font-bold text-[11px]">Republic of the Philippines</p>
                <p class="uppercase font-bold text-[11px]">Local Government Of Lake Sebu</p>
                <p class="uppercase text-[10px]">Lake Sebu, South Cotabato</p>
                
                <h1 class="uppercase font-bold text-xl mt-6 tracking-widest text-center">APPLICATION FOR LEAVE</h1>
            </div>
            
            <!-- Right: Stamp Box -->
            <div class="absolute top-0 right-0">
                <div class="border border-gray-400 px-2 py-0.5 w-32 text-center">
                    <span class="text-[8px] text-gray-700 italic">Stamp of Date of Receipt</span>
                </div>
            </div>
        </div>

        <!-- FORM OUTER BORDER -->
        <div class="border-2 border-black">
            
            <!-- Row 1: Sections 1 & 2 -->
            <div class="grid grid-cols-[40%_60%] border-b border-black">
                <div class="p-1  relative">
                    <p class="font-bold absolute top-1 left-1">1. OFFICE/DEPARTMENT</p>
                    <p class="text-center  w-3/4 mx-auto mt-4 mb-1 uppercase font-bold">{{ $leave->office_department ?? 'HRMO' }}</p>
                </div>
                <div class="p-1 relative">
                    <p class="font-bold absolute top-1 left-1">2. NAME:</p>
                    <div class="flex justify-around text-[9px] mt-1 pr-4 pl-12">
                        <span>(Last)</span>
                        <span>(First)</span>
                        <span>(Middle)</span>
                    </div>
                    <p class="text-center  w-[90%] mx-auto mb-1 uppercase font-bold tracking-wider">{{ $leave->employee->name ?? '' }}</p>
                </div>
            </div>

            <!-- Row 2: Sections 3, 4, 5 -->
            <div class="grid grid-cols-3 border-b border-black">
                <div class="p-1 relative h-10">
                    <p class="font-bold absolute top-1 left-1">3. DATE OF FILING</p>
                    <p class="text-center  w-3/4 mx-auto mt-4 mb-1 font-bold">{{ \Carbon\Carbon::parse($leave->date_of_filing ?? $leave->created_at)->format('M d, Y') }}</p>
                </div>
                <div class="p-1 relative h-10">
                    <p class="font-bold absolute top-1 left-1">4. POSITION</p>
                    <p class="text-center  w-3/4 mx-auto mt-4 mb-1 uppercase text-[9px] font-bold">{{ $leave->employee->position ?? '---' }}</p>
                </div>
                <div class="p-1 relative h-10">
                    <p class="font-bold absolute top-1 left-1">5. SALARY</p>
                    <p class="text-center  w-3/4 mx-auto mt-4 mb-1">---</p>
                </div>
            </div>

            <!-- SECTION 6 HEADER -->
            <div class="border-b border-black text-center py-1">
                <p class="font-extrabold text-sm tracking-wider">6. DETAILS OF APPLICATION</p>
            </div>

            <!-- Row 3: 6.A and 6.B -->
            <div class="grid grid-cols-2 border-b border-black">
                
                <!-- 6.A TYPE OF LEAVE -->
                <div class="p-2 border-r border-black">
                    <p class="font-bold mb-2">6.A TYPE OF LEAVE TO BE AVAILED OF</p>
                    <div class="space-y-1 ml-2">
                        <p><span class="box {{ str_contains($typeStr, 'Vacation Leave') ? 'box-checked' : '' }}"></span> Vacation Leave <span class="text-[8px]">(Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></p>
                        <p><span class="box {{ str_contains($typeStr, 'Mandatory/Forced Leave') ? 'box-checked' : '' }}"></span> Mandatory/Forced Leave <span class="text-[8px]">(Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></p>
                        <p><span class="box {{ str_contains($typeStr, 'Sick Leave') ? 'box-checked' : '' }}"></span> Sick Leave <span class="text-[8px]">(Sec. 43, Rule XVI, Omnibus Rules Implementing E. O. No. 292)</span></p>
                        <p><span class="box {{ str_contains($typeStr, 'Maternity Leave') ? 'box-checked' : '' }}"></span> Maternity Leave <span class="text-[8px]">(R.A. No. 11210 / IRR issued by CSC, DOLE and SSS)</span></p>
                        <p><span class="box {{ str_contains($typeStr, 'Paternity Leave') ? 'box-checked' : '' }}"></span> Paternity Leave <span class="text-[8px]">(R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended)</span></p>
                        <p><span class="box {{ str_contains($typeStr, 'Special Privilege Leave') ? 'box-checked' : '' }}"></span> Special Privilege Leave <span class="text-[8px]">(Sec 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></p>
                        <p><span class="box {{ str_contains($typeStr, 'Solo Parent Leave') ? 'box-checked' : '' }}"></span> Solo Parent Leave <span class="text-[8px]">(RA No. 8972 / CSC MC No. 8, s. 2004)</span></p>
                        <p><span class="box {{ str_contains($typeStr, 'Study Leave') ? 'box-checked' : '' }}"></span> Study Leave <span class="text-[8px]">(Sec. 68, Rule XVI, Omnibus Rules implementing E.O. No. 292)</span></p>
                        <p><span class="box {{ str_contains($typeStr, '10-Day VAWC Leave') ? 'box-checked' : '' }}"></span> 10-Day VAWC Leave <span class="text-[8px]">(RA No. 9262 / CSC MC No. 15, s. 2005)</span></p>
                        <p><span class="box {{ str_contains($typeStr, 'Rehabilitation Privilege') ? 'box-checked' : '' }}"></span> Rehabilitation Privilege <span class="text-[8px]">(Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></p>
                        <p><span class="box {{ str_contains($typeStr, 'Special Leave for Women') ? 'box-checked' : '' }}"></span> Special Leave Benefits for Women <span class="text-[8px]">(RA No. 9710 / CSC MC No. 25, s. 2010)</span></p>
                        <p><span class="box {{ str_contains($typeStr, 'Special Emergency Leave') ? 'box-checked' : '' }}"></span> Special Emergency (Calamity) Leave <span class="text-[8px]">(CSC MC No. 2, s. 2012, as amended)</span></p>
                        <p><span class="box {{ str_contains($typeStr, 'Adoption Leave') ? 'box-checked' : '' }}"></span> Adoption Leave <span class="text-[8px]">(R.A. No. 8552)</span></p>
                        <div class="mt-2 flex">
                            <span class="mr-1">Others:</span>
                            <div class="border-b border-black flex-1 text-center font-bold">{{ $leave->leave_type_others ?? '' }}</div>
                        </div>
                    </div>
                </div>

                <!-- 6.B DETAILS OF LEAVE -->
                <div class="p-2 relative">
                    <p class="font-bold mb-2">6.B DETAILS OF LEAVE</p>
                    
                    <div class="space-y-3 ml-2">
                        <!-- Vacation -->
                        <div>
                            <p class="italic text-[9px]">In case of Vacation/Special Privilege Leave:</p>
                            <div class="ml-4 space-y-1">
                                <p><span class="box {{ str_contains($detailsStr, 'Within the Philippines') ? 'box-checked' : '' }}"></span> Within the Philippines</p>
                                <div class="flex"><span class="box {{ str_contains($detailsStr, 'Abroad') ? 'box-checked' : '' }}"></span> <span class="mr-1">Abroad (Specify)</span> <div class="border-b border-black flex-1 font-bold">{{ $leave->details_specify_abroad ?? '' }}</div></div>
                            </div>
                        </div>
                        
                        <!-- Sick -->
                        <div>
                            <p class="italic text-[9px]">In case of Sick Leave:</p>
                            <div class="ml-4 space-y-1">
                                <div class="flex"><span class="box {{ str_contains($detailsStr, 'In Hospital') ? 'box-checked' : '' }}"></span> <span class="mr-1">In Hospital (Specify Illness)</span> <div class="border-b border-black flex-1 font-bold">{{ $leave->details_specify_hospital ?? '' }}</div></div>
                                <div class="flex"><span class="box {{ str_contains($detailsStr, 'Out Patient') ? 'box-checked' : '' }}"></span> <span class="mr-1">Out Patient (Specify Illness)</span> <div class="border-b border-black flex-1 font-bold">{{ $leave->details_specify_outpatient ?? '' }}</div></div>
                            </div>
                        </div>

                        <!-- Women -->
                        <div>
                            <p class="italic text-[9px]">In case of Special Leave Benefits for Women:</p>
                            <div class="ml-4 flex mt-1">
                                <span class="mr-1">(Specify Illness)</span> <div class="border-b border-black flex-1 font-bold">{{ $leave->details_specify_women ?? '' }}</div>
                            </div>
                        </div>

                        <!-- Study -->
                        <div>
                            <p class="italic text-[9px]">In case of Study Leave:</p>
                            <div class="ml-4 space-y-1">
                                <p><span class="box {{ str_contains($detailsStr, 'Completion of Masters Degree') ? 'box-checked' : '' }}"></span> Completion of Master's Degree</p>
                                <p><span class="box {{ str_contains($detailsStr, 'BAR/Board Examination Review') ? 'box-checked' : '' }}"></span> BAR/Board Exam Review</p>
                            </div>
                        </div>

                        <!-- Others -->
                        <div>
                            <p class="italic text-[9px]">Other purpose:</p>
                            <div class="ml-4 space-y-1">
                                <p><span class="box {{ str_contains($detailsStr, 'Monetization of Leave Credits') ? 'box-checked' : '' }}"></span> Monetization of Leave Credits</p>
                                <p><span class="box {{ str_contains($detailsStr, 'Terminal Leave') ? 'box-checked' : '' }}"></span> Terminal Leave</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 4: 6.C and 6.D -->
            <div class="grid grid-cols-2 border-b border-black">
                <div class="p-2 border-r border-black flex flex-col justify-between">
                    <div>
                        <p class="font-bold">6.C NUMBER OF WORKING DAYS APPLIED FOR</p>
                        <p class="border-b border-black w-3/4 ml-4 mt-2 font-bold">{{ $leave->working_days ?? '' }} days</p>
                        <p class="font-bold mt-4">INCLUSIVE DATES</p>
                        <p class="border-b border-black w-3/4 ml-4 mt-2 font-bold">
                            {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                        </p>
                    </div>
                </div>
                <div class="p-2 relative flex flex-col justify-between">
                    <div>
                        <p class="font-bold">6.D COMMUTATION</p>
                        <div class="ml-4 mt-2 space-y-1">
                            <!-- Commutation remains exact match because it is a single string -->
                            <p><span class="box {{ $leave->commutation == 'Not Requested' ? 'box-checked' : '' }}"></span> Not Requested</p>
                            <p><span class="box {{ $leave->commutation == 'Requested' ? 'box-checked' : '' }}"></span> Requested</p>
                        </div>
                    </div>
                    
                    <!-- Applicant Signature -->
                    <div class="mt-8 pt-4">
                        <div class="border-b border-black w-[80%] mx-auto"></div>
                        <p class="text-center text-[9px] mt-1">(Signature of Applicant)</p>
                    </div>
                </div>
            </div>

            <!-- SECTION 7 HEADER -->
            <div class="border-b border-black text-center py-1">
                <p class="font-extrabold text-sm tracking-wider">7. DETAILS OF ACTION ON APPLICATION</p>
            </div>

            <!-- Row 5: 7.A and 7.B -->
            <div class="grid grid-cols-2 border-b border-black">
                <!-- 7.A -->
                <div class="p-2 border-r border-black">
                    <p class="font-bold mb-2">7.A CERTIFICATION OF LEAVE CREDITS</p>
                    <p class="text-center mb-1">As of <span class="border-b border-black inline-block w-24"></span></p>
                    
                    <!-- Inner Table -->
                    <table class="w-[90%] mx-auto border-collapse border border-black text-center mb-4 text-[9px]">
                        <tr>
                            <td class="border border-black p-1 font-bold">TOTAL</td>
                            <td class="border border-black p-1 font-bold">Vacation Leave</td>
                            <td class="border border-black p-1 font-bold">Sick Leave</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-bold text-left px-2 h-5"></td>
                            <td class="border border-black p-1"></td>
                            <td class="border border-black p-1"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-bold text-left px-2 h-5"></td>
                            <td class="border border-black p-1"></td>
                            <td class="border border-black p-1"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-bold text-left px-2 h-5"></td>
                            <td class="border border-black p-1"></td>
                            <td class="border border-black p-1"></td>
                        </tr>
                    </table>
                    
                    <!-- HRMO Signature -->
                    <div class="mt-6 text-center">
                        <p class="font-bold uppercase tracking-wider">FELIMON E. ELLORAN, JR.</p>
                        <div class="border-t border-black w-[80%] mx-auto mt-1"></div>
                        <p class="text-[9px]">(Authorized Officer/MGDH I (HRMO))</p>
                    </div>
                </div>
                
                <!-- 7.B -->
                <div class="p-2">
                    <p class="font-bold mb-2">7.B RECOMMENDATION</p>
                    <div class="ml-4 space-y-2 mt-4">
                        <p><span class="box"></span> For approval</p>
                        <div class="flex items-end">
                            <span class="box mb-[2px]"></span> <span class="mr-1">For disapproval due to</span> 
                            <div class="border-b border-black flex-1"></div>
                        </div>
                        <div class="border-b border-black w-full mt-4 h-4"></div>
                        <div class="border-b border-black w-full mt-4 h-4"></div>
                        <div class="border-b border-black w-full mt-4 h-4"></div>
                    </div>
                    
                    <!-- Dept Head Signature -->
                    <div class="mt-10 text-center">
                        <div class="border-b border-black w-[80%] mx-auto mt-8"></div>
                        <p class="text-[9px] mt-1">(Authorized Officer/ DEPARMENT HEAD)</p>
                    </div>
                </div>
            </div>

            <!-- Row 6: 7.C and 7.D -->
            <div class="grid grid-cols-2">
                <!-- 7.C -->
                <div class="p-2  relative pb-20">
                    <p class="font-bold mb-2">7.C APPROVED FOR:</p>
                    <div class="ml-4 space-y-1 mt-2">
                        <p><span class="border-b border-black inline-block w-12"></span> days with pay</p>
                        <p><span class="border-b border-black inline-block w-12"></span> days without pay</p>
                        <p><span class="border-b border-black inline-block w-12"></span> others (Specify)</p>
                    </div>
                </div>
                
                <!-- 7.D -->
                <div class="p-2 relative pb-20">
                    <p class="font-bold mb-2">7.D DISAPPROVED DUE TO:</p>
                    <div class="ml-4 space-y-4 mt-4">
                        <div class="border-b border-black w-[90%]"></div>
                        <div class="border-b border-black w-[90%]"></div>
                        <div class="border-b border-black w-[90%]"></div>
                    </div>
                </div>
            </div>
            
            <!-- Mayor Signature (Spans across both columns at the bottom) -->
            <div class="text-center pt-2 pb-6">
                <p class="font-bold uppercase text-sm tracking-wide">HON. ANTONIO G. FUNGAN, JR.</p>
                <div class="border-t border-black w-[60%] mx-auto mt-1"></div>
                <p class="text-[9px]">(Authorized Official/ MUNICIPAL MAYOR)</p>
            </div>

        </div>
    </div>
    <!-- ADD THIS SCRIPT TO AUTO-PRINT -->
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>