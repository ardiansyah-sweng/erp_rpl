@extends('layouts.app')

@section('title', 'Detail Goods Receipt Note')

@section('page-title')
<h3 class="mb-0">Detail Goods Receipt Note</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('purchase.orders') }}">Purchase Orders</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail Goods Receipt Note</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title"> </h3>
      </div>
      <div class="card-body">
        <h6>ID Purchase Order</h6>
        <h4>PO0011</h4>

        <h6>Supplier</h6>
        <h4>PT MSIG Limas Topindo</h4>

        <h6>Status</h6>
        <h4>Partially Delivered</h4>

        <h6>Last Updated Status</h6>
        <h4>113 Days</h4>

        <h6>Order Date</h6>
        <h4>2025-03-10</h4>

        <h6>Updated At</h6>
        <h4>2025-07-01</h4>

        <h6 class="mt-4">Goods Receipt Note List</h6>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Product ID</th>
                <th>Delivery Date</th>
                <th>Delivery Quantity</th>
                <th>Comments</th>
                <th>Created At</th>
              </tr>
            </thead>
            <tbody>
              @php
                $dummyGRN = [
                  [
                    'id' => 1,
                    'po_number' => 'PO0001',
                    'product_id' => 'P001-est',
                    'delivery_date' => '2025-03-29',
                    'delivery_quantity' => 140,
                    'comments' => 'Praesentium eos aut tempore eum neque.',
                    'created_at' => '2025-03-29 16:23:33',
                  ],
                  [
                    'id' => 2,
                    'po_number' => 'PO0001',
                    'product_id' => 'P001-et',
                    'delivery_date' => '2025-04-12',
                    'delivery_quantity' => 14,
                    'comments' => 'Ab accusantium minus repellendus expedita blanditiis voluptatem.',
                    'created_at' => '2025-04-12 06:00:59',
                  ],
                  [
                    'id' => 3,
                    'po_number' => 'PO0001',
                    'product_id' => 'P001-numquam',
                    'delivery_date' => '2025-05-05',
                    'delivery_quantity' => 124,
                    'comments' => 'Rerum doloribus autem voluptatem temporibus.',
                    'created_at' => '2025-05-05 16:05:06',
                  ],
                  [
                    'id' => 4,
                    'po_number' => 'PO0001',
                    'product_id' => 'P001-rem',
                    'delivery_date' => '2025-03-09',
                    'delivery_quantity' => 218,
                    'comments' => 'Voluptates non ut consequatur qui mollitia veritatis cupiditate.',
                    'created_at' => '2025-03-09 11:52:36',
                  ],
                ];
              @endphp

              @foreach ($dummyGRN as $grn)
                <tr>
                  <td>{{ $grn['id'] }}</td>
                  <td>{{ $grn['product_id'] }}</td>
                  <td>{{ $grn['delivery_date'] }}</td>
                  <td>{{ $grn['delivery_quantity'] }}</td>
                  <td>{{ $grn['comments'] }}</td>
                  <td>{{ $grn['created_at'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div id="debug-output" class="mt-4" style="display: none;">
        <div class="card">
          <div class="card-body bg-light">
            <pre id="dd-content" class="p-3 bg-dark text-light" style="border-radius: 5px;"></pre>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
