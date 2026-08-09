@extends('layouts.app')

@section('title', 'Detail Cabang')

@section('page-title')
<h1>Detail Cabang</h1>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Informasi Cabang
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">Nama Cabang</th>
                        <td>{{ $branch->branch_name ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $branch->branch_address ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td>{{ $branch->branch_telephone ?? 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($branch->is_active)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Aktif
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle"></i> Tidak Aktif
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td>{{ $branch->created_at ? $branch->created_at->format('d/m/Y H:i:s') : 'Tidak ada data' }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Diupdate</th>
                        <td>{{ $branch->updated_at ? $branch->updated_at->format('d/m/Y H:i:s') : 'Tidak ada data' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
