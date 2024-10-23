<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Warehouse;
use App\Models\LogData;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function index()
    {
        $loggedInUser = Auth::user();
        if (!$loggedInUser) {
            return redirect()->route('login')->with('alert', 'Waktu login Anda telah habis, silakan login ulang.');
        } else {
            if ($loggedInUser && $loggedInUser->warehouse_id) {
                $users = User::with('group', 'warehouse')
                            ->where('warehouse_id', $loggedInUser->warehouse_id)
                            ->get();
            } else {
                $users = User::with('group', 'warehouse')->get();
            }

            return view('admin.management-user.users.index', compact('users'));
        }
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

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:191|unique:users',
    //         'password' => 'required|string|min:8|confirmed',
    //         'status' => 'required|in:active,inactive',
    //         'group_id' => 'nullable|exists:groups,id',
    //         'warehouse_id' => 'nullable|exists:warehouses,id'
    //     ]);
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => bcrypt($request->password),
    //         'status' => $request->status,
    //         'group_id' => $request->group_id,
    //         'warehouse_id' => $request->warehouse_id
    //     ]);

    //     LogData::create([
    //         'user_id' => Auth::id(),
    //         'name' => Auth::user()->name,
    //         'action' => 'insert',
    //         'details' => 'Created user ID: ' . $user->id . ' with data: ' . json_encode($request->only('name', 'email', 'password', 'status', 'group_id'))
    //     ]);
    //     return redirect()->route('management-user.users.index')->with('success', 'User added successfully.');
    // }
    public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:191|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'status' => 'required|in:active,inactive',
        'group_id' => 'nullable|exists:groups,id',
        'warehouse_id' => 'nullable|exists:warehouses,id'
    ]);

    // Create the new user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'status' => $request->status,
        'group_id' => $request->group_id,
        'warehouse_id' => $request->warehouse_id
    ]);

    // Log the creation of the new user
    LogData::create([
        'user_id' => Auth::id(),
        'name' => Auth::user()->name,
        'action' => 'insert',
        'details' => 'Created user ID: ' . $user->id . ' with data: ' . json_encode($request->only('name', 'email', 'password', 'status', 'group_id'))
    ]);

    // Generate a PDF containing user details
    $pdf = PDF::loadView('pdf.user-details', [
        'user' => $user,
        'plainPassword' => $request->password, // Optional, include if needed
    ]);

    // Create a dynamic filename using the user's name
    $filename = strtolower(str_replace(' ', '_', $user->name)) . '_Create_User.pdf';

    // Return the generated PDF for download
    return $pdf->download($filename);
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

    // Hash dan simpan password
    $user->password = Hash::make($request->new_password);
    $user->save();

    // Buat PDF
    $pdf = PDF::loadView('pdf.change-password', [
        'user' => $user,
        'plainPassword' => $request->new_password, // Kirim password plaintext ke view
    ]);

    // Ubah nama file menggunakan nama user
    $filename = strtolower(str_replace(' ', '_', $user->name)) . '_password_change_confirmation.pdf';

    // Kembalikan PDF untuk diunduh
    return $pdf->download($filename);
}

    

}
