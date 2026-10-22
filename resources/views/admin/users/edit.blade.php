<x-employee-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        
        <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition inline-block">&larr; Back to Users</a>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
            <h2 class="text-2xl font-black text-slate-800 mb-6">Edit User: {{ $user->name }}</h2>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl text-sm font-bold">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Login Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3">
                    </div>
                </div>

                <!-- System Role -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">System Role</label>
                    <select name="role" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3">
                        <option value="employee" {{ $user->role === 'employee' ? 'selected' : '' }}>Employee (Standard)</option>
                        <option value="department_head" {{ $user->role === 'department_head' ? 'selected' : '' }}>Department Head (Tier 1)</option>
                        <option value="hrmo" {{ $user->role === 'hrmo' ? 'selected' : '' }}>HRMO (Tier 2)</option>
                        <option value="mayor" {{ $user->role === 'mayor' ? 'selected' : '' }}>Mayor / Executive (Tier 3)</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>System Admin (IT)</option>
                    </select>
                </div>

                <!-- Reset Password (Optional) -->
                <div class="pt-4 border-t border-slate-200">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Reset Password <span class="text-slate-400 font-normal lowercase">(Leave blank to keep current)</span></label>
                    <input type="password" name="password" placeholder="Enter a new password..." class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3">
                </div>

                <!-- Submit -->
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-sm transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-employee-layout>