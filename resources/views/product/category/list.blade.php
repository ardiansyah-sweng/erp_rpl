@extends('layouts.app')

@section('title', 'Category Product')

@section('page-title')
    <h3 class="mb-0 me-2">Category Product</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Category Product</li>
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
        </div>
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
                        <th style="width: 10px">No</th>
                        <th>Kode</th>
                        <th>Nama Kategori</th>
                        <th>Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($category as $kategori)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kategori->id }}</td>
                            <td>{{ $kategori->category }}</td>
                            <td class="text-center">
                                @if($kategori->active)
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @else
                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('category.edit', $kategori->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('category.delete', $kategori->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $category->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
