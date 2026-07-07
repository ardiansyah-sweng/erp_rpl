@extends('layouts.app')

@section('title', 'Tambah User')

@section('page-title')
    <h3 class="mb-0">Tambah User Baru</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Kelola User</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-plus"></i> Form Tambah User
                    </h3>
                </div>

                @include('users.form', [
                    'action' => route('users.store'),
                    'method' => 'POST',
                    'submitText' => 'Simpan',
                    'roles' => $roles,
                ])
            </div>
        </div>
    </div>
@endsection
