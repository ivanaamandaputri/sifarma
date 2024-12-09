@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Tambah User Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Kolom Kiri untuk Foto -->
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <label for="profile"></label>
                                <div>
                                    <img src="https://via.placeholder.com/200" alt="Foto tidak tersedia"
                                        class="img-fluid custom-photo mt-2">
                                </div>
                                <input type="file" name="profile" class="form-control mt-2"
                                    onchange="previewImage(event)">
                            </div>
                        </div>

                        <!-- Kolom Kanan untuk Input Lainnya -->
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                                    placeholder="Masukkan NIP" required>
                                @error('nip')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama"
                                    class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan Nama"
                                    required>
                                @error('nama')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <select name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" required>
                                    <option value="" selected disabled>Pilih Jabatan</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Kepala Apotik">Kepala Apotik</option>
                                    <option value="Apoteker">Apoteker</option>
                                    <option value="Staff">Staff</option>
                                </select>
                                @error('jabatan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="id_instansi">Instansi</label>
                                <select name="id_instansi" class="form-control @error('id_instansi') is-invalid @enderror"
                                    required>
                                    <option value="" selected disabled>Pilih Instansi</option>

                                    @foreach ($instansi as $i)
                                        <option value="{{ $i->id }}">{{ $i->nama }}</option>
                                        <!-- Assuming 'nama' is the column to display -->
                                    @endforeach
                                </select>
                                @error('id_instansi')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                @error('id_instansi')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                                    <option value="" selected disabled>Pilih Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="operator">Operator</option>
                                </select>
                                @error('role')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan Password" required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Konfirmasi Password" required>
                                @error('password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .custom-photo {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
        }
    </style>

    <script>
        function previewImage(event) {
            const output = document.querySelector('.custom-photo');
            output.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection
