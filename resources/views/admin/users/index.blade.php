<x-employee-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition mb-2 inline-block">&larr; Back to Control Center</a>
                <h1 class="text-2xl md:text-3xl font-black text-slate-800">User Accounts</h1>
                <p class="text-slate-500 mt-1">Manage system access, roles, and passwords.</p>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-700 font-bold rounded-xl border border-emerald-200">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-rose-50 text-rose-700 font-bold rounded-xl border border-rose-200">{{ session('error') }}</div>
        @endif

        <!-- Users Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-bold">Name & Company ID</th>
                            <th class="p-4 font-bold">Email</th>
                            <th class="p-4 font-bold">System Role</th>
                            <th class="p-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4">
                                <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                <div class="text-xs text-slate-500 font-mono mt-0.5">{{ $user->company_id ?? 'No ID' }}</div>
                            </td>
                            <td class="p-4 text-sm text-slate-600">{{ $user->email }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 text-xs font-bold uppercase rounded-lg 
                                    {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                    {{ $user->role === 'mayor' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $user->role === 'hrmo' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $user->role === 'department_head' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $user->role === 'employee' ? 'bg-slate-100 text-slate-600' : '' }}">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-block px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded transition">Edit</a>
                                
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to permanently delete this user? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded transition">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Links -->
            @if($users->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>
</x-employee-layout>