<x-employee-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 space-y-6">
        
        <!-- Page Header & Action Button -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-rose-500 hover:text-rose-700 mb-2 inline-block">&larr; Back to Dashboard</a>
                <h1 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-3">
                    <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Department & Shift Settings
                </h1>
                <p class="text-slate-500 mt-1 font-medium">Manage LGU departments, required punches, and automated reporting emails.</p>
            </div>
            <div class="shrink-0">
                <button type="button" onclick="openAddModal()" class="w-full md:w-auto px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-md transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Department
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm font-bold flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl shadow-sm font-bold">
                @foreach ($errors->all() as $error) <div class="flex items-center gap-2"><svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $error }}</div> @endforeach
            </div>
        @endif

        <!-- EXISTING DEPARTMENTS TABLE (Now Full Width) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-6">
            <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <h2 class="font-black text-slate-800 text-lg">Active Departments <span class="text-slate-500 font-medium ml-1">({{ $departments->count() }})</span></h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider font-bold">
                            <th class="p-4 md:p-5">Department Name</th>
                            <th class="p-4 md:p-5">Report Email</th>
                            <th class="p-4 md:p-5 text-center">Shift Type</th>
                            <th class="p-4 md:p-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($departments as $dept)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-4 md:p-5 font-black text-slate-800">{{ $dept->name }}</td>
                                <td class="p-4 md:p-5">
                                    @if($dept->email)
                                        <a href="mailto:{{ $dept->email }}" class="text-blue-600 hover:text-blue-800 hover:underline font-bold flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $dept->email }}
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic text-xs font-medium">No email assigned</span>
                                    @endif
                                </td>
                                <td class="p-4 md:p-5 text-center">
                                    @if($dept->required_punches == 2)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[10px] font-black uppercase bg-purple-100 text-purple-700 shadow-sm border border-purple-200">
                                            2 Punches (Straight)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[10px] font-black uppercase bg-slate-100 text-slate-600 shadow-sm border border-slate-200">
                                            4 Punches (Regular)
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 md:p-5 text-right space-x-4 whitespace-nowrap flex items-center justify-end">
                                    <button type="button" 
                                        onclick="openEditModal({{ $dept->id }}, '{{ $dept->name }}', '{{ $dept->email }}', {{ $dept->required_punches }})"
                                        class="font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                                    </button>
                                    
                                    <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Are you sure? Employees currently in this department will be marked as Unassigned.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="font-bold text-rose-500 hover:text-rose-700 transition-colors flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-10 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        <p class="font-bold text-slate-600 text-base">No departments configured yet.</p>
                                        <p class="text-sm text-slate-400 mt-1">Click the "Add Department" button to get started.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 🌟 ADD DEPARTMENT MODAL 🌟 -->
    <div id="addDeptModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center z-[60] p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-rose-50 rounded-t-2xl">
                <h3 class="font-bold text-rose-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add New Department
                </h3>
                <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600 focus:outline-none bg-white rounded-full p-1 shadow-sm">&times;</button>
            </div>
            <div class="p-6">
                <form action="{{ route('departments.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Department Name *</label>
                        <input type="text" name="name" required placeholder="e.g. HRMO" class="w-full text-sm font-bold border-slate-300 rounded-xl shadow-sm focus:ring-rose-500 uppercase p-3 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Department Email</label>
                        <input type="email" name="email" placeholder="e.g. hrmo@lakesebu.gov.ph" class="w-full text-sm font-medium border-slate-300 rounded-xl shadow-sm focus:ring-rose-500 p-3 bg-slate-50 focus:bg-white transition-colors lowercase">
                        <p class="text-[10px] text-slate-400 mt-1.5 leading-tight font-medium">Used for automated end-of-month DTR report distribution.</p>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Required Punches *</label>
                        <select name="required_punches" required class="w-full text-sm font-bold text-slate-700 border-slate-300 rounded-xl shadow-sm focus:ring-rose-500 p-3 bg-slate-50 focus:bg-white cursor-pointer transition-colors">
                            <option value="4">4 Punches (Regular: AM & PM)</option>
                            <option value="2">2 Punches (Straight: Field/Medical Shift)</option>
                        </select>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="closeAddModal()" class="w-full py-3 text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">Cancel</button>
                        <button type="submit" class="w-full py-3 text-sm font-bold bg-rose-600 text-white rounded-xl hover:bg-rose-700 shadow-md transition-colors">Save Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 🌟 EDIT DEPARTMENT MODAL 🌟 -->
    <div id="editDeptModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center z-[60] p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-blue-50 rounded-t-2xl">
                <h3 class="font-bold text-blue-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Edit Department
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 focus:outline-none bg-white rounded-full p-1 shadow-sm">&times;</button>
            </div>
            <div class="p-6">
                <form id="editDeptForm" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Department Name *</label>
                        <input type="text" name="name" id="edit_dept_name" required class="w-full text-sm font-bold border-slate-300 rounded-xl shadow-sm focus:ring-blue-500 uppercase p-3 bg-slate-50 focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Department Email</label>
                        <input type="email" name="email" id="edit_dept_email" class="w-full text-sm font-medium border-slate-300 rounded-xl shadow-sm focus:ring-blue-500 p-3 bg-slate-50 focus:bg-white transition-colors lowercase">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Required Punches *</label>
                        <select name="required_punches" id="edit_dept_punches" required class="w-full text-sm font-bold text-slate-700 border-slate-300 rounded-xl shadow-sm focus:ring-blue-500 p-3 bg-slate-50 focus:bg-white cursor-pointer transition-colors">
                            <option value="4">4 Punches (Regular: AM & PM)</option>
                            <option value="2">2 Punches (Straight: Field/Medical Shift)</option>
                        </select>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="closeEditModal()" class="w-full py-3 text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">Cancel</button>
                        <button type="submit" class="w-full py-3 text-sm font-bold bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-md transition-colors">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addDeptModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addDeptModal').classList.add('hidden');
        }

        function openEditModal(id, name, email, punches) {
            document.getElementById('editDeptForm').action = '/departments/' + id;
            document.getElementById('edit_dept_name').value = name;
            document.getElementById('edit_dept_email').value = email;
            document.getElementById('edit_dept_punches').value = punches;
            document.getElementById('editDeptModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editDeptModal').classList.add('hidden');
        }
    </script>
</x-employee-layout>