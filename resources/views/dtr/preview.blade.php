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

            <!-- 🌟 THE NEW SYNC BUTTON & HIDDEN FORM 🌟 -->
            <div class="mt-8 pt-6 border-t-2 border-dashed border-slate-300 text-right hidden" id="syncBtnContainer">
                <form action="{{ route('dtr.sync_all') }}" method="POST" id="syncForm" class="m-0">
                    @csrf
                    <input type="hidden" name="payload" id="syncPayload">
                    <button type="button" class="w-full md:w-auto px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-lg cursor-pointer font-black transition-colors shadow-md" onclick="submitToDatabase()">✅ Confirm & Sync to Database</button>
                </form>
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

                // Alternating row background
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

        // 🌟 PACKAGES THE DATA AND SENDS TO LARAVEL 🌟
        function submitToDatabase() {
            if (confirm("Are you sure you want to sync all of this data to the database?")) {
                const payload = {
                    users: userMap,
                    logs: logs
                };
                
                // Put JSON into hidden input
                document.getElementById('syncPayload').value = JSON.stringify(payload);
                
                // Change button text to show it's working
                const btn = document.querySelector('.sync-database-btn') || document.querySelector('button[onclick="submitToDatabase()"]');
                btn.textContent = "⏳ Saving to Database...";
                btn.style.opacity = "0.7";
                btn.style.pointerEvents = "none";

                // Submit the form
                document.getElementById('syncForm').submit();
            }
        }
    </script>
</x-employee-layout>