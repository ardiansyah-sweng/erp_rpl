@extends('layouts.app')

@section('title', 'Pembayaran PO')

@section('page-title')
    <h3 class="mb-0 me-2">Pembayaran PO</h3>
    <a href="{{ route('po-payments.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Tambah Pembayaran
    </a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Pembayaran PO</li>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Pembayaran PO</h3>
            <form action="{{ route('po-payments.index') }}" method="GET" class="d-flex ms-auto">
                <div class="input-group input-group-sm" style="width: 360px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari PO, metode, atau catatan" value="{{ $search }}">
                    <button type="submit" class="btn btn-default">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Purchase Order</th>
                        <th>Supplier</th>
                        <th>Tanggal Bayar</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $index => $payment)
                        <tr>
                            <td class="text-center">{{ $payments->firstItem() + $index }}</td>
                            <td>{{ $payment->po_number }}</td>
                            <td>{{ $payment->purchaseOrder?->supplier?->company_name ?? '-' }}</td>
                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td class="text-end">{{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->method ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('po-payments.show', $payment->id) }}" class="btn btn-info btn-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($payments->hasPages())
            <div class="card-footer">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
@endsection
