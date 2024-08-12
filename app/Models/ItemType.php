<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    // Specify the fillable fields
    protected $fillable = ['name', 'status'];
}
