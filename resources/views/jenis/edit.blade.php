@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Edit Jenis Obat</h4>
            </div>
            <div class="card-body">
                <!-- Form untuk mengedit jenis obat -->
                <form action="{{ route('jenis_obat.update', $jenisObat) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">Nama Jenis Obat</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ old('nama', $jenisObat->nama) }}" required
                            placeholder="Masukkan nama jenis obat">

                        <!-- Menampilkan pesan error khusus untuk nama -->
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <a href="{{ route('jenis_obat.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
