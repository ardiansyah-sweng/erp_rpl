@extends('layouts.app')

@section('title', 'Detail Warehouse')

@section('page-title')
    <h3 class="mb-0">Detail Warehouse</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('warehouses.index') }}">Warehouse</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            Informasi Warehouse
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 30%">Nama Warehouse</th>
                    <td>{{ $warehouse->warehouse_name ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $warehouse->warehouse_address ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Telepon</th>
                    <td>{{ $warehouse->warehouse_phone ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Warehouse Raw Material</th>
                    <td>
                        @if(isset($warehouse->is_rm_warehouse) && $warehouse->is_rm_warehouse)
                            <span class="badge bg-success">Ya</span>
                        @else
                            <span class="badge bg-secondary">Tidak</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Warehouse Finished Goods</th>
                    <td>
                        @if(isset($warehouse->is_fg_warehouse) && $warehouse->is_fg_warehouse)
                            <span class="badge bg-success">Ya</span>
                        @else
                            <span class="badge bg-secondary">Tidak</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if(isset($warehouse->is_active) && $warehouse->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Tidak Aktif</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Dibuat Pada</th>
                    <td>{{ $warehouse->created_at ? $warehouse->created_at->format('d/m/Y H:i:s') : 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Diperbarui Pada</th>
                    <td>{{ $warehouse->updated_at ? $warehouse->updated_at->format('d/m/Y H:i:s') : 'Tidak ada data' }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
