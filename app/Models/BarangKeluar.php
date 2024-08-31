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
        'nomer_container',
        'nomer_polisi',
        'bank_transfer_id',
        'barang_masuk_id',
    ];

    // Define the relationship with BarangKeluarItem
    public function items()
    {
        return $this->hasMany(BarangKeluarItem::class);
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

    // Define the relationship with BankTransfer (Assuming this is a model)
    public function bankTransfer()
    {
        return $this->belongsTo(BankData::class);
    }
}
