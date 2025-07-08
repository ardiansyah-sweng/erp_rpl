<<<<<<< HEAD
@extends('layouts.app') <!-- Ganti dengan layout utama Anda -->

@section('title', 'Daftar Kategori Produk')

@section('content')
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6 d-flex align-items-center">
        <h3 class="mb-0 me-2">Daftar Kategori Produk</h3>
        <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm">Tambah</a>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Kategori Produk</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header">
    <h3 class="card-title">Tabel Kategori Produk</h3>
  </div>
  <div class="card-body">
    <table class="table table-bordered text-center">
      <thead>
        <tr>
          <th>No.</th>
          <th>Kode</th>
          <th>Nama Kategori Produk</th>
          <th>Aktif</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($categories as $index => $category)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $category->code }}</td>
          <td>{{ $category->name }}</td>
          <td>
            @if ($category->is_active)
              <span class="text-success">✔</span>
            @else
              <span class="text-danger">✘</span>
            @endif
          </td>
          <td>
            <a href="{{ route('category.edit', $category->id) }}" class="btn btn-sm btn-primary">Edit</a>
            <form action="{{ route('category.destroy', $category->id) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori ini?')">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5">Tidak ada data kategori.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer clearfix">
    {{ $categories->links('pagination::bootstrap-4') }}
  </div>
</div>
@endsection
=======
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kategori</title>
</head>
<body>
    <h1>Daftar Kategori</h1>
    <ul>
        @forelse ($category as $item)
            <li>{{ $item->category }}</li>
        @empty
            <li>Tidak ada kategori ditemukan.</li>
        @endforelse
    </ul>
</body>
</html>
>>>>>>> 16bb8d3c9a112da28235b7b677973a7eb0432539
