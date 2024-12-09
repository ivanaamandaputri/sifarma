@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <h4>Edit Instansi</h4>
        <form action="{{ route('instansi.update', $instansi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nama">Nama Instansi</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ $instansi->nama }}" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
@endsection
