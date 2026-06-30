@extends('layouts.app')

@section('title', 'Low Stock Alert')

@section('page-title')
<h3 class="mb-0 me-2">
    <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
    Low Stock Alert
</h3>
@if($lowStockCount > 0)
    <span class="badge bg-danger ms-2">{{ $lowStockCount }} item</span>
@else
    <span class="badge bg-success ms-2">Stok Aman</span>
@endif
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('item.list') }}">Item</a></li>
<li class="breadcrumb-item active" aria-current="page">Low Stock Alert</li>
@endsection

@section('content')
@if($lowStockCount > 0)
<div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
    <div>
        Terdapat <strong>{{ $lowStockCount }} item</strong> yang stoknya berada di bawah atau sama dengan batas minimum. Segera lakukan restock atau buat Purchase Order.
    </div>
</div>
@endif

<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">
            Daftar Item Stok Rendah
            <span class="badge bg-secondary ms-2">Total: {{ $lowStockCount }}</span>
        </h3>
        <div class="card-tools">
            <a href="{{ route('purchase.orders') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-cart-plus me-1"></i> Buat Purchase Order
            </a>
            <a href="{{ route('item.list') }}" class="btn btn-sm btn-secondary ms-1">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Item
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="text-center table-warning">
                <tr>
                    <th style="width: 10px">No</th>
                    <th>SKU</th>
                    <th>Nama Item</th>
                    <th>Satuan</th>
                    <th>Stok Saat Ini</th>
                    <th>Stok Minimum</th>
                    <th>Selisih</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $item->sku }}</strong></td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->unit?->unit_name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $item->stock_unit == 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                            {{ $item->stock_unit }}
                        </span>
                    </td>
                    <td>{{ $item->minimum_stock }}</td>
                    <td>
                        @php $selisih = $item->stock_unit - $item->minimum_stock; @endphp
                        <span class="text-danger fw-bold">{{ $selisih }}</span>
                    </td>
                    <td>
                        @if($item->stock_unit == 0)
                            <span class="badge bg-danger">Habis</span>
                        @else
                            <span class="badge bg-warning text-dark">Stok Rendah</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-success">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        Semua item memiliki stok di atas batas minimum. Tidak ada peringatan stok rendah.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
