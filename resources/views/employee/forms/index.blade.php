<x-employee-layout>
    <div class="max-w-[90rem] mx-auto p-4 sm:p-6 lg:p-10">
        
        <!-- Alerts -->
        @if(session('success'))
        <div id="success-alert" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm transition-opacity duration-500 flex items-center gap-3" role="alert">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="block sm:inline font-medium text-sm sm:text-base">{{ session('success') }}</span>
        </div>
        @endif

        @if ($errors->any())
        <div id="error-alert" class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl shadow-sm transition-opacity duration-500 flex items-start gap-3" role="alert">
            <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <strong class="font-bold text-sm block">Could not save the record!</strong>
                <ul class="list-disc pl-5 mt-1 text-sm text-rose-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800">File a Request</h1>
            <p class="text-sm sm:text-base text-slate-500 mt-1">Submit your Official Business trips, Leave applications, and Pass Slips here.</p>
        </div>

        <!-- Action Buttons (Responsive: 1 col Mobile, 3 cols Tablet/PC) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-10 sm:mb-12">
            <!-- Travel Order Button -->
            <button onclick="openModal('travelModal')" class="w-full text-left flex items-center justify-between p-5 sm:p-6 bg-white rounded-2xl shadow-sm border border-emerald-100 hover:shadow-md hover:border-emerald-300 hover:-translate-y-1 transition-all duration-200 group">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-emerald-700 group-hover:text-emerald-800">Travel Order</h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1">For long-distance Official Business</p>
                </div>
                <div class="bg-emerald-50 p-3 sm:p-4 rounded-full text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-sm shrink-0 ml-3">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
            </button>

            <!-- Pass Slip Button -->
            <button onclick="openModal('passSlipModal')" class="w-full text-left flex items-center justify-between p-5 sm:p-6 bg-white rounded-2xl shadow-sm border border-purple-100 hover:shadow-md hover:border-purple-300 hover:-translate-y-1 transition-all duration-200 group">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-purple-700 group-hover:text-purple-800">Pass Slip</h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1">Local Official Business Card</p>
                </div>
                <div class="bg-purple-50 p-3 sm:p-4 rounded-full text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors shadow-sm shrink-0 ml-3">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
            </button>

            <!-- Leave Button -->
            <button onclick="openModal('leaveModal')" class="w-full text-left flex items-center justify-between p-5 sm:p-6 bg-white rounded-2xl shadow-sm border border-blue-100 hover:shadow-md hover:border-blue-300 hover:-translate-y-1 transition-all duration-200 group">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-blue-700 group-hover:text-blue-800">Apply for Leave</h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1">Sick, Vacation, or Force Leave</p>
                </div>
                <div class="bg-blue-50 p-3 sm:p-4 rounded-full text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-sm shrink-0 ml-3">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </button>
        </div>

        <!-- 🌟 Recent Applications Section (Tabbed Layout) 🌟 -->
        <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-4 flex items-center gap-2 mt-4">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Your Recent Submissions
        </h3>
        
        <!-- Folder Tabs Header -->
        <div class="flex items-end px-2 sm:px-6">
            <!-- Travel Tab (Active by default) -->
            <button onclick="switchTab('travel')" id="tab-travel" class="tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-800 bg-white border border-slate-200 border-b-white rounded-t-2xl relative z-10 translate-y-[1px]">Travel order</button>

            <!-- Pass Slip Tab -->
            <button onclick="switchTab('pass')" id="tab-pass" class="tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-500 hover:text-slate-700 transition-colors border border-transparent border-b-0 rounded-t-2xl relative z-0 translate-y-[1px]">Pass Slip</button>

            <!-- Leave Tab -->
            <button onclick="switchTab('leave')" id="tab-leave" class="tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-500 hover:text-slate-700 transition-colors border border-transparent border-b-0 rounded-t-2xl relative z-0 translate-y-[1px]">Leave</button>
        </div>

        <!-- Main Content Box (Connected to active tab) -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm relative z-0 overflow-hidden mb-10 min-h-[300px]">
            
            <!-- 1. Travel Orders List -->
            <div id="content-travel" class="tab-content block overflow-x-auto w-full">
                <table class="w-full text-sm text-left min-w-[450px]">
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentTravels as $travel)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 sm:px-8 py-5 text-slate-800 font-bold whitespace-nowrap w-1">
                                    {{ \Carbon\Carbon::parse($travel->start_date)->format('M d') }} - 
                                    {{ \Carbon\Carbon::parse($travel->end_date)->format('M d') }}
                                </td>
                                <td class="px-4 sm:px-6 py-5 text-emerald-600 font-medium whitespace-nowrap sm:whitespace-normal">{{ $travel->purpose }}</td>
                                
                                <td class="px-2 sm:px-4 py-5 text-right whitespace-nowrap w-1">
                                    @if(($travel->status ?? 'pending') === 'approved')
                                        <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 text-[10px] tracking-wider uppercase rounded-full font-bold">Approved</span>
                                    @elseif(($travel->status ?? 'pending') === 'declined')
                                        <span class="px-3 py-1.5 bg-rose-100 text-rose-700 text-[10px] tracking-wider uppercase rounded-full font-bold cursor-help" title="{{ $travel->remarks }}">Declined</span>
                                    @elseif(($travel->status ?? 'pending') === 'pending_dept_head')
                                        <span class="px-3 py-1.5 bg-amber-100 text-amber-700 text-[10px] tracking-wider uppercase rounded-full font-bold">Pending (Head)</span>
                                    @elseif(($travel->status ?? 'pending') === 'pending_hrmo')
                                        <span class="px-3 py-1.5 bg-blue-100 text-blue-700 text-[10px] tracking-wider uppercase rounded-full font-bold">Pending (HRMO)</span>
                                    @elseif(($travel->status ?? 'pending') === 'pending_mayor')
                                        <span class="px-3 py-1.5 bg-indigo-100 text-indigo-700 text-[10px] tracking-wider uppercase rounded-full font-bold">Pending (Mayor)</span>
                                    @else
                                        <span class="px-3 py-1.5 bg-slate-100 text-slate-700 text-[10px] tracking-wider uppercase rounded-full font-bold">Pending (Department Head)</span>
                                    @endif
                                </td>

                                <td class="px-3 sm:px-8 py-5 text-right whitespace-nowrap w-1">
                                    <a href="{{ route('travel_orders.print', $travel->id) }}" target="_blank" title="View Document" class="inline-flex items-center justify-center p-2 bg-slate-100 text-slate-500 hover:text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 sm:px-6 py-16 text-center text-slate-400 font-medium">No recent travel orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 2. Pass Slips List -->
            <div id="content-pass" class="tab-content hidden overflow-x-auto w-full">
                <table class="w-full text-sm text-left min-w-[450px]">
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentPassSlips ?? [] as $pass)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 sm:px-8 py-5 text-slate-800 font-bold whitespace-nowrap w-1">
                                    {{ \Carbon\Carbon::parse($pass->transaction_date)->format('M d, Y') }}
                                </td>
                                <td class="px-4 sm:px-6 py-5 text-purple-600 font-medium whitespace-nowrap sm:whitespace-normal">{{ $pass->transaction_details }}</td>
                                
                                <td class="px-2 sm:px-4 py-5 text-right whitespace-nowrap w-1">
                                    @php $status = $pass->status ?? 'pending'; @endphp
                                    
                                    @if(in_array($status, ['approved', 'approved_dept_head', 'pending_hrmo', 'pending_mayor', 'approved_hrmo']))
                                        <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 text-[10px] tracking-wider uppercase rounded-full font-bold">Approved</span>
                                    @elseif(str_contains($status, 'declined'))
                                        <span class="px-3 py-1.5 bg-rose-100 text-rose-700 text-[10px] tracking-wider uppercase rounded-full font-bold cursor-help" title="{{ $pass->remarks ?? 'Declined' }}">Declined</span>
                                    @else
                                        <span class="px-3 py-1.5 bg-amber-100 text-amber-700 text-[10px] tracking-wider uppercase rounded-full font-bold">Pending (Head)</span>
                                    @endif
                                </td>

                                <td class="px-3 sm:px-8 py-5 text-right whitespace-nowrap w-1">
                                    <a href="{{ route('pass_slips.print', $pass->id) }}" target="_blank" title="View Document" class="inline-flex items-center justify-center p-2 bg-slate-100 text-slate-500 hover:text-purple-600 hover:bg-purple-100 rounded-lg transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 sm:px-6 py-16 text-center text-slate-400 font-medium">No recent pass slips found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 3. Leaves List -->
            <div id="content-leave" class="tab-content hidden overflow-x-auto w-full">
                <table class="w-full text-sm text-left min-w-[450px]">
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentLeaves as $leave)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 sm:px-8 py-5 text-slate-800 font-bold whitespace-nowrap w-1">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - 
                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('M d') }}
                                </td>
                                <td class="px-4 sm:px-6 py-5 text-blue-600 font-medium whitespace-nowrap sm:whitespace-normal">{{ $leave->type }}</td>
                                
                                <td class="px-2 sm:px-4 py-5 text-right whitespace-nowrap w-1">
                                    @if(($leave->status ?? 'pending') === 'approved')
                                        <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 text-[10px] tracking-wider uppercase rounded-full font-bold">Approved</span>
                                    @elseif(($leave->status ?? 'pending') === 'declined')
                                        <span class="px-3 py-1.5 bg-rose-100 text-rose-700 text-[10px] tracking-wider uppercase rounded-full font-bold cursor-help" title="{{ $leave->remarks }}">Declined</span>
                                    @elseif(($leave->status ?? 'pending') === 'pending_dept_head')
                                        <span class="px-3 py-1.5 bg-amber-100 text-amber-700 text-[10px] tracking-wider uppercase rounded-full font-bold">Pending (Head)</span>
                                    @elseif(($leave->status ?? 'pending') === 'pending_hrmo')
                                        <span class="px-3 py-1.5 bg-blue-100 text-blue-700 text-[10px] tracking-wider uppercase rounded-full font-bold">Pending (HRMO)</span>
                                    @elseif(($leave->status ?? 'pending') === 'pending_mayor')
                                        <span class="px-3 py-1.5 bg-indigo-100 text-indigo-700 text-[10px] tracking-wider uppercase rounded-full font-bold">Pending (Mayor)</span>
                                    @else
                                        <span class="px-3 py-1.5 bg-slate-100 text-slate-700 text-[10px] tracking-wider uppercase rounded-full font-bold">Pending</span>
                                    @endif
                                </td>

                                <td class="px-3 sm:px-8 py-5 text-right whitespace-nowrap w-1">
                                    <a href="{{ route('leaves.print', $leave->id) }}" target="_blank" title="View Document" class="inline-flex items-center justify-center p-2 bg-slate-100 text-slate-500 hover:text-blue-600 hover:bg-blue-100 rounded-lg transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 sm:px-6 py-16 text-center text-slate-400 font-medium">No recent leaves found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

    <!-- ============================================== -->
    <!-- MODAL: Pass Slip (Official Business Card)      -->
    <!-- ============================================== -->
    <div id="passSlipModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center z-[60] p-4 sm:p-6 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all my-auto max-h-[95vh] flex flex-col">
            <div class="px-4 sm:px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-purple-50 shrink-0">
                <h3 class="font-bold text-purple-800 text-base sm:text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    OFFICIAL BUSINESS CARD (PASS SLIP)
                </h3>
                <button type="button" onclick="closeModal('passSlipModal')" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1">&times;</button>
            </div>
            
            <div class="overflow-y-auto p-4 sm:p-6 flex-1">
                <form id="passSlipForm" action="{{ route('employee.pass_slip.store') ?? '#' }}" method="POST" class="space-y-4 sm:space-y-5">
                    @csrf
                    
                    <!-- Pre-filled Employee Information -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 shadow-sm space-y-3 sm:space-y-4">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Department</label>
                            <input type="text" value="{{ $employee->department ?? '' }}" readonly class="w-full bg-slate-100 border border-slate-200 text-slate-500 text-xs sm:text-sm font-medium rounded-lg px-3 py-2 sm:py-2.5 cursor-not-allowed focus:outline-none">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Employee's Full Name</label>
                                <input type="text" value="{{ $employee->name ?? '' }}" readonly class="w-full bg-slate-100 border border-slate-200 text-slate-500 text-xs sm:text-sm font-medium rounded-lg px-3 py-2 sm:py-2.5 cursor-not-allowed focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Full Position Title</label>
                                <input type="text" value="{{ $employee->position ?? '' }}" readonly class="w-full bg-slate-100 border border-slate-200 text-slate-500 text-xs sm:text-sm font-medium rounded-lg px-3 py-2 sm:py-2.5 cursor-not-allowed focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Specify the OFFICIAL BUSINESS transaction</label>
                        <textarea name="transaction_details" rows="2" required class="w-full text-xs sm:text-sm border-slate-300 rounded-lg shadow-sm p-3 focus:ring-purple-500 focus:border-purple-500" placeholder="Details of business..."></textarea>
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Date of Official Business Transaction</label>
                            <input type="date" name="transaction_date" required class="w-full text-xs sm:text-sm border-slate-300 rounded-lg shadow-sm p-2 sm:p-2.5 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div class="p-3 sm:p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Inclusive Time</label>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] sm:text-xs font-semibold text-slate-500">From:</span>
                                <input type="time" name="time_from" required class="flex-1 text-xs sm:text-sm border-slate-300 rounded-md shadow-sm p-1.5 focus:ring-purple-500 focus:border-purple-500">
                                <span class="text-[10px] sm:text-xs font-semibold text-slate-500">To:</span>
                                <input type="time" name="time_to" required class="flex-1 text-xs sm:text-sm border-slate-300 rounded-md shadow-sm p-1.5 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                    </div>

                    <!-- Unlogged Attendance (Biometrics) -->
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Unlogged Attendance in Biometric System</label>
                        
                        <!-- Responsive Checkbox Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-4 text-xs sm:text-sm text-slate-700 font-medium mb-4">
                            <label class="flex items-center gap-2 cursor-pointer hover:text-purple-700">
                                <input type="checkbox" name="unlogged_time_in_am" value="1" class="w-4 h-4 text-purple-600 rounded border-slate-300 focus:ring-purple-500">
                                <span>TIME In <span class="text-[10px] sm:text-xs text-slate-500">(8:00 AM)</span></span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer hover:text-purple-700">
                                <input type="checkbox" name="unlogged_time_out_am" value="1" class="w-4 h-4 text-purple-600 rounded border-slate-300 focus:ring-purple-500">
                                <span>TIME Out <span class="text-[10px] sm:text-xs text-slate-500">(12:00 NN)</span></span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer hover:text-purple-700">
                                <input type="checkbox" name="unlogged_time_in_pm" value="1" class="w-4 h-4 text-purple-600 rounded border-slate-300 focus:ring-purple-500">
                                <span>TIME In <span class="text-[10px] sm:text-xs text-slate-500">(1:00 PM)</span></span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer hover:text-purple-700">
                                <input type="checkbox" name="unlogged_time_out_pm" value="1" class="w-4 h-4 text-purple-600 rounded border-slate-300 focus:ring-purple-500">
                                <span>TIME Out <span class="text-[10px] sm:text-xs text-slate-500">(5:00 PM)</span></span>
                            </label>
                        </div>
                        
                        <div class="pt-3 border-t border-slate-200">
                            <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Number of unlogged Attendance in Biometric System</label>
                            <input type="number" name="unlogged_count" min="0" max="4" class="w-full sm:w-24 text-xs sm:text-sm border-slate-300 rounded-lg shadow-sm p-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>

                    <!-- Disclaimers / Notes -->
                    <div class="bg-amber-50 p-3 sm:p-4 rounded-lg border border-amber-200 text-amber-800 text-[10px] sm:text-xs italic leading-tight space-y-1.5 shadow-inner">
                        <p>* Please use this OFFICIAL BUSINESS CARD if transaction is within the Lake Sebu jurisdiction only.</p>
                        <p>* Please file this OFFICIAL BUSINESS CARD within your respective Department and submit 1 copy to the Human Resource Management and Development Office (HRMDO).</p>
                    </div>

                </form>
            </div>
            
            <!-- Footer Buttons -->
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 shrink-0">
                <button type="button" onclick="closeModal('passSlipModal')" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-100 transition-colors order-2 sm:order-1">Cancel</button>
                <button type="button" onclick="document.getElementById('passSlipForm').submit();" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold bg-purple-600 text-white rounded-xl hover:bg-purple-700 shadow-md transition-colors order-1 sm:order-2">Submit Pass Slip</button>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL: Travel Order                            -->
    <!-- ============================================== -->
    <div id="travelModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center z-[60] p-4 sm:p-6 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all my-auto">
            <div class="px-4 sm:px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-emerald-50">
                <h3 class="font-bold text-emerald-800 text-base sm:text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Travel Order Form
                </h3>
                <button type="button" onclick="closeModal('travelModal')" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1">&times;</button>
            </div>
            
            <form action="{{ route('employee.travel.store') }}" method="POST" class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                @csrf
                
                <!-- 🌟 ADDED: Hidden Employee ID so the database knows who this belongs to 🌟 -->
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                
                <!-- 🌟 UPDATED: Sequential Database ID Display 🌟 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        @php
                            // Get current Year and Month
                            $yearMonth = \Carbon\Carbon::now()->format('Y-m');
                            
                            // Predict the NEXT database ID
                            $lastTravel = \App\Models\TravelOrder::orderBy('id', 'desc')->first();
                            $nextId = $lastTravel ? $lastTravel->id + 1 : 1;
                            
                            // Format: TO-2026-09-0001
                            $predictedToNumber = 'TO-' . $yearMonth . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                        @endphp
                        
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">T.O. Number</label>
                        <input type="text" value="{{ $predictedToNumber }}" readonly class="w-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-black tracking-widest rounded-lg shadow-sm p-2.5 sm:p-3 focus:outline-none cursor-not-allowed" title="This number will be officially locked in upon submission.">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Your Name</label>
                        <input type="text" value="{{ $employee->name }}" readonly class="w-full border-slate-200 bg-slate-100 text-slate-500 text-sm font-medium rounded-lg shadow-sm p-2.5 sm:p-3 focus:outline-none cursor-not-allowed">
                    </div>
                </div>

                <div class="p-3 sm:p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 sm:mb-3">Inclusive Date of Travel</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-semibold text-slate-500 mb-1">From</label>
                            <input type="date" name="start_date" required class="w-full text-sm border-slate-300 rounded-lg shadow-sm p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-[10px] sm:text-xs font-semibold text-slate-500 mb-1">To</label>
                            <input type="date" name="end_date" required class="w-full text-sm border-slate-300 rounded-lg shadow-sm p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                </div>

                <div class="p-3 sm:p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 sm:mb-3">Destination</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-semibold text-slate-500 mb-1">From</label>
                            <input type="text" name="departure_from" placeholder="e.g. Lake Sebu" required class="w-full text-sm border-slate-300 rounded-lg shadow-sm p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-[10px] sm:text-xs font-semibold text-slate-500 mb-1">To</label>
                            <input type="text" name="destination" placeholder="e.g. Koronadal" required class="w-full text-sm border-slate-300 rounded-lg shadow-sm p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Purpose</label>
                    <input type="text" name="purpose" placeholder="Brief reason for travel..." required class="w-full text-sm border-slate-300 rounded-lg shadow-sm p-2.5 sm:p-3 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="p-3 sm:p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 sm:mb-3">Travelling Expenses & Allowances</label>
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                        <label class="flex items-center cursor-pointer hover:text-slate-900 transition-colors">
                            <input type="radio" name="expense_type" value="Official" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" required>
                            <span class="ml-2 text-sm font-bold text-slate-700">Official</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-slate-900 transition-colors">
                            <input type="radio" name="expense_type" value="Personal" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" required>
                            <span class="ml-2 text-sm font-bold text-slate-700">Personal</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 sm:pt-6 mt-2 sm:mt-4 flex justify-end gap-2 sm:gap-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('travelModal')" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">Cancel</button>
                    <button type="submit" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-md transition-colors">Submit Order</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL: Leave                                   -->
    <!-- ============================================== -->
    <div id="leaveModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center z-[60] p-4 sm:p-6">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[95vh] overflow-hidden flex flex-col">
            <div class="px-4 sm:px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-blue-50 shrink-0">
                <h3 class="font-bold text-blue-800 text-base sm:text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Leave Application Form
                </h3>
                <button type="button" onclick="closeModal('leaveModal')" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1">&times;</button>
            </div>
            
            <div class="overflow-y-auto p-4 sm:p-6 md:p-8 flex-1">
                <form id="leaveForm" action="{{ route('employee.leave.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5 bg-slate-50/80 p-4 sm:p-5 rounded-xl border border-slate-200 shadow-sm">
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 mb-1 sm:mb-1.5 uppercase tracking-wider">1. Office/Department</label>
                            <input type="text" name="office_department" class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-md shadow-sm px-3 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 transition-colors" placeholder="e.g. HRMO">
                        </div>
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 mb-1 sm:mb-1.5 uppercase tracking-wider">2. Your Name</label>
                            <input type="text" value="{{ $employee->name }}" readonly class="w-full text-xs sm:text-sm bg-slate-200 border-slate-300 text-slate-600 font-medium rounded-md shadow-sm px-3 py-2 sm:py-2.5 cursor-not-allowed">
                        </div>
                        <div class="sm:col-span-2 md:col-span-1">
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 mb-1 sm:mb-1.5 uppercase tracking-wider">3. Date of Filing</label>
                            <input type="date" name="date_of_filing" value="{{ date('Y-m-d') }}" class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-md shadow-sm px-3 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 transition-colors" required>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-5 sm:pt-6">
                        <h3 class="font-black text-slate-800 text-xs sm:text-sm tracking-wide mb-4 sm:mb-5">6. DETAILS OF APPLICATION</h3>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
                            
                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm">
                                <label class="block text-[11px] sm:text-xs font-bold text-slate-700 mb-3 sm:mb-4 uppercase tracking-wide">6.A Type of Leave</label>
                                <div class="space-y-2.5 sm:space-y-3 text-xs sm:text-sm text-slate-600 font-medium">
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="Vacation Leave" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500" required> Vacation Leave (Sec. 51, Rule XVI...)</label>
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="Mandatory/Forced Leave" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Mandatory/Forced Leave (Sec. 25...)</label>
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="Sick Leave" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Sick Leave (Sec. 43, Rule XVI...)</label>
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="Maternity Leave" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Maternity Leave (R.A. No. 11210)</label>
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="Paternity Leave" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Paternity Leave (R.A. No. 8187)</label>
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="Special Privilege Leave" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Special Privilege Leave (Sec. 21...)</label>
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="Solo Parent Leave" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Solo Parent Leave (RA No. 8972)</label>
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="Study Leave" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Study Leave (Sec. 68, Rule XVI...)</label>
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="10-Day VAWC Leave" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> 10-Day VAWC Leave (RA No. 9262)</label>
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="Rehabilitation Privilege" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Rehabilitation Privilege (Sec. 55...)</label>
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="Special Leave for Women" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Special Leave for Women (RA 9710)</label>
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="Special Emergency Leave" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Special Emergency (Calamity) Leave</label>
                                    <label class="flex items-start cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="leave_type" value="Adoption Leave" class="mt-0.5 sm:mt-1 mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Adoption Leave (R.A. No. 8552)</label>
                                    
                                    <div class="flex items-center sm:items-end mt-3 sm:mt-4 pt-3 border-t border-slate-100">
                                        <span class="mr-2 sm:mr-3 font-bold text-slate-700 whitespace-nowrap">Others:</span>
                                        <input type="text" name="leave_type_others" class="flex-1 min-w-0 text-xs sm:text-sm bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-1 sm:px-2 transition-colors placeholder:text-slate-400" placeholder="Specify here...">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm">
                                <label class="block text-[11px] sm:text-xs font-bold text-slate-700 mb-3 sm:mb-4 uppercase tracking-wide">6.B Details of Leave</label>
                                
                                <div class="space-y-4 sm:space-y-6 text-xs sm:text-sm text-slate-600 font-medium">
                                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                        <span class="block mb-2 font-bold text-slate-700 text-[11px] sm:text-xs">Vacation/Special Privilege Leave:</span>
                                        <label class="flex items-center ml-1 sm:ml-2 mb-2 cursor-pointer hover:text-slate-900"><input type="radio" name="leave_details" value="Within the Philippines" class="mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Within the Philippines</label>
                                        <div class="flex flex-col sm:flex-row sm:items-end ml-1 sm:ml-2 group gap-1 sm:gap-0">
                                            <label class="flex items-center cursor-pointer hover:text-slate-900 whitespace-nowrap"><input type="radio" name="leave_details" value="Abroad" class="mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Abroad (Specify)</label>
                                            <input type="text" name="details_specify_abroad" class="w-full sm:flex-1 text-xs sm:text-sm bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-1 sm:px-2 sm:ml-3 transition-colors">
                                        </div>
                                    </div>

                                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                        <span class="block mb-2 font-bold text-slate-700 text-[11px] sm:text-xs">Sick Leave:</span>
                                        <div class="flex flex-col sm:flex-row sm:items-end ml-1 sm:ml-2 mb-2 group gap-1 sm:gap-0">
                                            <label class="flex items-center cursor-pointer hover:text-slate-900 whitespace-nowrap"><input type="radio" name="leave_details" value="In Hospital" class="mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> In Hospital (Specify)</label>
                                            <input type="text" name="details_specify_hospital" class="w-full sm:flex-1 text-xs sm:text-sm bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-1 sm:px-2 sm:ml-3 transition-colors">
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:items-end ml-1 sm:ml-2 group gap-1 sm:gap-0">
                                            <label class="flex items-center cursor-pointer hover:text-slate-900 whitespace-nowrap"><input type="radio" name="leave_details" value="Out Patient" class="mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Out Patient (Specify)</label>
                                            <input type="text" name="details_specify_outpatient" class="w-full sm:flex-1 text-xs sm:text-sm bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-1 sm:px-2 sm:ml-3 transition-colors">
                                        </div>
                                    </div>

                                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                        <span class="block mb-2 font-bold text-slate-700 text-[11px] sm:text-xs">Special Leave Benefits for Women:</span>
                                        <div class="flex flex-col sm:flex-row sm:items-end ml-1 sm:ml-2 group gap-1 sm:gap-0">
                                            <span class="whitespace-nowrap font-medium text-xs">(Specify Illness)</span>
                                            <input type="text" name="details_specify_women" class="w-full sm:flex-1 text-xs sm:text-sm bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-1 sm:px-2 sm:ml-3 transition-colors">
                                        </div>
                                    </div>

                                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                        <span class="block mb-2 font-bold text-slate-700 text-[11px] sm:text-xs">Study Leave:</span>
                                        <label class="flex items-center ml-1 sm:ml-2 mb-2 cursor-pointer hover:text-slate-900"><input type="radio" name="leave_details" value="Completion of Masters Degree" class="mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Completion of Master's Degree</label>
                                        <label class="flex items-center ml-1 sm:ml-2 cursor-pointer hover:text-slate-900"><input type="radio" name="leave_details" value="BAR/Board Examination Review" class="mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> BAR/Board Exam Review</label>
                                    </div>

                                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                        <span class="block mb-2 font-bold text-slate-700 text-[11px] sm:text-xs">Other purpose:</span>
                                        <label class="flex items-center ml-1 sm:ml-2 mb-2 cursor-pointer hover:text-slate-900"><input type="radio" name="leave_details" value="Monetization of Leave Credits" class="mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Monetization of Leave Credits</label>
                                        <label class="flex items-center ml-1 sm:ml-2 cursor-pointer hover:text-slate-900"><input type="radio" name="leave_details" value="Terminal Leave" class="mr-2 sm:mr-3 text-blue-600 focus:ring-blue-500"> Terminal Leave</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 mt-5 sm:mt-6">
                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm">
                                <label class="block text-[11px] sm:text-xs font-bold text-slate-700 mb-2 sm:mb-3 uppercase tracking-wide">6.C Working Days Applied For</label>
                                <input type="number" name="working_days" class="w-full text-xs sm:text-sm bg-slate-50 border border-slate-300 rounded-lg shadow-sm px-3 py-2 sm:py-2.5 mb-4 sm:mb-5 focus:ring-2 focus:ring-blue-500 transition-colors" placeholder="e.g. 5" required>
                                
                                <label class="block text-[11px] sm:text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Inclusive Dates</label>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                                    <input type="date" name="start_date" class="w-full sm:w-1/2 text-xs sm:text-sm bg-slate-50 border border-slate-300 rounded-lg shadow-sm px-3 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 transition-colors" required>
                                    <span class="text-slate-400 font-bold text-xs uppercase text-center hidden sm:block">to</span>
                                    <input type="date" name="end_date" class="w-full sm:w-1/2 text-xs sm:text-sm bg-slate-50 border border-slate-300 rounded-lg shadow-sm px-3 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 transition-colors" required>
                                </div>
                            </div>

                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-center">
                                <label class="block text-[11px] sm:text-xs font-bold text-slate-700 mb-3 sm:mb-4 uppercase tracking-wide">6.D Commutation</label>
                                <div class="space-y-3 sm:space-y-4 text-xs sm:text-sm text-slate-600 font-medium sm:ml-2">
                                    <label class="flex items-center cursor-pointer hover:text-slate-900"><input type="radio" name="commutation" value="Not Requested" class="mr-2 sm:mr-3 w-4 h-4 text-blue-600 focus:ring-blue-500" required> Not Requested</label>
                                    <label class="flex items-center cursor-pointer hover:text-slate-900"><input type="radio" name="commutation" value="Requested" class="mr-2 sm:mr-3 w-4 h-4 text-blue-600 focus:ring-blue-500"> Requested</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 shrink-0">
                <button type="button" onclick="closeModal('leaveModal')" class="w-full sm:w-auto px-5 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-100 transition-colors order-2 sm:order-1">Cancel</button>
                <button type="button" onclick="document.getElementById('leaveForm').submit();" class="w-full sm:w-auto px-5 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold bg-blue-700 text-white rounded-xl hover:bg-blue-800 shadow-md transition-colors order-1 sm:order-2">Submit Leave Application</button>
            </div>
        </div>
    </div>

    <!-- Scripts for Alerts & Modals & Tabs -->
    <script>
        // Alert Hide Function
        setTimeout(() => {
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');
            
            if (successAlert) {
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500); 
            }
            if (errorAlert) {
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 500); 
            }
        }, 5000); 

        // Modal Functions
        function openModal(modalID) {
            document.getElementById(modalID).classList.remove('hidden');
        }
        function closeModal(modalID) {
            document.getElementById(modalID).classList.add('hidden');
        }

        // Folder Tab Switcher
        function switchTab(tabId) {
            const tabs = ['travel', 'pass', 'leave'];

            tabs.forEach(id => {
                const content = document.getElementById('content-' + id);
                const btn = document.getElementById('tab-' + id);

                if (id === tabId) {
                    content.classList.remove('hidden');
                    content.classList.add('block');
                    btn.className = "tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-800 bg-white border border-slate-200 border-b-white rounded-t-2xl relative z-10 translate-y-[1px]";
                } else {
                    content.classList.remove('block');
                    content.classList.add('hidden');
                    btn.className = "tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-500 hover:text-slate-700 transition-colors border border-transparent border-b-0 rounded-t-2xl relative z-0 translate-y-[1px]";
                }
            });
        }
    </script>
</x-employee-layout>