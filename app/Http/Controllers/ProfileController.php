<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Menampilkan halaman profil pengguna yang sedang login
    public function index()
    {
        // Ambil data pengguna yang sedang login
        $user = auth()->user();  // Mendapatkan pengguna yang sedang login

        return view('profile.index', compact('user'));
    }

    // Menampilkan halaman edit profil
    public function edit(User $user)
    {
        // Pastikan hanya pengguna yang bisa mengakses profil ini sesuai dengan role mereka
        if (auth()->user()->id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('profile.edit', compact('user'));
    }

    // Memproses pembaruan data profil
    public function update(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'profile' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Maksimal ukuran foto 2MB
            'old_password' => 'nullable|string|min:6', // Validasi password lama
            'new_password' => 'nullable|string|min:6|confirmed', // Validasi password baru
        ]);

        // Verifikasi apakah password lama sesuai
        if ($request->filled('old_password') && !Hash::check($request->old_password, $user->password)) {
            // Jika password lama tidak cocok, kembali dengan error
            return back()->withErrors(['old_password' => 'Password lama tidak cocok.']);
        }

        // Update data pengguna
        $user->nip = $request->nip;
        $user->nama = $request->nama;
        $user->jabatan = $request->jabatan;

        // Proses upload foto jika ada
        if ($request->hasFile('profile')) {
            // Hapus foto lama jika ada
            if ($user->profile) {
                Storage::delete('public/user/' . $user->profile); // Hapus foto lama dari storage
            }

            // Simpan foto baru
            $path = $request->file('profile')->store('user', 'public');
            $user->profile = basename($path);
        }

        // Jika password baru diisi, update password
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password); // Enkripsi password baru
        }

        // Simpan perubahan
        $user->save();

        // Redirect kembali ke halaman profil setelah sukses
        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui');
    }
}
