<x-employee-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-purple-50">
                <h3 class="font-bold text-purple-800 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit Pass Slip
                </h3>
                <a href="{{ url()->previous() }}" class="text-purple-600 hover:text-purple-800 font-bold text-sm">&larr; Go Back</a>
            </div>
            <div class="p-6 flex-1">
                @if(session('success'))
                    <div class="mb-5 p-4 bg-emerald-50 text-emerald-700 rounded-xl font-bold">{{ session('success') }}</div>
                @endif
                <form action="{{ route('employee.pass_slip.update', $passSlip->id) }}" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Employee</label>
                        <input type="text" disabled value="{{ $passSlip->employee->name ?? 'Unknown' }}" class="w-full text-sm font-medium bg-slate-100 border-slate-300 rounded-lg p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Specify the OFFICIAL BUSINESS transaction *</label>
                        <textarea name="transaction_details" rows="2" required class="w-full border-slate-300 rounded-lg p-3 focus:ring-purple-500">{{ old('transaction_details', $passSlip->transaction_details) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Date of Official Business *</label>
                            <input type="text" name="transaction_date" value="{{ old('transaction_date', $passSlip->transaction_date) }}" required class="september-date w-full border-slate-300 rounded-lg p-2.5 bg-white cursor-pointer focus:ring-purple-500">
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Inclusive Time</label>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-500">From: *</span>
                                <input type="time" name="time_from" value="{{ old('time_from', \Carbon\Carbon::parse($passSlip->time_from)->format('H:i')) }}" required class="flex-1 border-slate-300 rounded-md p-1.5 focus:ring-purple-500 bg-white">
                                <span class="text-xs font-semibold text-slate-500">To: *</span>
                                <input type="time" name="time_to" value="{{ old('time_to', \Carbon\Carbon::parse($passSlip->time_to)->format('H:i')) }}" required class="flex-1 border-slate-300 rounded-md p-1.5 focus:ring-purple-500 bg-white">
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Unlogged Attendance in Biometric System</label>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <label class="flex items-center cursor-pointer"><input type="checkbox" name="unlogged_time_in_am" value="1" {{ old('unlogged_time_in_am', $passSlip->unlogged_time_in_am) ? 'checked' : '' }} class="mr-2 text-purple-600 rounded"> TIME In (8:00 AM)</label>
                            <label class="flex items-center cursor-pointer"><input type="checkbox" name="unlogged_time_out_am" value="1" {{ old('unlogged_time_out_am', $passSlip->unlogged_time_out_am) ? 'checked' : '' }} class="mr-2 text-purple-600 rounded"> TIME Out (12:00 NN)</label>
                            <label class="flex items-center cursor-pointer"><input type="checkbox" name="unlogged_time_in_pm" value="1" {{ old('unlogged_time_in_pm', $passSlip->unlogged_time_in_pm) ? 'checked' : '' }} class="mr-2 text-purple-600 rounded"> TIME In (1:00 PM)</label>
                            <label class="flex items-center cursor-pointer"><input type="checkbox" name="unlogged_time_out_pm" value="1" {{ old('unlogged_time_out_pm', $passSlip->unlogged_time_out_pm) ? 'checked' : '' }} class="mr-2 text-purple-600 rounded"> TIME Out (5:00 PM)</label>
                        </div>
                        <div class="pt-3 border-t border-slate-200">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Number of unlogged punches</label>
                            <input type="number" name="unlogged_count" value="{{ old('unlogged_count', $passSlip->unlogged_count) }}" min="0" max="4" class="w-24 border-slate-300 rounded-lg p-2 focus:ring-purple-500">
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-md transition-colors">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>$(document).ready(function () { flatpickr(".september-date", { dateFormat: "Y-m-d", disableMobile: true }); });</script>
</x-employee-layout>