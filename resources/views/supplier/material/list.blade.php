@extends('layouts.app')

@section('title', 'Supplier Material')

@section('page-title')
    <h3 class="mb-0 me-2">Supplier Material</h3>
    <a href="/supplier/material/add" class="btn btn-primary btn-sm">Tambah</a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Supplier Material</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title">List Supplier Material</h3>
                        <div class="mt-1">
                            <span class="card-title">Jumlah Supplier Material: {{ $materials->total() }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <!-- BEGIN: FITUR FILTER & CETAK -->
                    <form method="GET" action="/supplier/material/list" class="mb-4">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Pencarian</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari perusahaan/produk..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel-fill"></i> Filter
                                </button>
                                <a href="/supplier/material/list" class="btn btn-secondary">Reset</a>

                                <button type="submit" formaction="/supplier/material/cetak-filter"
                                    class="btn btn-danger float-end ms-2" target="_blank">
                                    <i class="bi bi-file-earmark-pdf-fill"></i> Cetak Laporan
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- END: FITUR FILTER & CETAK -->

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">id</th>
                                    <th>supplier_id</th>
                                    <th>company_name</th>
                                    <th>product_id</th>
                                    <th>product_name</th>
                                    <th>base_price</th>
                                    <th>Created_at</th>
                                    <th>Updated_at </th>
                                    <th>Action</th>
                                    <th>Cetak</th> <!-- Tambahan agar tabel sejajar -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($materials as $index => $material)
                                    <tr class="align-middle">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $material->supplier_id }}</td>
                                        <td>{{ $material->company_name }}</td>
                                        <td>{{ $material->product_id }}</td>
                                        <td>{{ $material->product_name }}</td>
                                        <td>{{ $material->base_price }}</td>
                                        <td>{{ $material->created_at }}</td>
                                        <td>{{ $material->updated_at }}</td>

                                        <td>
                                            <a href="{{ url('/supplier/material/' . $material->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                            <a href="{{ url('/supplier/material/' . $material->id) }}"
                                                class="btn btn-sm btn-info">Detail</a>
                                        </td>

                                        <td>
                                            <a href="{{ url('/supplier/' . $material->supplier_id . '/cetak-pdf') }}"
                                                class="btn btn-danger btn-sm" target="_blank">
                                                Cetak PDF
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    {{ $materials->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
