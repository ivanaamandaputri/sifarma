@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Edit User</h4>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">
                <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Left Column for Profile Photo -->
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <label for="profile">Foto Profil</label>
                                <div>
                                    @if ($user->profile)
                                        <img src="{{ asset('storage/user/' . $user->profile) }}" alt="Foto User"
                                            class="img-fluid custom-photo mt-2" width="200">
                                    @else
                                        <img src="https://via.placeholder.com/150" alt="Foto tidak tersedia"
                                            class="img-fluid custom-photo mt-2" width="200">
                                    @endif
                                </div>
                                <input type="file" name="profile" class="form-control mt-2"
                                    onchange="previewImage(event)">
                            </div>
                        </div>

                        <!-- Right Column for Other Inputs -->
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" name="nip" class="form-control" value="{{ $user->nip }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" class="form-control" value="{{ $user->nama }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <select name="jabatan" class="form-control" required>
                                    <option value="Admin" {{ $user->jabatan == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="Kepala Apotik" {{ $user->jabatan == 'Kepala Apotik' ? 'selected' : '' }}>
                                        Kepala Apotik
                                    </option>
                                    <option value="Apoteker" {{ $user->jabatan == 'Apoteker' ? 'selected' : '' }}>Apoteker
                                    </option>
                                    <option value="Staff" {{ $user->jabatan == 'Staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" class="form-control" required>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="operator" {{ $user->role == 'operator' ? 'selected' : '' }}>Operator
                                    </option>
                                </select>
                            </div>

                            <!-- Input untuk Password Baru -->
                            <div class="form-group">
                                <label for="password">Password Baru</label>
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Minimal 6 karakter, huruf dan angka">
                                @error('password')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Input untuk Konfirmasi Password Baru -->
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Konfirmasi password baru">
                                @error('password_confirmation')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <br>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
            const output = document.querySelector('.form-group img');
            output.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection
