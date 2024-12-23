<?php

namespace App\Http\Controllers;

use App\Models\JenisObat;
use Illuminate\Http\Request;

class JenisObatController extends Controller
{
    // Menampilkan daftar jenis obat
    public function index()
    {
        // Mengambil semua data jenis obat
        $jenisObat = JenisObat::all();

        // Mengembalikan view dengan data jenis obat
        return view('jenis.index', compact('jenisObat'));
    }

    // Menampilkan form untuk membuat jenis obat baru
    public function create()
    {
        // Mengembalikan view untuk membuat jenis obat
        return view('jenis.create');
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_obat,nama', // Pastikan tabel jenis_obat
        ], [
            'nama.unique' => 'jenis obat sudah digunakan',  // Pesan error khusus
            'nama.required' => 'jenis obat harus diisi',
            'nama.max' => 'jenis obat terlalu panjang.'
        ]);

        // Menyimpan data jenis obat baru ke database
        $jenisObat = JenisObat::create($request->all());

        // Redirect kembali ke halaman daftar jenis obat dengan pesan sukses
        return redirect()->route('jenis_obat.index')->with('success', $jenisObat->nama . ' berhasil ditambahkan');
    }


    // Method edit
    public function edit($id)
    {
        $jenisObat = JenisObat::findOrFail($id);
        return view('jenis.edit', compact('jenisObat'));
    }

    public function update(Request $request, JenisObat $jenisObat)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_obat,nama,' . $jenisObat->id, // Mengabaikan validasi unik untuk jenis obat yang sedang diedit
        ], [
            'nama.unique' => 'jenis obat sudah digunakan',  // Pesan error khusus
            'nama.required' => 'jenis obat harus diisi',
            'nama.max' => 'jenis obat terlalu panjang.'
        ]);

        // Simpan nama jenis obat sebelum diperbarui
        $namaJenis = $jenisObat->nama;

        // Memperbarui data jenis obat
        $jenisObat->update($request->all());

        // Redirect kembali ke halaman daftar jenis obat dengan pesan sukses
        return redirect()->route('jenis_obat.index')->with('success', "$namaJenis berhasil diperbarui.");
    }


    public function destroy(JenisObat $jenisObat)
    {
        // Periksa apakah jenis obat sedang digunakan di tabel obat
        $obatTerkait = $jenisObat->obat()->exists();

        if ($obatTerkait) {
            // Jika ada obat terkait, kirimkan pesan error ke session
            return redirect()->route('jenis_obat.index')->with('error', "Jenis obat \"$jenisObat->nama\" tidak dapat dihapus karena sedang digunakan di tabel obat.");
        }

        // Simpan nama jenis obat sebelum menghapus
        $namaJenis = $jenisObat->nama;

        // Menghapus jenis obat
        $jenisObat->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('jenis_obat.index')->with('success', "Jenis obat \"$namaJenis\" berhasil dihapus.");
    }
}
