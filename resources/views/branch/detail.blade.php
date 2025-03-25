@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Cabang</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($branch)
        <table class="table table-bordered">
            <tr>
                <th>ID Cabang</th>
                <td>{{ $branch->id }}</td>
            </tr>
            <tr>
                <th>Nama Cabang</th>
                <td>{{ $branch->branch_name }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $branch->branch_address }}</td>
            </tr>
            <tr>
                <th>Telepon</th>
                <td>{{ $branch->branch_telephone }}</td>
            </tr>
        </table>
    @else
        <p>Data tidak ditemukan.</p>
    @endif

    <a href="{{ route('branch.list') }}" class="btn btn-primary">Kembali</a>
</div>
@endsection
