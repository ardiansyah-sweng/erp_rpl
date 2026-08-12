@extends('layouts.app')

@section('title', 'Bill Of Material')

@section('page-title')
<h3 class="mb-0 me-2">Bill Of Material</h3>
<span class="btn btn-primary btn-sm me-2">Total BOM: {{ $bomCount ?? 0 }}</span>
<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahBOM">Tambah Bill of Material</button>
<a href="{{ route('bom.print') }}" class="btn btn-primary btn-sm ms-2">Cetak Bill Of Material</a>

<div class="modal fade" id="modalTambahBOM" tabindex="-1" aria-labelledby="modalTambahBOMLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahBOMLabel">Tambah Bill of Material</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="billOfMaterialForm" action="{{ route('billofmaterial.add') }}" method="POST">
          @csrf
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">BOM ID</label>
              <!-- Dibuat readonly agar user tidak usah isi, di-generate otomatis oleh Controller -->
              <input type="text" class="form-control" placeholder="Otomatis dari Sistem" readonly>
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold">Nama BOM</label>
              <input type="text" class="form-control" id="bomNama" name="bom_name" placeholder="Nama BOM" required>
              @error('bom_name')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold">Measurement Unit</label>
              <select class="form-select" id="bomMeasurement" name="measurement_unit" required>
                @if(isset($measurement_units) && count($measurement_units) > 0)
                  @foreach($measurement_units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                  @endforeach
                @endif
                <option value="1">PCS</option>
                <option value="2">KG</option>
                <option value="3">L</option>
                <option value="4">Meter</option>
                <option value="5">Set</option>
                <option value="6">Pack</option>
              </select>
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold">Total Cost</label>
              <input type="number" class="form-control" id="bomTotalCost" name="total_cost" placeholder="Total Cost" required>
              @error('total_cost')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold">Status</label>
              <select class="form-select" id="bomStatus" name="active" required>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
              </select>
            </div>
          </div>
          
          <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">BOM</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">List Table</h3>
        <form action="#" method="GET" class="d-flex ms-auto">
          <div class="input-group input-group-sm ms-auto" style="width: 450px;">
            <input type="text" name="search" class="form-control" placeholder="Search BOM">
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

        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Daftar Bill of Materials</h5>
          </div>
          <div class="card-body p-0">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>No</th>
                  <th>ID BOM</th>
                  <th>Nama BOM</th>
                  <th>Measurement Unit</th>
                  <th>Total Cost</th>
                  <th>Status</th>
                  <th>Create</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($boms as $index => $bom)
                <tr>
                  <td>{{ $boms->firstItem() + $index }}</td>
                  <td>{{ $bom->bom_id }}</td>
                  <td>{{ $bom->bom_name }}</td>
                  <td>{{ $bom->measurement_unit }}</td>
                  <td>Rp. {{ number_format($bom->total_cost, 0, ',', '.') }}</td>
                  <td>
                    @if($bom->active)
                      <span class="badge bg-success">A K T I F</span>
                    @else
                      <span class="badge bg-secondary">T I D A K &nbsp;-&nbsp; A K T I F</span>
                    @endif
                  </td>
                  <td>{{ \Carbon\Carbon::parse($bom->created_at)->format('d-m-Y') }}</td>
                  <td>
                    <button class="btn btn-info btn-sm" onclick="getDetail({{ $bom->id }})">Lihat</button>
                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                    <form action="/bill-of-material/{{ $bom->id }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus BOM {{ $bom->bom_name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="8" class="text-center">Tidak ada data Bill of Material</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="modal fade" id="bomModal" tabindex="-1" aria-labelledby="bomModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="bomModalLabel">Detail Bill of Material</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                  <p><strong>Nama BOM:</strong> <span id="bom_name"></span></p>
                  <p><strong>Satuan:</strong> <span id="measurement_unit"></span></p>
                  <p><strong>Total Biaya:</strong> <span id="total_cost"></span></p>
                  <p><strong>Status:</strong> <span id="active_status"></span></p>

                  <h5>Detail Komponen</h5>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>SKU</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                      </tr>
                    </thead>
                    <tbody id="bom_details"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer clearfix">
        {{ $boms->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function getDetail(id) {
  fetch(`/bill-of-material/${id}`)
    .then(res => res.json())
    .then(data => {
      document.getElementById('bom_name').textContent = data.bom_name;
      document.getElementById('measurement_unit').textContent = data.measurement_unit;
      document.getElementById('total_cost').textContent = 'Rp. ' + parseInt(data.total_cost).toLocaleString();
      document.getElementById('active_status').textContent = data.active ? 'AKTIF' : 'TIDAK AKTIF';

      let rows = '';
      data.details.forEach((item, index) => {
        rows += `<tr>
          <td>${index + 1}</td>
          <td>${item.sku}</td>
          <td>${item.quantity}</td>
          <td>Rp. ${parseInt(item.cost).toLocaleString()}</td>
        </tr>`;
      });
      document.getElementById('bom_details').innerHTML = rows;

      var modal = new bootstrap.Modal(document.getElementById('bomModal'));
      modal.show();
    })
    .catch(err => alert('Gagal mengambil data'));
}
</script>
@endpush
