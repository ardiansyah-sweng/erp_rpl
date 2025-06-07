@extends('pdf.layouts.report')

@section('title', 'Preview Daftar Produk')

@section('content')
<h1 style="text-align: center">Daftar Produk</h1>
<div class="meta-info" style="text-align: center">
    Generated on: {{ date('d/m/Y H:i') }}
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID Produk</th>
            <th>Nama Produk</th>
            <th>Tipe Produk</th>
            <th>Kategori</th>
            <th>Deskripsi</th>
            <th>Dibuat Pada</th>
            <th>Diperbarui Pada</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $index => $product)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $product->product_id }}</td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->product_type }}</td>
            <td>{{ $product->category ? $product->category->category : '-' }}</td>
            <td>{{ $product->product_description }}</td>
            <td>{{ $product->created_at ? date('d/m/Y H:i', strtotime($product->created_at)) : '-' }}</td>
            <td>{{ $product->updated_at ? date('d/m/Y H:i', strtotime($product->updated_at)) : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top: 2em; text-align: center">
    <a href="{{ route('product.pdf') }}" class="btn btn-success">
        Download PDF
    </a>
    <a href="{{ route('product.list') }}" class="btn btn-secondary">
        Back to List
    </a>
</div>
@endsection
