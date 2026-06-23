@extends('layouts.app')

@php
use App\Helpers\EncryptionHelper;
@endphp

@section('title', 'Purchase Orders')

@section('page-title')
<h3 class="mb-0 me-2">Purchase Orders <span class="badge bg-primary ms-2">{{ $totalOrders }}</span></h3>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPurchaseOrderModal">Add</button>
<a href="{{ route('purchase_orders.report_form') }}" class="btn btn-primary ms-2">Cetak PDF</a>
<!-- Modal -->
<div class="modal fade" id="addPurchaseOrderModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Add Purchase Order</h5>
      </div>
      <div class="modal-body">
        <form id="purchaseOrderForm">
          <!-- PO Number -->
          <div class="form-group">
            <label for="po_number">PO Number</label>
            <input type="text" class="form-control" id="po_number" value="PO0001" readonly>
          </div>
          <div class="form-group">
            <label for="branch">Cabang</label>
            <select class="form-control" id="branch">
              <option value="">Pilih Cabang</option>
              <option value="Yogyakarta">Yogyakarta</option>
              <option value="Jakarta">Jakarta</option>
              <option value="Surakarta">Surakarta</option>
              <option value="Bogor">Bogor</option>
              <option value="Surabaya">Surabaya</option>
            </select>
          </div>
          <!-- Supplier ID dan Nama Supplier -->
          <div class="form-group">
            <label for="supplier_id">ID Supplier</label>
            <input type="text" id="supplierSearch" class="form-control" placeholder="Cari Supplier">
            <select class="form-control" id="supplier_id" size="5" style="display:none;">
              @foreach($suppliers as $supplier)
              <option value="{{ $supplier->supplier_id }}">{{ $supplier->supplier_id }} - {{ $supplier->company_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="supplier_name">Nama Supplier</label>
            <input type="text" class="form-control" id="supplier_name" readonly>
          </div>

          <table class="table" id="itemsTable">
            <thead>
              <tr>
                <th>SKU</th>
                <th>Nama Item</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <input type="text" class="form-control sku-search" placeholder="Cari SKU">
                  <select class="form-control sku-dropdown" size="5" style="display:none;"></select>
                </td>
                <td><input type="text" class="form-control nama-item" readonly></td>
                <td><input type="number" class="form-control qty" value="1"></td>
                <td><input type="number" class="form-control unit-price" value="0"></td>
                <td><input type="number" class="form-control amount" value="0" readonly></td>
                <td><button type="button" class="btn btn-danger remove">Hapus</button></td>
              </tr>
            </tbody>
          </table>
          <button type="button" id="addRow" class="btn btn-info mb-3">Tambah Barang</button>

          <div class="form-group">
            <label>Sub Total Rp.</label>
            <input type="text" class="form-control" id="subtotal" readonly>
          </div>
          <div class="form-group">
            <label>Tax Rp.</label>
            <input type="text" class="form-control" id="tax" readonly>
          </div>

          <button type="button" id="submitBtn" class="btn btn-primary">Add</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Purchase Orders</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <label for="statusFilter" class="form-label me-2 mb-0 fw-normal"></label>
          <div style="width: 220px;">
            <select id="statusFilter" class="form-select form-select-sm">
              <option value="">Pilih Status...</option>
              <option value="all">Tampilkan Semua</option>
              @php
                $statuses = ['Submitted', 'Approved', 'In Review', 'Revised', 'Closed', 'Cancelled', 'Draft', 'Fully Delivered'];
              @endphp
              @foreach ($statuses as $stat)
                <option value="{{ $stat }}" {{ (isset($status) && $status == $stat) ? 'selected' : '' }}>
                  {{ $stat }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <form action="{{ route('purchase_orders.search') }}" method="GET" class="d-flex ms-auto">
          <div class="input-group input-group-sm ms-auto" style="width: 450px;">
            <input type="text" name="keyword" class="form-control" placeholder="Search Purchase Order" value="{{ request('keyword') }}">
            <div class="input-group-append">
              <button type="submit" class="btn btn-default">
                <i class="bi bi-search"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th style="width: 10px">No</th>
              <th>PO Number</th>
              <th>Supplier</th>
              <th>Total</th>
              <th>Order Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @isset($purchaseOrders)
            @forelse($purchaseOrders as $index => $order)
            <tr class="align-middle">
              <td>{{ $index + 1 }}</td>
              <td><a href="/purchase_orders/detail/{{ EncryptionHelper::encrypt($order->po_number) }}">{{ $order->po_number }}</a></td>
              <td><a href="#">{{ $order->supplier ? $order->supplier->company_name : 'Supplier not found' }}</a></td>
              <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
              <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
              <td>{{ $order->status }}</td>
              <td>
                <a href="#" class="btn btn-sm btn-primary">Edit</a>
                <a href="#" class="btn btn-sm btn-danger">Delete</a>
                <a href="/purchase_orders/detail/{{ EncryptionHelper::encrypt($order->po_number) }}" class="btn btn-sm btn-info">Detail</a>
                <a href="goods_receipt_note/add" class="btn btn-sm btn-warning">GRN</a>
                <a href="goods_receipt_note/detail" class="btn btn-sm btn-success">Detail GRN</a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="py-3 px-6 text-center">Tidak ada data purchase order.</td>
            </tr>
            @endforelse
            @endisset
          </tbody>
        </table>
      </div>
      <div class="card-footer clearfix">
        {{ $purchaseOrders->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  var suppliers = {
    @foreach($suppliers as $supplier)
    "{{ $supplier->supplier_id }}": "{{ $supplier->company_name }}",
    @endforeach
  };

  var items = {
    "SUP001": {
      "KAOS-s": { name: "Kaos Kecil", price: 1000 },
      "KAOS-m": { name: "Kaos Sedang", price: 2000 },
      "KAOS-l": { name: "Kaos Besar", price: 3000 },
    },
    "SUP002": {
      "CELANA-s": { name: "Celana Kecil", price: 1000 },
      "CELANA-m": { name: "Celana Sedang", price: 2000 },
      "CELANA-l": { name: "Celana Besar", price: 3000 },
    }
  };

  document.getElementById('supplierSearch').addEventListener('input', function() {
    var filter = this.value.toLowerCase();
    var options = document.getElementById('supplier_id').options;
    for (var i = 0; i < options.length; i++) {
      var option = options[i];
      var text = option.text.toLowerCase();
      option.style.display = text.includes(filter) ? "" : "none";
    }
    document.getElementById('supplier_id').style.display = filter ? 'block' : 'none';
  });

  document.getElementById('supplier_id').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex];
    var supplierId = selectedOption.value;
    var supplierName = selectedOption.text.split(' - ')[1];
    document.getElementById('supplier_name').value = supplierName;
    document.getElementById('supplierSearch').value = supplierId;
    document.getElementById('supplier_id').style.display = 'none';
  });

  function populateSKU(supplierId) {
    var supplierItems = items[supplierId];
    $('#itemsTable tbody tr').each(function() {
      var skuDropdown = $(this).find('.sku');
      skuDropdown.empty();
      if (supplierItems) {
        for (var sku in supplierItems) {
          var item = supplierItems[sku];
          skuDropdown.append('<option value="' + sku + '">' + sku + ' - ' + item.name + '</option>');
        }
      }
      skuDropdown.css('display', supplierItems ? 'block' : 'none');
    });
  }

  document.getElementById('skuSearch').addEventListener('input', function() {
    var filter = this.value.toLowerCase();
    var supplierId = document.getElementById('supplier_id').value;
    var itemsList = items[supplierId] || {};
    var skuOptions = document.getElementById('sku_id');
    skuOptions.innerHTML = '';
    if (filter.length > 0 && itemsList) {
      for (var sku in itemsList) {
        var item = itemsList[sku];
        if (item.name.toLowerCase().includes(filter)) {
          skuOptions.innerHTML += '<option value="' + sku + '">' + sku + ' - ' + item.name + '</option>';
        }
      }
      skuOptions.style.display = filter.length > 0 ? 'block' : 'none';
    } else {
      skuOptions.style.display = 'none';
    }
  });

  document.getElementById('sku_id').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex];
    var sku = selectedOption.value;
    var supplierId = document.getElementById('supplier_id').value;
    var item = items[supplierId] ? items[supplierId][sku] : null;
    if (item) {
      document.getElementById('item_name').value = item.name;
      document.getElementById('unit_price').value = item.price;
    } else {
      document.getElementById('item_name').value = '';
      document.getElementById('unit_price').value = '';
    }
    document.getElementById('sku_id').style.display = 'none';
  });

  $('#supplier_id').on('change', function() {
    var supplierId = $(this).val();
    var supplierName = suppliers[supplierId];
    if (supplierName) {
      $('#supplier_name').val(supplierName);
      populateSKU(supplierId);
    } else {
      $('#supplier_name').val('');
    }
  });

  function updateAmount(row) {
    var qty = parseFloat(row.find('.qty').val()) || 0;
    var price = parseFloat(row.find('.unit-price').val()) || 0;
    var amount = qty * price;
    row.find('.amount').val(amount.toFixed(2));
    updateTotal();
  }

  function updateTotal() {
    var total = 0;
    $(".amount").each(function() {
      total += parseFloat($(this).val()) || 0;
    });
    $("#subtotal").val(total.toLocaleString("id-ID"));
    $("#tax").val(total.toLocaleString("id-ID"));
  }

  $(document).on('input', '.qty, .unit-price', function() {
    var row = $(this).closest('tr');
    updateAmount(row);
  });

  $(document).on('click', '.remove', function() {
    $(this).closest('tr').remove();
    updateTotal();
  });

  $(document).ready(function() {
    $(".sku-search").prop('disabled', true);
    $(".sku-dropdown").hide();

    $('#branch, #supplier_id').on('input change', function() {
      if ($('#branch').val() && $('#supplier_id').val()) {
        $(".sku-search").prop('disabled', false);
      } else {
        $(".sku-search").prop('disabled', true);
        $(".sku-dropdown").hide();
      }
    });

    $('.sku-search').on('click', function() {
      if (!$('#branch').val() || !$('#supplier_id').val()) {
        alert("Pilih Cabang dan Supplier terlebih dahulu!");
        return false;
      }
    });

    $(document).on('input', '.sku-search', function() {
      var row = $(this).closest('tr');
      var filter = $(this).val().toLowerCase();
      var supplierId = $('#supplier_id').val();
      var itemsList = items[supplierId] || {};
      var skuDropdown = row.find('.sku-dropdown');
      skuDropdown.empty();
      if (filter.length > 0) {
        for (var sku in itemsList) {
          var item = itemsList[sku];
          if (item.name.toLowerCase().includes(filter)) {
            skuDropdown.append('<option value="' + sku + '">' + sku + ' - ' + item.name + '</option>');
          }
        }
        skuDropdown.show();
      } else {
        skuDropdown.hide();
      }
    });

    $(document).on('change', '.sku-dropdown', function() {
      var row = $(this).closest('tr');
      var sku = $(this).val();
      var supplierId = $('#supplier_id').val();
      var item = items[supplierId] ? items[supplierId][sku] : null;
      if (item) {
        row.find('.sku-search').val(sku);
        row.find('.nama-item').val(item.name);
        row.find('.unit-price').val(item.price);
        updateAmount(row);
      }
      $(this).hide();
    });

    $(document).on('input', '.qty, .unit-price', function() {
      var row = $(this).closest('tr');
      updateAmount(row);
    });

    $(document).on('click', '.remove', function() {
      $(this).closest('tr').remove();
      updateTotal();
    });

    $(document).on('click', '#addRow', function() {
      var newRow = '<tr>' +
        '<td><input type="text" class="form-control sku-search" placeholder="Cari SKU"><select class="form-control sku-dropdown" size="5" style="display:none;"></select></td>' +
        '<td><input type="text" class="form-control nama-item" readonly></td>' +
        '<td><input type="number" class="form-control qty" value="1" min="1"></td>' +
        '<td><input type="number" class="form-control unit-price" value="0"></td>' +
        '<td><input type="number" class="form-control amount" value="0" readonly></td>' +
        '<td><button type="button" class="btn btn-danger remove">Hapus</button></td>' +
        '</tr>';
      $('#itemsTable tbody').append(newRow);
    });

    document.getElementById('submitBtn').addEventListener('click', function() {
      var po_number = document.getElementById('po_number').value;
      var supplier_id = document.getElementById('supplierSearch').value;
      var supplier_name = document.getElementById('supplier_name').value;
      var branch = document.getElementById('branch').value;
      var items = [];
      document.querySelectorAll('#itemsTable tbody tr').forEach(function(row) {
        items.push({
          sku: row.querySelector('.sku-search')?.value || '',
          name: row.querySelector('.nama-item')?.value || '',
          qty: row.querySelector('.qty')?.value || '',
          unitPrice: row.querySelector('.unit-price')?.value || '',
          amount: row.querySelector('.amount')?.value || ''
        });
      });
      var subtotal = document.getElementById('subtotal').value;
      var tax = document.getElementById('tax').value;
      var formData = {
        po_number: po_number,
        supplier_id: supplier_id,
        supplier_name: supplier_name,
        branch: branch,
        items: items,
        subtotal: subtotal,
        tax: tax
      };
      console.log("Form Data JSON:", formData);
    });

    $('#submitBtn').on('click', function() {
      setTimeout(function() {
        var dataForEmail = {
          header: {
            po_number: $('#po_number').val(),
            branch: $('#branch').val(),
            supplier_name: $('#supplier_name').val(),
            supplier_id: $('#supplierSearch').val(),
            order_date: new Date().toISOString().slice(0, 10)
          },
          items: [],
          subtotal: $('#subtotal').val().replace(/[^0-9]/g, ''),
          tax: $('#tax').val().replace(/[^0-9]/g, '')
        };
        $('#itemsTable tbody tr').each(function() {
          var row = $(this);
          var sku = row.find('.sku-search').val();
          if (sku) {
            dataForEmail.items.push({
              sku: sku,
              name: row.find('.nama-item').val(),
              qty: row.find('.qty').val(),
              unitPrice: row.find('.unit-price').val(),
              amount: row.find('.amount').val()
            });
          }
        });
        if (dataForEmail.items.length === 0) {
          return;
        }
        $.ajax({
          url: '{{ route("purchase_orders.send_email") }}',
          method: 'POST',
          contentType: 'application/json',
          data: JSON.stringify(dataForEmail),
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          success: function(response) {
            console.log('Email Terkirim:', response.success);
            alert(response.success);
          },
          error: function(xhr) {
            console.error('Gagal Kirim Email:', xhr.responseJSON.error);
            alert('Gagal mengirim email: ' + xhr.responseJSON.error);
          }
        });
      }, 500);
    });
  });

  document.addEventListener('DOMContentLoaded', function() {
    var statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
      statusFilter.addEventListener('change', function() {
        var selectedStatus = this.value;
        if (!selectedStatus) {
          return;
        }
        if (selectedStatus === 'all') {
          window.location.href = "{{ route('purchase.orders') }}";
        } else {
          window.location.href = "/purchase-order/status/" + selectedStatus;
        }
      });
    }
  });
</script>
@endpush
