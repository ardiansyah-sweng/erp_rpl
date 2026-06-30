@extends('layouts.app')

@section('title', 'Supplier Material')

@section('page-title')
<h3 class="mb-0 me-2">Supplier Material</h3>
<a href="/supplier/material/add" class="btn btn-primary btn-sm">Tambah</a>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Supplier Material</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header"><h3 class="card-title">List Table</h3></div>
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
                    <thead>
                        <tr>
                            <th style="width: 10px">id</th>
                            <th>supplier_id</th>
                            <th>company_name</th>
                            <th>product_id</th>
                            <th>product_name</th>
                            <th>base_price</th>
                            <th>Created_at</th>
                            <th>Updated_at </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materials as $index => $material)
                        <tr class="align-middle">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $material->supplier_id }}</td>
                            <td>{{ $material->company_name }}</td>
                            <td>{{ $material->product_id }}</td>
                            <td>{{ $material->product_name }}</td>
                            <td>{{ $material->base_price }}</td>
                            <td>{{ $material->created_at }}</td>
                            <td>{{ $material->updated_at }}</td>

                            <td>
                                <a href="{{ url('/supplier/material/' . $material->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('supplier.material.delete', $material->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data supplier material ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                <a href="{{ url('/supplier/material/' . $material->id) }}" class="btn btn-sm btn-info">Detail</a>
                            </td>

                            <td>
                                <a href="{{ url('/supplier/' . $material->supplier_id . '/cetak-pdf') }}"
                                    class="btn btn-danger btn-sm"
                                    target="_blank">
                                    Cetak PDF
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $materials->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
