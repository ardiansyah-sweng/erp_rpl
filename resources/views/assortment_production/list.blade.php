@extends('layouts.app')

@section('title', 'Productions')

@section('page-title')
<h3 class="mb-0 me-2">Productions</h3>
<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahProduksi">Tambah</button>
<!-- Modal Tambah Produksi & Material -->
<div class="modal fade" id="modalTambahProduksi" tabindex="-1" aria-labelledby="modalTambahProduksiLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahProduksiLabel">Tambah Produksi & Material</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Form Produksi -->
        <form id="productionInputForm" class="mb-3">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Nomor Produksi</label>
              <input type="text" class="form-control" id="nomorProduksi" placeholder="">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">SKU</label>
              <input type="text" class="form-control" id="sku" placeholder="">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Cabang</label>
              <select class="form-select" id="cabang">
                <option value="">Pilih Cabang</option>
                <option value="A">Cabang A</option>
                <option value="B">Cabang B</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Gudang Finished Goods</label>
              <input type="date" class="form-control" id="gudangFG">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Gudang Raw Material</label>
              <select class="form-select" id="gudangRM">
                <option value="">Pilih Gudang</option>
                <option value="RM1">Gudang RM1</option>
                <option value="RM2">Gudang RM2</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Tanggal Produksi</label>
              <input type="date" class="form-control" id="tanggalProduksi">
            </div>
          </div>
        </form>
        <div class="table-responsive mb-4">
          <table id="materialTable" class="table table-bordered align-middle mb-0" style="border-radius: 8px; overflow: hidden;">
            <thead class="table-light text-center">
              <tr>
                <th>Nama Item</th>
                <th>Quantity</th>
                <th>Satuan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <!-- Form Add Material -->
        <form id="addMaterialForm" class="mb-4">
          <div class="row align-items-end g-2 mb-2">
            <div class="col-md-4">
              <label for="materialName" class="form-label fw-semibold">Nama Item</label>
              <input type="text" class="form-control" id="materialName" placeholder="Nama Item" required>
            </div>
            <div class="col-md-3">
              <label for="materialQty" class="form-label fw-semibold">Quantity</label>
              <input type="number" class="form-control" id="materialQty" placeholder="Quantity" min="1" required>
            </div>
            <div class="col-md-3">
              <label for="materialUnit" class="form-label fw-semibold">Satuan</label>
              <select class="form-select" id="materialUnit">
                <option value="PCS">PCS</option>
                <option value="KG">KG</option>
                <option value="L">L</option>
              </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button type="submit" class="btn btn-success w-100" style="height: 38px;">Tambah Material</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Item</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">List Table</h3>
        <form action="{{ route('item.list') }}" method="GET" class="d-flex ms-auto">
          <div class="input-group input-group-sm ms-auto" style="width: 450px;">
            <input type="text" name="search" class="form-control" placeholder="Search Item">
            <div class="input-group-append">
              <button type="submit" class="btn btn-default">
                <i class="bi bi-search"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
      <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <table class="table table-bordered">
          <thead class="text-center">
            <tr>
              <th style="width: 10px">id</th>
              <th>production number</th>
              <th>sku</th>
              <th>branch ID</th>
              <th>whouseid </th>
              <th>fgwhouse id </th>
              <th>production_date </th>
              <th>finished_date </th>
              <th>in_production </th>
              <th>description </th>
              <th>created_at</th>
              <th>updated_at</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody class="text-center">
            @forelse($production as $produksi)
            <tr id="row-{{ $produksi->id }}">
              <td>{{ $produksi->id }}</td>
              <td>{{ $produksi->production_number }}</td>
              <td>{{ $produksi->sku }}</td>
              <td>{{ $produksi->branch_id }}</td>
              <td>{{ $produksi->rm_whouse_id }}</td>
              <td>{{ $produksi->fg_whouse_id }}</td>
              <td>{{ $produksi->production_date}}</td>
              <td>{{ $produksi->finished_date ? "FInish" : "Null"}}</td>
              <td>
                @if($produksi->in_production == 1)
                    <span class="badge bg-success">YES</span>
                @else
                    <span class="badge bg-danger">NO</span>
                @endif
              </td>
              <td>{{ $produksi->description}}</td>
              <td>{{ $produksi->created_at }}</td>
              <td>{{ $produksi->updated_at }}</td>
              <td>
                <button type="button" class="btn btn-sm btn-primary" onclick="editProduksi({{ $produksi->id }})">Edit</button>
                <form action="#" method="POST" style="display: inline;">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
                <a href="#" class="btn btn-sm btn-info">Detail</a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="9" class="text-center">No data available in table</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer clearfix">
        {{ $production->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>
@endsection

{{-- Modal Edit Produksi (form filled) --}}
<div class="modal fade" id="editProduksiModal" tabindex="-1" aria-labelledby="editProduksiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProduksiModalLabel">Edit Produksi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editProduksiForm">
          <input type="hidden" id="edit_produksi_id">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Production Number</label>
              <input type="text" class="form-control" id="edit_production_number" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">SKU</label>
              <input type="text" class="form-control" id="edit_sku" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Branch ID</label>
              <input type="number" class="form-control" id="edit_branch_id" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">RM Warehouse ID</label>
              <input type="number" class="form-control" id="edit_rm_whouse_id" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">FG Warehouse ID</label>
              <input type="number" class="form-control" id="edit_fg_whouse_id" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Production Date</label>
              <input type="text" class="form-control" id="edit_production_date" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Finished Date</label>
              <input type="date" class="form-control" id="edit_finished_date">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">In Production</label>
              <select class="form-select" id="edit_in_production">
                <option value="1">YES</option>
                <option value="0">NO</option>
              </select>
            </div>
            <div class="col-md-12">
              <label class="form-label fw-semibold">Description</label>
              <input type="text" class="form-control" id="edit_description">
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('addMaterialForm');
  const tableBody = document.querySelector('#materialTable tbody');
  if(form && tableBody) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const name = document.getElementById('materialName').value;
      const qty = document.getElementById('materialQty').value;
      const unit = document.getElementById('materialUnit').value;
      if (name && qty) {
        const row = document.createElement('tr');
        row.innerHTML = '<td>' + name + '</td><td>' + qty + '</td><td>' + unit + '</td><td><button type="button" class="btn btn-outline-danger btn-sm btn-hapus">Hapus</button></td>';
        tableBody.appendChild(row);
        form.reset();
      }
    });
    tableBody.addEventListener('click', function (e) {
      if (e.target.classList.contains('btn-hapus')) {
        e.target.closest('tr').remove();
      }
    });
  }
});

