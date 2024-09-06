<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluars';

    protected $fillable = [
        'tanggal_keluar',
        'gudang_id',
        'customer_id',
        'nomer_invoice',
        'nomer_polisi',
        'bank_transfer_id',
    ];

    // Define the relationship with BarangKeluarItem
    public function items()
    {
        return $this->hasMany(BarangKeluarItem::class, 'barang_keluar_id');
    }

    // Define the relationship with BarangMasuk
    public function barangMasuk()
    {
        return $this->hasManyThrough(BarangMasuk::class, BarangKeluarItem::class, 'barang_keluar_id', 'id', 'id', 'barang_masuk_id');
    }

    // Define the relationship with Gudang (Warehouse)
    public function gudang()
    {
        return $this->belongsTo(Warehouse::class, 'gudang_id');
    }

    // Define the relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Define the relationship with BankTransfer (BankData)
    public function bankTransfer()
    {
        return $this->belongsTo(BankData::class, 'bank_transfer_id');
    }
}
