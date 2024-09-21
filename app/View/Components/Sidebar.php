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
        // Fetch the menus accessible to the user's group
        $this->menus = Menu::with(['children.groups' => function($query) {
            
        }])
        ->whereNull('parent_id')
        ->where('is_active', 1)
        ->whereHas('groups', function ($query) use ($loggedInUser) {
            $query->where('group_id', $loggedInUser->group_id);
        })
        ->orderBy('priority')
        ->get();
    }

    public function render()
    {
        return view('components.sidebar');
    }
}
