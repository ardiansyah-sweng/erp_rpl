@extends('layouts.app')

@section('title', 'Detail Order')

@section('page-title')
<h3 class="mb-0">Detail Order</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('purchase.orders') }}">Purchase Orders</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail Order</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title"> </h3>
      </div>
      <div class="card-body">
        <div id="purchase-order-details">
          <h6>ID Purchase Order</h6>
          <h4>{{ $purchaseOrder->first()->po_number }}</h4>
          <h6>Supplier</h6>
          <h4>{{ $purchaseOrder->first()->supplier->company_name }}</h4>
          <h6>Status</h6>
          <h4>{{ $purchaseOrder->first()->status }}</h4>
          <h6>Last Updated Status</h6>
          @php
            $poLength = app()->make('App\Http\Controllers\PurchaseOrderController')
                             ->getPOLength($purchaseOrder[0]->po_number, $purchaseOrder[0]->order_date);
          @endphp
          <h4>{{ $poLength }} Days</h4>
          <h6>Order Date</h6>
          <h4>{{ $purchaseOrder->first()->order_date }}</h4>
          <h6>Updated At</h6>
          <h4>{{ Carbon\Carbon::parse($purchaseOrder->first()->updated_at)->format('Y-m-d') }}</h4>

          <h6 class="mt-4">Purchase Order Details</h6>
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Product ID</th>
                  <th>Quantity</th>
                  <th>Amount</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($purchaseOrder->first()->details as $detail)
                  @php
                    $subtotal = $detail->quantity * $detail->amount;
                    $grandTotal += $subtotal;
                  @endphp
                  <tr>
                    <td>{{ $detail->product_id }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->amount, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                  <td><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
                </tr>
              </tfoot>
            </table>
          </div>
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
