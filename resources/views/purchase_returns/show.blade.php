@extends('layouts.app')

@section('title', 'Detail Retur Barang')

@section('page-title')
<h3 class="mb-0">Detail Retur Barang</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('purchase-returns.index') }}">Retur Barang</a></li>
<li class="breadcrumb-item active">{{ $purchaseReturn->return_number }}</li>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-4" id="returnDocument">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ $purchaseReturn->return_number }}</h3>
        <span class="badge bg-success">Stok sudah diperbarui</span>
    </div>
    <div class="card-body">
        <div class="row g-4 mb-4">
            <div class="col-md-4"><small class="text-muted">Nomor Retur</small><div class="fw-semibold">{{ $purchaseReturn->return_number }}</div></div>
            <div class="col-md-4"><small class="text-muted">Purchase Order</small><div class="fw-semibold">{{ $purchaseReturn->po_number }}</div></div>
            <div class="col-md-4"><small class="text-muted">Tanggal Retur</small><div class="fw-semibold">{{ $purchaseReturn->return_date->format('d M Y') }}</div></div>
            <div class="col-md-4"><small class="text-muted">Supplier</small><div class="fw-semibold">{{ $purchaseReturn->purchaseOrder?->supplier?->company_name ?? '-' }}</div></div>
            <div class="col-md-4"><small class="text-muted">SKU</small><div class="fw-semibold">{{ $purchaseReturn->product_id }}</div></div>
            <div class="col-md-4"><small class="text-muted">Nama Barang</small><div class="fw-semibold">{{ $purchaseReturn->item?->name ?? '-' }}</div></div>
            <div class="col-md-4"><small class="text-muted">Jumlah Retur</small><div class="fw-semibold">{{ number_format($purchaseReturn->quantity, 0, ',', '.') }}</div></div>
            <div class="col-md-4"><small class="text-muted">Alasan</small><div class="fw-semibold">{{ $purchaseReturn->reason }}</div></div>
            <div class="col-md-12"><small class="text-muted">Catatan</small><div>{{ $purchaseReturn->notes ?: '-' }}</div></div>
        </div>
    </div>
</div>

<a href="{{ route('purchase-returns.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
<button type="button" class="btn btn-success" onclick="window.print()"><i class="bi bi-printer me-1"></i> Cetak</button>
@endsection

@push('styles')
<style>
@media print {
    body * { visibility: hidden !important; }
    #returnDocument, #returnDocument * { visibility: visible !important; }
    #returnDocument {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
        border: 0 !important;
        box-shadow: none !important;
    }
}
</style>
@endpush
