<x-employee-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-blue-50">
                <h3 class="font-bold text-blue-800 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit Leave Application
                </h3>
                <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800 font-bold text-sm">&larr; Go Back</a>
            </div>
            <div class="p-6 flex-1">
                @if(session('success'))
                    <div class="mb-5 p-4 bg-emerald-50 text-emerald-700 rounded-xl font-bold">{{ session('success') }}</div>
                @endif
                <form action="{{ route('employee.leave.update', $leave->id) }}" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 bg-slate-50/80 p-5 rounded-xl border border-slate-200 shadow-sm">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">1. Office/Department</label>
                            <input type="text" name="office_department" value="{{ old('office_department', $leave->office_department) }}" class="w-full border-slate-300 rounded-md p-2.5 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">2. Employee</label>
                            <input type="text" disabled value="{{ $leave->employee->name ?? 'Unknown' }}" class="w-full bg-slate-100 border-slate-300 rounded-md p-2.5 font-medium">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">3. Date of Filing *</label>
                            <input type="text" name="date_of_filing" value="{{ old('date_of_filing', $leave->date_of_filing) }}" required class="september-date w-full bg-white border-slate-300 rounded-md p-2.5 cursor-pointer focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <h3 class="font-black text-slate-800 text-sm tracking-wide mb-5">6. DETAILS OF APPLICATION</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- 6.A -->
                            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                                <label class="block text-xs font-bold text-slate-700 mb-4 uppercase tracking-wide">6.A Type of Leave *</label>
                                <div class="space-y-3 text-sm text-slate-600 font-medium">
                                    @php $dbType = $leave->leave_type ?? ''; @endphp
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="Vacation Leave" class="mr-3 text-blue-600" {{ str_contains($dbType, 'Vacation Leave') ? 'checked' : '' }}> Vacation Leave</label>
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="Mandatory/Forced Leave" class="mr-3 text-blue-600" {{ str_contains($dbType, 'Mandatory/Forced Leave') ? 'checked' : '' }}> Mandatory/Forced Leave</label>
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="Sick Leave" class="mr-3 text-blue-600" {{ str_contains($dbType, 'Sick Leave') ? 'checked' : '' }}> Sick Leave</label>
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="Maternity Leave" class="mr-3 text-blue-600" {{ str_contains($dbType, 'Maternity Leave') ? 'checked' : '' }}> Maternity Leave</label>
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="Paternity Leave" class="mr-3 text-blue-600" {{ str_contains($dbType, 'Paternity Leave') ? 'checked' : '' }}> Paternity Leave</label>
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="Special Privilege Leave" class="mr-3 text-blue-600" {{ str_contains($dbType, 'Special Privilege Leave') ? 'checked' : '' }}> Special Privilege Leave</label>
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="Solo Parent Leave" class="mr-3 text-blue-600" {{ str_contains($dbType, 'Solo Parent Leave') ? 'checked' : '' }}> Solo Parent Leave</label>
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="Study Leave" class="mr-3 text-blue-600" {{ str_contains($dbType, 'Study Leave') ? 'checked' : '' }}> Study Leave</label>
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="10-Day VAWC Leave" class="mr-3 text-blue-600" {{ str_contains($dbType, '10-Day VAWC Leave') ? 'checked' : '' }}> 10-Day VAWC Leave</label>
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="Rehabilitation Privilege" class="mr-3 text-blue-600" {{ str_contains($dbType, 'Rehabilitation Privilege') ? 'checked' : '' }}> Rehabilitation Privilege</label>
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="Special Leave for Women" class="mr-3 text-blue-600" {{ str_contains($dbType, 'Special Leave for Women') ? 'checked' : '' }}> Special Leave for Women</label>
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="Special Emergency Leave" class="mr-3 text-blue-600" {{ str_contains($dbType, 'Special Emergency Leave') ? 'checked' : '' }}> Special Emergency Leave</label>
                                    <label class="flex items-center"><input type="checkbox" name="leave_type[]" value="Adoption Leave" class="mr-3 text-blue-600" {{ str_contains($dbType, 'Adoption Leave') ? 'checked' : '' }}> Adoption Leave</label>
                                    <div class="flex items-end mt-4 pt-3 border-t border-slate-100">
                                        <span class="mr-3 font-bold text-slate-700">Others:</span>
                                        <input type="text" name="leave_type_others" value="{{ old('leave_type_others', $leave->leave_type_others) }}" class="flex-1 bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-2">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 6.B -->
                            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                                <label class="block text-xs font-bold text-slate-700 mb-4 uppercase tracking-wide">6.B Details of Leave</label>
                                <div class="space-y-6 text-sm text-slate-600 font-medium">
                                    @php $dbDetails = $leave->leave_details ?? ''; @endphp
                                    <div class="bg-slate-50 p-3 rounded-lg">
                                        <span class="block mb-2 font-bold text-slate-700 text-xs">Vacation/Special Privilege Leave:</span>
                                        <label class="flex items-center ml-2 mb-2"><input type="checkbox" name="leave_details[]" value="Within the Philippines" class="mr-3 text-blue-600" {{ str_contains($dbDetails, 'Within the Philippines') ? 'checked' : '' }}> Within the Philippines</label>
                                        <div class="flex items-end ml-2 gap-2">
                                            <label class="flex items-center whitespace-nowrap"><input type="checkbox" name="leave_details[]" value="Abroad" class="mr-3 text-blue-600" {{ str_contains($dbDetails, 'Abroad') ? 'checked' : '' }}> Abroad (Specify)</label>
                                            <input type="text" name="details_specify_abroad" value="{{ old('details_specify_abroad', $leave->details_specify_abroad) }}" class="flex-1 bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-2">
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-lg">
                                        <span class="block mb-2 font-bold text-slate-700 text-xs">Sick Leave:</span>
                                        <div class="flex items-end ml-2 mb-2 gap-2">
                                            <label class="flex items-center whitespace-nowrap"><input type="checkbox" name="leave_details[]" value="In Hospital" class="mr-3 text-blue-600" {{ str_contains($dbDetails, 'In Hospital') ? 'checked' : '' }}> In Hospital (Specify)</label>
                                            <input type="text" name="details_specify_hospital" value="{{ old('details_specify_hospital', $leave->details_specify_hospital) }}" class="flex-1 bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-2">
                                        </div>
                                        <div class="flex items-end ml-2 gap-2">
                                            <label class="flex items-center whitespace-nowrap"><input type="checkbox" name="leave_details[]" value="Out Patient" class="mr-3 text-blue-600" {{ str_contains($dbDetails, 'Out Patient') ? 'checked' : '' }}> Out Patient (Specify)</label>
                                            <input type="text" name="details_specify_outpatient" value="{{ old('details_specify_outpatient', $leave->details_specify_outpatient) }}" class="flex-1 bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-2">
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-lg">
                                        <span class="block mb-2 font-bold text-slate-700 text-xs">Special Leave Benefits for Women:</span>
                                        <div class="flex items-end ml-2 gap-2">
                                            <span class="whitespace-nowrap font-medium">(Specify Illness)</span>
                                            <input type="text" name="details_specify_women" value="{{ old('details_specify_women', $leave->details_specify_women) }}" class="flex-1 bg-transparent border-0 border-b-2 border-slate-300 focus:ring-0 focus:border-blue-500 py-1 px-2">
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-lg">
                                        <span class="block mb-2 font-bold text-slate-700 text-xs">Study Leave:</span>
                                        <label class="flex items-center ml-2 mb-2"><input type="checkbox" name="leave_details[]" value="Completion of Masters Degree" class="mr-3 text-blue-600" {{ str_contains($dbDetails, 'Completion of Masters Degree') ? 'checked' : '' }}> Completion of Master's Degree</label>
                                        <label class="flex items-center ml-2"><input type="checkbox" name="leave_details[]" value="BAR/Board Examination Review" class="mr-3 text-blue-600" {{ str_contains($dbDetails, 'BAR/Board Examination Review') ? 'checked' : '' }}> BAR/Board Exam Review</label>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-lg">
                                        <span class="block mb-2 font-bold text-slate-700 text-xs">Other purpose:</span>
                                        <label class="flex items-center ml-2 mb-2"><input type="checkbox" name="leave_details[]" value="Monetization of Leave Credits" class="mr-3 text-blue-600" {{ str_contains($dbDetails, 'Monetization of Leave Credits') ? 'checked' : '' }}> Monetization of Leave Credits</label>
                                        <label class="flex items-center ml-2"><input type="checkbox" name="leave_details[]" value="Terminal Leave" class="mr-3 text-blue-600" {{ str_contains($dbDetails, 'Terminal Leave') ? 'checked' : '' }}> Terminal Leave</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                                <label class="block text-xs font-bold text-slate-700 mb-3 uppercase tracking-wide">6.C Working Days Applied For *</label>
                                <input type="number" name="working_days" value="{{ old('working_days', $leave->working_days) }}" class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 mb-5" required>
                                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Inclusive Dates</label>
                                <div class="flex items-center gap-3">
                                    <input type="text" name="start_date" value="{{ old('start_date', $leave->start_date) }}" required class="september-date w-1/2 bg-white border border-slate-300 rounded-lg p-2.5 cursor-pointer">
                                    <span class="text-slate-400 font-bold text-xs uppercase">to</span>
                                    <input type="text" name="end_date" value="{{ old('end_date', $leave->end_date) }}" required class="september-date w-1/2 bg-white border border-slate-300 rounded-lg p-2.5 cursor-pointer">
                                </div>
                            </div>
                            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-center">
                                <label class="block text-xs font-bold text-slate-700 mb-4 uppercase tracking-wide">6.D Commutation</label>
                                <div class="space-y-4 text-sm text-slate-600 font-medium ml-2">
                                    <label class="flex items-center"><input type="radio" name="commutation" value="Not Requested" class="mr-3 w-4 h-4 text-blue-600" {{ old('commutation', $leave->commutation) == 'Not Requested' ? 'checked' : '' }}> Not Requested</label>
                                    <label class="flex items-center"><input type="radio" name="commutation" value="Requested" class="mr-3 w-4 h-4 text-blue-600" {{ old('commutation', $leave->commutation) == 'Requested' ? 'checked' : '' }}> Requested</label>
                                </div>
                            </div>
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