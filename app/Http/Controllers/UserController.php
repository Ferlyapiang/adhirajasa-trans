<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\LogData;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('group')->get();
        return view('admin.management-user.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.management-user.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $groups = Group::all();
        return view('admin.management-user.users.edit', compact('user', 'groups'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:191',
            'status' => 'required|in:active,inactive',
            'group_id' => 'nullable|exists:groups,id'
        ]);

        if ($user->status === 'inactive' && $request->status === 'active') {
            $user->status = 'active';
        }

        $user->update($request->only('name', 'email', 'status', 'group_id'));

        // Log the update action
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'update',
            'details' => 'Updated user ID: ' . $user->id . ' with data: ' . json_encode($request->only('name', 'email', 'status', 'group_id'))
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function create()
    {
        $groups = Group::all();
        return view('admin.management-user.users.create', compact('groups'));
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

        // Log the insert action
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'insert',
            'details' => 'Created user ID: ' . $user->id . ' with data: ' . json_encode($request->only('name', 'email', 'password', 'status', 'group_id'))
        ]);

        return redirect()->route('users.index')->with('success', 'User added successfully.');
    }

    public function destroy(User $user)
    {
        // Soft delete, assuming you have soft deletes enabled
        $user->delete();

        // Log the delete action
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'delete',
            'details' => 'Deleted user ID: ' . $user->id . ' with data: ' . json_encode($user->only('name', 'email', 'status', 'group_id'))
        ]);

        return redirect()->route('users.index')->with('success', 'User deactivated successfully.');
    }
}
