<?php

namespace App\View\Components;

use App\Models\Menu;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public $menus;

    public function __construct()
    {
        // Fetch the menus and order by priority
        $this->menus = Menu::with('children')
            ->whereNull('parent_id')
            ->where('is_active', 1)
            ->orderBy('priority') // Add order by priority
            ->get();
    }

    public function render()
    {
        return view('components.sidebar');
    }
}
