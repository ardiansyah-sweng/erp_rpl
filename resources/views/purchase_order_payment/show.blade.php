@extends('layouts.app')

@section('title', 'Detail Pembayaran PO')

@section('page-title')
    <h3 class="mb-0">Detail Pembayaran PO</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('po-payments.index') }}">Pembayaran PO</a></li>
    <li class="breadcrumb-item active" aria-current="page">#{{ $payment->id }}</li>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Informasi Pembayaran #{{ $payment->id }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 35%">Purchase Order</th>
                            <td>{{ $payment->po_number }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>{{ $payment->purchaseOrder?->supplier?->company_name ?? 'Tidak ada data' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pembayaran</th>
                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Dibayar</th>
                            <td>{{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Metode</th>
                            <td>{{ $payment->method ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $payment->note ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Total PO</th>
                            <td>{{ number_format($payment->purchaseOrder?->total ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Total Sudah Dibayar</th>
                            <td>{{ number_format($paidAmount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Sisa Tagihan</th>
                            <td>
                                @if($remainingAmount <= 0)
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    {{ number_format($remainingAmount, 0, ',', '.') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Dicatat Pada</th>
                            <td>{{ $payment->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('po-payments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
