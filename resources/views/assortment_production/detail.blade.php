@extends('layouts.app')

@section('title', 'Detail Assortment Production')

@section('page-title')
<h3 class="mb-0">Detail Assortment Production</h3>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header bg-primary text-white">
        Informasi From Assortment Production
      </div>
      <div class="card-body">
        @if(isset($data->header))

        @if(isset($data->details) && count($data->details) > 0)
        <div class="mt-4">
          <h5 class="text-primary">Detail Items</h5>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Production Number</th>
                <th>Bom</th>
                <th>Quantity</th>
                <th>Description</th>
                <th>Created At</th>
                <th>Updated At</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data->details as $detail)
              <tr>
                <td>{{ $detail->id ?? 'N/A' }}</td>
                <td>{{ $detail->production_number ?? 'N/A' }}</td>
                <td>{{ $detail->bom_id ?? 'N/A' }}</td>
                <td>{{ $detail->bom_quantity ?? 'N/A' }}</td>
                <td>{{ $detail->description ?? 'N/A' }}</td>
                <td>{{ $detail->created_at ?? 'N/A' }}</td>
                <td>{{ $detail->updated_at ?? 'N/A' }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endif
        @else
        <div class="alert alert-warning">
          <strong>Data tidak ditemukan!</strong>
          <p>Detail produksi dengan nomor yang diminta tidak dapat ditemukan.</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
