@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <!-- Header -->
        <div class="container-fluid d-flex justify-content-between">
            <h4 class="card-title">Daftar Instansi</h4>
            <a href="{{ route('instansi.create') }}" class="btn btn-primary mb-3">Tambah Instansi</a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card untuk tabel -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table-hover table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Instansi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($instansi as $i)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $i->nama }}</td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('instansi.edit', $i->id) }}" class="btn btn-warning">Edit</a>

                                        <!-- Tombol Hapus hanya ditampilkan jika tidak ada transaksi terkait -->
                                        @if (\App\Models\Transaksi::where('id_instansi', $i->id)->exists())
                                            <!-- Jika ada transaksi yang terkait, tombol Hapus dinonaktifkan -->
                                            <button class="btn btn-danger" disabled>Hapus</button>
                                        @else
                                            <!-- Tombol Hapus hanya jika tidak ada transaksi terkait -->
                                            <button class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#modalHapus{{ $i->id }}">Hapus</button>

                                            <!-- Modal Konfirmasi Hapus -->
                                            <div class="modal fade" id="modalHapus{{ $i->id }}" tabindex="-1"
                                                aria-labelledby="modalHapusLabel{{ $i->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalHapusLabel{{ $i->id }}">
                                                                Konfirmasi Hapus
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus data instansi
                                                            <strong>{{ $i->nama }}</strong>? Data yang sudah dihapus
                                                            tidak dapat dikembalikan.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <form action="{{ route('instansi.destroy', $i->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahan Gaya -->
    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Kesan timbul 3D */
        }
    </style>
@endsection
