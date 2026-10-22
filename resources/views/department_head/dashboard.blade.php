<x-employee-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 space-y-6 md:space-y-8">
        
        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-4">
            <h1 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-3">
                Department Head Portal <span class="text-2xl">👑</span>
            </h1>
            <p class="text-slate-500 mt-1">Managing Department: <strong class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ $me->department }}</strong></p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-700 font-bold rounded-xl border border-emerald-200 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- 🌟 Folder Tabs Header (Reordered) 🌟 -->
        <div class="flex items-end px-2 sm:px-6 mt-6 overflow-x-auto hide-scrollbar">
            <!-- Travel Tab (Active by default) -->
            <button onclick="switchTab('travel')" id="tab-travel" class="tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-800 bg-white border border-slate-200 border-b-white rounded-t-2xl relative z-10 translate-y-[1px] flex items-center gap-2 whitespace-nowrap">
                Travel Orders
                @if($pendingTravels->count() > 0)
                    <span class="bg-amber-100 text-amber-700 text-[10px] sm:text-xs font-bold py-0.5 px-2.5 rounded-full shadow-sm">{{ $pendingTravels->count() }}</span>
                @endif
            </button>

            <!-- Pass Slip Tab -->
            <button onclick="switchTab('pass')" id="tab-pass" class="tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-500 hover:text-slate-700 transition-colors border border-transparent border-b-0 rounded-t-2xl relative z-0 translate-y-[1px] flex items-center gap-2 whitespace-nowrap">
                Pass Slips
                @if(isset($pendingPassSlips) && $pendingPassSlips->count() > 0)
                    <span class="bg-purple-100 text-purple-700 text-[10px] sm:text-xs font-bold py-0.5 px-2.5 rounded-full shadow-sm">{{ $pendingPassSlips->count() }}</span>
                @endif
            </button>

            <!-- Leave Tab -->
            <button onclick="switchTab('leave')" id="tab-leave" class="tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-500 hover:text-slate-700 transition-colors border border-transparent border-b-0 rounded-t-2xl relative z-0 translate-y-[1px] flex items-center gap-2 whitespace-nowrap">
                Leave Applications
                @if($pendingLeaves->count() > 0)
                    <span class="bg-rose-100 text-rose-700 text-[10px] sm:text-xs font-bold py-0.5 px-2.5 rounded-full shadow-sm">{{ $pendingLeaves->count() }}</span>
                @endif
            </button>
        </div>

        <!-- Main Content Box (Connected to active tab) -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm relative z-0 overflow-hidden mb-10 min-h-[400px]">
            
            <!-- ========================================== -->
            <!-- 1. CONTENT: PENDING TRAVEL ORDERS (Active) -->
            <!-- ========================================== -->
            <div id="content-travel" class="tab-content block p-5 space-y-3">
                @forelse($pendingTravels as $travel)
                    <div x-data="{ activeModal: null }" class="p-4 bg-slate-50 hover:bg-amber-50/30 transition border border-slate-100 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                        
                        <div class="flex-1 cursor-pointer group" @click="activeModal = 'review'">
                            <p class="font-black text-slate-800 group-hover:text-amber-700 transition">{{ $travel->employee->name }}</p>
                            <p class="text-xs font-bold text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($travel->start_date)->format('M d, Y') }}</p>
                            <p class="text-xs font-medium text-slate-600 mt-1 flex items-center gap-1"><svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ $travel->destination ?? 'Official Travel' }}</p>
                        </div>
                        
                        <div class="shrink-0 flex items-center gap-3">
                            <button type="button" @click.stop="activeModal = 'review'" class="text-sm font-bold text-slate-500 hover:text-amber-600 underline underline-offset-2">Review</button>
                            <button type="button" @click.stop="activeModal = 'decline'" class="px-4 py-2 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-lg text-sm font-bold shadow-sm transition">Decline</button>
                            <button type="button" @click.stop="activeModal = 'approve'" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-bold shadow-sm transition">Approve &rarr;</button>
                        </div>

                        <template x-teleport="body">
                            <div x-show="activeModal !== null" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-transition.opacity>
                                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="activeModal = null"></div>
                                
                                <!-- REVIEW MODAL -->
                                <div x-show="activeModal === 'review'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col" x-transition>
                                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                        <h3 class="font-black text-xl text-slate-800 flex items-center gap-3"><span class="bg-amber-100 p-2 rounded-lg text-amber-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>Travel Order</h3>
                                        <button @click="activeModal = null" class="text-slate-400 hover:bg-slate-200 hover:text-slate-800 rounded-full p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                    </div>
                                    <div class="p-6 md:p-8 space-y-6">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-slate-100 pb-6">
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Employee</span><strong class="text-slate-800 text-lg">{{ $travel->employee->name }}</strong></div>
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">TO Number</span><strong class="text-slate-800 font-mono text-sm md:text-base bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">{{ $travel->form_number ?? 'Pending' }}</strong></div>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-slate-100 pb-6">
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Departure</span><strong class="text-slate-800 text-sm md:text-base">{{ $travel->departure_from ?? 'N/A' }}</strong></div>
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Destination</span><strong class="text-amber-700 bg-amber-50 border border-amber-100 px-3 py-1 rounded-lg text-sm">{{ $travel->destination ?? 'N/A' }}</strong></div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-6 border-b border-slate-100 pb-6">
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Start Date</span><strong class="text-slate-800 text-sm md:text-base">{{ \Carbon\Carbon::parse($travel->start_date)->format('F d, Y') }}</strong></div>
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">End Date</span><strong class="text-slate-800 text-sm md:text-base">{{ \Carbon\Carbon::parse($travel->end_date)->format('F d, Y') }}</strong></div>
                                        </div>
                                        <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-2">Purpose</span><div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-slate-700 text-sm leading-relaxed">{{ $travel->purpose ?? 'No purpose specified.' }}</div></div>
                                    </div>
                                    <div class="bg-slate-50 p-4 md:px-8 border-t border-slate-200 flex justify-end gap-3">
                                        <button @click="activeModal = null" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Cancel</button>
                                        <button @click="activeModal = 'decline'" type="button" class="px-5 py-2.5 bg-rose-500 text-white font-bold rounded-lg hover:bg-rose-600">Decline</button>
                                        <button @click="activeModal = 'approve'" type="button" class="px-6 py-2.5 bg-amber-500 text-white font-bold rounded-lg hover:bg-amber-600">Approve & Forward</button>
                                    </div>
                                </div>

                                <!-- APPROVE MODAL -->
                                <div x-show="activeModal === 'approve'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col text-center" x-transition>
                                    <div class="p-8 space-y-4">
                                        <div class="w-16 h-16 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                                        <h3 class="font-black text-2xl text-slate-800">Confirm Approval</h3>
                                        <p class="text-slate-500">Are you sure you want to approve this travel order for <strong>{{ $travel->employee->name }}</strong> and forward it to the HRMO?</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-center gap-3">
                                        <button @click="activeModal = 'review'" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Back</button>
                                        <form action="{{ route('dept_head.approve_travel', $travel->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 shadow-md transition">Yes, Approve It</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- DECLINE MODAL -->
                                <div x-show="activeModal === 'decline'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col" x-transition>
                                    <div class="px-6 py-4 border-b border-rose-100 bg-rose-50 flex justify-between items-center">
                                        <h3 class="font-black text-xl text-rose-800 flex items-center gap-3"><svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Decline Request</h3>
                                    </div>
                                    <form action="{{ route('dept_head.decline_travel', $travel->id) }}" method="POST" class="m-0 flex flex-col h-full">
                                        @csrf
                                        <div class="p-6 md:p-8 space-y-4">
                                            <p class="text-slate-600 text-sm">You are declining the travel request for <strong>{{ $travel->employee->name }}</strong>. Please provide a reason below.</p>
                                            <div class="space-y-1">
                                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Remarks / Reason</label>
                                                <textarea name="remarks" rows="4" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-lg focus:ring-rose-500 focus:border-rose-500 p-3" placeholder="Explain why this request is being declined..."></textarea>
                                            </div>
                                        </div>
                                        <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-end gap-3 mt-auto">
                                            <button @click="activeModal = 'review'" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Cancel</button>
                                            <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white font-bold rounded-lg hover:bg-rose-700 shadow-md">Confirm Decline</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </template>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 italic text-center py-16">No pending travel orders to review.</p>
                @endforelse
            </div>

            <!-- ========================================== -->
            <!-- 2. CONTENT: PENDING PASS SLIPS (Hidden)    -->
            <!-- ========================================== -->
            <div id="content-pass" class="tab-content hidden p-5 space-y-3">
                @forelse($pendingPassSlips ?? [] as $pass)
                    <div x-data="{ activeModal: null }" class="p-4 bg-slate-50 hover:bg-purple-50/30 transition border border-slate-100 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                        
                        <div class="flex-1 cursor-pointer group" @click="activeModal = 'review'">
                            <p class="font-black text-slate-800 group-hover:text-purple-700 transition">{{ $pass->employee->name }}</p>
                            <p class="text-xs font-bold text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($pass->transaction_date)->format('M d, Y') }}</p>
                            <p class="text-xs font-medium text-slate-600 mt-1 truncate max-w-sm">{{ $pass->transaction_details }}</p>
                        </div>
                        
                        <div class="shrink-0 flex items-center gap-3">
                            <button type="button" @click.stop="activeModal = 'review'" class="text-sm font-bold text-slate-500 hover:text-purple-600 underline underline-offset-2">Review</button>
                            <button type="button" @click.stop="activeModal = 'decline'" class="px-4 py-2 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-lg text-sm font-bold shadow-sm transition">Decline</button>
                            <button type="button" @click.stop="activeModal = 'approve'" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-bold shadow-sm transition">Approve &rarr;</button>
                        </div>

                        <template x-teleport="body">
                            <div x-show="activeModal !== null" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-transition.opacity>
                                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="activeModal = null"></div>
                                
                                <!-- REVIEW MODAL -->
                                <div x-show="activeModal === 'review'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col" x-transition>
                                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                        <h3 class="font-black text-xl text-slate-800 flex items-center gap-3"><span class="bg-purple-100 p-2 rounded-lg text-purple-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg></span>Pass Slip Request</h3>
                                        <button @click="activeModal = null" class="text-slate-400 hover:bg-slate-200 hover:text-slate-800 rounded-full p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                    </div>
                                    
                                    <div class="p-6 md:p-8 space-y-6">
                                        <!-- Employee Info Section -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-slate-100 pb-6">
                                            <div>
                                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Employee Name</span>
                                                <strong class="text-slate-800 text-lg">{{ $pass->employee->name }}</strong>
                                            </div>
                                            <div>
                                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Transaction Date</span>
                                                <strong class="text-slate-800 text-sm md:text-base">{{ \Carbon\Carbon::parse($pass->transaction_date)->format('F d, Y') }}</strong>
                                            </div>
                                            <div>
                                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Department</span>
                                                <strong class="text-slate-800 text-sm md:text-base">{{ $pass->employee->department ?? 'N/A' }}</strong>
                                            </div>
                                            <div>
                                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Position</span>
                                                <strong class="text-slate-800 text-sm md:text-base">{{ $pass->employee->position ?? 'N/A' }}</strong>
                                            </div>
                                        </div>

                                        <!-- Time Section -->
                                        <div class="grid grid-cols-2 gap-6 border-b border-slate-100 pb-6">
                                            <div>
                                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Time From</span>
                                                <strong class="text-slate-800 text-sm md:text-base">{{ \Carbon\Carbon::parse($pass->time_from)->format('h:i A') }}</strong>
                                            </div>
                                            <div>
                                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Time To</span>
                                                <strong class="text-slate-800 text-sm md:text-base">{{ \Carbon\Carbon::parse($pass->time_to)->format('h:i A') }}</strong>
                                            </div>
                                        </div>

                                        <!-- Details Section -->
                                        <div class="border-b border-slate-100 pb-6">
                                            <span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-2">Transaction Details</span>
                                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-slate-700 text-sm leading-relaxed whitespace-pre-wrap">{{ $pass->transaction_details }}</div>
                                        </div>

                                        <!-- Biometrics Section -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                            <div>
                                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-2">Unlogged Attendance</span>
                                                @if($pass->unlogged_time_in_am || $pass->unlogged_time_out_am || $pass->unlogged_time_in_pm || $pass->unlogged_time_out_pm)
                                                    <ul class="list-disc pl-5 text-sm font-bold text-slate-700 space-y-1">
                                                        @if($pass->unlogged_time_in_am) <li>TIME In (8:00 AM)</li> @endif
                                                        @if($pass->unlogged_time_out_am) <li>TIME Out (12:00 NN)</li> @endif
                                                        @if($pass->unlogged_time_in_pm) <li>TIME In (1:00 PM)</li> @endif
                                                        @if($pass->unlogged_time_out_pm) <li>TIME Out (5:00 PM)</li> @endif
                                                    </ul>
                                                @else
                                                    <p class="text-sm text-slate-500 italic">No unlogged time selected.</p>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-2">Total Unlogged Count</span>
                                                <strong class="text-xl font-black text-purple-700">{{ $pass->unlogged_count ?? '0' }}</strong>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <!-- Footer Actions -->
                                    <div class="bg-slate-50 p-4 md:px-8 border-t border-slate-200 flex justify-end gap-3">
                                        <button @click="activeModal = null" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100 transition-colors">Cancel</button>
                                        <button @click="activeModal = 'decline'" type="button" class="px-5 py-2.5 bg-rose-500 text-white font-bold rounded-lg hover:bg-rose-600 transition-colors">Decline</button>
                                        <button @click="activeModal = 'approve'" type="button" class="px-6 py-2.5 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 transition-colors shadow-md">Approve & Forward</button>
                                    </div>
                                </div>

                                <!-- APPROVE MODAL -->
                                <div x-show="activeModal === 'approve'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col text-center" x-transition>
                                    <div class="p-8 space-y-4">
                                        <div class="w-16 h-16 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                                        <h3 class="font-black text-2xl text-slate-800">Confirm Approval</h3>
                                        <p class="text-slate-500">Are you sure you want to approve this pass slip for <strong>{{ $pass->employee->name }}</strong> and forward it to the HRMO?</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-center gap-3">
                                        <button @click="activeModal = 'review'" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Back</button>
                                        <form action="{{ route('dept_head.approve_pass_slip', $pass->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 shadow-md transition">Yes, Approve It</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- DECLINE MODAL -->
                                <div x-show="activeModal === 'decline'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col" x-transition>
                                    <div class="px-6 py-4 border-b border-rose-100 bg-rose-50 flex justify-between items-center">
                                        <h3 class="font-black text-xl text-rose-800 flex items-center gap-3"><svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Decline Request</h3>
                                    </div>
                                    <form action="{{ route('dept_head.decline_pass_slip', $pass->id) }}" method="POST" class="m-0 flex flex-col h-full">
                                        @csrf
                                        <div class="p-6 md:p-8 space-y-4">
                                            <p class="text-slate-600 text-sm">You are declining the pass slip request for <strong>{{ $pass->employee->name }}</strong>. Please provide a reason below.</p>
                                            <div class="space-y-1">
                                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Remarks / Reason</label>
                                                <textarea name="remarks" rows="4" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-lg focus:ring-rose-500 focus:border-rose-500 p-3" placeholder="Explain why this request is being declined..."></textarea>
                                            </div>
                                        </div>
                                        <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-end gap-3 mt-auto">
                                            <button @click="activeModal = 'review'" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Cancel</button>
                                            <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white font-bold rounded-lg hover:bg-rose-700 shadow-md">Confirm Decline</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </template>
                        
                    </div>
                @empty
                    <p class="text-sm text-slate-400 italic text-center py-16">No pending pass slips to review.</p>
                @endforelse
            </div>

            <!-- ========================================== -->
            <!-- 3. CONTENT: PENDING LEAVES (Hidden)        -->
            <!-- ========================================== -->
            <div id="content-leave" class="tab-content hidden p-5 space-y-3">
                @forelse($pendingLeaves as $leave)
                    <div x-data="{ activeModal: null }" class="p-4 bg-slate-50 hover:bg-indigo-50/40 transition border border-slate-100 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                        
                        <div class="flex-1 cursor-pointer group" @click="activeModal = 'review'">
                            <p class="font-black text-slate-800 group-hover:text-indigo-700 transition">{{ $leave->employee->name }}</p>
                            <p class="text-xs font-bold text-slate-500 mt-0.5">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} 
                                @if($leave->end_date && $leave->start_date !== $leave->end_date)
                                    - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                @endif
                            </p>
                            <span class="inline-block mt-2 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-200 text-slate-600">{{ $leave->type ?? 'Leave' }}</span>
                        </div>
                        
                        <div class="shrink-0 flex items-center gap-3">
                            <button type="button" @click.stop="activeModal = 'review'" class="text-sm font-bold text-slate-500 hover:text-indigo-600 underline underline-offset-2">Review</button>
                            <button type="button" @click.stop="activeModal = 'decline'" class="px-4 py-2 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-lg text-sm font-bold shadow-sm transition">Decline</button>
                            <button type="button" @click.stop="activeModal = 'approve'" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold shadow-sm transition">Approve &rarr;</button>
                        </div>

                        <template x-teleport="body">
                            <div x-show="activeModal !== null" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-transition.opacity>
                                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="activeModal = null"></div>
                                
                                <!-- REVIEW MODAL -->
                                <div x-show="activeModal === 'review'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col" x-transition>
                                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                        <h3 class="font-black text-xl text-slate-800 flex items-center gap-3"><span class="bg-rose-100 p-2 rounded-lg text-rose-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></span>Leave Request</h3>
                                        <button @click="activeModal = null" class="text-slate-400 hover:bg-slate-200 hover:text-slate-800 rounded-full p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                    </div>
                                    <div class="p-6 md:p-8 space-y-6">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-slate-100 pb-6">
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Employee</span><strong class="text-slate-800 text-lg">{{ $leave->employee->name }}</strong></div>
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Leave Type</span><strong class="text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-lg text-sm">{{ $leave->type }}</strong></div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-6 border-b border-slate-100 pb-6">
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Start Date</span><strong class="text-slate-800 text-sm md:text-base">{{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') }}</strong></div>
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">End Date</span><strong class="text-slate-800 text-sm md:text-base">{{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}</strong></div>
                                        </div>
                                        <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-2">Reason for Leave</span><div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-slate-700 text-sm leading-relaxed">{{ $leave->reason ?? $leave->leave_details ?? 'No additional details provided.' }}</div></div>
                                    </div>
                                    <div class="bg-slate-50 p-4 md:px-8 border-t border-slate-200 flex justify-end gap-3">
                                        <button @click="activeModal = null" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Cancel</button>
                                        <button @click="activeModal = 'decline'" type="button" class="px-5 py-2.5 bg-rose-500 text-white font-bold rounded-lg hover:bg-rose-600">Decline</button>
                                        <button @click="activeModal = 'approve'" type="button" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700">Approve & Forward</button>
                                    </div>
                                </div>

                                <!-- APPROVE MODAL -->
                                <div x-show="activeModal === 'approve'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col text-center" x-transition>
                                    <div class="p-8 space-y-4">
                                        <div class="w-16 h-16 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                                        <h3 class="font-black text-2xl text-slate-800">Confirm Approval</h3>
                                        <p class="text-slate-500">Are you sure you want to approve this leave for <strong>{{ $leave->employee->name }}</strong> and forward it to the HRMO?</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-center gap-3">
                                        <button @click="activeModal = 'review'" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Back</button>
                                        <form action="{{ route('dept_head.approve_leave', $leave->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 shadow-md transition">Yes, Approve It</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- DECLINE MODAL -->
                                <div x-show="activeModal === 'decline'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col" x-transition>
                                    <div class="px-6 py-4 border-b border-rose-100 bg-rose-50 flex justify-between items-center">
                                        <h3 class="font-black text-xl text-rose-800 flex items-center gap-3"><svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Decline Request</h3>
                                    </div>
                                    <form action="{{ route('dept_head.decline_leave', $leave->id) }}" method="POST" class="m-0 flex flex-col h-full">
                                        @csrf
                                        <div class="p-6 md:p-8 space-y-4">
                                            <p class="text-slate-600 text-sm">You are declining the leave request for <strong>{{ $leave->employee->name }}</strong>. Please provide a reason below.</p>
                                            <div class="space-y-1">
                                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Remarks / Reason</label>
                                                <textarea name="remarks" rows="4" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-lg focus:ring-rose-500 focus:border-rose-500 p-3" placeholder="Explain why this request is being declined..."></textarea>
                                            </div>
                                        </div>
                                        <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-end gap-3 mt-auto">
                                            <button @click="activeModal = 'review'" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Cancel</button>
                                            <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white font-bold rounded-lg hover:bg-rose-700 shadow-md">Confirm Decline</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 italic text-center py-16">No pending leaves to review.</p>
                @endforelse
            </div>
            
        </div>
    </div>

    <!-- Script for Folder Tabs -->
    <script>
        function switchTab(tabId) {
            const tabs = ['travel', 'pass', 'leave'];

            tabs.forEach(id => {
                const content = document.getElementById('content-' + id);
                const btn = document.getElementById('tab-' + id);

                if (id === tabId) {
                    content.classList.remove('hidden');
                    content.classList.add('block');
                    btn.className = "tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-800 bg-white border border-slate-200 border-b-white rounded-t-2xl relative z-10 translate-y-[1px] flex items-center gap-2 whitespace-nowrap";
                } else {
                    content.classList.remove('block');
                    content.classList.add('hidden');
                    btn.className = "tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-500 hover:text-slate-700 transition-colors border border-transparent border-b-0 rounded-t-2xl relative z-0 translate-y-[1px] flex items-center gap-2 whitespace-nowrap";
                }
            });
        }
    </script>
</x-employee-layout>