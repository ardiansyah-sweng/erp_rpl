@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('page-title')
<h3 class="mb-0">Tambah Produk</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/product/list">Produk</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Tambah Item Produk</h3>
            </div>
            <form action="{{ route('item.add') }}" method="POST" id="productForm">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="product_id">ID Produk</label>
                        <input type="text" class="form-control" id="product_id" name="product_id" value="{{ old('product_id') }}">
                    </div>

                    <div class="form-group">
                        <label for="sku">SKU</label>
                        <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}">
                    </div>

                    <div class="form-group">
                        <label for="item_name">Nama Item Produk</label>
                        <input type="text" class="form-control" id="item_name" name="item_name" value="{{ old('item_name') }}">
                    </div>

                    <div class="form-group">
                        <label for="measurement_unit">Unit</label>
                        <select class="form-select" id="measurement_unit" name="measurement_unit" required>
                            <option selected disabled value="">Choose...</option>
                            <option value="1">Bal</option>
                            <option value="2">Batang</option>
                            <option value="3">Botol</option>
                            <option value="4">Bungkus</option>
                            <option value="5">Butir</option>
                            <option value="6">Box</option>
                            <option value="7">Drum</option>
                            <option value="8">Dus</option>
                            <option value="9">Galon</option>
                            <option value="10">Gram</option>
                            <option value="11">Gross</option>
                            <option value="12">Karton</option>
                            <option value="13">Karung</option>
                            <option value="14">Kontainer</option>
                            <option value="15">Ikat</option>
                            <option value="16">Kaleng</option>
                            <option value="17">Kilogram</option>
                            <option value="18">Kodi</option>
                            <option value="19">Krat</option>
                            <option value="20">Kuintal</option>
                            <option value="21">Lusin</option>
                            <option value="22">Lembar</option>
                            <option value="23">Liter</option>
                            <option value="24">Miligram</option>
                            <option value="25">Mililiter</option>
                            <option value="26">Ons</option>
                            <option value="27">Orang</option>
                            <option value="28">Pack</option>
                            <option value="29">Pallet</option>
                            <option value="30">Pieces</option>
                            <option value="31">Resep</option>
                            <option value="32">Rim</option>
                            <option value="33">Sachet</option>
                            <option value="34">Slop</option>
                            <option value="35">Strip</option>
                            <option value="36">Tim</option>
                            <option value="37">Ton</option>
                            <option value="38">Unit</option>
                        </select>
                        <div class="invalid-feedback">Please select a valid unit.</div>
                    </div>

                    <div class="form-group">
                        <label for="selling_price">Harga Jual Rp.</label>
                        <input type="number" class="form-control" id="selling_price" name="selling_price" value="{{ old('selling_price') }}">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="button" class="btn btn-primary" onclick="validateForm()">Add</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectUnit(unit) {
    document.getElementById('measurement_unit').value = unit;
}

function validateForm() {
    let isValid = true;

    $('.error-message').remove();

    let productId = $('#product_id').val();
    let productSku = $('#sku').val();
    let productName = $('#item_name').val();
    let productPrice = $('#selling_price').val();

    if (productId.length !== 4 || productId === "") {
        $('#product_id').after("<div class='error-message'><span style='color: red;'>ID Produk harus terdiri dari 4 karakter.</span></div>");
        isValid = false;
    }

    if (productSku === null || productSku === "") {
        $('#sku').after("<div class='error-message'><span style='color: red;'>SKU harus diisi.</span></div>");
        isValid = false;
    }

    if (productName === null || productName === "") {
        $('#item_name').after("<div class='error-message'><span style='color: red;'>Nama Item Produk harus diisi.</span></div>");
        isValid = false;
    }

    if ($('#measurement_unit').val() === null || $('#measurement_unit').val() === "") {
        $('#measurement_unit').after("<div class='error-message'><span style='color: red;'>Unit harus dipilih.</span></div>");
        isValid = false;
    }

    let hargaPattern = /^\d+(\.\d{1,2})?$/;
    if (productPrice === "" || productPrice === null) {
        $('#selling_price').after("<div class='error-message'><span style='color: red;'>Harga Jual harus diisi.</span></div>");
        isValid = false;
    } else if (!hargaPattern.test(productPrice)) {
        $('#selling_price').after("<div class='error-message'><span style='color: red;'>Harga Jual harus berupa angka.</span></div>");
        isValid = false;
    }

    if (isValid) {
        document.getElementById('productForm').submit();
    }

    return isValid;
}
</script>
@endpush
