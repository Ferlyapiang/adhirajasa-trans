<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'router',
        'icon',
        'is_active',
        'parent_id',
    ];

    /**
     * Define the relationship to get child menus.
     */
    public function scopeActiveMenus($query)
    {
        return $query->with(['children' => function($query) {
            $query->where('is_active', 1); // Only include active children
        }])
        ->where('is_active', 1) // Only include active menus
        ->whereNull('parent_id')
        ->orderBy('priority');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
                    ->where('is_active', 1) // Only include active children
                    ->orderBy('priority');
    }
    /**
     * Define the relationship to get the parent menu.
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
}
