@extends('layouts.app')

@section('title', 'Detail Return Barang')

@section('page-title')
    <h3 class="mb-0">Detail Return Barang</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('goods-returns.index') }}">Return Barang</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $goodsReturn->return_number }}</li>
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
                    <h3 class="card-title">Informasi {{ $goodsReturn->return_number }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 35%">Nomor Return</th>
                            <td>{{ $goodsReturn->return_number }}</td>
                        </tr>
                        <tr>
                            <th>Goods Receipt Note</th>
                            <td>GRN #{{ $goodsReturn->grn_id }}</td>
                        </tr>
                        <tr>
                            <th>Purchase Order</th>
                            <td>{{ $goodsReturn->po_number }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>{{ $goodsReturn->purchaseOrder?->supplier?->company_name ?? 'Tidak ada data' }}</td>
                        </tr>
                        <tr>
                            <th>Item</th>
                            <td>{{ $goodsReturn->product_id }} -
                                {{ $goodsReturn->item?->name ?? 'Nama item tidak ditemukan' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Return</th>
                            <td>{{ $goodsReturn->return_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Return</th>
                            <td>{{ number_format($goodsReturn->return_quantity, 0, ',', '.') }} unit</td>
                        </tr>
                        <tr>
                            <th>Alasan</th>
                            <td>{{ $goodsReturn->reason }}</td>
                        </tr>
                        <tr>
                            <th>Bukti Lampiran</th>
                            <td>
                                @if ($goodsReturn->bukti_lampiran)
                                    <<a href="{{ route('return.image', basename($goodsReturn->bukti_lampiran)) }}"
                                        target="_blank">
                                        <img src="{{ route('return.image', basename($goodsReturn->bukti_lampiran)) }}" ...>
                                        </a>
                                        <br>
                                        <small class="text-muted">Klik gambar untuk memperbesar</small>
                                    @else
                                        <span class="text-muted">Tidak ada lampiran</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Stok Item Saat Ini</th>
                            <td>{{ number_format($goodsReturn->item?->stock_unit ?? 0, 0, ',', '.') }} unit</td>
                        </tr>
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>{{ $goodsReturn->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('goods-returns.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('goods-returns.pdf', $goodsReturn->id) }}" class="btn btn-danger" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
