<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\User;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Filter transaksi berdasarkan request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function filterTransaksi(Request $request)
    {
        // Validasi input filter
        $request->validate([
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2000|max:' . now()->year,
            'instansi_id' => 'nullable|exists:instansi,id',
            'obat_id' => 'nullable|exists:obat,id',
        ]);

        // Query transaksi dengan relasi
        $query = Transaksi::with(['obat', 'user', 'instansi'])
            ->whereIn('status', ['Disetujui', 'Diretur', 'Retur']); // Filter transaksi berdasarkan status relevan

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_order', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_order', $request->tahun);
        }

        // Filter berdasarkan instansi
        if ($request->filled('instansi_id')) {
            $query->where('id_instansi', $request->instansi_id);
        }

        // Filter berdasarkan obat
        if ($request->filled('obat_id')) {
            $query->where('id_obat', $request->obat_id);
        }

        return $query;
    }

    /**
     * Menampilkan halaman laporan dengan filter.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Gunakan filter transaksi
        $query = $this->filterTransaksi($request);

        // Ambil data transaksi dengan pagination
        $laporanTransaksi = $query->orderBy('tanggal_order', 'asc')->paginate(10);

        // Ambil daftar obat untuk filter
        $obatList = Obat::all();

        // Ambil daftar instansi untuk filter
        $instansiList = Instansi::all();

        // Kirim data ke view
        return view('laporan.index', compact('laporanTransaksi', 'obatList', 'instansiList'));
    }

    /**
     * Mencetak laporan berdasarkan filter.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function cetak(Request $request)
    {
        $instansiList = Instansi::all();

        // Gunakan filter transaksi
        $query = $this->filterTransaksi($request);

        // Ambil semua data setelah filter
        $laporanTransaksi = $query->orderBy('tanggal_order', 'asc')->get();

        // Hitung grand total langsung di database
        $grandTotal = $laporanTransaksi->sum('total_harga');

        // Kirim data ke view
        return view('laporan.cetak', compact('laporanTransaksi', 'grandTotal'));
    }
}
