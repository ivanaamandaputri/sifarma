@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="container-fluid d-flex justify-content-between">
            <h4 class="card-title">Data User</h4>
            <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Tambah User</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table-hover table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Instansi</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nip }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->jabatan }}</td>
                                    <td>{{ $item->instansi->nama ?? 'Tidak Diketahui' }}</td>
                                    <td>
                                        @if ($item->role == 'admin')
                                            <span class="badge bg-success">Admin</span>
                                        @elseif($item->role == 'operator')
                                            <span class="badge bg-secondary">Operator</span>
                                        @else
                                            <span class="badge bg-warning">Tidak Diketahui</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('user.show', $item->id) }}" class="btn btn-info btn">Detail</a>
                                        <a href="{{ route('user.edit', $item->id) }}" class="btn btn-warning btn">Edit</a>
                                        <button class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalHapus{{ $item->id }}"
                                            @if ($item->role !== 'admin' && $item->transaksi->isNotEmpty()) disabled 
                                            title="Tombol hapus dinonaktifkan karena transaksi sudah ada" @endif>
                                            Hapus
                                        </button>


                                    </td>
                                </tr>

                                <!-- Modal Konfirmasi Hapus -->
                                <div class="modal fade" id="modalHapus{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="modalHapusLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalHapusLabel{{ $item->id }}">Konfirmasi
                                                    Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus data user
                                                <strong>{{ $item->nama }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('user.destroy', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-photo {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection
