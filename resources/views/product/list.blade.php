@php
use App\Helpers\EncryptionHelper;
@endphp
@extends('layouts.app')

@section('title', 'Produk')

@section('page-title')
    <h3 class="mb-0 me-2">Produk</h3>
    <span class="btn btn-primary btn-sm me-2">{{ $totalProducts }}</span>
    <a href="{{ route('product.add') }}" class="btn btn-primary btn-sm">Tambah</a>

    <div class="btn-group">
        <a href="{{ route('category.print') }}" target="_blank" class="btn btn-primary btn-sm">Cetak Kategori</a>
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" 
                data-bs-toggle="dropdown" aria-expanded="false">
        </button>
        <ul class="dropdown-menu">
            @foreach($categories->unique('category')->sortBy('category') as $cat)
                <li>
                    <a class="dropdown-item" href="{{ route('category.print.single', $cat->id) }}" target="_blank">
                        Cetak {{ $cat->category }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="dropdown d-inline-block ms-2">
        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="printProductsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Cetak PDF Produk
        </button>
        <ul class="dropdown-menu" aria-labelledby="printProductsDropdown">
            <li><a class="dropdown-item" href="{{ route('product.print.type', ['type' => 'ALL']) }}" target="_blank">Semua Produk</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('product.print.type', ['type' => 'FG']) }}" target="_blank">Finished Goods</a></li>
            <li><a class="dropdown-item" href="{{ route('product.print.type', ['type' => 'RM']) }}" target="_blank">Raw Material</a></li>
            <li><a class="dropdown-item" href="{{ route('product.print.type', ['type' => 'HFG']) }}" target="_blank">Half Finished Goods</a></li>
        </ul>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Produk</li>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header"><h3 class="card-title">List Table <br>
            Total Product : <strong>{{ $totalProducts }}</strong></h3>
        </div>
        <div class="card-body">
            <!--begin::Filter & Search Bar-->
            <form action="{{ route('product.list') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <div style="width: 220px;">
                    <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Pilih Jenis</option>
                        @foreach (\App\Enums\ProductType::cases() as $productType)
                            <option value="{{ $productType->value }}" {{ (isset($type) && $type == $productType->value) ? 'selected' : '' }}>
                                {{ $productType->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="width: 220px;">
                    <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories->unique('category')->sortBy('category') as $cat)
                            <option value="{{ $cat->id }}" {{ (isset($category) && $category == $cat->id) ? 'selected' : '' }}>
                                {{ $cat->category }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            <!--end::Filter & Search Bar-->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">id</th>
                        <th>product_id</th>
                        <th>product_name</th>
                        <th>product_type</th>
                        <th>product_category</th>
                        <th>product_description</th>
                        <th>jumlah_item</th>
                        <th>Created At</th>
                        <th>Updated At </th>
                        <th>Action </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $index => $product)
                    <tr class="align-middle">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <a href="/products/detail/{{ EncryptionHelper::encrypt($product->product_id) }}" class="text-dark"> 
                                {{ $product->product_id }}
                            </a>
                        </td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->type->label() }}</td>
                        <td>{{ $product->categoryRelation ? $product->categoryRelation->category : 'Tidak Ada' }}</td>
                        <td>{{ $product->product_description }}</td>
                        <td>{{ $product->items_count }}</td>
                        <td>{{ $product->created_at }}</td>
                        <td>{{ $product->updated_at }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">Edit</a>
                            <form method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus category ini?')">Delete</button>
                            </form>
                            <a href="#" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
