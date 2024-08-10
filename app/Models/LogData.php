<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogData extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'action', 'details'];

    // Optionally, you can define relationships or additional methods here
}
