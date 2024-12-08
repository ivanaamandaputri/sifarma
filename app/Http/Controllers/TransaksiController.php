<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\Users;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // Menampilkan daftar transaksi
    public function index()
    {
        $transaksi = Transaksi::with(['obat', 'user'])
            ->where('id_users', Auth::id())
            ->orderBy('tanggal_order', 'desc')
            ->get();
        return view('transaksi.index', compact('transaksi'));
    }

    // Menampilkan form untuk membuat transaksi baru
    public function create()
    {
        $obat = Obat::all();
        return view('transaksi.create', compact('obat'));
    }

    // Menyimpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'id_obat' => 'required|exists:obat,id',
            'jumlah_permintaan' => 'required|integer|min:1',
            'tanggal_order' => 'required|date',
        ]);

        $obat = Obat::find($request->id_obat);

        if ($request->jumlah_permintaan > $obat->stok) {
            return back()->withErrors(['jumlah_permintaan' => 'Jumlah melebihi stok yang tersedia'])->withInput();
        }

        Transaksi::create([
            'id_users' => Auth::id(),
            'id_obat' => $request->id_obat,
            'id_jenis_obat' => $obat->id_jenis_obat,
            'id_instansi' => Auth::user()->id_instansi,
            'tanggal_order' => $request->tanggal_order,
            'jumlah_permintaan' => $request->jumlah_permintaan,
            'jumlah_acc' => 0,
            'jumlah_retur' => 0,
            'total_harga' => $obat->harga * $request->jumlah_permintaan,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit transaksi
    public function edit(Transaksi $transaksi)
    {
        if ($transaksi->status != 'Menunggu') {
            return redirect()->route('transaksi.index')->with('error', 'Transaksi ini tidak bisa diedit.');
        }

        $obat = Obat::all();
        return view('transaksi.edit', compact('transaksi', 'obat'));
    }

    // Memperbarui transaksi yang ada
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'id_obat' => 'required|exists:obat,id',
            'jumlah_permintaan' => 'required|integer|min:1',
        ]);

        $obat = Obat::find($request->id_obat);

        if ($request->jumlah_permintaan > $obat->stok) {
            return back()->withErrors(['jumlah_permintaan' => 'Jumlah melebihi stok yang tersedia'])->withInput();
        }

        $transaksi->update([
            'id_obat' => $request->id_obat,
            'jumlah_permintaan' => $request->jumlah_permintaan,
            'total_harga' => $obat->harga * $request->jumlah_permintaan,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    // Menghapus transaksi
    public function destroy(Transaksi $transaksi)
    {
        if ($transaksi->status != 'Menunggu') {
            return redirect()->route('transaksi.index')->with('error', 'Transaksi ini tidak bisa dihapus.');
        }

        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    // Menangani alasan penolakan
    public function alasanPenolakan(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        $transaksi->update([
            'status' => 'Ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return back()->with('success', 'Transaksi telah ditolak dengan alasan.');
    }

    // Menyetujui transaksi
    public function setujui(Transaksi $transaksi)
    {
        if ($transaksi->status === 'Menunggu') {
            $transaksi->update([
                'status' => 'Disetujui',
                'jumlah_acc' => $transaksi->jumlah_permintaan,
            ]);

            return back()->with('success', 'Transaksi berhasil disetujui.');
        }

        return back()->with('error', 'Transaksi tidak dapat disetujui.');
    }
}
