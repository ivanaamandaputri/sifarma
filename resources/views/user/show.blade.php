@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Detail Pengguna</h4>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- Kolom Foto (kiri) -->
                    <div class="col-md-4 text-center">
                        @if ($user->profile)
                            <!-- Tampilkan gambar dengan lingkaran yang lebih besar -->
                            <img src="{{ asset('storage/user/' . $user->profile) }}" alt="Foto User"
                                class="img-fluid custom-photo" style="width: 200px; height: 200px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/200" alt="No Foto" class="img-fluid custom-photo"
                                style="width: 200px; height: 200px; object-fit: cover;">
                        @endif
                    </div>

                    <!-- Kolom Inputan (kanan) -->
                    <div class="col-md-8">
                        <div class="form-group row">
                            <label for="nip" class="col-sm-4 col-form-label font-weight-bold">NIP</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ $user->nip }}" disabled>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="nama" class="col-sm-4 col-form-label font-weight-bold">Nama</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ $user->nama }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="jabatan" class="col-sm-4 col-form-label font-weight-bold">Jabatan</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ $user->jabatan }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="instansi" class="col-sm-4 col-form-label font-weight-bold">Instansi</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ $user->instansi->nama }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="role" class="col-sm-4 col-form-label font-weight-bold">Role</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ $user->role }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
    <style>
        .custom-photo {
            width: 200px;
            /* Ukuran gambar */
            height: 200px;
            /* Ukuran gambar */
            object-fit: cover;
            /* Menyesuaikan gambar dengan area tanpa merusak aspek rasio */
            border-radius: 15px;
            /* Sudut tumpul pada gambar */
        }
    </style>
@endsection
