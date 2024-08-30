<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluarItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_keluar_id',
        'barang_masuk_id',
        'barang_id',
        'qty',
        'unit',
        'harga',
    ];

    // Relationship with BarangKeluar
    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    // Relationship with BarangMasuk
    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuk_id');
    }

    // Relationship with Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
