@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Detail Obat</h4>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- Kolom Foto (kiri) -->
                    <div class="col-md-4 mb-md-0 mb-3 text-center">
                        @if ($obat->foto)
                            <img src="{{ asset('storage/obat/' . $obat->foto) }}" alt="Foto {{ $obat->nama }}"
                                class="img-fluid custom-photo">
                        @else
                            <img src="https://via.placeholder.com/200" alt="Foto Tidak Tersedia"
                                class="img-fluid custom-photo">
                        @endif
                    </div>

                    <!-- Kolom Inputan (kanan) -->
                    <div class="col-md-8">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Nama Obat</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ $obat->nama }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Dosis</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ $obat->dosis }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Jenis</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"
                                    value="{{ $obat->jenisObat->nama ?? 'Tidak Ditemukan' }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Stok</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"
                                    value="{{ number_format($obat->stok, 0, ',', '.') }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Harga</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"
                                    value="Rp {{ number_format($obat->harga, 0, ',', '.') }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Tanggal Kadaluwarsa</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($obat->exp)->locale('id')->translatedFormat('j M Y') }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Keterangan</label>
                            <div class="col-sm-8">
                                <div class="bg-light rounded border p-2">
                                    {!! $obat->keterangan !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 text-right">
                                <a href="{{ route('operator.dataobat') }}" class="btn btn-secondary btn-sm">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-photo {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-control {
            font-size: 14px;
        }

        .btn-sm {
            font-size: 14px;
        }

        .card-header {
            font-size: 16px;
        }

        @media (max-width: 576px) {
            .custom-photo {
                width: 150px;
                height: 150px;
            }
        }
    </style>
@endsection
