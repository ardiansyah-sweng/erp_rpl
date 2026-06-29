@extends('layouts.app')

@section('title', 'Category')

@section('page-title')
<h3 class="mb-0 me-2">Category</h3>
<a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">Tambah</a>
<a href="{{ route('category.print') }}" class="btn btn-primary btn-sm ms-2" target="_blank">Cetak Category</a>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Category</li>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-start">
        <div>
            <h3 class="card-title">List Table</h3>
            <div class="pt-4">
                <strong>Total Kategori: {{ $totalCategory }}</strong>
            </div>
        </div>
        <form action="{{ route('categories.index') }}" method="GET" class="d-flex ms-auto">
            <!-- Search bar berada di ujung kanan -->
            <div class="input-group input-group-sm ms-auto" style="width: 450px;">
                <input type="text" name="search" class="form-control" placeholder="Search Category" value="{{ $search ?? '' }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <table class="table table-bordered">
            <thead class="text-center">
                <tr>
                    <th style="width: 10px">ID</th>
                    <th>Category Name</th>
                    <th>Parent Category</th>
                    <th>Active</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>
                        <a href="{{ route('categories.show', $category->id) }}" style="color: inherit; text-decoration: none;">
                            {{ $category->category }}
                        </a>
                    </td>
                    <td>{{ $category->parent->category ?? 'No Parent' }}</td>
                    <td class="text-center">
                        @if($category->is_active == 1)
                            <i class="bi bi-check-circle-fill text-success"></i>
                        @else
                            <i class="bi bi-x-circle-fill text-danger"></i>
                        @endif
                    </td>
                    <td>{{ $category->created_at }}</td>
                    <td>{{ $category->updated_at }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" dusk="delete-category-{{ $category->id }}">Delete</button>
                        </form>
                        <a href="{{ route('categories.show', $category->id) }}" class="btn btn-info">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No data available in table</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 pb-3 d-none">
        <strong>Total Kategori: {{ $totalCategory }}</strong>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
        {{ $categories->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
