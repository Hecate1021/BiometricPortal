<x-employee-layout>
    <style>
        /* Custom Scrollbar for the table */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* 🌟 CUSTOM RED HIGHLIGHT FOR MISSED REQUIRED FIELDS 🌟 */
        .was-validated :invalid {
            border-color: #f43f5e !important; /* Rose 500 */
            background-color: #fff1f2 !important; /* Rose 50 */
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 space-y-6">

        <!-- Header & Add Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Employee Directory
                </h1>
                <p class="text-slate-500 mt-1">Manage employee profiles, departments, and job titles.</p>
            </div>
            <button onclick="openModal('addEmployeeModal')" class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-md transition-colors flex items-center justify-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Employee
            </button>
        </div>

        <!-- Alerts -->
        @if(session('success'))
        <div id="success-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm flex items-center gap-3 transition-opacity duration-500">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
        @endif

        @if ($errors->any())
        <div id="error-alert" class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl shadow-sm flex items-start gap-3 transition-opacity duration-500">
            <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <strong class="font-bold text-sm block">Please check the form below for errors!</strong>
                <ul class="list-disc pl-5 mt-1 text-xs text-rose-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- 🌟 LIVE SEARCH BAR 🌟 -->
        <div class="flex justify-end">
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" id="employeeSearchInput" placeholder="Search name, ID, or department..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all text-slate-700 font-medium">
            </div>
        </div>

        <!-- 📋 MAIN EMPLOYEE TABLE 📋 -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto hide-scrollbar">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Employee Name</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Department & Title</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">System ID</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">ZKTeco ID</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="employeeTableBody">
                        @forelse($employees as $employee)
                        <!-- 🌟 Added 'employee-row' class for JS targeting 🌟 -->
                        <tr class="hover:bg-slate-50 transition-colors employee-row">
                            <td class="px-6 py-4">
                                <div class="font-black text-slate-800">{{ $employee->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700 text-sm">{{ $employee->department ?? 'Unassigned' }}</div>
                                <div class="text-xs text-slate-500 italic mt-0.5">{{ $employee->position ?? 'ADMINISTRATIVE ASSISTANT I' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 rounded font-mono text-xs font-bold shadow-sm">
                                    {{ $employee->company_id ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-purple-50 text-purple-700 border border-purple-200 px-2.5 py-1 rounded font-mono text-xs font-bold shadow-sm">
                                    {{ $employee->biometric_id ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit Button -->
                                    <button onclick="openModal('editEmployeeModal-{{ $employee->id }}')" class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <!-- Delete Button -->
                                    <button onclick="openModal('deleteEmployeeModal-{{ $employee->id }}')" class="p-2 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors" title="Remove">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- ========================================== -->
                        <!-- ✏️ EDIT MODAL (Generated for each row)      -->
                        <!-- ========================================== -->
                        <div id="editEmployeeModal-{{ $employee->id }}" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center z-[60] p-4 sm:p-6 overflow-y-auto">
                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl transform transition-all my-auto max-h-[95vh] flex flex-col">

                                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-blue-50 shrink-0 rounded-t-2xl">
                                    <h3 class="font-bold text-blue-800 text-lg flex items-center gap-2">
                                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit Employee: {{ $employee->name }}
                                    </h3>
                                    <button type="button" onclick="closeModal('editEmployeeModal-{{ $employee->id }}')" class="text-slate-400 hover:text-slate-600">&times;</button>
                                </div>

                                <div class="overflow-y-auto p-6 flex-1">
                                    <form id="editForm-{{ $employee->id }}" action="{{ route('employees.update', $employee->id) }}" method="POST" class="space-y-5 needs-validation" novalidate>
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Full Name <span class="text-rose-500">*</span></label>
                                            <input type="text" name="name" value="{{ old('name', $employee->name) }}" required class="w-full bg-white border border-slate-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 text-sm font-bold text-slate-800">
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">System ID</label>
                                                <input type="text" name="company_id" value="{{ old('company_id', $employee->company_id) }}" placeholder="e.g. EM0001" class="w-full bg-slate-50 border border-slate-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 font-mono text-sm uppercase">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">ZKTeco Biometric ID</label>
                                                <input type="text" name="biometric_id" value="{{ old('biometric_id', $employee->biometric_id) }}" placeholder="e.g. 150" class="w-full bg-slate-50 border border-slate-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 font-mono text-sm">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Secondary Biometric ID (Optional)</label>
                                            <input type="text" name="secondary_biometric_id" value="{{ old('secondary_biometric_id', $employee->secondary_biometric_id) }}" placeholder="e.g. 2025032" class="w-full text-sm font-medium border-slate-300 rounded-xl shadow-sm focus:ring-blue-500 p-3 bg-slate-50 focus:bg-white transition-colors">
                                            <p class="text-[10px] text-slate-400 mt-1.5 leading-tight font-medium">Use if the employee is registered on a second physical device with a different ID.</p>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-5 mt-5">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Department</label>
                                                <select name="department" class="w-full bg-white border border-slate-300 text-slate-700 rounded-lg p-3 shadow-sm focus:ring-blue-500 text-sm">
                                                    <option value="">-- Unassigned --</option>
                                                    @foreach($departments as $dept)
                                                        <option value="{{ $dept->name }}" {{ old('department', $employee->department) === $dept->name ? 'selected' : '' }}>
                                                            {{ $dept->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Position / Job Title</label>
                                                <input type="text" name="position" value="{{ old('position', $employee->position) }}" placeholder="e.g. Admin Assistant I" class="w-full bg-white border border-slate-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 text-sm">
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 shrink-0 rounded-b-2xl">
                                    <button type="button" onclick="closeModal('editEmployeeModal-{{ $employee->id }}')" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-100 transition-colors">Cancel</button>
                                    <button type="submit" form="editForm-{{ $employee->id }}" class="px-5 py-2.5 text-sm font-bold bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-md transition-colors">Save Changes</button>
                                </div>
                            </div>
                        </div>

                        <!-- ========================================== -->
                        <!-- 🗑️ DELETE MODAL (Generated for each row)    -->
                        <!-- ========================================== -->
                        <div id="deleteEmployeeModal-{{ $employee->id }}" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center z-[60] p-4">
                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all text-center p-6">
                                <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <h3 class="text-lg font-black text-slate-800 mb-2">Remove Employee?</h3>
                                <p class="text-sm text-slate-500 mb-6">Are you sure you want to remove <strong>{{ $employee->name }}</strong>? Their old DTR records will be preserved, but they will be hidden from the active list.</p>

                                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="flex gap-3 justify-center">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="closeModal('deleteEmployeeModal-{{ $employee->id }}')" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors flex-1">Cancel</button>
                                    <button type="submit" class="px-5 py-2.5 text-sm font-bold bg-rose-600 text-white rounded-xl hover:bg-rose-700 shadow-md transition-colors flex-1">Yes, Remove</button>
                                </form>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">No active employees found in the system.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- ➕ ADD NEW EMPLOYEE MODAL                        -->
    <!-- ============================================== -->
    <div id="addEmployeeModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center z-[60] p-4 sm:p-6 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl transform transition-all my-auto max-h-[95vh] flex flex-col">

            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-emerald-50 shrink-0 rounded-t-2xl">
                <h3 class="font-bold text-emerald-800 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Register New Employee
                </h3>
                <button type="button" onclick="closeModal('addEmployeeModal')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <div class="overflow-y-auto p-6 flex-1">
                <form id="addEmployeeForm" action="{{ route('employees.store') }}" method="POST" class="space-y-5 needs-validation" novalidate>
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Juan Dela Cruz" required class="w-full bg-white border border-slate-300 rounded-lg shadow-sm p-3 focus:ring-emerald-500 text-sm font-bold text-slate-800">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">System ID</label>
                            <input type="text" name="company_id" value="{{ old('company_id') }}" placeholder="e.g. EM0001" class="w-full bg-slate-50 border border-slate-300 rounded-lg shadow-sm p-3 focus:ring-emerald-500 font-mono text-sm uppercase">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">ZKTeco Biometric ID</label>
                            <input type="text" name="biometric_id" value="{{ old('biometric_id') }}" placeholder="e.g. 150" class="w-full bg-slate-50 border border-slate-300 rounded-lg shadow-sm p-3 focus:ring-emerald-500 font-mono text-sm">
                        </div>

                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Secondary Biometric ID (Optional)</label>
                        <input type="text" name="secondary_biometric_id" value="{{ old('secondary_biometric_id') }}" placeholder="e.g. 2025032" class="w-full text-sm font-medium border-slate-300 rounded-xl shadow-sm focus:ring-emerald-500 p-3 bg-slate-50 focus:bg-white transition-colors">
                        <p class="text-[10px] text-slate-400 mt-1.5 leading-tight font-medium">Use if the employee is registered on a second physical device with a different ID.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-5 mt-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Department</label>
                            <select name="department" class="w-full bg-white border border-slate-300 text-slate-700 rounded-lg p-3 shadow-sm focus:ring-emerald-500 text-sm">
                                <option value="">-- Unassigned --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->name }}" {{ old('department') === $dept->name ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Position / Job Title</label>
                            <input type="text" name="position" value="{{ old('position') }}" placeholder="e.g. Admin Assistant I" class="w-full bg-white border border-slate-300 rounded-lg shadow-sm p-3 focus:ring-emerald-500 text-sm">
                        </div>
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 shrink-0 rounded-b-2xl">
                <button type="button" onclick="closeModal('addEmployeeModal')" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-100 transition-colors">Cancel</button>
                <button type="submit" form="addEmployeeForm" class="px-5 py-2.5 text-sm font-bold bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 shadow-md transition-colors">Save Employee</button>
            </div>
        </div>
    </div>

    <!-- Script to handle Modals, Validation, & Live Search -->
    <script>
        // 🌟 LIVE SEARCH JAVASCRIPT 🌟
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('employeeSearchInput');
            const rows = document.querySelectorAll('.employee-row');

            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();

                    rows.forEach(row => {
                        // Get all text content inside this specific row
                        const rowText = row.innerText.toLowerCase();

                        // Show or hide the row based on the search
                        if (rowText.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });

        // 🌟 CUSTOM JAVASCRIPT VALIDATION HIGHLIGHTING 🌟
        document.querySelectorAll('form.needs-validation').forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Open & Close Modal Functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Check for validation errors on page load and reopen the Add modal if needed
        document.addEventListener("DOMContentLoaded", function() {
            @if($errors->any())
                openModal('addEmployeeModal');
            @endif

            // Auto hide alerts after 5 seconds
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
        });
    </script>
</x-employee-layout>
