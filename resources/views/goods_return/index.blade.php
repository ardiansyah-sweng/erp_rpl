@extends('layouts.app')

@section('title', 'Return Barang')

@section('page-title')
    <h3 class="mb-0 me-2">Return Barang</h3>
    <a href="{{ route('goods-returns.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Tambah Return
    </a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Return Barang</li>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Return Barang</h3>
            <form action="{{ route('goods-returns.index') }}" method="GET" class="d-flex ms-auto">
                <div class="input-group input-group-sm" style="width: 360px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari PO, SKU, item, atau alasan" value="{{ $search }}">
                    <button type="submit" class="btn btn-default">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nomor Return</th>
                        <th>Purchase Order</th>
                        <th>Supplier</th>
                        <th>Item</th>
                        <th>Tanggal Return</th>
                        <th>Jumlah</th>
                        <th>Alasan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($goodsReturns as $index => $goodsReturn)
                        <tr>
                            <td class="text-center">{{ $goodsReturns->firstItem() + $index }}</td>
                            <td>{{ $goodsReturn->return_number }}</td>
                            <td>{{ $goodsReturn->po_number }}</td>
                            <td>{{ $goodsReturn->purchaseOrder?->supplier?->company_name ?? '-' }}</td>
                            <td>
                                {{ $goodsReturn->product_id }}<br>
                                <small class="text-secondary">{{ $goodsReturn->item?->name ?? 'Nama item tidak ditemukan' }}</small>
                            </td>
                            <td>{{ $goodsReturn->return_date->format('d/m/Y') }}</td>
                            <td class="text-center">{{ number_format($goodsReturn->return_quantity, 0, ',', '.') }}</td>
                            <td>{{ $goodsReturn->reason }}</td>
                            <td class="text-center">
                                <a href="{{ route('goods-returns.show', $goodsReturn->id) }}" class="btn btn-info btn-sm">
                                    Detail
                                </a>
                                <a href="{{ route('goods-returns.pdf', $goodsReturn->id) }}" class="btn btn-danger btn-sm" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada data return barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($goodsReturns->hasPages())
            <div class="card-footer">
                {{ $goodsReturns->links() }}
            </div>
        @endif
    </div>
@endsection
