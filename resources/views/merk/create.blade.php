@extends('layouts.app')

@section('title', 'Tambah Merk')

@section('page-title')
    <h3 class="mb-0">Tambah Merk Baru</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('merk.index') }}">Merk</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="bi bi-plus"></i> Form Tambah Merk
                                    </h3>
                                </div>

                                @include('merk.form', [
                                    'action' => route('merk.store'),
                                    'method' => 'POST',
                                    'submitText' => 'Simpan'
                                ])
                            </div>
                        </div>
                    </div>
@endsection
