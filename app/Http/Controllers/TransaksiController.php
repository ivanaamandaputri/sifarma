<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\Retur;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // Menampilkan daftar transaksi
    public function index()
    {
        // Mengambil transaksi yang terkait dengan pengguna yang sedang login
        $transaksi = Transaksi::with(['obat', 'user'])
            ->where('id_users', Auth::id())
            ->orderBy('tanggal_order', 'desc')
            ->get();

        // Kirimkan data ke view
        return view('transaksi.index', compact('transaksi'));
    }

    // Menampilkan form untuk membuat transaksi baru
    public function create()
    {
        // Mengambil semua obat untuk ditampilkan di form
        $obat = Obat::all();
        return view('transaksi.create', compact('obat'));
    }

    // Menyimpan transaksi baru
    public function store(Request $request)
    {
        // Validasi data input dari form
        $request->validate([
            'id_obat' => 'required|exists:obat,id',
            'jumlah_permintaan' => 'required|integer|min:1',
            'tanggal_order' => 'required|date',
        ]);

        // Mencari obat berdasarkan ID
        $obat = Obat::find($request->id_obat);

        // Memeriksa apakah jumlah yang diminta melebihi stok
        if ($request->jumlah_permintaan > $obat->stok) {
            return back()->withErrors(['jumlah_permintaan' => 'Jumlah melebihi stok yang tersedia'])->withInput();
        }

        // Simpan transaksi
        Transaksi::create([
            'id_obat' => $request->id_obat,
            'jumlah_permintaan' => $request->jumlah_permintaan,
            'tanggal_order' => $request->tanggal_order,
            'total_harga' => $obat->harga * $request->jumlah_permintaan,
            'id_users' => auth()->id(),
            'id_instansi' => 1, // Sesuaikan dengan instansi terkait
            'jumlah_acc' => 0, // Inisialisasi
            'jumlah_retur' => 0, // Inisialisasi
            'status' => 'Menunggu', // Status default
            'alasan_penolakan' => null, // Jika tidak ada alasan
            'alasan_retur' => null, // Jika tidak ada alasan
        ]);

        // Update stok obat
        $obat->stok -= $request->jumlah_permintaan;
        $obat->save();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dibuat!');
    }

    // Menampilkan form untuk mengedit transaksi
    public function edit(Transaksi $transaksi)
    {
        // Pastikan hanya transaksi yang statusnya "Menunggu" yang bisa diubah oleh operator
        if ($transaksi->status != 'Menunggu') {
            return redirect()->route('transaksi.index')->with('error', 'Transaksi ini tidak bisa diedit karena sudah diproses.');
        }

        // Ambil daftar semua obat
        $obatList = Obat::with('jenisObat')->get(); // Pastikan relasi 'jenisObat' ada di model Obat

        // Ambil data obat yang sesuai dengan transaksi
        $obat = Obat::findOrFail($transaksi->id_obat);

        // Kirim data ke view
        return view('transaksi.edit', compact('transaksi', 'obat', 'obatList'));
    }


    // Memperbarui transaksi yang sudah ada
    public function update(Request $request, Transaksi $transaksi)
    {
        // Validasi data input dari form
        $request->validate([
            'id_obat' => 'required|exists:obat,id',
            'jumlah_permintaan' => 'required|integer|min:1',
        ]);

        // Mencari obat berdasarkan ID
        $obat = Obat::find($request->id_obat);
        $total = $obat->harga * $request->jumlah_permintaan;

        // Menghitung selisih jumlah obat
        $selisih = $request->jumlah_permintaan - $transaksi->jumlah_permintaan;

        // Memeriksa apakah stok mencukupi untuk pembaruan
        if ($selisih > 0 && $selisih > $obat->stok) {
            return back()->withErrors(['jumlah_permintaan' => 'Jumlah melebihi stok yang tersedia'])->withInput();
        }

        // Memperbarui transaksi
        $transaksi->update([
            'id_obat' => $request->id_obat,
            'jumlah_permintaan' => $request->jumlah_permintaan,
            'total_harga' => $total,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    // Menghapus transaksi
    public function destroy(Transaksi $transaksi)
    {
        // Pastikan hanya transaksi yang statusnya "Menunggu" yang bisa dihapus oleh operator
        if ($transaksi->status != 'Menunggu') {
            return redirect()->route('transaksi.index')->with('error', 'Transaksi ini tidak bisa dihapus karena sudah diproses.');
        }

        // Mengembalikan stok obat
        $obat = $transaksi->obat;
        $obat->increment('stok', $transaksi->jumlah_permintaan);

        // Menghapus transaksi
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus');
    }


    // Menyimpan retur transaksi
    public function storeRetur(Request $request)
    {
        // Validasi data input untuk retur
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'jumlah_retur' => 'required|integer|min:1',
            'alasan_retur' => 'nullable|string',
        ]);

        // Mencari transaksi berdasarkan ID
        $transaksi = Transaksi::find($validated['transaksi_id']);

        // Memeriksa apakah jumlah retur lebih dari jumlah yang diterima
        if ($validated['jumlah_retur'] > $transaksi->jumlah_acc) {
            return back()->withErrors(['jumlah_retur' => 'Jumlah retur melebihi jumlah yang diterima'])->withInput();
        }

        // Mengupdate transaksi dengan jumlah retur dan status
        $transaksi->update([
            'jumlah_retur' => $validated['jumlah_retur'],
            'status' => 'Diretur',
            'alasan_retur' => $validated['alasan_retur'],
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Retur berhasil diproses');
    }
    public function selesai($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Periksa status dan ubah jika perlu
        if ($transaksi->status === 'Disetujui') {
            $transaksi->status = 'Selesai';
            $transaksi->save();

            return response()->json(['message' => 'Transaksi Selesai']);
        }

        return response()->json(['error' => 'Transaksi tidak bisa diubah'], 400);
    }
    // Menangani retur transaksi
    public function retur(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|integer',
            'alasan_retur' => 'nullable|string',
            'password' => 'required|string',
        ]);

        // Verifikasi password dan validasi lainnya
        // Proses untuk membuat retur baru
        //  $retur = Retur::create([
        //      'transaksi_id' => $request->transaksi_id,
        //      'obat_id' => $request->obat_id,
        //      'jumlah' => $request->jumlah,
        //      'alasan_retur' => $request->alasan_retur,
        //      'password' => $request->password, // Proses validasi password di sini
        //      'status' => 'Diretur',
        //      'user_id' => auth()->id(),
        //  ]);

        return response()->json(['success' => 'Retur berhasil diproses']);
    }
    public function showTransaksi($tanggal)
    {
        return view('nama-view', compact('tanggal'));
    }

    // Menangani pengajuan transaksi
    public function buatPengajuan(Request $request)
    {
        $pengajuan = Transaksi::create($request->all());

        // Kirim Notification ke semua admin
        $admins = User::where('role', 'admin')->get(); // Ambil semua admin
        foreach ($admins as $admin) {
            Notification::create([
                'id_users' => $admin->id,
                'judul' => 'Pengajuan Baru',
                'pesan' => 'Ada pengajuan baru pada tanggal ' . $pengajuan->tanggal_order . ' untuk obat ' . $pengajuan->obat->nama . '. Silakan periksa.',
                'role' => 'admin',
            ]);
        }

        return redirect()->back()->with('success', 'Pengajuan berhasil dibuat.');
    }
}
