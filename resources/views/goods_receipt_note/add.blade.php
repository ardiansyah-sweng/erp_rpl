@extends('layouts.app')

@section('title', 'Add Goods Receipt Note')

@section('page-title')
<h3 class="mb-0">Add Goods Receipt Note</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Form Goods Receipt Note</h3>
      </div>
      <div class="card-body" id="printArea">
        <div class="form-group mb-3">
          <label for="po_number">PO Number</label>
          <input type="text" class="form-control" id="po_number" value="POO0002" readonly>
        </div>
        <form action="{{ route('goods_receipt_note.store') }}" method="POST">
          @csrf
          <div class="form-group mb-3">
            <label for="branch">Branch</label>
            <input type="text" class="form-control" id="branch" value="Yogyakarta" readonly>
          </div>
          <div class="form-group mb-3">
            <label for="supplier_id">Supplier ID</label>
            <input type="text" class="form-control" id="supplier_id" value="SUUP001" readonly>
          </div>
          <div class="form-group mb-3">
            <label for="supplier_name">Name Supplier</label>
            <input type="text" class="form-control" id="supplier_name" value="PT. XYZ" readonly>
          </div>

          <div class="table-responsive" id="printArea">
            <table class="table table-bordered" id="itemsTable">
              <thead class="table-primary text-center">
                <tr>
                  <th>SKU</th>
                  <th>Name Item</th>
                  <th>Qty</th>
                  <th>Unit Price</th>
                  <th>Amount</th>
                  <th>Delivery Date</th>
                  <th>Delivery Quantity</th>
                  <th class="no-print">Action</th>
                </tr>
              </thead>
              <tbody class="text-center">
                <tr>
                  <td><input type="text" class="form-control" value="KAOS-M" readonly></td>
                  <td><input type="text" class="form-control" value="Kaos Sedang" readonly></td>
                  <td><input type="text" class="form-control" value="15" readonly></td>
                  <td><input type="text" class="form-control" value="20000" readonly></td>
                  <td><input type="text" class="form-control" value="300000" readonly></td>
                  <td><input type="date" class="form-control" value="2025-06-10" required></td>
                  <td><input type="number" class="form-control" value="10" min="0" max="15" required></td>
                  <td class="no-print mb-3" style="display: flex; gap: 8px;">
                    <button type="button" class="btn btn-info btn-sm comments">
                      <i class="bi bi-chat"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm remove-row-btn">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td><input type="text" class="form-control sku" value="KAOS-L" readonly></td>
                  <td><input type="text" class="form-control name-item" value="Kaos Besar" readonly></td>
                  <td><input type="number" class="form-control qty" value="10" readonly></td>
                  <td><input type="number" class="form-control unit-price" value="20000" readonly></td>
                  <td><input type="number" class="form-control amount" value="300000" readonly></td>
                  <td><input type="date" class="form-control delivery-date" value="2025-06-10" required></td>
                  <td><input type="number" class="form-control delivery-quantity" value="10" min="0" max="15" required></td>
                  <td class="mb-3" style="display: flex; gap: 8px;">
                    <button type="button" class="btn btn-info btn-sm comments">
                      <i class="bi bi-chat"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm remove-row-btn">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary mb-3">
              <i class="bi bi-check-circle"></i> Tambah
            </button>
            <button type="button" class="btn btn-danger mb-3">
              <i class="bi bi-x-circle"></i> Batal
            </button>
            <button type="button" class="btn btn-success mb-3" onclick="printGRN()">
              <i class="bi bi-printer-fill"></i> Cetak
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Comment Modal -->
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="commentModalLabel">
          <i class="bi bi-chat-text"></i> Tambah Komentar
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="commentText" class="form-label">Komentar:</label>
          <textarea class="form-control" id="commentText" rows="5" placeholder="Masukkan komentar Anda di sini..."></textarea>
        </div>
        <div class="alert alert-info">
          <i class="bi bi-info-circle"></i>
          <small>Komentar ini akan disimpan untuk item yang dipilih dalam tabel.</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
          <i class="bi bi-x-circle"></i> Batal
        </button>
        <button type="button" class="btn btn-primary" id="saveComment">
          <i class="bi bi-check-circle"></i> Simpan
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
@media print {
  * {
    margin: 0 !important;
    padding: 0 !important;
    box-sizing: border-box !important;
  }

  .no-print,
  .app-header,
  .app-sidebar,
  .app-footer,
  .btn,
  nav,
  aside,
  .breadcrumb,
  .card-header,
  form > .form-group:last-child {
    display: none !important;
  }

  body {
    background: white !important;
    color: black !important;
    font-family: Arial, sans-serif !important;
    font-size: 11px !important;
    line-height: 1.4 !important;
  }

  #printArea {
    padding: 20px !important;
    width: 100% !important;
    background: white !important;
  }

  .print-header {
    text-align: center !important;
    margin-bottom: 20px !important;
  }

  .print-header h2 {
    font-size: 16px !important;
    font-weight: bold !important;
  }

  .print-info {
    display: flex !important;
    justify-content: space-between !important;
    margin-bottom: 20px !important;
    font-size: 11px !important;
  }

  .print-info div {
    flex: 1 !important;
    padding: 2px 4px !important;
  }

  #printArea table {
    width: 100% !important;
    border-collapse: collapse !important;
    border: 1px solid #000 !important;
    table-layout: fixed !important;
  }

  #printArea th,
  #printArea td {
    border: 1px solid #000 !important;
    padding: 6px 4px !important;
    text-align: center !important;
    vertical-align: middle !important;
    font-size: 10px !important;
    word-wrap: break-word !important;
  }

  #printArea th {
    background-color: #f0f0f0 !important;
    font-weight: bold !important;
    font-size: 11px !important;
  }

  #printArea input {
    all: unset !important;
    display: block !important;
    width: 100% !important;
    text-align: center !important;
    font-size: 10px !important;
    border: none !important;
  }

  #printArea th:nth-child(8),
  #printArea td:nth-child(8) {
    display: none !important;
  }

  #printArea th:nth-child(1), #printArea td:nth-child(1) { width: 18% !important; }
  #printArea th:nth-child(2), #printArea td:nth-child(2) { width: 25% !important; }
  #printArea th:nth-child(3), #printArea td:nth-child(3) { width: 10% !important; }
  #printArea th:nth-child(4), #printArea td:nth-child(4) { width: 15% !important; }
  #printArea th:nth-child(5), #printArea td:nth-child(5) { width: 15% !important; }
  #printArea th:nth-child(6), #printArea td:nth-child(6) { width: 12% !important; }
  #printArea th:nth-child(7), #printArea td:nth-child(7) { width: 10% !important; }

  @page {
    size: A4 portrait;
    margin: 1cm !important;
  }

  .container-fluid,
  .row,
  .col-md-12,
  .card,
  .card-body,
  .table-responsive {
    border: none !important;
    padding: 0 !important;
    margin: 0 !important;
    background: white !important;
  }
}

