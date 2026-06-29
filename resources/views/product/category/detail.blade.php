@extends('layouts.app')

@section('title', 'Detail Category')

@section('page-title')
    <h3 class="mb-0">Detail Category</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('category.list') }}">Category Product</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            Detail Kategori
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>ID Kategori</th>
                    <td>{{ $category->id ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Nama Kategori</th>
                    <td>{{ $category->category ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Parent ID</th>
                    <td>{{ $category->parent_id ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $category->created_at ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $category->updated_at ?? 'Tidak ada data' }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
