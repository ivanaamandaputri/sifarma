<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::orderBy('nama', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instansi = Instansi::all(); // Fetch all instansi
        return view('user.create', compact('instansi')); // Pass instansi to the view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|string|unique:users,nip',
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
            ],
            'konfirmasi_password' => 'required|same:password',
            'role' => 'required|string|in:admin,operator',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_instansi' => 'required|exists:instansi,id', // Validasi instansi
        ]);

        // Menangani upload profile jika ada
        $filename = null;
        if ($request->hasFile('profile')) {
            $profile = $request->file('profile');
            $filename = 'profile_' . uniqid() . '.' . $profile->getClientOriginalExtension();
            $profile->storeAs('public/user', $filename);
        }

        // Menyimpan data user baru
        User::create([
            'nip' => $request->nip,
            'password' => bcrypt($request->password), // Hash password
            'role' => $request->role,
            'profile' => $filename, // Ganti dengan $filename
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'id_instansi' => $request->id_instansi, // Menyimpan instansi yang dipilih
        ]);

        return redirect()->route('user.index')->with('success', 'Data berhasil disimpan.');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'nip' => 'required|unique:users,nip,' . $user->id,
            // Validasi password hanya jika password baru diisi
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
            ],
            'konfirmasi_password' => 'nullable|same:password',
            'role' => 'required|string|in:admin,operator',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string',
        ]);

        // Update data user
        $user->nip = $request->nip;
        $user->nama = $request->nama;
        $user->jabatan = $request->jabatan;
        $user->profile = $request->profile;
        $user->role = $request->role;

        // Jika password diisi, update password
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        // Simpan perubahan
        $user->save();

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->role === 'admin') {
                return redirect()->route('user.index')->with('error', 'User dengan level admin tidak dapat dihapus.');
            }

            if ($user->profile) {
                Storage::delete('public/user/' . $user->profile);
            }

            $user->delete();

            return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === "23000") {
                return redirect()->route('user.index')->with('error', 'User tidak dapat dihapus karena terkait dengan data lain.');
            }

            return redirect()->route('user.index')->with('error', 'Terjadi kesalahan saat menghapus user.');
        }
    }
}
