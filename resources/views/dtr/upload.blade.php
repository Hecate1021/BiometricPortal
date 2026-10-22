<x-employee-layout>
    
    <!-- Tiny style block just for JS active states -->
    <style>
        .employee-item.active {
            background-color: #2563eb !important;
            color: white !important;
            font-weight: 800 !important;
        }
        .missing {
            color: #ef4444;
            font-style: italic;
        }
    </style>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10 space-y-6">

        <!-- Laravel Alerts -->
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl mb-4 border border-emerald-200 font-bold flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-50 text-rose-800 p-4 rounded-xl mb-4 border border-rose-200 font-bold flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
            <h1 class="text-2xl md:text-3xl font-black text-center text-slate-800 mb-6 md:mb-8">ZKTeco Employee DTR Manager</h1>
            
            <div class="flex flex-col md:flex-row justify-between mb-6 p-5 bg-slate-50 border border-slate-200 rounded-xl gap-5">
                <div class="flex flex-col w-full md:w-1/2">
                    <label for="userFile" class="font-bold mb-2 text-slate-600 text-sm">1. Upload user.dat</label>
                    <input type="file" id="userFile" accept=".dat,.txt" class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg cursor-pointer w-full file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition shadow-sm">
                </div>
                <div class="flex flex-col w-full md:w-1/2">
                    <label for="attlogFile" class="font-bold mb-2 text-slate-600 text-sm">2. Upload attlog.dat</label>
                    <input type="file" id="attlogFile" accept=".dat,.txt" class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg cursor-pointer w-full file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition shadow-sm">
                </div>
            </div>

            <button class="w-full p-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-base cursor-pointer font-black transition-colors shadow-sm" onclick="generateDTR()">Preview Data in Browser</button>

            <!-- Side-by-side workspace -->
            <div class="flex-col lg:flex-row gap-6 mt-8 hidden" id="workspace">
                
                <!-- Left Side: Employee List -->
                <div class="w-full lg:w-1/3 bg-slate-50 border border-slate-200 rounded-xl overflow-hidden shadow-sm flex flex-col h-[500px]">
                    <h3 class="bg-slate-800 text-white p-4 text-center font-bold m-0 text-base shrink-0">Employee List</h3>
                    <ul class="list-none m-0 p-0 overflow-y-auto flex-1 divide-y divide-slate-200" id="employeeList"></ul>
                </div>

                <!-- Right Side: DTR Table -->
                <div class="w-full lg:w-2/3 border border-slate-200 rounded-xl p-5 md:p-6 bg-white shadow-sm overflow-x-auto">
                    <h2 id="selectedEmployeeName" class="mt-0 text-slate-800 text-lg md:text-xl pb-4 border-b border-slate-200 mb-5 font-bold">Select an employee from the list</h2>
                    <table id="dtrTable" class="w-full text-left border-collapse hidden min-w-[500px]">
                        <thead>
                            <tr class="bg-slate-800 text-white text-xs uppercase tracking-wider">
                                <th class="p-3 border border-slate-300 text-center font-bold">Date</th>
                                <th class="p-3 border border-slate-300 text-center font-bold">AM In</th>
                                <th class="p-3 border border-slate-300 text-center font-bold">AM Out</th>
                                <th class="p-3 border border-slate-300 text-center font-bold">PM In</th>
                                <th class="p-3 border border-slate-300 text-center font-bold">PM Out</th>
                            </tr>
                        </thead>
                        <tbody id="dtrBody" class="divide-y divide-slate-200 text-sm"></tbody>
                    </table>
                </div>
            </div>

            <!-- 🌟 THE CHECK & SYNC BUTTON 🌟 -->
            <div class="mt-8 pt-6 border-t-2 border-dashed border-slate-300 text-right hidden" id="syncBtnContainer">
                <form action="{{ route('dtr.sync_all') }}" method="POST" id="syncForm" class="m-0">
                    @csrf
                    <input type="hidden" name="payload" id="syncPayload">
                    <button type="button" class="w-full md:w-auto px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-lg cursor-pointer font-black transition-colors shadow-md flex justify-center items-center gap-2" onclick="analyzeAndSync()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Analyze & Sync to Database
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- 🌟 ANALYSIS MODAL 🌟 -->
    <div id="analysisModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden flex items-center justify-center z-[100] px-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Database Synchronization Check
                </h3>
                <button onclick="document.getElementById('analysisModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 focus:outline-none">&times;</button>
            </div>
            
            <div class="p-6">
                <!-- Loading State -->
                <div id="analysisLoading" class="text-center font-bold text-slate-600 py-4">
                    <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Cross-checking records against live database...
                </div>
                
                <!-- Results State -->
                <div id="analysisResults" class="hidden">
                    <p class="text-slate-600 text-sm text-center mb-4">Here is the breakdown of the USB data vs the Live Database:</p>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200 shadow-sm">
                            <div id="newCount" class="text-3xl font-black text-emerald-600">0</div>
                            <div class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider mt-1">New Logs<br>(Will be Saved)</div>
                        </div>
                        <div class="bg-amber-50 p-4 rounded-xl border border-amber-200 shadow-sm relative group cursor-help">
                            <div id="duplicateCount" class="text-3xl font-black text-amber-600">0</div>
                            <div class="text-[10px] font-bold text-amber-800 uppercase tracking-wider mt-1">Duplicates<br>(Already Online)</div>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 mt-5 text-center bg-slate-50 p-3 rounded-lg border border-slate-100 italic">
                        The system will safely skip all duplicates. Are you ready to sync the new data?
                    </p>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('analysisModal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-100 transition-colors">Cancel</button>
                <button type="button" id="confirmSyncBtn" onclick="confirmFinalSync()" class="hidden px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-md transition-colors">Yes, Sync Data</button>
            </div>
        </div>
    </div>

    <script>
        let userMap = {};
        let logs = [];
        let employeeLogs = {};

        function readFileAsText(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = (e) => resolve(e.target.result);
                reader.onerror = (e) => reject(e);
                reader.readAsText(file);
            });
        }

        async function generateDTR() {
            const userFile = document.getElementById('userFile').files[0];
            const attlogFile = document.getElementById('attlogFile').files[0];

            if (!userFile || !attlogFile) {
                alert("Please upload both user.dat and attlog.dat files.");
                return;
            }

            try {
                const userText = await readFileAsText(userFile);
                const attlogText = await readFileAsText(attlogFile);

                parseUsers(userText);
                parseAttlogs(attlogText);
                processLogs();
                buildNameList();

                // Show the workspace area AND the final sync button!
                document.getElementById('workspace').classList.remove('hidden');
                document.getElementById('workspace').classList.add('flex');
                document.getElementById('syncBtnContainer').classList.remove('hidden');

            } catch (error) {
                console.error("Error reading files:", error);
                alert("An error occurred while reading the files.");
            }
        }

        function parseUsers(text) {
            userMap = {};
            const regex = /([A-ZÑ][A-ZÑ\s,\.-]{3,}[A-ZÑ\.])[^\d]*?(\d+)/g;
            let match;
            while ((match = regex.exec(text)) !== null) {
                const name = match[1].trim();
                const id = match[2].trim();
                userMap[id] = name;
            }
        }

        function parseAttlogs(text) {
            logs = [];
            const lines = text.split(/\r?\n/);
            for (let line of lines) {
                const parts = line.split('\t');
                if (parts.length >= 2) {
                    const id = parts[0].trim();
                    const dateTime = parts[1].trim();
                    if (id && dateTime) {
                        const [date, time] = dateTime.split(' ');
                        logs.push({ id, date, time });
                    }
                }
            }
        }

        function processLogs() {
            employeeLogs = {};
            logs.forEach(log => {
                if (!employeeLogs[log.id]) employeeLogs[log.id] = {};
                if (!employeeLogs[log.id][log.date]) employeeLogs[log.id][log.date] = [];
                if (!employeeLogs[log.id][log.date].includes(log.time)) {
                    employeeLogs[log.id][log.date].push(log.time);
                }
            });
        }

        function buildNameList() {
            const listContainer = document.getElementById('employeeList');
            listContainer.innerHTML = "";
            const ids = Object.keys(employeeLogs);
            
            const sortedIds = ids.sort((a, b) => {
                const nameA = userMap[a] || `Unknown (ID: ${a})`;
                const nameB = userMap[b] || `Unknown (ID: ${b})`;
                return nameA.localeCompare(nameB);
            });

            sortedIds.forEach(id => {
                const employeeName = userMap[id] || `Unknown (ID: ${id})`;
                const li = document.createElement('li');
                li.className = 'employee-item p-3 px-4 cursor-pointer transition-colors hover:bg-slate-200 text-sm font-medium text-slate-700 bg-white';
                li.textContent = employeeName;
                
                li.onclick = function() {
                    document.querySelectorAll('.employee-item').forEach(el => el.classList.remove('active'));
                    li.classList.add('active');
                    showEmployeeDTR(id, employeeName);
                };
                listContainer.appendChild(li);
            });
        }

        function showEmployeeDTR(id, employeeName) {
            document.getElementById('selectedEmployeeName').textContent = `DTR: ${employeeName} (ID: ${id})`;
            document.getElementById('dtrTable').classList.remove('hidden');
            const tbody = document.getElementById('dtrBody');
            tbody.innerHTML = "";

            const dates = Object.keys(employeeLogs[id]).sort();
            dates.forEach((date, index) => {
                const times = employeeLogs[id][date].sort();
                const amIn = times[0] || "<span class='missing'>--</span>";
                const amOut = times[1] || "<span class='missing'>--</span>";
                const pmIn = times[2] || "<span class='missing'>--</span>";
                let pmOut = times[3] || "<span class='missing'>--</span>";
                if (times.length > 4) pmOut = times[times.length - 1]; 

                const rowClass = index % 2 === 0 ? 'bg-slate-50' : 'bg-white';
                const tr = document.createElement('tr');
                tr.className = `${rowClass} hover:bg-blue-50 transition-colors`;
                tr.innerHTML = `
                    <td class="p-3 border border-slate-200 text-center text-slate-700"><strong>${date}</strong></td>
                    <td class="p-3 border border-slate-200 text-center" style="color: #16a34a; font-weight: bold;">${amIn}</td>
                    <td class="p-3 border border-slate-200 text-center" style="color: #ea580c; font-weight: bold;">${amOut}</td>
                    <td class="p-3 border border-slate-200 text-center" style="color: #ea580c; font-weight: bold;">${pmIn}</td>
                    <td class="p-3 border border-slate-200 text-center" style="color: #2563eb; font-weight: bold;">${pmOut}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        // 🌟 NEW: AJAX DATABASE ANALYSIS BEFORE SYNC 🌟
        async function analyzeAndSync() {
            // Setup Modal UI
            document.getElementById('analysisModal').classList.remove('hidden');
            document.getElementById('analysisLoading').classList.remove('hidden');
            document.getElementById('analysisResults').classList.add('hidden');
            document.getElementById('confirmSyncBtn').classList.add('hidden');

            const payload = {
                users: userMap,
                logs: logs
            };

            try {
                // Ask Laravel to cross-check the logs
                let response = await fetch('{{ route('dtr.analyze_sync') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                let data = await response.json();

                // Hide loading spinner, show results!
                document.getElementById('analysisLoading').classList.add('hidden');
                document.getElementById('analysisResults').classList.remove('hidden');
                
                // Inject Numbers
                document.getElementById('newCount').textContent = data.new;
                document.getElementById('duplicateCount').textContent = data.duplicates;

                // Only show confirm button if there is actually new data to sync
                if (data.new > 0) {
                    document.getElementById('confirmSyncBtn').classList.remove('hidden');
                } else {
                    document.getElementById('analysisResults').innerHTML += '<p class="text-rose-600 font-bold text-center mt-2">All data is already up to date!</p>';
                }

            } catch (error) {
                document.getElementById('analysisLoading').innerHTML = '<span class="text-rose-600 font-bold">Error analyzing data. Please check console or try again.</span>';
                console.error(error);
            }
        }

        // 🌟 RUNS ONLY AFTER THE USER CONFIRMS 🌟
        function confirmFinalSync() {
            document.getElementById('syncPayload').value = JSON.stringify({users: userMap, logs: logs});
            const btn = document.getElementById('confirmSyncBtn');
            btn.textContent = "⏳ Saving Database...";
            btn.style.opacity = "0.7";
            btn.style.pointerEvents = "none";
            document.getElementById('syncForm').submit();
        }
    </script>
</x-employee-layout>