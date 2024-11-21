<?php

namespace App\View\Components;

use App\Models\Menu;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
    public $menus;

    public function __construct()
    {
        $loggedInUser = Auth::user();
    
    // Check if group_id is null and redirect to login
    if ($loggedInUser === null) {
        return redirect()->route('login')->with('alert', 'Waktu login Anda telah habis, silakan login ulang.');
    }

    // Load the menus only if the user is valid
    $this->menus = Menu::with(['children.groups' => function($query) use ($loggedInUser) {
        $query->where('group_id', $loggedInUser->group_id);
    }])
    ->whereNull('parent_id')
    ->where('is_active', 1)
    ->whereHas('groups', function ($query) use ($loggedInUser) {
        $query->where('group_id', $loggedInUser->group_id);
    })
    ->orderBy('priority')
    ->get();

    // Handle case where menus might be null
    if ($this->menus->isEmpty()) {
        return redirect()->route('login')->with('alert', 'Data menu tidak ditemukan, silakan login ulang.');
    }
    }

    public function render()
    {
        return view('components.sidebar');
    }
}
