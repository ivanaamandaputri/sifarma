@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Data Order Obat</h4>
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary">Tambah Transaksi</a>
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

        <br>
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table-hover table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Obat</th>
                                <th>Dosis</th>
                                <th>Jenis</th>
                                <th>jumlah Order</th>
                                <th>Acc</th>
                                <th>Harga (Rp)</th>
                                <th>Total (Rp)</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $number = 1; @endphp
                            @foreach ($transaksi as $item)
                                <tr>
                                    <td>{{ $number++ }}</td>
                                    <td>{{ $item->tanggal_order ? \Carbon\Carbon::parse($item->tanggal_order)->format('d M Y') : '-' }}
                                    </td>
                                    <td>{{ $item->obat->nama }}</td>
                                    <td>{{ $item->obat->dosis }}</td>
                                    <td>{{ $item->obat->jenisObat->nama }}</td>
                                    <td>{{ number_format($item->jumlah_permintaan, 0, ',', '.') }}</td>
                                    <td>{{ $item->acc ?? '-' }}</td>
                                    <td>{{ number_format($item->obat->harga, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($item->status === 'Disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif ($item->status === 'Ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @elseif ($item->status === 'Selesai')
                                            <span class="badge bg-dark">Selesai</span>
                                        @else
                                            <span class="badge bg-warning">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status === 'Ditolak')
                                            <button type="button" class="btn btn-sm btn-light view-reason-btn"
                                                data-reason="{{ $item->alasan_penolakan }}">Alasan</button>
                                        @elseif ($item->status === 'Menunggu')
                                            <div class="d-inline-block me-2">
                                                <a href="{{ route('transaksi.edit', $item->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                            </div>
                                            <div class="d-inline-block">
                                                <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                            </div>
                                        @elseif ($item->status === 'Disetujui')
                                            <button type="button" class="btn btn-success btn-sm selesai-btn"
                                                data-id="{{ $item->id }}">Selesai</button>
                                            <button type="button" class="btn btn-warning btn-sm retur-btn"
                                                data-id="{{ $item->id }}" data-obat="{{ $item->obat->id }}"
                                                data-nama="{{ $item->obat->nama_obat }}">Retur</button>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus transaksi ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
                    </div>
                </div>
            </div>
        </div> --}}


        <!-- Modal Alasan Penolakan -->
        <div class="modal fade" id="reasonModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Alasan Penolakan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p id="reasonText"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Selesai -->
        <div class="modal fade" id="confirmSelesaiModal" tabindex="-1" aria-labelledby="confirmSelesaiModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmSelesaiModalLabel">Konfirmasi Selesai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menyelesaikan transaksi ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success" id="confirmSelesaiBtn">Ya, Selesai</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Retur -->
        <div class="modal fade" id="returModal" tabindex="-1" aria-labelledby="returModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="returModalLabel">Retur Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="returForm">
                            <div class="mb-3">
                                <label for="jumlah_permintaan" class="form-label">jumlah_permintaan</label>
                                <input type="number" class="form-control" id="jumlah_permintaan" required>
                            </div>
                            <div class="mb-3">
                                <label for="alasan" class="form-label">Alasan Retur</label>
                                <textarea class="form-control" id="alasan" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Retur</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let selectedTransaksiId;
            let selectedObatId;

            $('.delete-btn').click(function() {
                selectedTransaksiId = $(this).closest('form').data('id');
                $('#confirmDeleteModal').modal('show');
            });

            $('#confirmDeleteBtn').click(function() {
                $.ajax({
                    url: '{{ route('transaksi.destroy', ':id') }}'.replace(':id',
                        selectedTransaksiId),
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        $('#confirmDeleteModal').modal('hide');
                        location.reload();
                    }
                });


            });

            $('.view-reason-btn').click(function() {
                var reason = $(this).data('reason');
                $('#reasonText').text(reason);
                $('#reasonModal').modal('show');
            });

            $('.selesai-btn').click(function() {
                selectedTransaksiId = $(this).data('id');
                $('#confirmSelesaiModal').modal('show');
            });

            $('#confirmSelesaiBtn').click(function() {
                $.ajax({
                    url: '{{ route('transaksi.index') }}/' + selectedTransaksiId,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'PUT',
                        status: 'Selesai'
                    },
                    success: function() {
                        $('#confirmSelesaiModal').modal('hide');
                        location.reload();
                    }
                });
            });

            $('.retur-btn').click(function() {
                selectedTransaksiId = $(this).data('id');
                selectedObatId = $(this).data('obat');
                $('#returModal').modal('show');
            });

            $('#returForm').submit(function(e) {
                e.preventDefault();

                const jumlah_permintaan = $('#jumlah_permintaan').val();
                const alasan = $('#alasan').val();
                const password = $('#password').val();

                $.ajax({
                    url: '{{ route('transaksi.retur') }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        transaksi_id: selectedTransaksiId,
                        id_obat: selectedObatId,
                        jumlah_permintaan: jumlah_permintaan,
                        alasan: alasan,
                        password: password
                    },
                    success: function() {
                        $('#returModal').modal('hide');
                        location.reload();
                    }
                });
            });
        });
    </script>
@endsection
