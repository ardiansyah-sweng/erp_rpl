@extends('layouts.app')

@section('title', 'Item')

@section('page-title')
<h3 class="mb-0 me-2">Item</h3>
<a href="{{ route('item.add') }}" class="btn btn-primary btn-sm">Tambah</a>
<a href="{{ route('item.low-stock') }}" class="btn btn-warning btn-sm ms-2">
    <i class="bi bi-exclamation-triangle-fill me-1"></i> Low Stock Alert
</a>

<div class="dropdown ms-2">
    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="printOptionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-printer-fill me-1"></i> Cetak PDF
    </button>
    <ul class="dropdown-menu" aria-labelledby="printOptionsDropdown">
        <li><a class="dropdown-item" href="{{ route('item.report') }}">Semua Item</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><p class="dropdown-header">Berdasarkan Tipe Produk</p></li>
        <li><a class="dropdown-item" href="{{ url('/item/pdf/product/FG') }}">Finished Goods (FG)</a></li>
        <li><a class="dropdown-item" href="{{ url('/item/pdf/product/HFG') }}">Half-Finished Goods (HFG)</a></li>
        <li><a class="dropdown-item" href="{{ url('/item/pdf/product/RM') }}">Raw Materials (RM)</a></li>
    </ul>
</div>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Item</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    List Table
                    <span class="badge bg-secondary ms-2">Total Item: {{ $itemCount ?? 0 }}</span>
                </h3>
                <form action="{{ route('item.list') }}" method="GET" class="d-flex ms-auto">
                    <div class="input-group input-group-sm ms-auto" style="width: 450px;">
                        <input type="text" name="search" class="form-control" placeholder="Search Item" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
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
                            <th style="width: 10px">id</th>
                            <th>sku</th>
                            <th>item_name</th>
                            <th>unit_name</th>
                            <th>avg_base_price</th>
                            <th>selling_price</th>
                            <th>created_at</th>
                            <th>updated_at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($items as $item)
                        <tr id="row-{{ $item->id }}">
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->sku }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->unit?->unit_name ?? ($item->measurement ?? '-') }}</td>
                            <td>{{ $item->avg_base_price ?? '0' }}</td>
                            <td>{{ $item->selling_price }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->updated_at }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('item.delete', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">Delete</button>
                                </form>
                                <a href="{{ url('/item/' . $item->id) }}" class="btn btn-sm btn-info text-white">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No data available in table</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $items->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
