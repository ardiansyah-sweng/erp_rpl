@extends('layouts.app')

@section('title', 'Tambah Supplier Item')

@section('page-title')
<h3 class="mb-0">Tambah Supplier Item</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Tambah Supplier Item</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form id="picForm" action="{{ route('supplier.material.add') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="supplier_id" class="form-label">ID Supplier</label>
                <input type="text" class="form-control" id="supplier_id" name="supplier_id" required maxlength="6" onblur="fetchSupplierName()">
                <span id="supplierIdError" class="error"></span>
                <div class="invalid-feedback">ID Supplier harus diisi dan terdiri dari 6 karakter.</div>
            </div>
            <div class="mb-3">
                <label for="supplier_name" class="form-label">Nama Supplier</label>
                <input type="text" class="form-control" id="supplier_name" name="supplier_name" readonly required maxlength="255">
                <div class="invalid-feedback">Nama Supplier harus diisi.</div>
            </div>
            <div class="mb-3">
                <label for="company_name" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name" readonly required maxlength="255">
                <div class="invalid-feedback">Company Name harus diisi.</div>
            </div>
            <div class="mb-3">
                <label for="product_id" class="form-label">Product ID</label>
                <input type="text" class="form-control" id="product_id" name="product_id" required maxlength="50">
                <div class="invalid-feedback">Product ID harus diisi dan maksimal 50 karakter.</div>
            </div>
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required maxlength="255">
                <div class="invalid-feedback">Product Name harus diisi dan maksimal 255 karakter.</div>
            </div>
            <div class="mb-3">
                <label for="base_price" class="form-label">Base Price Rp:</label>
                <input type="number" class="form-control" id="base_price" name="base_price" required min="1">
                <div class="invalid-feedback">Base Price harus diisi dan lebih besar dari 0.</div>
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary" onclick="return validateForm()">Add</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function fetchSupplierName() {
        const supplierId = $('#supplier_id').val().trim();
        if (supplierId.length === 6) {
            $.ajax({
                url: '/api/supplier/' + supplierId,
                method: 'GET',
                success: function(data) {
                    $('#supplier_name').val(data.company_name);
                    $('#company_name').val(data.company_name);
                },
                error: function() {
                    $('#supplier_name').val('');
                    $('#company_name').val('');
                    alert('Supplier ID tidak ditemukan.');
                }
            });
        } else {
            $('#supplier_name').val('');
            $('#company_name').val('');
        }
    }

    function validateForm() {
        let isValid = true;

        $('#supplier_id, #product_id, #supplier_name, #company_name, #product_name, #base_price').removeClass('is-invalid');

        const supplierId = $('#supplier_id').val().trim();
        const supplierName = $('#supplier_name').val().trim();
        const companyName = $('#company_name').val().trim();
        const productId = $('#product_id').val().trim();
        const productName = $('#product_name').val().trim();
        const basePrice = parseFloat($('#base_price').val());

        if (!supplierId || supplierId.length !== 6) {
            $('#supplier_id').addClass('is-invalid');
            isValid = false;
        }

        if (!supplierName) {
            $('#supplier_name').addClass('is-invalid');
            isValid = false;
        }

        if (!companyName) {
            $('#company_name').addClass('is-invalid');
            isValid = false;
        }

        if (!productId) {
            $('#product_id').addClass('is-invalid');
            isValid = false;
        }

        if (!productName) {
            $('#product_name').addClass('is-invalid');
            isValid = false;
        }

        if (isNaN(basePrice) || basePrice <= 0) {
            $('#base_price').addClass('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            alert('Harap perbaiki semua error pada form sebelum melanjutkan.');
        }
        return isValid;
    }
</script>
@endpush
