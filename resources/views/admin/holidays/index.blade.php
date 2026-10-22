<x-employee-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 space-y-6 md:space-y-8">

        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-4 flex justify-between items-end">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-3">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                    Holidays & Work Suspensions
                </h1>
                <p class="text-slate-500 mt-1 font-medium">Manage system exemptions for DTR calculations.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-700 font-bold rounded-xl border border-emerald-200 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- ADD NEW HOLIDAY FORM -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ type: 'regular' }">
                    <div class="p-5 border-b border-slate-100 bg-slate-50">
                        <h2 class="font-bold text-lg text-slate-800">Add New Date</h2>
                    </div>

                    <form action="{{ route('holidays.store') }}" method="POST" class="p-5 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Event Name</label>
                            <input type="text" name="name" required placeholder="e.g. Independence Day, Typhoon Ambo" class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 p-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Date</label>
                            <input type="date" name="date" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 p-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Event Type</label>
                            <select name="type" x-model="type" class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm font-medium rounded-lg focus:ring-amber-500 focus:border-amber-500 p-2.5">
                                <option value="regular">Regular Holiday (Full Day)</option>
                                <option value="special">Special Non-Working (Full Day)</option>
                                <option value="suspension">Work Suspension (Calamity/Half-day)</option>
                                <option value="suspension">Work From Home (Full Day)</option>
                            </select>
                        </div>

                        <!-- Conditionally shown only if 'suspension' is selected -->
                        <div x-show="type === 'suspension'" x-transition class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <label class="block text-xs font-bold text-amber-800 uppercase tracking-wider mb-1.5">Suspension Time (Cut-off)</label>
                            <input type="time" name="time_suspended" class="w-full bg-white border border-amber-300 text-slate-800 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 p-2.5">
                            <p class="text-[10px] text-amber-700 mt-1.5 font-medium leading-tight">If work is suspended at noon, enter 12:00 PM. Employees will not be marked absent for missing punches after this time.</p>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-amber-500 text-white font-bold rounded-lg hover:bg-amber-600 shadow-sm transition-colors mt-2">
                            Save Event
                        </button>
                    </form>
                </div>
            </div>

            <!-- HOLIDAYS LIST TABLE -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 bg-slate-50">
                        <h2 class="font-bold text-lg text-slate-800">Recorded Dates</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 text-xs text-slate-500 uppercase tracking-wider border-b border-slate-200">
                                    <th class="p-4 font-bold">Date</th>
                                    <th class="p-4 font-bold">Event Details</th>
                                    <th class="p-4 font-bold text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($holidays as $holiday)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="p-4 whitespace-nowrap">
                                            <p class="font-black text-slate-800">{{ \Carbon\Carbon::parse($holiday->date)->format('M d, Y') }}</p>
                                            <p class="text-xs font-bold text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($holiday->date)->format('l') }}</p>
                                        </td>
                                        <td class="p-4">
                                            <p class="font-bold text-slate-800 text-base">{{ $holiday->name }}</p>
                                            <div class="mt-1.5 flex flex-wrap gap-2">
                                                @if($holiday->type === 'regular')
                                                    <span class="bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded border border-blue-200">Regular Holiday</span>
                                                @elseif($holiday->type === 'special')
                                                    <span class="bg-purple-100 text-purple-700 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded border border-purple-200">Special Non-Working</span>
                                                @elseif($holiday->type === 'suspension')
                                                    <span class="bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded border border-amber-200">Work Suspension</span>
                                                    <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200">
                                                        Cut-off: {{ \Carbon\Carbon::parse($holiday->time_suspended)->format('h:i A') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="p-4 text-center align-middle">
                                            <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-8 text-center text-slate-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="text-sm font-medium">No holidays or suspensions recorded yet.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-employee-layout>
