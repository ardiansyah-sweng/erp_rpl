@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('page-title')
    <h3 class="mb-0">Tambah Produk</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Tambah Produk</li>
@endsection

@section('content')
    <div class="row">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form id="productForm" action="{{ route('product.add') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                @csrf
                <div class="mb-3">
                    <label for="product_id" class="form-label">ID Produk</label>
                    <input type="text" class="form-control" id="product_id" name="product_id" value="{{ old('product_id') }}" maxlength="4" required>
                    <div class="invalid-feedback">ID Produk harus diisi.</div>
                </div>
                <div class="mb-3">
                    <label for="product_name" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name') }}" maxlength="35" required>
                    <div class="invalid-feedback">Nama Produk harus diisi.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="product_type" id="finished" value="FG" {{ old('product_type') == 'FG' ? 'checked' : '' }}>
                            <label class="form-check-label" for="finished">Finished Good</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="product_type" id="half_finished" value="HFG" {{ old('product_type') == 'HFG' ? 'checked' : '' }}>
                            <label class="form-check-label" for="half_finished">Half Finished Good</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="product_type" id="raw_material" value="RM" {{ old('product_type') == 'RM' ? 'checked' : '' }}>
                            <label class="form-check-label" for="raw_material">Raw Material</label>
                        </div>
                    </div>
                    <div class="invalid-feedback">Jenis produk harus dipilih.</div>
                </div>
                <div class="mb-3">
                    <label for="product_category" class="form-label">Kategori</label>
                    <select class="form-select" id="product_category" name="product_category" required>
                        <option value="" disabled {{ old('product_category') ? '' : 'selected' }}>-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('product_category') == $cat->id ? 'selected' : '' }}>{{ $cat->category }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Kategori harus dipilih.</div>
                </div>
                <div class="mb-3">
                    <label for="product_description" class="form-label">Deskripsi Produk</label>
                    <textarea class="form-control" id="product_description" name="product_description" rows="3">{{ old('product_description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Foto Produk (Opsional)</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/jpeg, image/png, image/jpg">
                    <small class="text-muted">Format yang diizinkan: JPG, JPEG, PNG. Maksimal ukuran: 2MB.</small>
                    @error('image')
                        <div class="text-danger mt-1" style="font-size: 0.875em;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-start mt-4">
                    <div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                        <button type="reset" class="btn btn-secondary ms-2">Batal</button>
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

        $('#product_id, #product_name, #product_category').removeClass('is-invalid');
        $('input[name="product_type"]').parent().parent().removeClass('is-invalid');

        const productId = $('#product_id').val().trim();
        const productName = $('#product_name').val().trim();
        const productType = $('input[name="product_type"]:checked').val();
        const category = $('#product_category').val();

        if (!productId) {
            $('#product_id').addClass('is-invalid');
            isValid = false;
        }

        if (!productName) {
            $('#product_name').addClass('is-invalid');
            isValid = false;
        }

        if (!productType) {
            $('input[name="product_type"]').parent().parent().addClass('is-invalid');
            isValid = false;
        }

        if (!category) {
            $('#product_category').addClass('is-invalid');
            isValid = false;
        }

        return isValid;
    }
</script>
@endpush
