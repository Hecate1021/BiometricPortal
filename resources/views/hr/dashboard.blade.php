<x-employee-layout>
    <!-- CSS & JS Integrations (Select2 & Flatpickr) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <style>
        .select2-container .select2-selection--single {
            height: 42px !important; border-color: #cbd5e1 !important; border-radius: 0.5rem !important;
            padding: 5px 10px !important; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
            display: flex !important; align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; right: 10px !important; }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155 !important; font-size: 0.875rem !important; font-weight: 500 !important; padding-left: 0 !important;
        }
        .select2-search__field { outline: none !important; focus: ring-emerald-500 !important; border-radius: 0.375rem !important; }
        .select2-dropdown { border-color: #cbd5e1 !important; border-radius: 0.5rem !important; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important; overflow: hidden !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .was-validated :invalid { border-color: #f43f5e !important; background-color: #fff1f2 !important; }
        .was-validated select:invalid + .select2-container .select2-selection--single { border-color: #f43f5e !important; background-color: #fff1f2 !important; }
        .flatpickr-calendar { z-index: 99999 !important; }
        .flatpickr-input { background-color: #ffffff !important; }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 space-y-6 md:space-y-8">
        
        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-4">
            <h1 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-3">HR Staff Portal 🗄️</h1>
            <p class="text-slate-500 mt-1">File requests for employees and track all live records.</p>
        </div>

        @if(session('success'))
        <div id="success-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm transition-opacity duration-500 flex items-center gap-3" role="alert">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="block sm:inline font-medium text-sm sm:text-base">{{ session('success') }}</span>
        </div>
        @endif

        <div class="space-y-8">

           

            <!-- 🌟 HR PROXY FILING SECTION 🌟 -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-8">
                <div class="p-5 border-b border-blue-200 bg-blue-50 flex items-center justify-between">
                    <h2 class="font-bold text-lg text-blue-900 flex items-center gap-2"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> File Request on Behalf of Employee</h2>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button type="button" onclick="openModal('travelModal')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 text-sm"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> File Travel Order</button>
                    <button type="button" onclick="openModal('passSlipModal')" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 text-sm"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg> File Pass Slip</button>
                    <button type="button" onclick="openModal('leaveModal')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 text-sm"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> File Leave</button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-indigo-100 bg-indigo-50 flex items-center justify-between">
                    <h2 class="font-bold text-lg text-indigo-900 flex items-center gap-2"><svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> Bulk Print DTRs</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('dtr.print_bulk') }}" method="GET" target="_blank" class="flex flex-col md:flex-row md:items-end gap-4">
                        <div class="w-full md:w-1/4">
                            <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Select Month</label>
                            <select name="month" class="w-full text-sm bg-slate-50 border border-slate-300 text-slate-700 font-medium rounded-lg shadow-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                @for($m = 1; $m <= 12; $m++) <option value="{{ $m }}" {{ (request('month') ?? 9) == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option> @endfor
                            </select>
                        </div>
                        <div class="w-full md:w-1/4">
                            <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Select Year</label>
                            <select name="year" class="w-full text-sm bg-slate-50 border border-slate-300 text-slate-700 font-medium rounded-lg shadow-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                @for($y = now()->year - 1; $y <= now()->year + 1; $y++) <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option> @endfor
                            </select>
                        </div>
                        <div class="w-full md:flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Filter by Department</label>
                            <select name="department" class="w-full text-sm bg-slate-50 border border-slate-300 text-slate-700 font-medium rounded-lg shadow-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                <option value="All" class="font-bold">All Departments</option>
                                @foreach($departments ?? \App\Models\Department::orderBy('name')->get() as $dept) <option value="{{ $dept->name ?? $dept }}">{{ $dept->name ?? $dept }}</option> @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-auto mt-2 md:mt-0 shrink-0">
                            <button type="submit" class="w-full md:w-auto px-6 py-2.5 text-sm font-bold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md transition-colors flex items-center justify-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> Generate Print</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 🌟 Folder Tabs Header & Search (Tracking) 🌟 -->
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between px-2 sm:px-6 mt-8 gap-4 border-b border-slate-200">
                <div class="flex items-end overflow-x-auto hide-scrollbar w-full md:w-auto">
                    <button onclick="switchTab('travel')" id="tab-travel" class="tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-800 bg-white border border-slate-200 border-b-white rounded-t-2xl relative z-10 translate-y-[1px] flex items-center gap-2 whitespace-nowrap">Travel Orders Tracking</button>
                    <button onclick="switchTab('pass')" id="tab-pass" class="tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-500 hover:text-slate-700 transition-colors border border-transparent border-b-0 rounded-t-2xl relative z-0 translate-y-[1px] flex items-center gap-2 whitespace-nowrap">Pass Slips Tracking</button>
                    <button onclick="switchTab('leave')" id="tab-leave" class="tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-500 hover:text-slate-700 transition-colors border border-transparent border-b-0 rounded-t-2xl relative z-0 translate-y-[1px] flex items-center gap-2 whitespace-nowrap">Leave Requests Tracking</button>
                </div>
                
                <div class="relative w-full md:w-72 shrink-0 mb-2">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="requestSearchInput" class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 shadow-sm transition-colors" placeholder="Search forms (name, ID)...">
                </div>
            </div>

            <!-- Main Tracking Content Box -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm relative z-0 overflow-hidden mb-10 min-h-[400px]">
                
                <!-- 1. CONTENT: TRAVEL ORDERS TRACKING -->
                <div id="content-travel" class="tab-content block p-5 space-y-4 bg-slate-50">
                    @forelse($allTravels ?? [] as $travel)
                        <!-- 🌟 COLOR-CODED LEFT BORDER (EMERALD) 🌟 -->
                        <div class="tracking-card p-4 md:p-5 bg-white border border-slate-200 border-l-4 border-l-emerald-500 rounded-xl flex flex-col gap-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                <div class="flex-1">
                                    <p class="font-black text-slate-800 text-lg flex items-center gap-2">
                                        {{ $travel->employee->name ?? 'Unknown Employee' }}
                                        <!-- 🌟 TRACKING NUMBER BADGE 🌟 -->
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] uppercase font-black px-2 py-0.5 rounded border border-emerald-200">
                                            {{ $travel->form_number ?? 'TO-'.str_pad($travel->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </p>
                                    <p class="text-xs font-bold text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($travel->start_date)->format('M d, Y') }}</p>
                                    <p class="text-xs font-medium text-slate-600 mt-1 flex items-center gap-1"><svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ $travel->destination ?? 'Official Travel' }}</p>
                                </div>
                                <div class="shrink-0">
                                    @if(strtolower($travel->status) === 'approved' || !empty($travel->mayor_approved_at)) <span class="bg-emerald-100 text-emerald-800 text-[10px] uppercase font-black px-3 py-1.5 rounded-full border border-emerald-200 shadow-sm">Fully Approved</span>
                                    @elseif(strtolower($travel->status) === 'declined' || strtolower($travel->status) === 'rejected') <span class="bg-rose-100 text-rose-800 text-[10px] uppercase font-black px-3 py-1.5 rounded-full border border-rose-200 shadow-sm">Declined</span>
                                    @else <span class="bg-amber-100 text-amber-800 text-[10px] uppercase font-black px-3 py-1.5 rounded-full border border-amber-200 shadow-sm">{{ str_replace('_', ' ', $travel->status ?? 'In Progress') }}</span> @endif
                                </div>
                            </div>
                            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-5 pt-4 border-t border-slate-100 mt-1">
                                <div class="flex items-center gap-2 sm:gap-3 text-[10px] sm:text-xs font-bold w-full xl:w-auto overflow-x-auto pb-2 xl:pb-0">
                                    <div class="flex items-center gap-1 whitespace-nowrap {{ !empty($travel->dept_head_approved_at) ? 'text-emerald-600' : 'text-amber-500' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Dept Head</div><div class="w-4 sm:w-8 h-[2px] rounded-full shrink-0 {{ !empty($travel->dept_head_approved_at) ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                                    <div class="flex items-center gap-1 whitespace-nowrap {{ !empty($travel->hrmo_approved_at) ? 'text-emerald-600' : (!empty($travel->dept_head_approved_at) ? 'text-amber-500' : 'text-slate-400') }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> HRMO</div><div class="w-4 sm:w-8 h-[2px] rounded-full shrink-0 {{ !empty($travel->hrmo_approved_at) ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                                    <div class="flex items-center gap-1 whitespace-nowrap {{ !empty($travel->mayor_approved_at) ? 'text-emerald-600' : (!empty($travel->hrmo_approved_at) ? 'text-amber-500' : 'text-slate-400') }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Mayor</div>
                                </div>
                                <div class="shrink-0 flex justify-end gap-2">
                                    <a href="{{ route('employee.travel.edit', $travel->id) }}" class="px-4 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                                    </a>
                                    @php $isApproved = strtolower($travel->status) === 'approved' || !empty($travel->mayor_approved_at); @endphp
                                    <a href="{{ route('travel_orders.print', $travel->id) }}" target="_blank" class="px-5 py-2.5 bg-emerald-600 border border-emerald-600 text-white hover:bg-emerald-700 font-bold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> {{ $isApproved ? 'Print' : 'Review' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 italic text-center py-16">No travel orders to track right now.</p>
                    @endforelse
                </div>

                <!-- 2. CONTENT: PASS SLIPS TRACKING -->
                <div id="content-pass" class="tab-content hidden p-5 space-y-4 bg-slate-50">
                    @forelse($allPassSlips ?? [] as $pass)
                        <!-- 🌟 COLOR-CODED LEFT BORDER (PURPLE) 🌟 -->
                        <div class="tracking-card p-4 md:p-5 bg-white border border-slate-200 border-l-4 border-l-purple-500 rounded-xl flex flex-col gap-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                <div class="flex-1">
                                    <p class="font-black text-slate-800 text-lg flex items-center gap-2">
                                        {{ $pass->employee->name ?? 'Unknown Employee' }}
                                        <!-- 🌟 TRACKING NUMBER BADGE 🌟 -->
                                        <span class="bg-purple-100 text-purple-700 text-[10px] uppercase font-black px-2 py-0.5 rounded border border-purple-200">
                                            PS-{{ str_pad($pass->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </p>
                                    <p class="text-xs font-bold text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($pass->transaction_date)->format('M d, Y') }}</p>
                                    <p class="text-xs font-medium text-slate-600 mt-1 flex items-center gap-1"><svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg> {{ Str::limit($pass->transaction_details, 50) }}</p>
                                </div>
                                <div class="shrink-0">
                                    @if(!empty($pass->dept_head_approved_at) || in_array(strtolower($pass->status), ['approved', 'approved_dept_head', 'pending_hrmo', 'pending_mayor', 'approved_hrmo'])) <span class="bg-emerald-100 text-emerald-800 text-[10px] uppercase font-black px-3 py-1.5 rounded-full border border-emerald-200 shadow-sm">Fully Approved</span>
                                    @elseif(strtolower($pass->status) === 'declined' || strtolower($pass->status) === 'rejected') <span class="bg-rose-100 text-rose-800 text-[10px] uppercase font-black px-3 py-1.5 rounded-full border border-rose-200 shadow-sm">Declined</span>
                                    @else <span class="bg-amber-100 text-amber-800 text-[10px] uppercase font-black px-3 py-1.5 rounded-full border border-amber-200 shadow-sm">Pending (Dept Head)</span> @endif
                                </div>
                            </div>
                            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-5 pt-4 border-t border-slate-100 mt-1">
                                <div class="flex items-center gap-2 sm:gap-3 text-[10px] sm:text-xs font-bold w-full xl:w-auto overflow-x-auto pb-2 xl:pb-0">
                                    <div class="flex items-center gap-1 whitespace-nowrap {{ !empty($pass->dept_head_approved_at) || in_array(strtolower($pass->status), ['approved', 'pending_hrmo']) ? 'text-emerald-600' : 'text-amber-500' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Dept Head Review</div>
                                </div>
                                <div class="shrink-0 flex justify-end gap-2">
                                    <a href="{{ route('employee.pass_slip.edit', $pass->id) }}" class="px-4 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                                    </a>
                                    @php $isApproved = !empty($pass->dept_head_approved_at) || in_array(strtolower($pass->status), ['approved', 'pending_hrmo']); @endphp
                                    <a href="{{ route('pass_slips.print', $pass->id) }}" target="_blank" class="px-5 py-2.5 bg-purple-600 border border-purple-600 text-white hover:bg-purple-700 font-bold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> {{ $isApproved ? 'Print' : 'Review' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 italic text-center py-16">No pass slips to track right now.</p>
                    @endforelse
                </div>

                <!-- 3. CONTENT: LEAVES TRACKING -->
                <div id="content-leave" class="tab-content hidden p-5 space-y-4 bg-slate-50">
                    @forelse($allLeaves ?? [] as $leave)
                        <!-- 🌟 COLOR-CODED LEFT BORDER (BLUE) 🌟 -->
                        <div class="tracking-card p-4 md:p-5 bg-white border border-slate-200 border-l-4 border-l-blue-500 rounded-xl flex flex-col gap-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                <div class="flex-1">
                                    <p class="font-black text-slate-800 text-lg flex items-center gap-2">
                                        {{ $leave->employee->name ?? 'Unknown Employee' }}
                                        <!-- 🌟 TRACKING NUMBER BADGE 🌟 -->
                                        <span class="bg-blue-100 text-blue-700 text-[10px] uppercase font-black px-2 py-0.5 rounded border border-blue-200">
                                            LV-{{ str_pad($leave->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </p>
                                    <p class="text-xs font-bold text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} @if($leave->end_date && $leave->start_date !== $leave->end_date) - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }} @endif</p>
                                    <span class="inline-block mt-2 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded bg-slate-100 text-slate-600">{{ $leave->type ?? 'Unspecified Leave' }}</span>
                                </div>
                                <div class="shrink-0">
                                    @if(strtolower($leave->status) === 'approved' || !empty($leave->mayor_approved_at)) <span class="bg-emerald-100 text-emerald-800 text-[10px] uppercase font-black px-3 py-1.5 rounded-full border border-emerald-200 shadow-sm">Fully Approved</span>
                                    @elseif(strtolower($leave->status) === 'declined' || strtolower($leave->status) === 'rejected') <span class="bg-rose-100 text-rose-800 text-[10px] uppercase font-black px-3 py-1.5 rounded-full border border-rose-200 shadow-sm">Declined</span>
                                    @else <span class="bg-amber-100 text-amber-800 text-[10px] uppercase font-black px-3 py-1.5 rounded-full border border-amber-200 shadow-sm">{{ str_replace('_', ' ', $leave->status ?? 'In Progress') }}</span> @endif
                                </div>
                            </div>
                            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-5 pt-4 border-t border-slate-100 mt-1">
                                <div class="flex items-center gap-2 sm:gap-3 text-[10px] sm:text-xs font-bold w-full xl:w-auto overflow-x-auto pb-2 xl:pb-0">
                                    <div class="flex items-center gap-1 whitespace-nowrap {{ !empty($leave->dept_head_approved_at) ? 'text-emerald-600' : 'text-amber-500' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Dept Head</div><div class="w-4 sm:w-8 h-[2px] rounded-full shrink-0 {{ !empty($leave->dept_head_approved_at) ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                                    <div class="flex items-center gap-1 whitespace-nowrap {{ !empty($leave->hrmo_approved_at) ? 'text-emerald-600' : (!empty($leave->dept_head_approved_at) ? 'text-amber-500' : 'text-slate-400') }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> HRMO</div><div class="w-4 sm:w-8 h-[2px] rounded-full shrink-0 {{ !empty($leave->hrmo_approved_at) ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                                    <div class="flex items-center gap-1 whitespace-nowrap {{ !empty($leave->mayor_approved_at) ? 'text-emerald-600' : (!empty($leave->hrmo_approved_at) ? 'text-amber-500' : 'text-slate-400') }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Mayor</div>
                                </div>
                                <div class="shrink-0 flex justify-end gap-2">
                                    <a href="{{ route('employee.leave.edit', $leave->id) }}" class="px-4 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                                    </a>
                                    @php $isApproved = strtolower($leave->status) === 'approved' || !empty($leave->mayor_approved_at); @endphp
                                    <a href="{{ route('leaves.print', $leave->id) }}" target="_blank" class="px-5 py-2.5 bg-blue-600 border border-blue-600 text-white hover:bg-blue-700 font-bold rounded-lg text-sm shadow-sm transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> {{ $isApproved ? 'Print' : 'Review' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 italic text-center py-16">No leaves to track right now.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- 🌟 CREATE MODALS (FOR FILING ON BEHALF) 🌟 -->
    <div id="travelModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center z-[60] p-4 sm:p-6 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all my-auto max-h-[95vh] flex flex-col">
            <div class="px-4 sm:px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-emerald-50 shrink-0 rounded-t-2xl">
                <h3 class="font-bold text-emerald-800 text-base sm:text-lg flex items-center gap-2"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg> Travel Order Form</h3>
                <button type="button" onclick="closeModal('travelModal')" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1">&times;</button>
            </div>
            <div class="overflow-y-auto p-4 sm:p-6 flex-1">
                <form id="travelForm" action="{{ route('employee.travel.store') }}" method="POST" class="space-y-4 sm:space-y-5 needs-validation" novalidate>
                    @csrf
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Select Employee <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label>
                        <select name="employee_id" id="travelEmployeeSelect" required class="w-full text-sm font-medium">
                            <option value="">-- Search and Select an Employee --</option>
                            @foreach($activeEmployees ?? [] as $emp) <option value="{{ $emp->id }}">{{ $emp->name }} (ID: {{ $emp->company_id }})</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Travel Order No. (From Physical Document)</label>
                        <input type="text" name="form_number" placeholder="e.g., 2026-10-01-1234" class="w-full border-slate-300 rounded-lg shadow-sm p-2.5 sm:p-3 focus:ring-emerald-500 font-mono text-xs sm:text-sm uppercase">
                        <p class="text-[9px] sm:text-[10px] text-slate-500 mt-1 italic leading-tight">Leave blank to auto-generate.</p>
                    </div>
                    <div class="p-3 sm:p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 sm:mb-3">Inclusive Date of Travel</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div><label class="block text-[10px] sm:text-xs font-semibold text-slate-500 mb-1">From <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label><input type="text" name="start_date" placeholder="Select date" autocomplete="off" required class="september-date w-full border-slate-300 rounded-lg shadow-sm p-2 sm:p-2.5 focus:ring-emerald-500 text-xs sm:text-sm bg-white cursor-pointer"></div>
                            <div><label class="block text-[10px] sm:text-xs font-semibold text-slate-500 mb-1">To <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label><input type="text" name="end_date" placeholder="Select date" autocomplete="off" required class="september-date w-full border-slate-300 rounded-lg shadow-sm p-2 sm:p-2.5 focus:ring-emerald-500 text-xs sm:text-sm bg-white cursor-pointer"></div>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 sm:mb-3">Destination</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div><label class="block text-[10px] sm:text-xs font-semibold text-slate-500 mb-1">From <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label><input type="text" name="departure_from" placeholder="e.g. Lake Sebu" required class="w-full border-slate-300 rounded-lg shadow-sm p-2 sm:p-2.5 focus:ring-emerald-500 text-xs sm:text-sm"></div>
                            <div><label class="block text-[10px] sm:text-xs font-semibold text-slate-500 mb-1">To <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label><input type="text" name="destination" placeholder="e.g. Koronadal" required class="w-full border-slate-300 rounded-lg shadow-sm p-2 sm:p-2.5 focus:ring-emerald-500 text-xs sm:text-sm"></div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Purpose <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label>
                        <input type="text" name="purpose" placeholder="Brief reason for travel..." required class="w-full border-slate-300 rounded-lg shadow-sm p-2.5 sm:p-3 focus:ring-emerald-500 text-xs sm:text-sm">
                    </div>
                    <div class="p-3 sm:p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 sm:mb-3">Travelling Expenses & Allowances <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label>
                        <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                            <label class="flex items-center cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="expense_type" value="Official" required class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300"><span class="ml-2 text-xs sm:text-sm font-bold text-slate-700">Official</span></label>
                            <label class="flex items-center cursor-pointer hover:text-slate-900 transition-colors"><input type="radio" name="expense_type" value="Personal" required class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300"><span class="ml-2 text-xs sm:text-sm font-bold text-slate-700">Personal</span></label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 shrink-0 rounded-b-2xl">
                <button type="button" onclick="closeModal('travelModal')" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-100 transition-colors order-2 sm:order-1">Cancel</button>
                <button type="submit" form="travelForm" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 shadow-md transition-colors order-1 sm:order-2">Submit Travel Order</button>
            </div>
        </div>
    </div>

    <div id="leaveModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center z-[60] p-4 sm:p-6 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl transform transition-all my-auto max-h-[95vh] flex flex-col">
            <div class="px-4 sm:px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-blue-50 shrink-0 rounded-t-2xl">
                <h3 class="font-bold text-blue-800 text-base sm:text-lg flex items-center gap-2"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Leave Application Form</h3>
                <button type="button" onclick="closeModal('leaveModal')" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1">&times;</button>
            </div>
            <div class="overflow-y-auto p-4 sm:p-6 md:p-8 flex-1">
                <form id="leaveForm" action="{{ route('employee.leave.store') }}" method="POST" class="space-y-6 needs-validation" novalidate>
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5 bg-slate-50/80 p-4 sm:p-5 rounded-xl border border-slate-200 shadow-sm">
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">1. Office/Department</label>
                            <input type="text" name="office_department" class="w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-md shadow-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500" placeholder="e.g. HRMO">
                        </div>
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">2. Select Employee <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label>
                            <select name="employee_id" id="leaveEmployeeSelect" required class="w-full text-sm font-medium">
                                <option value="">-- Search Employee --</option>
                                @foreach($activeEmployees ?? [] as $emp) <option value="{{ $emp->id }}">{{ $emp->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">3. Date of Filing <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label>
                            <input type="text" name="date_of_filing" placeholder="Select date" autocomplete="off" class="september-date w-full text-xs sm:text-sm bg-white border border-slate-300 rounded-md shadow-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 cursor-pointer" required>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <h3 class="font-black text-slate-800 text-sm tracking-wide mb-4 sm:mb-5">6. DETAILS OF APPLICATION</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm">
                                <label class="block text-xs font-bold text-slate-700 mb-3 sm:mb-4 uppercase tracking-wide">6.A Type of Leave <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label>
                                <div class="space-y-2 sm:space-y-3 text-xs sm:text-sm text-slate-600 font-medium">
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="Vacation Leave" class="mt-1 mr-3 text-blue-600 rounded"> Vacation Leave</label>
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="Mandatory/Forced Leave" class="mt-1 mr-3 text-blue-600 rounded"> Mandatory/Forced Leave</label>
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="Sick Leave" class="mt-1 mr-3 text-blue-600 rounded"> Sick Leave</label>
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="Maternity Leave" class="mt-1 mr-3 text-blue-600 rounded"> Maternity Leave</label>
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="Paternity Leave" class="mt-1 mr-3 text-blue-600 rounded"> Paternity Leave</label>
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="Special Privilege Leave" class="mt-1 mr-3 text-blue-600 rounded"> Special Privilege Leave</label>
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="Solo Parent Leave" class="mt-1 mr-3 text-blue-600 rounded"> Solo Parent Leave</label>
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="Study Leave" class="mt-1 mr-3 text-blue-600 rounded"> Study Leave</label>
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="10-Day VAWC Leave" class="mt-1 mr-3 text-blue-600 rounded"> 10-Day VAWC Leave</label>
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="Rehabilitation Privilege" class="mt-1 mr-3 text-blue-600 rounded"> Rehabilitation Privilege</label>
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="Special Leave for Women" class="mt-1 mr-3 text-blue-600 rounded"> Special Leave for Women</label>
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="Special Emergency Leave" class="mt-1 mr-3 text-blue-600 rounded"> Special Emergency Leave</label>
                                    <label class="flex items-start cursor-pointer"><input type="checkbox" name="leave_type[]" value="Adoption Leave" class="mt-1 mr-3 text-blue-600 rounded"> Adoption Leave</label>
                                    <div class="flex items-end mt-4 pt-3 border-t border-slate-100">
                                        <span class="mr-3 font-bold text-slate-700">Others:</span>
                                        <input type="text" name="leave_type_others" class="flex-1 text-xs sm:text-sm bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-2" placeholder="Specify here...">
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm">
                                <label class="block text-xs font-bold text-slate-700 mb-3 sm:mb-4 uppercase tracking-wide">6.B Details of Leave</label>
                                <div class="space-y-4 sm:space-y-6 text-xs sm:text-sm text-slate-600 font-medium">
                                    <div class="bg-slate-50 p-3 rounded-lg">
                                        <span class="block mb-2 font-bold text-slate-700 text-[10px] sm:text-xs">Vacation/Special Privilege Leave:</span>
                                        <label class="flex items-center ml-2 mb-2"><input type="checkbox" name="leave_details[]" value="Within the Philippines" class="mr-3 text-blue-600 rounded"> Within the Philippines</label>
                                        <div class="flex flex-col sm:flex-row sm:items-end ml-2 gap-2">
                                            <label class="flex items-center whitespace-nowrap"><input type="checkbox" name="leave_details[]" value="Abroad" class="mr-3 text-blue-600 rounded"> Abroad (Specify)</label>
                                            <input type="text" name="details_specify_abroad" class="flex-1 text-xs sm:text-sm bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-2 min-w-[100px]">
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-lg">
                                        <span class="block mb-2 font-bold text-slate-700 text-[10px] sm:text-xs">Sick Leave:</span>
                                        <div class="flex flex-col sm:flex-row sm:items-end ml-2 mb-2 gap-2">
                                            <label class="flex items-center whitespace-nowrap"><input type="checkbox" name="leave_details[]" value="In Hospital" class="mr-3 text-blue-600 rounded"> In Hospital (Specify)</label>
                                            <input type="text" name="details_specify_hospital" class="flex-1 text-xs sm:text-sm bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-2 min-w-[100px]">
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:items-end ml-2 gap-2">
                                            <label class="flex items-center whitespace-nowrap"><input type="checkbox" name="leave_details[]" value="Out Patient" class="mr-3 text-blue-600 rounded"> Out Patient (Specify)</label>
                                            <input type="text" name="details_specify_outpatient" class="flex-1 text-xs sm:text-sm bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-2 min-w-[100px]">
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-lg">
                                        <span class="block mb-2 font-bold text-slate-700 text-[10px] sm:text-xs">Special Leave Benefits for Women:</span>
                                        <div class="flex flex-col sm:flex-row sm:items-end ml-2 gap-2">
                                            <span class="whitespace-nowrap font-medium">(Specify Illness)</span>
                                            <input type="text" name="details_specify_women" class="flex-1 text-xs sm:text-sm bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-2 min-w-[100px]">
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-lg">
                                        <span class="block mb-2 font-bold text-slate-700 text-[10px] sm:text-xs">Study Leave:</span>
                                        <label class="flex items-center ml-2 mb-2"><input type="checkbox" name="leave_details[]" value="Completion of Masters Degree" class="mr-3 text-blue-600 rounded"> Completion of Master's Degree</label>
                                        <label class="flex items-center ml-2"><input type="checkbox" name="leave_details[]" value="BAR/Board Examination Review" class="mr-3 text-blue-600 rounded"> BAR/Board Exam Review</label>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-lg">
                                        <span class="block mb-2 font-bold text-slate-700 text-[10px] sm:text-xs">Other purpose:</span>
                                        <label class="flex items-center ml-2 mb-2"><input type="checkbox" name="leave_details[]" value="Monetization of Leave Credits" class="mr-3 text-blue-600 rounded"> Monetization of Leave Credits</label>
                                        <label class="flex items-center ml-2"><input type="checkbox" name="leave_details[]" value="Terminal Leave" class="mr-3 text-blue-600 rounded"> Terminal Leave</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4 sm:mt-6">
                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm">
                                <label class="block text-xs font-bold text-slate-700 mb-2 sm:mb-3 uppercase tracking-wide">6.C Working Days Applied For <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label>
                                <input type="number" name="working_days" class="w-full text-xs sm:text-sm bg-slate-50 border border-slate-300 rounded-lg shadow-sm px-3 py-2.5 mb-4 sm:mb-5" placeholder="e.g. 5" required>
                                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Inclusive Dates</label>
                                <div class="flex flex-col sm:flex-row items-center gap-2 sm:space-x-3">
                                    <input type="text" name="start_date" placeholder="Select date" autocomplete="off" required class="september-date w-full sm:w-1/2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg shadow-sm px-3 py-2.5 cursor-pointer">
                                    <span class="text-slate-400 font-bold text-xs uppercase hidden sm:inline">to</span>
                                    <input type="text" name="end_date" placeholder="Select date" autocomplete="off" required class="september-date w-full sm:w-1/2 text-xs sm:text-sm bg-white border border-slate-300 rounded-lg shadow-sm px-3 py-2.5 cursor-pointer">
                                </div>
                            </div>
                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-center">
                                <label class="block text-xs font-bold text-slate-700 mb-3 sm:mb-4 uppercase tracking-wide">6.D Commutation</label>
                                <div class="space-y-3 sm:space-y-4 text-xs sm:text-sm text-slate-600 font-medium ml-2">
                                    <label class="flex items-center"><input type="radio" name="commutation" value="Not Requested" class="mr-3 w-4 h-4 text-blue-600"> Not Requested</label>
                                    <label class="flex items-center"><input type="radio" name="commutation" value="Requested" class="mr-3 w-4 h-4 text-blue-600"> Requested</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 shrink-0 rounded-b-2xl">
                <button type="button" onclick="closeModal('leaveModal')" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-100 transition-colors order-2 sm:order-1">Cancel</button>
                <button type="submit" form="leaveForm" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold bg-blue-700 text-white rounded-xl hover:bg-blue-800 shadow-md transition-colors order-1 sm:order-2">Submit Leave Application</button>
            </div>
        </div>
    </div>

    <div id="passSlipModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center z-[60] p-4 sm:p-6 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all my-auto max-h-[95vh] flex flex-col">
            <div class="px-4 sm:px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-purple-50 shrink-0 rounded-t-2xl">
                <h3 class="font-bold text-purple-800 text-base sm:text-lg flex items-center gap-2"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg> File Pass Slip Request</h3>
                <button type="button" onclick="closeModal('passSlipModal')" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1">&times;</button>
            </div>
            <div class="overflow-y-auto p-4 sm:p-6 flex-1">
                <form id="passSlipForm" action="{{ route('employee.pass_slip.store') ?? '#' }}" method="POST" class="space-y-4 sm:space-y-5 needs-validation" novalidate>
                    @csrf
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Select Employee <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label>
                        <select name="employee_id" id="passEmployeeSelect" required class="w-full text-sm font-medium">
                            <option value="">-- Search Employee --</option>
                            @foreach($activeEmployees ?? [] as $emp) <option value="{{ $emp->id }}">{{ $emp->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Specify the OFFICIAL BUSINESS transaction <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label>
                        <textarea name="transaction_details" rows="2" required class="w-full text-xs sm:text-sm border-slate-300 rounded-lg shadow-sm p-3 focus:ring-purple-500" placeholder="Details of business..."></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Date of Official Business Transaction <span class="text-rose-500 font-black text-sm ml-0.5">*</span></label>
                            <input type="text" name="transaction_date" placeholder="Select date" autocomplete="off" required class="september-date w-full text-xs sm:text-sm border-slate-300 rounded-lg shadow-sm p-2 sm:p-2.5 focus:ring-purple-500 bg-white cursor-pointer">
                        </div>
                        <div class="p-3 sm:p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Inclusive Time</label>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] sm:text-xs font-semibold text-slate-500">From: <span class="text-rose-500 font-black text-sm ml-0.5">*</span></span>
                                <input type="time" name="time_from" required class="flex-1 text-xs sm:text-sm border-slate-300 rounded-md shadow-sm p-1.5 focus:ring-purple-500 bg-white">
                                <span class="text-[10px] sm:text-xs font-semibold text-slate-500">To: <span class="text-rose-500 font-black text-sm ml-0.5">*</span></span>
                                <input type="time" name="time_to" required class="flex-1 text-xs sm:text-sm border-slate-300 rounded-md shadow-sm p-1.5 focus:ring-purple-500 bg-white">
                            </div>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Unlogged Attendance in Biometric System</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-4 text-xs sm:text-sm text-slate-700 font-medium mb-4">
                            <label class="flex items-center gap-2 cursor-pointer hover:text-purple-700"><input type="checkbox" name="unlogged_time_in_am" value="1" class="w-4 h-4 text-purple-600 rounded border-slate-300 focus:ring-purple-500"><span>TIME In <span class="text-[10px] sm:text-xs text-slate-500">(8:00 AM)</span></span></label>
                            <label class="flex items-center gap-2 cursor-pointer hover:text-purple-700"><input type="checkbox" name="unlogged_time_out_am" value="1" class="w-4 h-4 text-purple-600 rounded border-slate-300 focus:ring-purple-500"><span>TIME Out <span class="text-[10px] sm:text-xs text-slate-500">(12:00 NN)</span></span></label>
                            <label class="flex items-center gap-2 cursor-pointer hover:text-purple-700"><input type="checkbox" name="unlogged_time_in_pm" value="1" class="w-4 h-4 text-purple-600 rounded border-slate-300 focus:ring-purple-500"><span>TIME In <span class="text-[10px] sm:text-xs text-slate-500">(1:00 PM)</span></span></label>
                            <label class="flex items-center gap-2 cursor-pointer hover:text-purple-700"><input type="checkbox" name="unlogged_time_out_pm" value="1" class="w-4 h-4 text-purple-600 rounded border-slate-300 focus:ring-purple-500"><span>TIME Out <span class="text-[10px] sm:text-xs text-slate-500">(5:00 PM)</span></span></label>
                        </div>
                        <div class="pt-3 border-t border-slate-200">
                            <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Number of unlogged Attendance in Biometric System</label>
                            <input type="number" name="unlogged_count" min="0" max="4" class="w-full sm:w-24 text-xs sm:text-sm border-slate-300 rounded-lg shadow-sm p-2 focus:ring-purple-500">
                        </div>
                    </div>
                </form>
            </div>
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 shrink-0 rounded-b-2xl">
                <button type="button" onclick="closeModal('passSlipModal')" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-100 transition-colors order-2 sm:order-1">Cancel</button>
                <button type="submit" form="passSlipForm" class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold bg-purple-600 text-white rounded-xl hover:bg-purple-700 shadow-md transition-colors order-1 sm:order-2">Submit Pass Slip</button>
            </div>
        </div>
    </div>

    <!-- Script to handle Modals, Tabs, and Live Search -->
    <script>
        $(document).ready(function () {
            flatpickr(".september-date", {
                dateFormat: "Y-m-d", disableMobile: true,
                onChange: function(selectedDates, dateStr, instance) { instance.close(); },
                onOpen: function (selectedDates, dateStr, instance) {
                    if (selectedDates.length === 0) { instance.jumpToDate(new Date()); }
                }
            });
        });

        document.querySelectorAll('form.needs-validation').forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) { event.preventDefault(); event.stopPropagation(); }
                form.classList.add('was-validated');
            }, false);
        });

        $(document).ready(function() {
            $('#travelEmployeeSelect').select2({ dropdownParent: $('#travelModal') });
            $('#leaveEmployeeSelect').select2({ dropdownParent: $('#leaveModal') });
            $('#passEmployeeSelect').select2({ dropdownParent: $('#passSlipModal') });
        });

        function openModal(modalId) { document.getElementById(modalId).classList.remove('hidden'); }
        function closeModal(modalId) { document.getElementById(modalId).classList.add('hidden'); }

        function switchTab(tabId) {
            localStorage.setItem('activeHrTab', tabId);
            const tabs = ['travel', 'pass', 'leave'];
            tabs.forEach(id => {
                const content = document.getElementById('content-' + id);
                const btn = document.getElementById('tab-' + id);
                if (id === tabId) {
                    content.classList.remove('hidden'); content.classList.add('block');
                    btn.className = "tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-800 bg-white border border-slate-200 border-b-white rounded-t-2xl relative z-10 translate-y-[1px] flex items-center gap-2 whitespace-nowrap";
                } else {
                    content.classList.remove('block'); content.classList.add('hidden');
                    btn.className = "tab-btn px-5 sm:px-8 py-3 text-sm sm:text-base font-bold text-slate-500 hover:text-slate-700 transition-colors border border-transparent border-b-0 rounded-t-2xl relative z-0 translate-y-[1px] flex items-center gap-2 whitespace-nowrap";
                }
            });
        }

        // 🌟 LIVE SEARCH FOR TRACKING TABS 🌟
        const requestSearchInput = document.getElementById('requestSearchInput');
        if (requestSearchInput) {
            requestSearchInput.addEventListener('keyup', function() {
                let searchQuery = this.value.toLowerCase();
                document.querySelectorAll('.tracking-card').forEach(card => {
                    let text = card.textContent.toLowerCase();
                    card.style.display = text.includes(searchQuery) ? '' : 'none';
                });
            });
        }

        $(document).ready(function() {
            let targetTab = localStorage.getItem('activeHrTab') || 'travel';
            switchTab(targetTab);

            setTimeout(() => {
                const successAlert = document.getElementById('success-alert');
                const errorAlert = document.getElementById('error-alert');
                if (successAlert) { successAlert.style.opacity = '0'; setTimeout(() => successAlert.remove(), 500); }
                if (errorAlert) { errorAlert.style.opacity = '0'; setTimeout(() => errorAlert.remove(), 500); }
            }, 5000); 
        });
    </script>
</x-employee-layout>