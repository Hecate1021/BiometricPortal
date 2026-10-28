<x-employee-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Background Queue Monitor
                </h1>
                <p class="text-slate-500 mt-1">Monitor pending emails and background PDF generation tasks.</p>
            </div>
            <button onclick="window.location.reload()" class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold py-2.5 px-5 rounded-xl shadow-sm transition-colors flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh Data
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-bold uppercase tracking-wider">Pending Tasks</p>
                    <h3 class="text-3xl font-black text-blue-900 mt-1">{{ $pendingJobs->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
            </div>
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-rose-600 text-sm font-bold uppercase tracking-wider">Failed Tasks</p>
                    <h3 class="text-3xl font-black text-rose-900 mt-1">{{ $failedJobs->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Pending Jobs Table -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-slate-800">In Queue (Waiting to Process)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-white border-b border-slate-200">
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Job Name</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Attempts</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Queued At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pendingJobs as $job)
                            @php $payload = json_decode($job->payload); @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-mono text-sm text-slate-600">#{{ $job->id }}</td>
                                <td class="px-6 py-4 font-bold text-sm text-slate-800">{{ class_basename($payload->displayName ?? 'Unknown Job') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $job->attempts }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ \Carbon\Carbon::createFromTimestamp($job->created_at)->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">No tasks currently waiting in the queue.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Failed Jobs Table -->
        @if($failedJobs->count() > 0)
        <div class="bg-white border border-rose-200 rounded-2xl shadow-sm overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-rose-200 bg-rose-50">
                <h3 class="font-bold text-rose-800">Failed Tasks</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-white border-b border-rose-100">
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Job Name</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Failed At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-rose-50">
                        @foreach($failedJobs as $failed)
                            @php $failedPayload = json_decode($failed->payload); @endphp
                            <tr class="hover:bg-rose-50">
                                <td class="px-6 py-4 font-mono text-sm text-rose-600">#{{ $failed->id }}</td>
                                <td class="px-6 py-4 font-bold text-sm text-slate-800">{{ class_basename($failedPayload->displayName ?? 'Unknown Job') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ \Carbon\Carbon::parse($failed->failed_at)->format('M d, Y h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</x-employee-layout>
