<x-employee-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 space-y-6 md:space-y-8">

        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-4">
            <h1 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-3">
                HRMO Review Portal 📑
            </h1>
            <p class="text-slate-500 mt-1 font-medium">Reviewing forms approved by Department Heads.</p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-700 font-bold rounded-xl border border-emerald-200 flex items-center gap-3 shadow-sm transition-all" id="success-alert">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
            <script>setTimeout(() => document.getElementById('success-alert')?.remove(), 5000);</script>
        @endif

        <div class="space-y-8">

            <!-- ========================================== -->
            <!-- 🌟 PENDING LEAVES SECTION (BLUE) 🌟        -->
            <!-- ========================================== -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-blue-100 bg-blue-50 flex items-center justify-between">
                    <h2 class="font-bold text-lg text-blue-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Pending Leaves (HR Review)
                    </h2>
                    @if($pendingLeaves->count() > 0)
                        <span class="bg-blue-600 text-white text-xs font-black py-1 px-3 rounded-full shadow-sm">{{ $pendingLeaves->count() }}</span>
                    @endif
                </div>

                <div class="p-5 space-y-4 bg-slate-50/50">
                    @forelse($pendingLeaves as $leave)
                        <!-- Tracking Card -->
                        <div x-data="{ activeModal: null }" class="tracking-card p-4 md:p-5 bg-white border border-slate-200 border-l-4 border-l-blue-500 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4 hover:shadow-md transition-shadow">

                            <div class="flex-1 cursor-pointer group" @click="activeModal = 'review'">
                                <p class="font-black text-slate-800 text-lg flex items-center gap-2 group-hover:text-blue-700 transition-colors">
                                    {{ $leave->employee->name }}
                                    <span class="bg-blue-100 text-blue-700 text-[10px] uppercase font-black px-2 py-0.5 rounded border border-blue-200">
                                        LV-{{ str_pad($leave->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </p>
                                <p class="text-xs font-bold text-slate-500 mt-0.5">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}
                                    @if($leave->end_date && $leave->start_date !== $leave->end_date)
                                        - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                    @endif
                                </p>
                                <span class="inline-block mt-2 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded bg-slate-100 text-slate-600 border border-slate-200">{{ $leave->type ?? 'Leave' }}</span>
                            </div>

                            <div class="shrink-0 flex items-center gap-2 sm:gap-3 border-t md:border-t-0 border-slate-100 pt-3 md:pt-0">
                                <button type="button" @click.stop="activeModal = 'review'" class="px-4 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> Review
                                </button>
                                <button type="button" @click.stop="activeModal = 'decline'" class="px-4 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 font-bold rounded-lg text-sm shadow-sm transition">Decline</button>
                                <button type="button" @click.stop="activeModal = 'approve'" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-sm shadow-sm transition flex items-center gap-2">Verify <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                            </div>

                            <template x-teleport="body">
                                <div x-show="activeModal !== null" style="display: none;" class="fixed inset-0 z-[999] flex items-center justify-center p-4 sm:p-6" x-transition.opacity>
                                    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="activeModal = null"></div>

                                    <!-- ================= REVIEW MODAL ================= -->
                                    <div x-show="activeModal === 'review'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col" x-transition>
                                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                            <h3 class="font-black text-xl text-slate-800 flex items-center gap-3"><span class="bg-blue-100 p-2 rounded-lg text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></span>Leave Request</h3>
                                            <button @click="activeModal = null" class="text-slate-400 hover:bg-slate-200 hover:text-slate-800 rounded-full p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                        </div>
                                        <div class="p-6 md:p-8 space-y-6 overflow-y-auto max-h-[70vh]">
                                            <!-- Dept Head Verification Box -->
                                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-sm flex items-start gap-3">
                                                <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <div>
                                                    <strong class="text-emerald-800 block">Department Head Approved</strong>
                                                    <span class="text-emerald-600">This form has passed the first tier of approval.</span>
                                                </div>
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
                                        <div class="bg-slate-50 p-4 md:px-8 border-t border-slate-200 flex justify-end gap-3 shrink-0">
                                            <button @click="activeModal = null" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Cancel</button>
                                            <button @click="activeModal = 'decline'" type="button" class="px-5 py-2.5 bg-rose-500 text-white font-bold rounded-lg hover:bg-rose-600">Decline</button>
                                            <button @click="activeModal = 'approve'" type="button" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Verify & Forward</button>
                                        </div>
                                    </div>

                                    <!-- ================= APPROVE MODAL ================= -->
                                    <div x-show="activeModal === 'approve'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col text-center" x-transition>
                                        <div class="p-8 space-y-4">
                                            <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                            <h3 class="font-black text-2xl text-slate-800">Confirm HR Verification</h3>
                                            <p class="text-slate-500 text-sm leading-relaxed">Are you sure you want to verify this leave for <strong>{{ $leave->employee->name }}</strong> and forward it to the Mayor for final approval?</p>
                                        </div>
                                        <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-center gap-3">
                                            <button @click="activeModal = 'review'" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Back</button>
                                            <form action="{{ route('leaves.approve', $leave->id) }}" method="POST" class="m-0">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-md transition">Yes, Verify It</button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- ================= DECLINE MODAL ================= -->
                                    <div x-show="activeModal === 'decline'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col" x-transition>
                                        <div class="px-6 py-4 border-b border-rose-100 bg-rose-50 flex justify-between items-center">
                                            <h3 class="font-black text-xl text-rose-800 flex items-center gap-3"><svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Decline Request</h3>
                                        </div>
                                        <form action="{{ route('leaves.decline', $leave->id) }}" method="POST" class="m-0 flex flex-col h-full">
                                            @csrf @method('PATCH')
                                            <div class="p-6 md:p-8 space-y-4">
                                                <p class="text-slate-600 text-sm">You are declining the leave request for <strong>{{ $leave->employee->name }}</strong>. Please provide a reason below.</p>
                                                <div class="space-y-1">
                                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Remarks / Reason</label>
                                                    <textarea name="remarks" rows="4" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-lg focus:ring-rose-500 focus:border-rose-500 p-3 shadow-sm" placeholder="Explain why this request is being declined by HR..."></textarea>
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
                        <!-- Standardized Empty State -->
                        <div class="py-12 flex flex-col items-center justify-center text-center">
                            <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-slate-600 font-black text-lg">No Pending Leaves</h3>
                            <p class="text-slate-400 text-sm mt-1">All leave requests have been reviewed.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 🌟 PENDING TRAVEL ORDERS SECTION (EMERALD) -->
            <!-- ========================================== -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-emerald-100 bg-emerald-50 flex items-center justify-between">
                    <h2 class="font-bold text-lg text-emerald-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Pending Travel Orders (HR Review)
                    </h2>
                    @if($pendingTravels->count() > 0)
                        <span class="bg-emerald-600 text-white text-xs font-black py-1 px-3 rounded-full shadow-sm">{{ $pendingTravels->count() }}</span>
                    @endif
                </div>

                <div class="p-5 space-y-4 bg-slate-50/50">
                    @forelse($pendingTravels as $travel)
                        <!-- Tracking Card -->
                        <div x-data="{ activeModal: null }" class="tracking-card p-4 md:p-5 bg-white border border-slate-200 border-l-4 border-l-emerald-500 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4 hover:shadow-md transition-shadow">

                            <div class="flex-1 cursor-pointer group" @click="activeModal = 'review'">
                                <p class="font-black text-slate-800 text-lg flex items-center gap-2 group-hover:text-emerald-700 transition-colors">
                                    {{ $travel->employee->name }}
                                    <span class="bg-emerald-100 text-emerald-700 text-[10px] uppercase font-black px-2 py-0.5 rounded border border-emerald-200">
                                        {{ $travel->form_number ?? 'TO-'.str_pad($travel->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </p>
                                <p class="text-xs font-bold text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($travel->start_date)->format('M d, Y') }}</p>
                                <p class="text-xs font-medium text-slate-600 mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $travel->destination ?? 'Official Travel' }}
                                </p>
                            </div>

                            <div class="shrink-0 flex items-center gap-2 sm:gap-3 border-t md:border-t-0 border-slate-100 pt-3 md:pt-0">
                                <button type="button" @click.stop="activeModal = 'review'" class="px-4 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> Review
                                </button>
                                <button type="button" @click.stop="activeModal = 'decline'" class="px-4 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 font-bold rounded-lg text-sm shadow-sm transition">Decline</button>
                                <button type="button" @click.stop="activeModal = 'approve'" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-sm shadow-sm transition flex items-center gap-2">Verify <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                            </div>

                            <template x-teleport="body">
                                <div x-show="activeModal !== null" style="display: none;" class="fixed inset-0 z-[999] flex items-center justify-center p-4 sm:p-6" x-transition.opacity>
                                    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="activeModal = null"></div>

                                    <!-- ================= REVIEW MODAL ================= -->
                                    <div x-show="activeModal === 'review'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col" x-transition>
                                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                            <h3 class="font-black text-xl text-slate-800 flex items-center gap-3"><span class="bg-emerald-100 p-2 rounded-lg text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>Travel Order</h3>
                                            <button @click="activeModal = null" class="text-slate-400 hover:bg-slate-200 hover:text-slate-800 rounded-full p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                        </div>
                                        <div class="p-6 md:p-8 space-y-6 overflow-y-auto max-h-[70vh]">
                                            <!-- Dept Head Verification Box -->
                                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-sm flex items-start gap-3">
                                                <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <div>
                                                    <strong class="text-emerald-800 block">Department Head Approved</strong>
                                                    <span class="text-emerald-600">This form has passed the first tier of approval.</span>
                                                </div>
                                            </div>

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
                                        <div class="bg-slate-50 p-4 md:px-8 border-t border-slate-200 flex justify-end gap-3 shrink-0">
                                            <button @click="activeModal = null" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Cancel</button>
                                            <button @click="activeModal = 'decline'" type="button" class="px-5 py-2.5 bg-rose-500 text-white font-bold rounded-lg hover:bg-rose-600">Decline</button>
                                            <button @click="activeModal = 'approve'" type="button" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700">Verify & Forward</button>
                                        </div>
                                    </div>

                                    <!-- ================= APPROVE MODAL ================= -->
                                    <div x-show="activeModal === 'approve'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col text-center" x-transition>
                                        <div class="p-8 space-y-4">
                                            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                            <h3 class="font-black text-2xl text-slate-800">Confirm HR Verification</h3>
                                            <p class="text-slate-500 text-sm leading-relaxed">Are you sure you want to verify this travel order for <strong>{{ $travel->employee->name }}</strong> and forward it to the Mayor for final approval?</p>
                                        </div>
                                        <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-center gap-3">
                                            <button @click="activeModal = 'review'" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100">Back</button>
                                            <form action="{{ route('travel_orders.approve', $travel->id) }}" method="POST" class="m-0">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 shadow-md transition">Yes, Verify It</button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- ================= DECLINE MODAL ================= -->
                                    <div x-show="activeModal === 'decline'" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col" x-transition>
                                        <div class="px-6 py-4 border-b border-rose-100 bg-rose-50 flex justify-between items-center">
                                            <h3 class="font-black text-xl text-rose-800 flex items-center gap-3"><svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Decline Request</h3>
                                        </div>
                                        <form action="{{ route('travel_orders.decline', $travel->id) }}" method="POST" class="m-0 flex flex-col h-full">
                                            @csrf @method('PATCH')
                                            <div class="p-6 md:p-8 space-y-4">
                                                <p class="text-slate-600 text-sm">You are declining the travel request for <strong>{{ $travel->employee->name }}</strong>. Please provide a reason below.</p>
                                                <div class="space-y-1">
                                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Remarks / Reason</label>
                                                    <textarea name="remarks" rows="4" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-lg focus:ring-rose-500 focus:border-rose-500 p-3 shadow-sm" placeholder="Explain why this request is being declined by HR..."></textarea>
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
                        <!-- Standardized Empty State -->
                        <div class="py-12 flex flex-col items-center justify-center text-center">
                            <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-slate-600 font-black text-lg">No Pending Travel Orders</h3>
                            <p class="text-slate-400 text-sm mt-1">All travel requests have been reviewed.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-employee-layout>
