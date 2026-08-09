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

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">List Warehouse</h3>
            <form action="{{ route('warehouses.index') }}" method="GET" class="d-flex ms-auto">
                <div class="input-group input-group-sm ms-auto" style="width: 560px;">
                    <input type="text" name="search" class="form-control" placeholder="Search Warehouse" value="{{ $search ?? '' }}">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
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
                            <td colspan="10" class="text-center">
                                @if(($search ?? false) || ($status ?? false))
                                    Tidak ada warehouse yang sesuai dengan filter
                                @else
                                    No data available in table
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $warehouses->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
