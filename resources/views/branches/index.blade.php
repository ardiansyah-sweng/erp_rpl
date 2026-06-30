@extends('layouts.app')

@section('title', 'Branch')

@section('page-title')
<h3 class="mb-0 me-2">Branch</h3>
<a href="{{ route('branches.create') }}" class="btn btn-primary btn-sm">Tambah</a>
<a href="{{ route('branches.index', ['export' => 'pdf']) }}" class="btn btn-primary btn-sm ms-2">Cetak Branch</a>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Branch</li>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">List Table <br>
            Total Branch : <strong>{{ $branches->total() }}</strong>
        </h3>
        <form action="{{ route('branches.index') }}" method="GET" class="d-flex ms-auto">
            <div class="input-group input-group-sm ms-auto" style="width: 450px;">
                <input type="text" name="search" class="form-control" placeholder="Search Branch">
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
                    <th style="width: 10px">id</th>
                    <th>Branch Name</th>
                    <th>Branch Address</th>
                    <th>Branch Telephone</th>
                    <th>Aktif</th>
                    <th>Created At</th>
                    <th>Updated At </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branches as $branch)
                <tr>
                    <td>{{ $branch->id }}</td>
                    <td>
                        <a href="{{ route('branch.detail', ['id' => $branch->id]) }}" style="color: inherit; text-decoration: none;">
                            {{ $branch->branch_name }}
                        </a>
                    </td>
                    <td>{{ $branch->branch_address }}</td>
                    <td>{{ $branch->branch_telephone }}</td>
                    <td class="text-center">
                        @if($branch->is_active == 1)
                            <i class="bi bi-check-circle-fill text-success"></i>
                        @else
                            <i class="bi bi-x-circle-fill text-danger"></i>
                        @endif
                    </td>
                    <td>{{ $branch->created_at }}</td>
                    <td>{{ $branch->updated_at }}</td>
                    <td>
                        <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus cabang ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" dusk="delete-branch-{{ $branch->id }}">Delete</button>
                        </form>
                        <a href="{{ url('/branch/detail/'.$branch->id) }}" class="btn btn-info">Detail</a>
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
    <!-- /.card-body -->
    <div class="card-footer clearfix">
        {{ $branches->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
