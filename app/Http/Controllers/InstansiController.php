<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    // Menampilkan daftar instansi
    public function index()
    {
        $instansi = Instansi::all();  // Ambil semua data instansi
        return view('instansi.index', compact('instansi'));
    }

    // Menampilkan form untuk membuat instansi baru
    public function create()
    {
        $instansi = Instansi::all();
        return view('instansi.create', compact('instansi'));
    }

    // Menyimpan instansi baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Simpan data instansi baru
        Instansi::create($request->all());

        return redirect()->route('instansi.index')->with('success', 'Instansi berhasil ditambahkan');
    }

    // Menampilkan form untuk mengedit instansi
    public function edit($id)
    {
        $instansi = Instansi::findOrFail($id);  // Cari instansi berdasarkan ID
        return view('instansi.edit', compact('instansi'));
    }

    // Mengupdate instansi
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $instansi = Instansi::findOrFail($id);
        $instansi->update($request->all());

        return redirect()->route('instansi.index')->with('success', 'Instansi berhasil diperbarui');
    }

    // Menghapus instansi
    public function destroy($id)
    {
        $instansi = Instansi::findOrFail($id);

        // Mengecek apakah instansi sudah memiliki transaksi
        if (\App\Models\Transaksi::where('id_instansi', $instansi->id)->exists()) {
            // Jika sudah ada transaksi yang terkait, tidak bisa dihapus
            return redirect()->route('instansi.index')->with('error', 'Instansi ini tidak dapat dihapus karena terkait dengan transaksi.');
        } 

        // Menghapus instansi jika tidak terkait dengan transaksi
        $instansi->delete();

        return redirect()->route('instansi.index')->with('success', 'Instansi berhasil dihapus');
    }
}
