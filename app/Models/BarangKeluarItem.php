<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluarItem extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar_items';

    protected $fillable = [
        'barang_keluar_id',
        'barang_id',
        'no_ref',
        'qty',
        'unit',
        'harga',
        'total_harga',
    ];

    // Define the relationship with BarangKeluar
    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    // Define the relationship with Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
