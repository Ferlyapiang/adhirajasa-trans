<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Warehouse;
use App\Models\LogData;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $loggedInUser = Auth::user();

        if ($loggedInUser && $loggedInUser->warehouse_id) {
            $users = User::with('group', 'warehouse')
                        ->where('warehouse_id', $loggedInUser->warehouse_id)
                        ->get();
        } else {
            $users = User::with('group', 'warehouse')->get();
        }

        return view('admin.management-user.users.index', compact('users'));
    }


    public function show(User $user)
    {
        return view('admin.management-user.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $groups = Group::all();
        $warehouses = Warehouse::all();
        return view('admin.management-user.users.edit', compact('user', 'groups', 'warehouses'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:191',
            'status' => 'required|in:active,inactive',
            'group_id' => 'nullable|exists:groups,id',
            'warehouse_id' => 'nullable|exists:warehouses,id'
        ]);

        if ($user->status === 'inactive' && $request->status === 'active') {
            $user->status = 'active';
        }

        $user->update($request->only('name', 'email', 'status', 'group_id', 'warehouse_id'));

        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'update',
            'details' => 'Updated user ID: ' . $user->id . ' with data: ' . json_encode($request->only('name', 'email', 'status', 'group_id'))
        ]);

        return redirect()->route('management-user.users.index')->with('success', 'User updated successfully.');
    }

    public function create()
    {
        $groups = Group::all();
        $warehouses = Warehouse::all();
        return view('admin.management-user.users.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',
            'group_id' => 'nullable|exists:groups,id',
            'warehouse_id' => 'nullable|exists:warehouses,id'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->status,
            'group_id' => $request->group_id,
            'warehouse_id' => $request->warehouse_id
        ]);

        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'insert',
            'details' => 'Created user ID: ' . $user->id . ' with data: ' . json_encode($request->only('name', 'email', 'password', 'status', 'group_id'))
        ]);
        return redirect()->route('management-user.users.index')->with('success', 'User added successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'delete',
            'details' => 'Deleted user ID: ' . $user->id . ' with data: ' . json_encode($user->only('name', 'email', 'status', 'group_id'))
        ]);

        return redirect()->route('management-user.users.index')->with('success', 'User deactivated successfully.');
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = User::where('email', $email)->exists();
        
        return response()->json(['exists' => $exists]);
    }

    public function showChangePasswordForm(User $user)
    {
        return view('admin.management-user.users.change-password', compact('user'));
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('management-user.users.index')->with('success', 'Password updated successfully.');
    }



}
