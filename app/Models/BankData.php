<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankData extends Model
{
    protected $fillable = ['bank_name', 'account_number', 'account_name', 'warehouse_name', 'status'];
}