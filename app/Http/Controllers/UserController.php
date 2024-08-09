<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Group;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('group')->get();
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Ambil semua grup dari model Group
        $groups = Group::all();
        
        // Kirim data grup dan pengguna ke view
        return view('admin.users.edit', compact('user', 'groups'));
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:191',
            'status' => 'required|in:active,inactive',
            'group_id' => 'nullable|exists:groups,id'
        ]);

        // Periksa apakah status pengguna berubah dari inactive ke active
        if ($user->status === 'inactive' && $request->status === 'active') {
            $user->status = 'active';
            // Atur status menjadi 'active'
            $user->save();
        }

        $user->update($request->only('name', 'email', 'status', 'group_id'));

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',
            'group_id' => 'nullable|exists:groups,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->status,
            'group_id' => $request->group_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User added successfully.');
    }

    public function destroy(User $user)
    {
        $user->softDelete();

        return redirect()->route('users.index')->with('success', 'User deactivated successfully.');
    }
}
