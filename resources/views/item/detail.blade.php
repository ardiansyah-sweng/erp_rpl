@extends('layouts.app')

@section('title', 'Detail Produk')

@section('page-title')
<h3 class="mb-0">Detail Produk</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('item.list') }}">Item</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail Produk</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Informasi Produk
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">ID</th>
                        <td>{{ $item->id ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>Product ID</th>
                        <td>{{ $item->product_id ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>SKU</th>
                        <td>{{ $item->sku ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>Item Name</th>
                        <td>{{ $item->name ?? $item->item_name ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>Measurement Unit</th>
                        <td>{{ $item->measurement ?? $item->measurement_unit ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>Average Base Price</th>
                        <td>{{ $item->avg_base_price ?? $item->base_price ?? '0' }}</td>
                    </tr>
                    <tr>
                        <th>Selling Price</th>
                        <td>{{ $item->selling_price ?? '0' }}</td>
                    </tr>
                    <tr>
                        <th>Purchase Unit</th>
                        <td>{{ $item->purchase_unit ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>Sell Unit</th>
                        <td>{{ $item->sell_unit ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>Stock Unit</th>
                        <td>{{ $item->stock_unit ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $item->created_at ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $item->updated_at ?? 'Tidak ada data' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
