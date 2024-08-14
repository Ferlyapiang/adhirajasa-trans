<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankData extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika diperlukan
    protected $table = 'bank_datas';

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_name',
        'warehouse_id',
        'status'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}

