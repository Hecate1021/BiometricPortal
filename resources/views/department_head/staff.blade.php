<x-employee-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 space-y-6 md:space-y-8">
        
        <div class="border-b border-slate-200 pb-4">
            <h1 class="text-2xl md:text-3xl font-black text-slate-800">Department Staff</h1>
            <p class="text-slate-500 mt-1">Directory for: <strong class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ $me->department }}</strong></p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h2 class="font-bold text-lg text-slate-800">Employee Directory</h2>
                <span class="bg-slate-200 text-slate-700 text-xs font-bold py-1 px-3 rounded-full">{{ $employees->count() }} Members</span>
            </div>
            
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($employees as $emp)
                    <div class="p-4 border border-slate-200 rounded-xl hover:shadow-md hover:border-indigo-300 transition bg-white flex flex-col justify-between gap-4">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 block">{{ $emp->company_id ?? 'Pending' }}</span>
                            <span class="font-bold text-slate-700 leading-tight block">{{ $emp->name }}</span>
                        </div>
                        
                        <a href="{{ route('dtr.show', $emp->id) }}" class="inline-flex items-center justify-center w-full py-2 bg-slate-50 hover:bg-indigo-50 text-indigo-600 text-xs font-bold rounded-lg border border-slate-200 hover:border-indigo-200 transition">
                            View DTR
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-employee-layout>