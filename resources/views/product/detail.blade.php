@extends('layouts.app')

@section('title', 'Detail Produk')

@section('page-title')
    <h3 class="mb-0">Detail Produk</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('product.list') }}">Produk</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            Informasi Produk
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 30%">ID</th>
                    <td>{{ $product->id ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Produk ID</th>
                    <td>{{ $product->product_id ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Produk Name</th>
                    <td>{{ $product->name ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Produk Type</th>
                    <td>{{ $product->type->label() ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Produk Category</th>
                    <td>{{ $product->categoryRelation->category ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Produk Description</th>
                    <td>{{ $product->description ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $product->created_at ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $product->updated_at ?? 'Tidak ada data' }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
