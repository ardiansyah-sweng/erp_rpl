@extends('layouts.app')

@section('title', 'Tambah Gudang')

@section('page-title')
    <h3 class="mb-0">Tambah Gudang</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('warehouses.index') }}">Warehouse</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Gudang</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @include('warehouse.form', [
                    'action' => route('warehouses.store'),
                    'method' => 'POST',
                    'warehouse' => null
                ])
            </div>
        </div>
    </div>
@endsection
