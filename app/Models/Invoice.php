<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_masuks_id',
        'barang_keluars_id',
    ];

    // Relationship with barang_masuks
    public function barangMasuks()
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuks_id');
    }

    // Relationship with barang_keluars
    public function barangKeluars()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluars_id');
    }
}
