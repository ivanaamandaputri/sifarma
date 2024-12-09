@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="transaksi-tab" data-bs-toggle="tab" data-bs-target="#transaksi"
                    type="button" role="tab" aria-controls="transaksi" aria-selected="true">
                    Laporan Transaksi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="data-obat-tab" data-bs-toggle="tab" data-bs-target="#data-obat" type="button"
                    role="tab" aria-controls="data-obat" aria-selected="false">
                    Laporan Data Obat
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="myTabContent">
            <!-- Laporan Transaksi Tab -->
            <div class="tab-pane fade show active" id="transaksi" role="tabpanel" aria-labelledby="transaksi-tab">
                <div class="row py-3">
                    <!-- Laporan Transaksi -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header text-center">
                                <h5>Laporan Transaksi Obat Keluar</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('laporan.index') }}" method="GET">
                                    <div class="row g-3">
                                        <!-- Filter Bulan -->
                                        <div class="col-md-3">
                                            <label for="bulan" class="form-label">Bulan</label>
                                            <select name="bulan" id="bulan" class="form-select">
                                                <option value="">Semua Bulan</option>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}"
                                                        {{ old('bulan', request('bulan')) == $i ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>

                                        <!-- Filter Tahun -->
                                        <div class="col-md-3">
                                            <label for="tahun" class="form-label">Tahun</label>
                                            <select name="tahun" id="tahun" class="form-select">
                                                <option value="">Semua Tahun</option>
                                                @for ($i = 2020; $i <= date('Y'); $i++)
                                                    <option value="{{ $i }}"
                                                        {{ old('tahun', request('tahun')) == $i ? 'selected' : '' }}>
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>

                                        <!-- Filter Instansi -->
                                        <div class="col-md-3">
                                            <label for="ruangan" class="form-label">Instansi</label>
                                            <select name="ruangan" id="ruangan" class="form-select">
                                                <option value="">Semua Instansi</option>
                                                @foreach ($instansiList as $instansi)
                                                    <option value="{{ $instansi->id }}"
                                                        {{ old('nama', request('nama')) == $instansi->id ? 'selected' : '' }}>
                                                        {{ $instansi->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Filter Obat -->
                                        <div class="col-md-3">
                                            <label for="obat_id" class="form-label">Obat</label>
                                            <select name="obat_id" id="obat_id" class="form-select">
                                                <option value="">Semua Obat</option>
                                                @foreach ($obatList as $obat)
                                                    <option value="{{ $obat->id }}"
                                                        {{ old('obat_id', request('obat_id')) == $obat->id ? 'selected' : '' }}>
                                                        {{ $obat->nama_obat }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Print Button -->
                                    <div class="mt-3 text-end">
                                        <a href="{{ route('laporan.cetak', [
                                            'bulan' => request('bulan'),
                                            'tahun' => request('tahun'),
                                            'ruangan' => request('ruangan'),
                                            'obat_id' => request('obat_id'),
                                        ]) }}"
                                            class="btn btn-success">
                                            <i class="fas fa-print"></i> Cetak Laporan
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Data Obat Tab -->
            <div class="tab-pane fade" id="data-obat" role="tabpanel" aria-labelledby="data-obat-tab">
                <div class="row py-3">
                    <!-- Laporan Data Obat -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header text-center">
                                <h5>Laporan Data Obat</h5>
                            </div>
                            <div class="card-body">
                                <table class="table-bordered table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Obat</th>
                                            <th>Jenis</th>
                                            <th>Stok</th>
                                            <th>Satuan</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($obatList as $index => $obat)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $obat->nama_obat }}</td>
                                                <td>{{ $obat->jenis_obat }}</td>
                                                <td>{{ $obat->stok }}</td>
                                                <td>{{ $obat->satuan }}</td>
                                                <td>{{ number_format($obat->harga, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
