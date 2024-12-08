<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    private function filterTransaksi(Request $request)
    {
        // Query dasar dengan status
        $query = Transaksi::with('obat', 'user') // Menggunakan relasi 'obat' dan 'user'
            ->whereIn('status', ['Menunggu', 'Disetujui', 'Ditolak', 'Diretur']);

        // Filter berdasarkan bulan
        if ($request->has('bulan') && $request->bulan != '') {
            $query->whereMonth('tanggal_order', $request->bulan); // Ganti tanggal ke 'tanggal_order'
        }

        // Filter berdasarkan tahun
        if ($request->has('tahun') && $request->tahun != '') {
            $query->whereYear('tanggal_order', $request->tahun); // Ganti tanggal ke 'tanggal_order'
        }

        // Filter berdasarkan instansi (ruangan)
        if ($request->has('ruangan') && $request->ruangan != '') {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('jabatan', $request->ruangan); // Menyesuaikan dengan 'jabatan' di tabel 'users'
            });
        }

        // Filter berdasarkan obat
        if ($request->has('obat_id') && $request->obat_id != '') {
            $query->where('id_obat', $request->obat_id); // Ganti dengan 'id_obat'
        }

        return $query;
    }

    public function index(Request $request)
    {
        // Gunakan fungsi filter
        $query = $this->filterTransaksi($request);

        // Ambil data transaksi dengan pagination
        $laporanTransaksi = $query->orderBy('tanggal_order', 'asc')->paginate(10); // Ganti 'tanggal' ke 'tanggal_order'

        // Ambil daftar obat untuk filter
        $obatList = Obat::all();

        // Daftar instansi yang tersedia
        $instansiList = [
            'Puskesmas Kaligangsa',
            'Puskesmas Margadana',
            'Puskesmas Tegal Barat',
            'Puskesmas Debong Lor',
            'Puskesmas Tegal Timur',
            'Puskesmas Slerok',
            'Puskesmas Tegal Selatan',
            'Puskesmas Bandung',
        ];

        // Kirim data ke view
        return view('laporan.index', compact('laporanTransaksi', 'obatList', 'instansiList'));
    }

    public function cetak(Request $request)
    {
        // Gunakan fungsi filter
        $query = Transaksi::with('obat') // Gunakan relasi
            ->select('transaksi.*')
            ->orderBy('tanggal_order', 'asc'); // Ganti 'tanggal' ke 'tanggal_order'

        // Filter berdasarkan bulan
        if ($request->has('bulan') && $request->bulan != '') {
            $query->whereMonth('tanggal_order', $request->bulan); // Ganti tanggal ke 'tanggal_order'
        }

        // Filter berdasarkan tahun
        if ($request->has('tahun') && $request->tahun != '') {
            $query->whereYear('tanggal_order', $request->tahun); // Ganti tanggal ke 'tanggal_order'
        }

        // Filter berdasarkan ruangan
        if ($request->has('ruangan') && $request->ruangan != '') {
            $query->where('jabatan', $request->ruangan); // Menyesuaikan dengan 'jabatan' di tabel 'users'
        }

        // Filter berdasarkan obat
        if ($request->has('obat_id') && $request->obat_id != '') {
            $query->where('id_obat', $request->obat_id); // Ganti dengan 'id_obat'
        }

        // Ambil data
        $laporanTransaksi = $query->get();

        return view('laporan.cetak', compact('laporanTransaksi'));
    }
}
