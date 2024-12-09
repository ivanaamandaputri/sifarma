@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <h4>Daftar Instansi</h4>
        <a href="{{ route('instansi.create') }}" class="btn btn-primary mb-3">Tambah Instansi</a>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Instansi</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($instansi as $i)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $i->nama }}</td>
                        <td>
                            <a href="{{ route('instansi.edit', $i->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('instansi.destroy', $i->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
