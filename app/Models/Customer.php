<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_pt',
        'no_npwp',
        'no_ktp',
        'no_hp',
        'email',
        'type_payment_customer',
        'warehouse_id',
        'address',
        'status',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
