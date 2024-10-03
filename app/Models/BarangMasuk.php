<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $fillable = [
        'joc_number',
        'tanggal_masuk',
        'gudang_id',
        'customer_id',
        'type_mobil_id',
        'nomer_polisi',
        'nomer_container',
        'harga_simpan_barang',
        'harga_lembur',
        'status_invoice',
    ];

    public function items()
    {
        return $this->hasMany(BarangMasukItem::class);
    }

    // Define the relationship with BarangMasuk
    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class);
    }

    // Define the relationship with Gudang (Warehouse)
    public function gudang()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // Define the relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function bankTransfer()
    {
        return $this->belongsTo(BankData::class);
    }

    public function typeMobil()
    {
        return $this->belongsTo(JenisMobil::class, 'type_mobil_id'); // Adjust 'jenis_mobil_id' if necessary
    }

}