.table-responsive {
  overflow-x: auto;
}

.comments,
.remove-row-btn {
  margin: 2px;
}
</style>
@endpush

@push('scripts')
<script>
function printGRN() {
  var printContents = document.getElementById('printArea').innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
  location.reload();
}

document.addEventListener('DOMContentLoaded', function() {
  var commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
  var commentTextArea = document.getElementById('commentText');
  var currentCommentButton = null;

  document.getElementById('itemsTable').addEventListener('click', function(e) {
    var targetCommentBtn = e.target.closest('.comments');
    var targetDeleteBtn = e.target.closest('.remove-row-btn');

    if (targetCommentBtn) {
      currentCommentButton = targetCommentBtn;
      commentTextArea.value = currentCommentButton.dataset.comment || '';
      commentModal.show();
    }

    if (targetDeleteBtn) {
      targetDeleteBtn.closest('tr').remove();
    }
  });

  document.getElementById('saveComment').addEventListener('click', function() {
    if (currentCommentButton) {
      var newComment = commentTextArea.value.trim();
      currentCommentButton.dataset.comment = newComment;
      var icon = currentCommentButton.querySelector('i');
      if (newComment) {
        currentCommentButton.classList.replace('btn-info', 'btn-success');
        icon.classList.replace('bi-chat', 'bi-chat-fill');
      } else {
        currentCommentButton.classList.replace('btn-success', 'btn-info');
        icon.classList.replace('bi-chat-fill', 'bi-chat');
      }
    }
    commentModal.hide();
  });
});
</script>
@endpush
