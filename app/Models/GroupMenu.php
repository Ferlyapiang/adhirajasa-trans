<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMenu extends Model
{
    protected $table = 'group_menu'; // Specify table name if necessary
    protected $fillable = ['group_id', 'menu_id'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
