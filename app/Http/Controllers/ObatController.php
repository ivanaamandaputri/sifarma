<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\JenisObat;
use App\Models\StokMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObatController extends Controller
{
    // Menampilkan daftar obat
    public function index()
    {
        $obat = Obat::orderBy('nama', 'asc')->orderBy('created_at', 'desc')->get();
        $batasMinimum = 5; // Batas minimum stok
        $tanggalHariIni = Carbon::now(); // Tanggal saat ini
        $peringatanExp = 30; // Batas peringatan (30 hari sebelum exp)

        foreach ($obat as $ob) {
            $ob->warning = $ob->stok <= $batasMinimum;

            if ($ob->exp) {
                $tanggalExp = Carbon::parse($ob->exp);
                if ($tanggalExp->isPast()) {
                    $ob->expWarning = 'Sudah Kedaluwarsa';
                } elseif ($tanggalExp->diffInDays($tanggalHariIni) <= $peringatanExp) {
                    $ob->expWarning = 'Mendekati Kedaluwarsa';
                } else {
                    $ob->expWarning = null;
                }
            } else {
                $ob->expWarning = 'Tanggal Kedaluwarsa Tidak Tersedia';
            }
        }

        $jenisObat = JenisObat::all();
        $readOnly = auth()->user()->level === 'operator';

        return view('obat.index', compact('obat', 'jenisObat', 'readOnly'));
    }

    // Menampilkan form untuk membuat obat baru
    public function create()
    {
        $jenisObat = JenisObat::select('id', 'nama')->get();
        return view('obat.create', compact('jenisObat'));
    }

    // Menyimpan data obat baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'dosis' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'exp' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jenis_obat' => 'required|exists:jenis_obat,id',
        ]);

        $existingObat = Obat::where('nama', $request->nama)
            ->where('dosis', $request->dosis)
            ->where('jenis_obat', $request->jenis_obat)
            ->first();

        if ($existingObat) {
            return redirect()->back()->withErrors(['error' => 'Obat dengan kombinasi data yang sama sudah ada.'])->withInput();
        }

        $filename = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = 'Foto_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/obat', $filename);
        }

        Obat::create([
            'nama' => $request->nama,
            'dosis' => $request->dosis,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'exp' => $request->exp,
            'keterangan' => $request->keterangan,
            'foto' => $filename,
            'jenis_obat' => $request->jenis_obat,
        ]);

        return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan');
    }

    // Menampilkan form untuk mengedit obat
    public function edit($id)
    {
        $obat = Obat::findOrFail($id);
        $jenisObat = JenisObat::all();
        return view('obat.edit', compact('obat', 'jenisObat'));
    }

    // Mengupdate data obat
    public function update(Request $request, $id)
    {
        $obat = Obat::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'dosis' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'exp' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_obat' => 'required|exists:jenis_obat,id',
        ]);

        if ($request->hasFile('foto')) {
            if ($obat->foto) {
                Storage::delete('public/obat/' . $obat->foto);
            }
            $foto = $request->file('foto');
            $filename = 'FTM_' . time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/obat', $filename);
            $obat->foto = $filename;
        }

        $existingObat = Obat::where('nama', $request->nama)
            ->where('dosis', $request->dosis)
            ->where('jenis_obat', $request->jenis_obat)
            ->where('id', '!=', $id)
            ->first();

        if ($existingObat) {
            return redirect()->back()->withErrors(['error' => 'Obat dengan kombinasi nama, dosis, dan jenis yang sama sudah ada.'])->withInput();
        }

        $obat->update($request->except('foto') + ['foto' => $obat->foto]);

        return redirect()->route('obat.index')->with('success', 'Obat berhasil diperbarui');
    }

    // Menghapus data obat
    public function destroy($id)
    {
        $obat = Obat::findOrFail($id);

        if ($obat->foto) {
            Storage::delete('public/obat/' . $obat->foto);
        }

        if (\App\Models\Transaksi::where('obat_id', $obat->id)->exists()) {
            return redirect()->route('obat.index')->with('error', 'Obat ini tidak dapat dihapus karena terkait dengan transaksi.');
        }

        $obat->delete();

        return redirect()->route('obat.index')->with('success', 'Obat berhasil dihapus');
    }

    // Menampilkan detail obat
    public function show($id)
    {
        $obat = Obat::findOrFail($id);
        return view('obat.show', compact('obat'));
    }
    // Menampilkan data obat untuk operator
    public function operatorIndex()
    {
        $obat = Obat::orderBy('nama', 'asc')->get();
        return view('operator.dataobat', compact('obat')); // Pastikan untuk mengarahkan ke view yang tepat
    }

    // Menampilkan detail obat untuk operator
    public function operatorShowobat($id)
    {
        // Mencari data obat berdasarkan ID
        $obat = Obat::findOrFail($id);
        return view('operator.showobat', compact('obat')); // Mengembalikan detail data obat
    }


    // Menambah stok obat
    public function tambahStok(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'sumber' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $obat = Obat::findOrFail($id);

        StokMasuk::create([
            'obat_id' => $obat->id,
            'jumlah' => $request->jumlah,
            'sumber' => $request->sumber,
            'tanggal' => $request->tanggal,
        ]);

        $obat->increment('stok', $request->jumlah);

        return redirect()->route('obat.index')->with('success', 'Stok berhasil ditambahkan!');
    }
}
