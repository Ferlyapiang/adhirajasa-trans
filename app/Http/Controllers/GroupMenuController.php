<?php

namespace App\Http\Controllers;

use App\Models\GroupMenu;
use App\Models\Group;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMenuController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user ) {
            return redirect()->route('login')->with('alert', 'Waktu login Anda telah habis, silakan login ulang.');
        } else {
            $groupMenus = GroupMenu::with(['group', 'menu'])->get();
            $groups = Group::all();
            $menus = Menu::all();
        }
        return view('admin.management-menu.group_menu.index', compact('groupMenus', 'groups', 'menus'));
    }

    public function create()
    {
        $groups = Group::all();
        $menus = Menu::all();

        return view('admin.management-menu.group_menu.create', compact('groups', 'menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'menu_id' => 'required|exists:menus,id',
        ]);

        GroupMenu::create($request->all());

        return redirect()->route('management-menu.group_menu.index')->with('success', 'Group Menu created successfully.');
    }

    public function destroy($id)
    {
        $groupMenu = GroupMenu::findOrFail($id);
        $groupMenu->delete();

        return redirect()->route('management-menu.group_menu.index')->with('success', 'Group Menu deleted successfully.');
    }
}
