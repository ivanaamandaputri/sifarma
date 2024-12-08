@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Profil Saya</h4>
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-body">
                <div class="row align-items-center">
                    <!-- Kolom Foto (kiri) -->
                    <div class="col-md-4 text-center">
                        @if ($users->foto)
                            <img src="{{ asset('storage/users/' . $users->foto) }}" alt="Foto users"
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
                                <input type="text" class="form-control" value="{{ $users->nip }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama" class="col-sm-4 col-form-label font-weight-bold">Nama Pegawai</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ $users->nama }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="jabatan" class="col-sm-4 col-form-label font-weight-bold">Jabatan</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ $users->jabatan }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ruangan" class="col-sm-4 col-form-label font-weight-bold">Ruangan</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ $users->ruangan }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="level" class="col-sm-4 col-form-label font-weight-bold">Level</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ $users->level }}" disabled>
                            </div>
                        </div>
                        <br>
                        <a href="{{ route('profile.edit', $users->id) }}" class="btn btn-primary">Edit Profil</a>
                    </div>
                </div>
            </div>
            <br>
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
@endsection
