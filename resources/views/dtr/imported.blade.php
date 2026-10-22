<x-employee-layout>
    <div class="max-w-4xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8 py-6 md:py-8">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Data Extracted Successfully</h1>
                <p class="text-sm text-slate-500 mt-1">Showing clean data parsed from your <code>user.dat</code> file.</p>
            </div>
            <a href="{{ route('dtr.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-bold shadow-sm transition text-center shrink-0">
                Proceed to Employee List &rarr;
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                <span class="text-xs uppercase tracking-wider text-slate-500 font-bold">Imported Records ({{ count($cleanData) }})</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[500px]">
                    <thead>
                        <tr class="bg-white border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-4">Biometric ID</th>
                            <th class="p-4">Employee Name</th>
                            <th class="p-4 text-right">Import Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($cleanData as $row)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 font-mono font-bold text-slate-700">#{{ $row['biometric_id'] }}</td>
                                <td class="p-4 font-bold text-slate-900">{{ $row['name'] }}</td>
                                <td class="p-4 text-right">
                                    @if($row['status'] === 'New Record')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-bold bg-emerald-100 text-emerald-800">
                                            {{ $row['status'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-bold bg-blue-100 text-blue-800">
                                            {{ $row['status'] }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-8 text-center text-slate-500 font-medium">
                                    No valid data could be parsed from the uploaded file.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-employee-layout>