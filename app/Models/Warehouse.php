<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    // Specify the table associated with the model if it's not the pluralized version of the model name
    protected $table = 'warehouses';

    // Specify the primary key if it's not the default 'id'
    protected $primaryKey = 'id';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = true;
    protected $keyType = 'int';

    // Define which attributes are mass assignable
    protected $fillable = [
        'name',
        'initial',
        'address',
        'status',
        'phone_number',
        'email',
        'status_office',
    ];

    // Optionally define any attributes that should be hidden from arrays or JSON representations
    protected $hidden = [
        // Attributes you want to hide
    ];

    // Optionally define any attributes that should be cast to a different data type
    protected $casts = [
        // Attributes you want to cast
    ];
}