// Mengambil data produksi yang sudah ada, lalu mengisi form edit (form filled)
function editProduksi(id) {
  fetch(`/assortment_production/detail/${id}`)
    .then(res => res.json())
    .then(data => {
      document.getElementById('edit_produksi_id').value = data.id;
      document.getElementById('edit_production_number').value = data.production_number;
      document.getElementById('edit_sku').value = data.sku;
      document.getElementById('edit_branch_id').value = data.branch_id;
      document.getElementById('edit_rm_whouse_id').value = data.rm_whouse_id;
      document.getElementById('edit_fg_whouse_id').value = data.fg_whouse_id;
      document.getElementById('edit_production_date').value = data.production_date;
      document.getElementById('edit_finished_date').value = data.finished_date ?? '';
      document.getElementById('edit_in_production').value = data.in_production;
      document.getElementById('edit_description').value = data.description ?? '';

      var modal = new bootstrap.Modal(document.getElementById('editProduksiModal'));
      modal.show();
    })
    .catch(err => alert('Gagal mengambil data produksi'));
}

// Mengirim perubahan ke endpoint update yang sudah ada
document.getElementById('editProduksiForm').addEventListener('submit', function (e) {
  e.preventDefault();
  const id = document.getElementById('edit_produksi_id').value;

  fetch(`/assortment_production/update/${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
    body: JSON.stringify({
      production_number: document.getElementById('edit_production_number').value,
      sku:               document.getElementById('edit_sku').value,
      branch_id:         document.getElementById('edit_branch_id').value,
      rm_whouse_id:      document.getElementById('edit_rm_whouse_id').value,
      fg_whouse_id:      document.getElementById('edit_fg_whouse_id').value,
      production_date:   document.getElementById('edit_production_date').value,
      finished_date:     document.getElementById('edit_finished_date').value || null,
      in_production:     document.getElementById('edit_in_production').value,
      description:       document.getElementById('edit_description').value,
    }),
  })
    .then(res => res.json())
    .then(() => {
      alert('Data produksi berhasil diperbarui.');
      location.reload();
    })
    .catch(err => alert('Gagal menyimpan perubahan.'));
});
</script>
@endpush