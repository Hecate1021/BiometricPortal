<x-employee-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 space-y-6 md:space-y-8">
        
        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-4">
            <h1 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-3">
                Executive Portal 🏛️
            </h1>
            <p class="text-slate-500 mt-1">Final approval queue for municipal documents.</p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-700 font-bold rounded-xl border border-emerald-200 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- 🌟 Folder Tabs Header 🌟 -->
        <div class="flex items-end px-2 sm:px-6 mt-6">
            <!-- Leave Tab (Active by default) -->
            <button onclick="switchTab('leave')" id="tab-leave" class="tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-800 bg-white border border-slate-200 border-b-white rounded-t-2xl relative z-10 translate-y-[1px] flex items-center gap-2">
                Leave Applications
                @if($pendingLeaves->count() > 0)
                    <span class="bg-blue-100 text-blue-700 text-[10px] sm:text-xs font-bold py-0.5 px-2.5 rounded-full shadow-sm">{{ $pendingLeaves->count() }}</span>
                @endif
            </button>

            <!-- Travel Tab -->
            <button onclick="switchTab('travel')" id="tab-travel" class="tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-500 hover:text-slate-700 transition-colors border border-transparent border-b-0 rounded-t-2xl relative z-0 translate-y-[1px] flex items-center gap-2">
                Travel Orders
                @if($pendingTravels->count() > 0)
                    <span class="bg-emerald-100 text-emerald-700 text-[10px] sm:text-xs font-bold py-0.5 px-2.5 rounded-full shadow-sm">{{ $pendingTravels->count() }}</span>
                @endif
            </button>
        </div>

        <!-- Main Content Box (Connected to active tab) -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm relative z-0 overflow-hidden mb-10 min-h-[400px]">
            
            <!-- ========================================== -->
            <!-- CONTENT: PENDING LEAVES (Active Default)   -->
            <!-- ========================================== -->
            <div id="content-leave" class="tab-content block p-5 space-y-3">
                @forelse($pendingLeaves as $leave)
                    <div x-data="{ activeModal: null }" class="p-4 bg-slate-50 hover:bg-slate-100 transition border border-slate-100 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                        
                        <div class="flex-1 cursor-pointer group" @click="activeModal = 'review'">
                            <p class="font-black text-slate-800 group-hover:text-blue-700 transition">{{ $leave->employee->name }} <span class="text-xs font-semibold text-slate-400 ml-2">({{ $leave->employee->department }})</span></p>
                            <p class="text-xs font-bold text-slate-500 mt-0.5">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} 
                                @if($leave->end_date && $leave->start_date !== $leave->end_date)
                                    - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                @endif
                            </p>
                            <span class="inline-block mt-2 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-blue-100 text-blue-700">{{ $leave->type }}</span>
                        </div>
                        
                        <div class="shrink-0 flex items-center gap-3">
                            <button type="button" @click.stop="activeModal = 'review'" class="text-sm font-bold text-slate-500 hover:text-slate-800 underline underline-offset-2">Review</button>
                            <button type="button" @click.stop="activeModal = 'decline'" class="px-4 py-2 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-lg text-sm font-bold shadow-sm transition">Decline</button>
                            <button type="button" @click.stop="activeModal = 'approve'" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm font-bold shadow-sm transition">Final Approve &rarr;</button>
                        </div>

                        <!-- Modals Teleport -->
                        <template x-teleport="body">
                            <div x-show="activeModal !== null" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-transition.opacity>
                                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="activeModal = null"></div>
                                
                                <!-- REVIEW MODAL -->
                                <div x-show="activeModal === 'review'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col" x-transition>
                                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                        <h3 class="font-black text-xl text-slate-800 flex items-center gap-3">Leave Application (Final Review)</h3>
                                        <button @click="activeModal = null" class="text-slate-400 hover:bg-slate-200 hover:text-slate-800 rounded-full p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                    </div>
                                    <div class="p-6 md:p-8 space-y-6">
                                        
                                        <!-- Vetted Badge -->
                                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Vetted by Department Head and HRMO
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-slate-100 pb-6">
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Employee</span><strong class="text-slate-800 text-lg">{{ $leave->employee->name }}</strong></div>
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Leave Type</span><strong class="text-blue-700 bg-blue-50 border border-blue-100 px-3 py-1 rounded-lg text-sm">{{ $leave->type }}</strong></div>
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
                                        <button @click="activeModal = 'approve'" type="button" class="px-6 py-2.5 bg-slate-800 text-white font-bold rounded-lg hover:bg-slate-900">Finalize Approval</button>
                                    </div>
                                </div>

                                <!-- APPROVE MODAL -->
                                <div x-show="activeModal === 'approve'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col text-center" x-transition>
                                    <div class="p-8 space-y-4">
                                        <div class="w-16 h-16 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                                        <h3 class="font-black text-2xl text-slate-800">Final Approval</h3>
                                        <p class="text-slate-500">This will officially approve the leave for <strong>{{ $leave->employee->name }}</strong> and unlock their document for printing.</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-center gap-3">
                                        <button @click="activeModal = 'review'" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Back</button>
                                        <form action="{{ route('mayor.leaves.approve', $leave->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 shadow-md transition">Confirm & Sign</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- DECLINE MODAL -->
                                <div x-show="activeModal === 'decline'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col" x-transition>
                                    <div class="px-6 py-4 border-b border-rose-100 bg-rose-50 flex justify-between items-center">
                                        <h3 class="font-black text-xl text-rose-800 flex items-center gap-3"><svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Decline Request</h3>
                                    </div>
                                    <form action="{{ route('mayor.leaves.decline', $leave->id) }}" method="POST" class="m-0 flex flex-col h-full">
                                        @csrf
                                        @method('PATCH')
                                        <div class="p-6 md:p-8 space-y-4">
                                            <p class="text-slate-600 text-sm">You are declining the leave request for <strong>{{ $leave->employee->name }}</strong>. Please provide a reason below.</p>
                                            <div class="space-y-1">
                                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Remarks / Reason</label>
                                                <textarea name="remarks" rows="4" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-lg focus:ring-rose-500 focus:border-rose-500 p-3" placeholder="Executive remarks for decline..."></textarea>
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
                    <p class="text-sm text-slate-400 italic text-center py-16">No final leave approvals pending.</p>
                @endforelse
            </div>

            <!-- ========================================== -->
            <!-- CONTENT: PENDING TRAVEL ORDERS (Hidden)    -->
            <!-- ========================================== -->
            <div id="content-travel" class="tab-content hidden p-5 space-y-3">
                @forelse($pendingTravels as $travel)
                    <div x-data="{ activeModal: null }" class="p-4 bg-slate-50 hover:bg-slate-100 transition border border-slate-100 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                        
                        <div class="flex-1 cursor-pointer group" @click="activeModal = 'review'">
                            <p class="font-black text-slate-800 group-hover:text-emerald-700 transition">{{ $travel->employee->name }} <span class="text-xs font-semibold text-slate-400 ml-2">({{ $travel->employee->department }})</span></p>
                            <p class="text-xs font-bold text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($travel->start_date)->format('M d, Y') }}</p>
                            <p class="text-xs font-medium text-slate-600 mt-1 flex items-center gap-1"><svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ $travel->destination ?? 'Official Travel' }}</p>
                        </div>
                        
                        <div class="shrink-0 flex items-center gap-3">
                            <button type="button" @click.stop="activeModal = 'review'" class="text-sm font-bold text-slate-500 hover:text-slate-800 underline underline-offset-2">Review</button>
                            <button type="button" @click.stop="activeModal = 'decline'" class="px-4 py-2 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-lg text-sm font-bold shadow-sm transition">Decline</button>
                            <button type="button" @click.stop="activeModal = 'approve'" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm font-bold shadow-sm transition">Final Approve &rarr;</button>
                        </div>

                        <template x-teleport="body">
                            <div x-show="activeModal !== null" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-transition.opacity>
                                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="activeModal = null"></div>
                                
                                <!-- REVIEW MODAL -->
                                <div x-show="activeModal === 'review'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col" x-transition>
                                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                        <h3 class="font-black text-xl text-slate-800 flex items-center gap-3">Travel Order (Final Review)</h3>
                                        <button @click="activeModal = null" class="text-slate-400 hover:bg-slate-200 hover:text-slate-800 rounded-full p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                    </div>
                                    <div class="p-6 md:p-8 space-y-6">
                                        <!-- Vetted Badge -->
                                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Vetted by Department Head and HRMO
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-slate-100 pb-6">
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Employee</span><strong class="text-slate-800 text-lg">{{ $travel->employee->name }}</strong></div>
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">TO Number</span><strong class="text-slate-800 font-mono text-sm md:text-base bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">{{ $travel->form_number ?? 'Pending' }}</strong></div>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-slate-100 pb-6">
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Departure</span><strong class="text-slate-800 text-sm md:text-base">{{ $travel->departure_from ?? 'N/A' }}</strong></div>
                                            <div><span class="text-slate-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Destination</span><strong class="text-emerald-700 bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-lg text-sm">{{ $travel->destination ?? 'N/A' }}</strong></div>
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
                                        <button @click="activeModal = 'approve'" type="button" class="px-6 py-2.5 bg-slate-800 text-white font-bold rounded-lg hover:bg-slate-900">Finalize Approval</button>
                                    </div>
                                </div>

                                <!-- APPROVE MODAL -->
                                <div x-show="activeModal === 'approve'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col text-center" x-transition>
                                    <div class="p-8 space-y-4">
                                        <div class="w-16 h-16 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                                        <h3 class="font-black text-2xl text-slate-800">Final Approval</h3>
                                        <p class="text-slate-500">This will officially approve the travel order for <strong>{{ $travel->employee->name }}</strong> and unlock their document for printing.</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-center gap-3">
                                        <button @click="activeModal = 'review'" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Back</button>
                                        <form action="{{ route('mayor.travel_orders.approve', $travel->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 shadow-md transition">Confirm & Sign</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- DECLINE MODAL -->
                                <div x-show="activeModal === 'decline'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col" x-transition>
                                    <div class="px-6 py-4 border-b border-rose-100 bg-rose-50 flex justify-between items-center">
                                        <h3 class="font-black text-xl text-rose-800 flex items-center gap-3"><svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Decline Request</h3>
                                    </div>
                                    <form action="{{ route('mayor.travel_orders.decline', $travel->id) }}" method="POST" class="m-0 flex flex-col h-full">
                                        @csrf
                                        @method('PATCH')
                                        <div class="p-6 md:p-8 space-y-4">
                                            <p class="text-slate-600 text-sm">You are declining the travel request for <strong>{{ $travel->employee->name }}</strong>. Please provide a reason below.</p>
                                            <div class="space-y-1">
                                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Remarks / Reason</label>
                                                <textarea name="remarks" rows="4" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-lg focus:ring-rose-500 focus:border-rose-500 p-3" placeholder="Executive remarks for decline..."></textarea>
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
                    <p class="text-sm text-slate-400 italic text-center py-16">No final travel approvals pending.</p>
                @endforelse
            </div>
            
        </div>
    </div>

    <!-- Script for Folder Tabs -->
    <script>
        function switchTab(tabId) {
            const tabs = ['leave', 'travel'];

            tabs.forEach(id => {
                const content = document.getElementById('content-' + id);
                const btn = document.getElementById('tab-' + id);

                if (id === tabId) {
                    content.classList.remove('hidden');
                    content.classList.add('block');
                    btn.className = "tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-800 bg-white border border-slate-200 border-b-white rounded-t-2xl relative z-10 translate-y-[1px] flex items-center gap-2";
                } else {
                    content.classList.remove('block');
                    content.classList.add('hidden');
                    btn.className = "tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-500 hover:text-slate-700 transition-colors border border-transparent border-b-0 rounded-t-2xl relative z-0 translate-y-[1px] flex items-center gap-2";
                }
            });
        }
    </script>
</x-employee-layout>