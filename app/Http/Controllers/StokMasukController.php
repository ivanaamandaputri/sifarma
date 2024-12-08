<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\StokMasuk;
use Illuminate\Http\Request;

class StokMasukController extends Controller
{
    public function tambahStok(Request $request)
    {
        $request->validate([
            'id_obat' => 'required|exists:obat,id', // Disesuaikan dengan nama kolom di tabel stok_masuk
            'jumlah' => 'required|integer|min:1',
            'sumber' => 'nullable|string|max:255',
        ]);

        // Tambah stok ke tabel stok_masuk
        StokMasuk::create([
            'id_obat' => $request->id_obat,
            'jumlah' => $request->jumlah,
            'sumber' => $request->sumber,
        ]);

        // Update stok terkini di tabel obat
        $obat = Obat::findOrFail($request->id_obat);
        $obat->increment('stok', $request->jumlah);

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan.');
    }
}
