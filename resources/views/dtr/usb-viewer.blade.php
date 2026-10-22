<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline USB DTR Viewer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto space-y-6">

        <!-- Header & File Inputs -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h1 class="text-2xl font-bold text-slate-800 mb-6 text-center">ZKTeco USB DTR Viewer</h1>

            <div class="flex flex-col md:flex-row gap-6 mb-6">
                <!-- User.dat Input -->
                <div class="flex-1 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="userFile">1. Upload user.dat</label>
                    <input type="file" id="userFile" accept=".dat,.txt"
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer">
                </div>

                <!-- Attlog.dat Input -->
                <div class="flex-1 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="attlogFile">2. Upload attlog.dat</label>
                    <input type="file" id="attlogFile" accept=".dat,.txt"
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer">
                </div>
            </div>

            <button onclick="generateDTR()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow transition">
                Load & Generate DTR
            </button>
        </div>

        <!-- Side-by-side workspace (Hidden by default) -->
        <div id="workspace" class="hidden flex-col md:flex-row gap-6 items-start">

            <!-- Left Side: Employee List -->
            <div class="w-full md:w-1/3 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-shrink-0">
                <div class="bg-slate-800 text-white p-4 text-center font-bold tracking-wider uppercase text-sm">
                    Employee List
                </div>
                <ul id="employeeList" class="max-h-[600px] overflow-y-auto divide-y divide-slate-100">
                    <!-- Names will populate here -->
                </ul>
            </div>

            <!-- Right Side: DTR Table -->
            <div class="w-full md:w-2/3 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 id="selectedEmployeeName" class="text-xl font-bold text-slate-800 mb-6 border-b border-slate-100 pb-4">
                    Select an employee from the list
                </h2>

                <div class="overflow-x-auto">
                    <table id="dtrTable" class="w-full text-center border-collapse hidden">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 uppercase text-xs tracking-wider">
                                <th class="p-3 border border-slate-200 font-semibold">Date</th>
                                <th class="p-3 border border-slate-200 font-semibold text-emerald-700 bg-emerald-50/50">AM In</th>
                                <th class="p-3 border border-slate-200 font-semibold text-orange-700 bg-orange-50/50">AM Out</th>
                                <th class="p-3 border border-slate-200 font-semibold text-orange-700 bg-orange-50/50">PM In</th>
                                <th class="p-3 border border-slate-200 font-semibold text-blue-700 bg-blue-50/50">PM Out</th>
                            </tr>
                        </thead>
                        <tbody id="dtrBody" class="divide-y divide-slate-100 text-sm">
                            <!-- Specific DTR rows will load here -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- The exact JavaScript you provided, untouched logic -->
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

                // Show the workspace area (Tailwind flex class)
                document.getElementById('workspace').classList.remove('hidden');
                document.getElementById('workspace').classList.add('flex');

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
                if (!employeeLogs[log.id]) {
                    employeeLogs[log.id] = {};
                }
                if (!employeeLogs[log.id][log.date]) {
                    employeeLogs[log.id][log.date] = [];
                }
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
                // Tailwind styling for the list items
                li.className = 'p-4 cursor-pointer hover:bg-slate-50 transition text-slate-700 font-medium border-l-4 border-transparent';
                li.textContent = employeeName;

                li.onclick = function() {
                    // Reset all tabs
                    document.querySelectorAll('#employeeList li').forEach(el => {
                        el.classList.remove('bg-blue-50', 'text-blue-700', 'border-blue-600');
                        el.classList.add('border-transparent');
                    });

                    // Style active tab
                    li.classList.remove('border-transparent');
                    li.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-600');

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

            dates.forEach(date => {
                const times = employeeLogs[id][date].sort();

                const amIn = times[0] || "<span class='text-slate-300 italic'>--</span>";
                const amOut = times[1] || "<span class='text-slate-300 italic'>--</span>";
                const pmIn = times[2] || "<span class='text-slate-300 italic'>--</span>";

                let pmOut = times[3] || "<span class='text-slate-300 italic'>--</span>";
                if (times.length > 4) {
                    pmOut = times[times.length - 1];
                }

                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50 transition";
                tr.innerHTML = `
                    <td class="p-3 border border-slate-200 font-mono text-slate-800 font-bold">${date}</td>
                    <td class="p-3 border border-slate-200 font-mono text-emerald-600 font-bold">${amIn}</td>
                    <td class="p-3 border border-slate-200 font-mono text-orange-600 font-bold">${amOut}</td>
                    <td class="p-3 border border-slate-200 font-mono text-orange-600 font-bold">${pmIn}</td>
                    <td class="p-3 border border-slate-200 font-mono text-blue-600 font-bold">${pmOut}</td>
                `;
                tbody.appendChild(tr);
            });
        }
    </script>
</body>
</html>
