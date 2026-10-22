<x-employee-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-blue-50">
                <h3 class="font-bold text-blue-800 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit Travel Order
                </h3>
                <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800 font-bold text-sm">&larr; Go Back</a>
            </div>
            <div class="p-6 flex-1">
                @if(session('success'))
                    <div class="mb-5 p-4 bg-emerald-50 text-emerald-700 rounded-xl font-bold">{{ session('success') }}</div>
                @endif
                <form action="{{ route('employee.travel.update', $travel->id) }}" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Employee</label>
                        <input type="text" disabled value="{{ $travel->employee->name ?? 'Unknown' }}" class="w-full text-sm font-medium bg-slate-100 border-slate-300 rounded-lg p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Travel Order No.</label>
                        <input type="text" name="form_number" value="{{ old('form_number', $travel->form_number) }}" class="w-full border-slate-300 rounded-lg p-2.5 font-mono text-sm uppercase focus:ring-blue-500">
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Inclusive Date of Travel</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">From *</label>
                                <input type="text" name="start_date" value="{{ old('start_date', $travel->start_date) }}" required class="september-date w-full border-slate-300 rounded-lg p-2.5 bg-white cursor-pointer focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">To *</label>
                                <input type="text" name="end_date" value="{{ old('end_date', $travel->end_date) }}" required class="september-date w-full border-slate-300 rounded-lg p-2.5 bg-white cursor-pointer focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Destination</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">From *</label>
                                <input type="text" name="departure_from" value="{{ old('departure_from', $travel->departure_from) }}" required class="w-full border-slate-300 rounded-lg p-2.5 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">To *</label>
                                <input type="text" name="destination" value="{{ old('destination', $travel->destination) }}" required class="w-full border-slate-300 rounded-lg p-2.5 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Purpose *</label>
                        <input type="text" name="purpose" value="{{ old('purpose', $travel->purpose) }}" required class="w-full border-slate-300 rounded-lg p-2.5 focus:ring-blue-500">
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Travelling Expenses & Allowances *</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center cursor-pointer"><input type="radio" name="expense_type" value="Official" required {{ old('expense_type', $travel->expense_type) == 'Official' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500"><span class="ml-2 font-bold text-slate-700">Official</span></label>
                            <label class="flex items-center cursor-pointer"><input type="radio" name="expense_type" value="Personal" required {{ old('expense_type', $travel->expense_type) == 'Personal' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500"><span class="ml-2 font-bold text-slate-700">Personal</span></label>
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition-colors">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>$(document).ready(function () { flatpickr(".september-date", { dateFormat: "Y-m-d", disableMobile: true }); });</script>
</x-employee-layout>