@extends('layouts.app')

@section('title', 'Customer')

@section('page-title')
    <h3 class="mb-0 me-2">Customer</h3>
    <span class="btn btn-primary btn-sm me-2">Total Customer: {{ $customers->total() }}</span>
    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">Tambah</a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Customer</li>
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
            <h3 class="card-title">List Customer</h3>
            <form action="{{ route('customers.index') }}" method="GET" class="d-flex ms-auto">
                <div class="input-group input-group-sm ms-auto" style="width: 560px;">
                    <input type="text" name="search" class="form-control" placeholder="Search Customer" value="{{ $search ?? '' }}">
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
                        <th>Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->customer_name }}</td>
                            <td>{{ $item->customer_address }}</td>
                            <td>{{ $item->customer_phone }}</td>
                            <td>
                                <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('customers.show', $item->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('customers.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('customers.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                @if(($search ?? false) || ($status ?? false))
                                    Tidak ada pelanggan yang sesuai dengan filter
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
            {{ $customers->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
