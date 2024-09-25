<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisMobil extends Model
{
    use HasFactory;

    protected $table = 'type_mobil'; 

    protected $fillable = [
        'type', 
        'rental_price', 
        'status',
    ];


    protected $primaryKey = 'id';
}
