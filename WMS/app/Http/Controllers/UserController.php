<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function indexView()
    {
        $users = User::all();
        return view('admin.user_management.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|string|in:admin,supervisor,staff',
            'password' => 'required|string|min:6',
        ]);

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        if ($request->expectsJson()) {
            return response()->json($user, 201);
        }

        return redirect()->route('admin.user_management.index')->with('success', 'User created successfully');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $id,
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:20',
            'role' => 'sometimes|required|string|in:admin,supervisor,staff',
        ]);

        $user->update($validated);

        if ($request->expectsJson()) {
            return response()->json($user);
        }

        return redirect()->route('admin.user_management.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if (request()->expectsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('admin.user_management.index')->with('success', 'User deleted successfully');
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Hash the new password
        $user->password = Hash::make($validated['password']);
        $user->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Password reset successfully']);
        }

        return redirect()->route('admin.user_management.index')->with('success', 'Password reset successfully');
    }
}