@extends('layouts.app')

@section('title', 'Edit Merk')

@section('page-title')
    <h3 class="mb-0">Edit Merk</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('merk.index') }}">Merk</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="bi bi-pencil"></i> Form Edit Merk
                                    </h3>
                                    <div class="card-tools">
                                        <small class="text-muted">ID: {{ $merk->id }}</small>
                                    </div>
                                </div>

                                @include('merk.form', [
                                    'action' => route('merk.update', $merk->id),
                                    'method' => 'PUT',
                                    'submitText' => 'Update'
                                ])
                            </div>
                        </div>
                    </div>
@endsection
