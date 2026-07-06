@extends('layouts.app')

@section('title', 'Detail Material')

@section('page-title')
<h3 class="mb-0">Detail Material</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Supplier Material</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="card">
                    <div class="card-header">
                        <h3>Detail Material</h3>
                    </div>
                    <div class="card-body">
                        @if($material)
                        <div id="viewMode">
                            <table class="table table-striped">
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $material->id }}</td>
                                </tr>
                                <tr>
                                    <th>Supplier ID</th>
                                    <td>{{ $material->supplier_id }}</td>
                                </tr>
                                <tr>
                                    <th>Company Name</th>
                                    <td>{{ $material->company_name }}</td>
                                </tr>
                                <tr>
                                    <th>Product ID</th>
                                    <td>{{ $material->product_id }}</td>
                                </tr>
                                <tr>
                                    <th>Product Name</th>
                                    <td>{{ $material->product_name }}</td>
                                </tr>
                                <tr>
                                    <th>Base Price</th>
                                    <td>Rp {{ number_format($material->base_price, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $material->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $material->updated_at }}</td>
                                </tr>
                            </table>
                            <div class="btn-group mt-3" role="group">
                                <button type="button" class="btn btn-primary" onclick="toggleEditMode()">Edit</button>
                                <a href="/supplier/material" class="btn btn-secondary">Kembali ke Daftar</a>
                            </div>
                        </div>

                        <div id="editMode" style="display: none;">
                            <h4 class="mb-3">Edit Material</h4>
                            <form action="{{ route('supplier.material.update', $material->id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_supplier_id" class="form-label">Supplier ID (Read-only)</label>
                                        <input type="text" class="form-control" id="edit_supplier_id" value="{{ $material->supplier_id }}" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_company_name" class="form-label">Company Name (Read-only)</label>
                                        <input type="text" class="form-control" id="edit_company_name" value="{{ $material->company_name }}" disabled>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_product_id" class="form-label">Product ID (Read-only)</label>
                                        <input type="text" class="form-control" id="edit_product_id" value="{{ $material->product_id }}" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_product_name" name="product_name" value="{{ $material->product_name }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_base_price" class="form-label">Base Price <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="edit_base_price" name="base_price" value="{{ $material->base_price }}" min="0" required>
                                </div>

                                <div class="alert alert-info">
                                    <small><strong>Catatan:</strong> Supplier ID, Company Name, dan Product ID tidak dapat diubah (read-only)</small>
                                </div>

                                <div class="btn-group mt-3" role="group">
                                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                    <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">Batal</button>
                                </div>
                            </form>
                        </div>
                        @else
                            <div class="alert alert-danger">Data tidak ditemukan</div>
                            <a href="/supplier/material" class="btn btn-secondary">Kembali ke Daftar</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleEditMode() {
        const viewMode = document.getElementById('viewMode');
        const editMode = document.getElementById('editMode');

        if (viewMode.style.display === 'none') {
            viewMode.style.display = 'block';
            editMode.style.display = 'none';
        } else {
            viewMode.style.display = 'none';
            editMode.style.display = 'block';
        }
    }
</script>
@endpush
