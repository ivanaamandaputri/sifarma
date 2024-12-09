@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <h4>Tambah Instansi</h4>
        <form action="{{ route('instansi.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama">Nama Instansi</label>
                <input type="text" name="nama" id="nama" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
@endsection
