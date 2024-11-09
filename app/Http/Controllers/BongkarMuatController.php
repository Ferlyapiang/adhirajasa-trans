<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BongkarMuatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('alert', 'Waktu login Anda telah habis, silakan login ulang.');
        }

        // Fetch data using the query
        $bongkarMuats = DB::select("
        SELECT 
            bm.id AS record_id,
            'Bongkar' AS record_type,
            DATE_FORMAT(bm.tanggal_masuk, '%d-%m-%Y') AS tanggal,
            tm.id AS type_mobil_id,
            tm.type,
            tm.rental_price
        FROM 
            barang_masuks bm
        JOIN 
            type_mobil tm ON bm.type_mobil_id = tm.id
        
        UNION ALL
        
        SELECT 
            bk.id AS record_id,
            'Muat' AS record_type,
            DATE_FORMAT(bk.tanggal_keluar, '%d-%m-%Y') AS tanggal,
            tm.id AS type_mobil_id,
            tm.type,
            tm.rental_price
        FROM 
            barang_keluars bk
        JOIN 
            type_mobil tm ON bk.type_mobil_id = tm.id
    ");

        return view('data-bongkar.bongkar-muat.index', compact('bongkarMuats'));
    }
}
