@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('page-title')
    <h3 class="mb-0">Detail Pelanggan</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            Informasi Pelanggan
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 30%">Nama Pelanggan</th>
                    <td>{{ $customer->customer_name ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $customer->customer_address ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Telepon</th>
                    <td>{{ $customer->customer_phone ?? 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if(isset($customer->is_active) && $customer->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Tidak Aktif</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Dibuat Pada</th>
                    <td>{{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i:s') : 'Tidak ada data' }}</td>
                </tr>
                <tr>
                    <th>Diperbarui Pada</th>
                    <td>{{ $customer->updated_at ? $customer->updated_at->format('d/m/Y H:i:s') : 'Tidak ada data' }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
