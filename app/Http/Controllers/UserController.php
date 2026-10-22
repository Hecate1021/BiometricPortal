<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // 1. List all users
    public function index()
    {
        // Fetch users and paginate them (15 per page)
        $users = User::orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    // 2. Show the edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // 3. Save the updates
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            // Ensure email is unique, but ignore this specific user's current email
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|string',
            'password' => 'nullable|min:8', // Only validate if they type a new password
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Only update the password if the Admin typed a new one
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User account updated successfully!');
    }

    // 4. Delete the user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Safety check: Prevent the admin from deleting themselves!
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own admin account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User account deleted successfully.');
    }
}