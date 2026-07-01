@extends('layouts.app')

@section('title', 'Retur Barang')

@section('page-title')
<h3 class="mb-0 me-3">Retur Barang</h3>
<a href="{{ route('purchase-returns.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-1"></i> Tambah Retur
</a>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active">Retur Barang</li>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header">
        <form action="{{ route('purchase-returns.index') }}" method="GET" class="d-flex ms-auto" style="max-width: 450px;">
            <div class="input-group input-group-sm">
                <input type="text" name="keyword" class="form-control" value="{{ $keyword }}"
                       placeholder="Cari nomor retur, PO, SKU, item, atau supplier">
                <button class="btn btn-default" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped mb-0">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nomor Retur</th>
                    <th>PO</th>
                    <th>Supplier</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Alasan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($returns as $return)
                    <tr class="align-middle">
                        <td>{{ $returns->firstItem() + $loop->index }}</td>
                        <td>{{ $return->return_number }}</td>
                        <td>{{ $return->po_number }}</td>
                        <td>{{ $return->purchaseOrder?->supplier?->company_name ?? '-' }}</td>
                        <td>{{ $return->product_id }} - {{ $return->item?->name ?? '-' }}</td>
                        <td>{{ number_format($return->quantity, 0, ',', '.') }}</td>
                        <td>{{ $return->return_date->format('d M Y') }}</td>
                        <td>{{ $return->reason }}</td>
                        <td>
                            <a href="{{ route('purchase-returns.show', $return) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center py-4">Belum ada data retur barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($returns->hasPages())
        <div class="card-footer">{{ $returns->links('pagination::bootstrap-4') }}</div>
    @endif
</div>
@endsection
