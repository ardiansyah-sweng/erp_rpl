@extends('layouts.app')

@section('title', 'Warehouse')

@section('page-title')
    <h3 class="mb-0 me-2">Warehouse</h3>
    <span class="btn btn-primary btn-sm me-2">Total Warehouse: {{ $warehouseCount ?? 0 }}</span>
    <a href="{{ route('warehouse.add') }}" class="btn btn-primary btn-sm">Tambah</a>
    <a href="{{ route('warehouse.report') }}" class="btn btn-primary btn-sm ms-2">Cetak Warehouse</a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Warehouse</li>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Summary Cards Jumlah Jenis Warehouse -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 fw-medium" style="font-size: 0.75rem; letter-spacing: 0.5px;">RM WAREHOUSE</p>
                            <h2 class="mb-0 fw-bold text-white" style="font-size: 2rem;">{{ $rmWarehouseCount }}</h2>
                            <p class="text-white-50 mb-0 mt-1" style="font-size: 0.7rem;">Raw Material Storage</p>
                        </div>
                        <div class="text-white opacity-50" style="font-size: 2.5rem;">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 mb-1 fw-medium" style="font-size: 0.75rem; letter-spacing: 0.5px;">FG WAREHOUSE</p>
                            <h2 class="mb-0 fw-bold text-white" style="font-size: 2rem;">{{ $fgWarehouseCount }}</h2>
                            <p class="text-white-50 mb-0 mt-1" style="font-size: 0.7rem;">Finished Goods Storage</p>
                        </div>
                        <div class="text-white opacity-50" style="font-size: 2.5rem;">
                            <i class="bi bi-archive-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Summary Cards -->

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">List Warehouse</h3>
            <form action="#" method="GET" class="d-flex ms-auto">
                <div class="input-group input-group-sm ms-auto" style="width: 450px;">
                    <input type="text" name="search" class="form-control" placeholder="Search Warehouse">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th style="width: 10px">No</th>
                        <th>Warehouse Name</th>
                        <th>Warehouse Address</th>
                        <th>Warehouse Telephone</th>
                        <th>RM Warehouse</th>
                        <th>FG Warehouse</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouses as $index => $item)
                        <tr>
                            <td>{{ $index + 1}} </td>
                            <td>{{ $item->warehouse_name }}</td>
                            <td>{{ $item->warehouse_address }}</td>
                            <td>{{ $item->warehouse_phone }}</td>
                            <td>
                                <span class="badge {{ $item->is_rm_warehouse ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $item->is_rm_warehouse ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $item->is_fg_warehouse ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $item->is_fg_warehouse ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->updated_at }}</td>
                            <td>
                                <a href="{{ route('warehouses.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('warehouses.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus warehouse ini?')">
                                        Delete
                                    </button>
                                </form>
                                <a href="{{ route('warehouse.detail', $item->id) }}" class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No data available in table</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $warehouses->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
