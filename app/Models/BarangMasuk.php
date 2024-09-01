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
        'jenis_mobil',
        'nomer_polisi',
        'nomer_container',
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

}


class BarangMasukItem extends Model
{
    protected $fillable = [
        'barang_masuk_id',
        'barang_id',
        'qty',
        'unit',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
