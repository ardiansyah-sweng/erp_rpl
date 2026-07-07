@extends('layouts.app')

@section('title', 'Kelola User')

@section('page-title')
    <h3 class="mb-0 me-2">Kelola User</h3>
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Tambah</a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Kelola User</li>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">List User</h3>
            <form action="{{ route('users.index') }}" method="GET" class="d-flex ms-auto">
                <div class="input-group input-group-sm ms-auto" style="width: 320px;">
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari nama/email" value="{{ $search ?? '' }}">
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
                        <th style="width: 10px">ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role?->label() ?? '-' }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                @if($search ?? false)
                                    Tidak ada user yang sesuai dengan pencarian
                                @else
                                    Belum ada data user
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            @if(isset($users) && method_exists($users, 'links'))
                {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
            @endif
        </div>
    </div>
@endsection
