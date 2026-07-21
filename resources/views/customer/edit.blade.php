@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('page-title')
    <h3 class="mb-0">Edit Pelanggan</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulir Edit Pelanggan</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mx-3 mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success mx-3 mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mx-3 mt-3">
                        {{ session('error') }}
                    </div>
                @endif

                @include('customer.form', [
                    'action' => route('customers.update', $customer->id),
                    'method' => 'PUT',
                    'customer' => $customer
                ])
            </div>
        </div>
    </div>
@endsection
