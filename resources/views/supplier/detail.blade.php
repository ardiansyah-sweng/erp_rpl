@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('page-title')
<h3 class="mb-0">Filled Form Supplier</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Edit Supplier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(isset($error))
        <div class="alert alert-danger">
            {{ $error }}
        </div>
        @endif

        @if($sup)
        <form method="POST" action="{{ route('supplier.updateSupplier', $sup->supplier_id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">ID Supplier</label>
                <input type="text" class="form-control" value="{{ $sup->supplier_id }}" disabled>
                <input type="hidden" name="supplier_id" value="{{ $sup->supplier_id }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Perusahaan</label>
                <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $sup->company_name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $sup->address) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">No. Telepon</label>
                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $sup->phone_number) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Bank Account</label>
                <input type="text" name="bank_account" class="form-control" value="{{ old('bank_account', $sup->bank_account) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ url()->current() }}" class="btn btn-secondary">Batal</a>
        </form>
        @endif
    </div>
</div>
@endsection
