<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the management menus.
     */
    public function index()
{
    $menus = Menu::with('children')
        ->whereNull('parent_id')
        ->orderBy('priority')
        ->get();
    
    return view('admin.management-menu.menus.index', compact('menus'));
}

    /**
     * Show the form for creating a new menu.
     */
    public function create()
    {
        $parentMenus = Menu::whereNull('parent_id')->get();
        return view('admin.management-menu.menus.create', compact('parentMenus'));
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string',
            'router' => 'nullable|string',
            'icon' => 'nullable|string',
            'is_active' => 'required|boolean',
            'parent_id' => 'nullable|exists:menus,id',
            'priority' => 'nullable|integer',
        ]);

        Menu::create($request->all());

        return redirect()->route('management-menu.menus.index')->with('success', 'Menu created successfully.');
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(Menu $menu)
    {
        $parentMenus = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->get();
        return view('admin.management-menu.menus.edit', compact('menu', 'parentMenus'));
    }

    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string',
            'router' => 'nullable|string',
            'icon' => 'nullable|string',
            'is_active' => 'required|boolean',
            'parent_id' => 'nullable|exists:menus,id',
            'priority' => 'nullable|integer',
        ]);

        $menu->update($request->all());

        return redirect()->route('management-menu.menus.index')->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified menu from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('management-menu.menus.index')->with('success', 'Menu deleted successfully.');
    }

    /**
     * Optionally retrieve sidebar menus for a view.
     */
    public function getSidebarMenus()
{
    $menus = Menu::with('children')
        ->whereNull('parent_id')
        ->orderBy('priority')
        ->get();
    
    return $menus;
}
}
