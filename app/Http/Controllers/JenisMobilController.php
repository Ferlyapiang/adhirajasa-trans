<?php

namespace App\Http\Controllers;

use App\Models\JenisMobil;
use Illuminate\Http\Request;

class JenisMobilController extends Controller
{
    public function index()
    {
        $jenisMobil = JenisMobil::all();
        return view('master-data.jenis-mobil.index', compact('jenisMobil'));
    }

    // Menampilkan form untuk menambah jenis mobil
    public function create()
    {
        return view('master-data.jenis-mobil.create');
    }

    // Menyimpan jenis mobil baru
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'rental_price' => 'required|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        // Menyimpan data ke database
        JenisMobil::create($validated);

        return redirect()->route('master-data.jenis-mobil.index')->with('success', 'Jenis mobil berhasil ditambahkan.');
    }

    // Menampilkan detail jenis mobil berdasarkan ID
    public function show($id)
    {
        $jenisMobil = JenisMobil::findOrFail($id);
        return view('master-data.jenis-mobil.show', compact('jenisMobil'));
    }

    // Menampilkan form untuk mengedit jenis mobil berdasarkan ID
    public function edit($id)
    {
        $jenisMobil = JenisMobil::findOrFail($id);
        return view('master-data.jenis-mobil.edit', compact('jenisMobil'));
    }

    // Memperbarui data jenis mobil yang sudah ada
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'rental_price' => 'required|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        // Menemukan jenis mobil dan mengupdate data
        $jenisMobil = JenisMobil::findOrFail($id);
        $jenisMobil->update($validated);

        return redirect()->route('master-data.jenis-mobil.index')->with('success', 'Jenis mobil berhasil diperbarui.');
    }

    // Menghapus jenis mobil berdasarkan ID
    public function destroy($id)
    {
        $jenisMobil = JenisMobil::findOrFail($id);
        $jenisMobil->delete();

        return redirect()->route('master-data.jenis-mobil.index')->with('success', 'Jenis mobil berhasil dihapus.');
    }
}