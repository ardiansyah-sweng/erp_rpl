@extends('layouts.master')

@section('content')
    <h1>Detail Cabang</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>Nama Cabang:</strong> {{ $branch->branch_name }}</p>
            <p><strong>Alamat:</strong> {{ $branch->branch_address }}</p>
            <p><strong>Telepon:</strong> {{ $branch->branch_telephone }}</p>
        </div>
    </div>
@endsection
