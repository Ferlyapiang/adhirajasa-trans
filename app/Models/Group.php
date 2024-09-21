<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'group_menu')->withTimestamps();
    }
}
