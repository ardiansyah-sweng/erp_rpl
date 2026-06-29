@extends('layouts.app')

@section('title', 'Tambah Supplier')

@section('page-title')
<h3 class="mb-0">Tambah Supplier</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Tambah Supplier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <form id="picForm" action="{{ route('supplier.add') }}" method="POST" onsubmit="return false;">
            @csrf
            <div class="mb-3">
                <label for="supplier_id" class="form-label">ID Supplier</label>
                <input type="text" class="form-control" id="supplier_id" name="supplier_id" required>
                <span id="supplierIdError" class="error"></span>
            </div>
            <div class="mb-3">
                <label for="company_name" class="form-label">Nama Supplier</label>
                <input type="text" class="form-control" id="company_name" name="company_name" required>
                <span id="companynameError" class="error"></span>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Alamat Supplier</label>
                <input type="text" class="form-control" id="address" name="address" required>
                <span id="addressError" class="error"></span>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Telephone</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                <span id="phonenuberError" class="error"></span>
            </div>
            <div class="mb-3">
                <label for="bank_account" class="form-label">Rekening Bank</label>
                <input type="text" class="form-control" id="bank_account" name="bank_account" required>
                <span id="bankaccountError" class="error"></span>
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-primary" onclick="validateForm()">Add</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function validateForm() {
        let isValid = true;
        $('#supplierIdError').html("");
        $('#companynameError').html("");
        $('#addressError').html("");
        $('#phonenuberError').html("");
        $('#bankaccountError').html("");

        let supplierId = $('#supplier_id').val();
        let company_name = $('#company_name').val();
        let address = $('#address').val();
        let phone_number = $('#phone_number').val();
        let bank_account = $('#bank_account').val();

        if (supplierId === null || supplierId === "") {
            $('#supplierIdError').html("<span style=\"color: red;\">ID Supplier harus diisi.</span>");
            isValid = false;
        }
        if (company_name === null || company_name === "") {
            $('#companynameError').html("<span style=\"color: red;\">company name harus diisi.</span>");
            isValid = false;
        }
        if (address === null || address === "") {
            $('#addressError').html("<span style=\"color: red;\">Address harus diisi.</span>");
            isValid = false;
        }
        let phonePattern = /^\d{10,13}$/;
        if (!phonePattern.test(phone_number)) {
            $('#phonenuberError').html("<span style=\"color: red;\">Nomor Telephone harus diisi(10-13 digit).</span>");
            isValid = false;
        }
        if (bank_account === null || bank_account === "") {
            $('#bankaccountError').html("<span style=\"color: red;\">bank account harus diisi.</span>");
            isValid = false;
        }
        if (isValid) {
            document.getElementById('picForm').submit();
        }
        return isValid;
    }
</script>
@endpush
