<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'jenis',
        'nomer_rak',
        'sku',
        'pemilik',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'pemilik');
    }
}

