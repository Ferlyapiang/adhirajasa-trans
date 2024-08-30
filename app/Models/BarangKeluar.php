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
    ];

    // Relationship with Gudang (Warehouse)
    public function gudang()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // Relationship with Owner
    public function owner()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship with BankTransfer
    public function bankTransfer()
    {
        return $this->belongsTo(BankData::class, 'bank_transfer_id');
    }

    // Relationship with BarangKeluarItem
    public function items()
    {
        return $this->hasMany(BarangKeluarItem::class);
    }
}
