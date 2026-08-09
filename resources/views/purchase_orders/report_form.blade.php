@extends('layouts.app')

@section('title', 'Laporan Purchase Order')

@section('page-title')
<h3 class="mb-0">Laporan Purchase Order</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('purchase.orders') }}">Purchase Orders</a></li>
<li class="breadcrumb-item active" aria-current="page">Laporan</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Form Laporan Purchase Order</h3>
      </div>
      <form action="{{ route('purchase_orders.export') }}" method="POST">
      @csrf
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date">Tanggal Mulai</label>
              <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
              @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date">Tanggal Akhir</label>
              <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
              @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        
        <div class="form-group mb-4">
          <label for="supplier_id">Supplier</label>
          <select name="supplier_id" id="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
            <option value="">Semua Supplier</option>
            @foreach($suppliers as $supplier)
              <option value="{{ $supplier->supplier_id }}">{{ $supplier->company_name }}</option>
            @endforeach
          </select>
          @error('supplier_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="card-footer">
        <button type="submit" name="export_type" value="pdf" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i> Generate PDF</button>
        <button type="submit" name="export_type" value="excel" class="btn btn-success ms-2"><i class="bi bi-file-earmark-excel me-1"></i> Download Excel</button>
        <button type="submit" name="export_type" value="csv" class="btn btn-info ms-2"><i class="bi bi-filetype-csv me-1"></i> Download CSV</button>
        
        <a href="{{ route('purchase.orders') }}" class="btn btn-secondary ms-2"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
      </div>
    </form>
    </div>
  </div>
</div>
@endsection
