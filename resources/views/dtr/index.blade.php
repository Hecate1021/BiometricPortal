<x-employee-layout>
    <!-- CSS & JS Integrations (Select2 & Flatpickr) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <style>
        /* Tailwind styling overrides for Select2 */
        .select2-container .select2-selection--single {
            height: 42px !important;
            border-color: #cbd5e1 !important;
            border-radius: 0.5rem !important;
            padding: 5px 10px !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 10px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155 !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            padding-left: 0 !important;
        }
        .select2-search__field { outline: none !important; focus: ring-emerald-500 !important; border-radius: 0.375rem !important; }
        .select2-dropdown { border-color: #cbd5e1 !important; border-radius: 0.5rem !important; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important; overflow: hidden !important; }

        /* Custom Scrollbar for the table */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* 🌟 CUSTOM RED HIGHLIGHT FOR MISSED REQUIRED FIELDS 🌟 */
        .was-validated :invalid {
            border-color: #f43f5e !important; 
            background-color: #fff1f2 !important; 
        }
        /* Make the Select2 box turn red if invalid */
        .was-validated select:invalid + .select2-container .select2-selection--single {
            border-color: #f43f5e !important;
            background-color: #fff1f2 !important;
        }
        /* Custom Flatpickr styling */
        .flatpickr-input { background-color: #ffffff !important; }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 space-y-6 md:space-y-8">
        
        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-4">
            <h1 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-3">
                HR Staff Portal 🗄️
            </h1>
            <p class="text-slate-500 mt-1">File requests for employees and track all live records.</p>
        </div>

        @if(session('success'))
        <div id="success-alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm transition-opacity duration-500 flex items-center gap-3" role="alert">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="block sm:inline font-medium text-sm sm:text-base">{{ session('success') }}</span>
        </div>
        @endif

        @if ($errors->any())
        <div id="error-alert" class="p-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl shadow-sm transition-opacity duration-500 flex items-start gap-3" role="alert">
            <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <strong class="font-bold text-sm block">Could not save the record!</strong>
                <ul class="list-disc pl-5 mt-1 text-sm text-rose-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="space-y-8">

            <!-- Security Check: Only show upload forms to Admins -->
            @if(Auth::check() && strtolower(trim(Auth::user()->role)) === 'admin')
                <!-- Database Upload Forms (1 col mobile, 2 cols tablet+) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- User.dat Sync Form -->
                    <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-slate-200">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h2 class="text-lg md:text-xl font-bold text-slate-800">Sync Employees</h2>
                        </div>
                        <p class="text-xs md:text-sm text-slate-500 mb-5">Upload <code>user.dat</code> to register names to biometric IDs.</p>
                        
                        <form action="{{ route('dtr.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                            @csrf
                            <input type="file" name="userFile" accept=".dat,.txt" required
                                   class="block w-full text-xs md:text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer border border-slate-200 rounded-lg">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-sm transition text-sm">
                                Sync Profiles
                            </button>
                        </form>
                    </div>

                    <!-- Attlog.dat Sync Form -->
                    <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-slate-200">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                            </div>
                            <h2 class="text-lg md:text-xl font-bold text-slate-800">Sync Offline Punches</h2>
                        </div>
                        <p class="text-xs md:text-sm text-slate-500 mb-5">Upload <code>attlog.dat</code> to recover missing attendance data.</p>
                        
                        <form action="{{ route('dtr.import_logs') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                            @csrf
                            <input type="file" name="attlogFile" accept=".dat,.txt" required
                                   class="block w-full text-xs md:text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition cursor-pointer border border-slate-200 rounded-lg">
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-sm transition text-sm">
                                Sync Attendance Logs
                            </button>
                        </form>
                    </div>

                </div>

                <form action="{{ route('employees.generate_ids') }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to generate formatted Company IDs for all new employees?');">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        Auto-Generate Company IDs
                    </button>
                </form>
            @endif

            <!-- ============================================================== -->
            <!-- ALPINE.JS WRAPPER FOR BULK DEPARTMENT ASSIGNMENT & HIDING      -->
            <!-- ============================================================== -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden"
                 x-data="{ 
                    selectedEmployees: [], 
                    selectedDepartment: '',
                    toggleAll(event) {
                        if (event.target.checked) {
                            this.selectedEmployees = Array.from(document.querySelectorAll('.emp-checkbox')).map(cb => cb.value);
                        } else {
                            this.selectedEmployees = [];
                        }
                    }
                 }">
                
                <!-- Table Header, Filter & Search Bar -->
                <div class="p-4 md:p-5 border-b border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-50">
                    <h3 class="text-base md:text-lg font-bold text-slate-800 shrink-0">
                        Registered Employees <span class="text-slate-500 text-xs md:text-sm font-medium ml-1 md:ml-2">({{ $employees->count() ?? 0 }} Total)</span>
                    </h3>
                    
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center">
                        <!-- LIVE DEPARTMENT FILTER -->
                        <select id="departmentFilter" class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-48 p-2.5 shadow-sm transition-colors">
                            <option value="">All Departments</option>
                            <option value="Unassigned">Unassigned</option>
                            @foreach($departments ?? [] as $dept)
                                <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>

                        <!-- DTR Search Bar -->
                        <div class="relative w-full sm:w-64 lg:w-80 shrink-0">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" id="dtrSearchInput" class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 shadow-sm transition-colors" placeholder="Search name or ID...">
                        </div>
                        
                        <!-- 🌟 SHOW HIDDEN CHECKBOX 🌟 -->
                        <div class="flex items-center sm:ml-2 sm:border-l border-slate-300 sm:pl-3 w-full sm:w-auto mt-2 sm:mt-0">
                            <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-slate-600 whitespace-nowrap hover:text-slate-900 transition-colors">
                                <input type="checkbox" onchange="window.location.href='?show_hidden=' + (this.checked ? '1' : '0')" {{ request('show_hidden') == '1' ? 'checked' : '' }} class="rounded border-slate-300 text-slate-600 focus:ring-slate-500 w-4 h-4 shadow-sm">
                                Show Hidden
                            </label>
                        </div>
                    </div>
                </div>

                <!-- THE SLIDE-DOWN BULK ACTION BAR (ADMIN ONLY) -->
                @if(Auth::check() && strtolower(trim(Auth::user()->role)) === 'admin')
                    <div x-show="selectedEmployees.length > 0" x-transition class="bg-blue-50 border-b border-blue-200 p-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4" style="display: none;">
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="bg-blue-600 text-white font-bold px-3 py-1 rounded-full text-sm shadow-sm" x-text="selectedEmployees.length"></span>
                            <span class="text-blue-900 font-bold text-sm">Employees Selected</span>
                        </div>
                        
                        <!-- 🌟 UNIFIED BULK ACTIONS FORM 🌟 -->
                        <form method="POST" class="m-0 flex flex-col md:flex-row items-center gap-3 w-full md:w-auto justify-end flex-wrap">
                            @csrf
                            <template x-for="id in selectedEmployees">
                                <input type="hidden" name="employee_ids[]" :value="id">
                            </template>
                            
                            <!-- Assign Dept Action -->
                            <div class="flex items-center gap-2 w-full md:w-auto">
                                <select name="department" x-model="selectedDepartment" class="border-blue-300 text-blue-900 rounded-lg shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500 font-bold w-full sm:w-auto bg-white">
                                    <option value="" disabled>-- Choose Department --</option>
                                    @foreach($departments ?? [] as $dept)
                                        <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" formaction="{{ route('employees.bulk_department') }}" class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-lg shadow-md transition-colors text-sm disabled:opacity-50 disabled:cursor-not-allowed shrink-0" x-bind:disabled="!selectedDepartment">
                                    Assign Dept
                                </button>
                            </div>
                            
                            <div class="h-8 w-px bg-blue-300 mx-2 hidden md:block"></div>
                            
                            <!-- Bulk Hide/Unhide Buttons -->
                            <div class="flex items-center gap-2 w-full md:w-auto">
                                <button type="submit" formaction="{{ route('employees.bulk_hide') }}" onclick="return confirm('Hide selected employees from the system?')" class="w-full sm:w-auto px-5 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-black rounded-lg shadow-md transition-colors text-sm shrink-0">
                                    Hide Selected
                                </button>
                                
                                @if(request('show_hidden') == '1')
                                <button type="submit" formaction="{{ route('employees.bulk_unhide') }}" onclick="return confirm('Unhide selected employees?')" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-black rounded-lg shadow-md transition-colors text-sm shrink-0">
                                    Unhide Selected
                                </button>
                                @endif
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-white border-b border-slate-200 text-slate-500 text-[10px] md:text-xs uppercase tracking-wider font-bold">
                                <!-- Admin-only Checkbox Header -->
                                @if(Auth::check() && strtolower(trim(Auth::user()->role)) === 'admin')
                                    <th class="p-4 md:p-5 w-12 text-center">
                                        <input type="checkbox" @click="toggleAll" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4 shadow-sm">
                                    </th>
                                @endif
                                <th class="p-4 md:p-5">Biometric ID</th>
                                <th class="p-4 md:p-5">Employee ID</th>
                                <th class="p-4 md:p-5">Employee Name</th>
                                <th class="p-4 md:p-5">Department</th>
                                <th class="p-4 md:p-5 text-center">Total Scans</th>
                                <th class="p-4 md:p-5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm" id="dtrTableBody">
                            @forelse($employees ?? [] as $emp)
                                @php
                                    // Check if this specific employee is a Department Head in the users table
                                    $userAccount = \App\Models\User::where('company_id', $emp->company_id)->first();
                                    $isDeptHead = $userAccount && strtolower(trim($userAccount->role)) === 'department_head';
                                @endphp
                                
                                <tr class="transition-colors text-slate-700 emp-row {{ $emp->is_hidden ? 'opacity-60 bg-slate-50' : '' }}" 
                                    data-department="{{ $emp->department ?? 'Unassigned' }}"
                                    :class="selectedEmployees.includes('{{ $emp->id }}') ? 'bg-blue-50/70' : 'hover:bg-blue-50/30'">
                                    
                                    <!-- Admin-only Checkbox Cell -->
                                    @if(Auth::check() && strtolower(trim(Auth::user()->role)) === 'admin')
                                        <td class="p-4 md:p-5 text-center">
                                            <input type="checkbox" value="{{ $emp->id }}" x-model="selectedEmployees" class="emp-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4 shadow-sm">
                                        </td>
                                    @endif
                                    
                                    <td class="p-4 md:p-5 font-mono font-bold text-blue-600">#{{ $emp->biometric_id }}</td>
                                    <td class="p-4 md:p-5 font-mono font-semibold text-slate-600">{{ $emp->company_id ?? 'Pending' }}</td>
                                    
                                    <!-- Employee Name with Badges -->
                                    <td class="p-4 md:p-5 font-bold text-slate-800 flex items-center gap-2">
                                        {{ $emp->name }}
                                        @if($isDeptHead)
                                            <span class="bg-amber-100 text-amber-700 text-[10px] px-2 py-0.5 rounded-full border border-amber-200 shadow-sm" title="Department Head">👑 Head</span>
                                        @endif
                                        @if($emp->is_hidden)
                                            <span class="bg-rose-100 text-rose-700 text-[10px] px-2 py-0.5 rounded-full border border-rose-200 shadow-sm" title="Hidden from System">Hidden</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Department Column -->
                                    <td class="p-4 md:p-5">
                                        @if($emp->department)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm">
                                                {{ $emp->department }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs italic font-medium">Unassigned</span>
                                        @endif
                                    </td>

                                    <td class="p-4 md:p-5 text-center">
                                        <span class="px-2 md:px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 whitespace-nowrap shadow-sm">
                                            {{ $emp->attendance_logs_count ?? 0 }} punches
                                        </span>
                                    </td>
                                    
                                    <td class="p-4 md:p-5 text-right space-x-2 md:space-x-4 whitespace-nowrap flex items-center justify-end">
                                        <a href="{{ route('dtr.show', $emp->id) }}" class="inline-flex items-center text-xs md:text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                            View DTR &rarr;
                                        </a>
                                        
                                        @if(Auth::check() && strtolower(trim(Auth::user()->role)) === 'admin')
                                            <form action="{{ route('employees.toggle_dept_head', $emp->id) }}" method="POST" class="inline-block m-0">
                                                @csrf
                                                @if($isDeptHead)
                                                    <button type="submit" class="inline-flex items-center text-xs md:text-sm font-bold text-rose-500 hover:text-rose-700 transition-colors" onclick="return confirm('Remove Department Head role from {{ $emp->name }}?')">
                                                        Revoke Head
                                                    </button>
                                                @else
                                                    <button type="submit" class="inline-flex items-center text-xs md:text-sm font-bold text-amber-500 hover:text-amber-700 transition-colors" onclick="return confirm('Make {{ $emp->name }} a Department Head?')">
                                                        Make Head 👑
                                                    </button>
                                                @endif
                                            </form>

                                            <!-- 🌟 INDIVIDUAL HIDE / UNHIDE BUTTON 🌟 -->
                                            @if($emp->is_hidden)
                                                <a href="{{ route('dtr.unhide', $emp->id) }}" class="inline-flex items-center text-xs md:text-sm font-bold text-emerald-500 hover:text-emerald-700 transition-colors">
                                                    Unhide
                                                </a>
                                            @else
                                                <a href="{{ route('dtr.hide', $emp->id) }}" class="inline-flex items-center text-xs md:text-sm font-bold text-slate-400 hover:text-rose-500 transition-colors" onclick="return confirm('Hide this employee from the system?')">
                                                    Hide
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-state">
                                    <td colspan="{{ (Auth::check() && strtolower(trim(Auth::user()->role)) === 'admin') ? '7' : '6' }}" class="p-8 md:p-10 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 md:w-12 md:h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            <p class="text-sm md:text-base font-medium text-slate-600">No employees found in the database.</p>
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
    
    <!-- Combined Search & Department Filter Script -->
    <script>
        const searchInput = document.getElementById('dtrSearchInput');
        const deptFilter = document.getElementById('departmentFilter');
        const tableRows = document.querySelectorAll('#dtrTableBody tr.emp-row');

        function filterTable() {
            let searchQuery = searchInput.value.toLowerCase();
            let selectedDept = deptFilter.value.toLowerCase();

            tableRows.forEach(function(row) {
                let rowText = row.textContent.toLowerCase();
                let rowDept = (row.getAttribute('data-department') || '').toLowerCase();

                // Check if the row matches BOTH the text search and the dropdown filter
                let matchesSearch = rowText.includes(searchQuery);
                let matchesDept = (selectedDept === "" || rowDept === selectedDept);

                if (matchesSearch && matchesDept) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Trigger filter on both typing in search OR changing the dropdown
        if (searchInput) searchInput.addEventListener('keyup', filterTable);
        if (deptFilter) deptFilter.addEventListener('change', filterTable);
    </script>
</x-employee-layout>