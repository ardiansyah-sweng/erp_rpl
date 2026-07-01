@extends('layouts.app')

@section('title', 'Tambah Retur Barang')

@section('page-title')
<h3 class="mb-0">Tambah Retur Barang</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('purchase-returns.index') }}">Retur Barang</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Data retur belum dapat disimpan.</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="card card-primary mb-4">
    <div class="card-header"><h3 class="card-title">Form Retur ke Supplier</h3></div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-1"></i>
            Pilih PO yang barangnya sudah diterima. Saat retur disimpan, stok barang otomatis berkurang.
        </div>

        <form method="GET" action="{{ route('purchase-returns.create') }}" class="mb-4">
            <label for="poSelector" class="form-label">Purchase Order</label>
            <div class="input-group">
                <select id="poSelector" name="po_number" class="form-select" required>
                    <option value="">Pilih Purchase Order</option>
                    @foreach ($purchaseOrders as $po)
                        <option value="{{ $po->po_number }}" @selected($selectedPoNumber === $po->po_number)>
                            {{ $po->po_number }} - {{ $po->supplier?->company_name ?? $po->supplier_id }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="submit">Tampilkan Barang</button>
            </div>
        </form>

        @if ($selectedPurchaseOrder)
            <div class="row mb-4">
                <div class="col-md-4"><strong>PO:</strong><br>{{ $selectedPurchaseOrder->po_number }}</div>
                <div class="col-md-4"><strong>Supplier:</strong><br>{{ $selectedPurchaseOrder->supplier?->company_name ?? '-' }}</div>
                <div class="col-md-4"><strong>Tanggal PO:</strong><br>{{ \Carbon\Carbon::parse($selectedPurchaseOrder->order_date)->format('d M Y') }}</div>
            </div>

            @if ($returnableItems->isEmpty())
                <div class="alert alert-warning mb-0">
                    Tidak ada barang yang dapat diretur dari PO ini. Barang mungkin sudah seluruhnya diretur atau stoknya sudah habis.
                </div>
            @else
                <form method="POST" action="{{ route('purchase-returns.store') }}">
                    @csrf
                    <input type="hidden" name="po_number" value="{{ $selectedPurchaseOrder->po_number }}">

                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label for="product_id" class="form-label">Barang</label>
                            <select id="product_id" name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                <option value="">Pilih barang</option>
                                @foreach ($returnableItems as $item)
                                    <option value="{{ $item->sku }}" data-max="{{ $item->available_quantity }}" @selected(old('product_id') === $item->sku)>
                                        {{ $item->sku }} - {{ $item->name }} (maks. {{ $item->available_quantity }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="return_date" class="form-label">Tanggal Retur</label>
                            <input id="return_date" type="date" name="return_date"
                                   value="{{ old('return_date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}"
                                   class="form-control @error('return_date') is-invalid @enderror" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="quantity" class="form-label">Jumlah Retur</label>
                            <input id="quantity" type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1"
                                   class="form-control @error('quantity') is-invalid @enderror" required>
                            <div id="quantityHelp" class="form-text">Pilih barang untuk melihat batas jumlah retur.</div>
                        </div>
                        <div class="col-md-7 mb-3">
                            <label for="reason" class="form-label">Alasan Retur</label>
                            <select id="reason" name="reason" class="form-select @error('reason') is-invalid @enderror" required>
                                <option value="">Pilih alasan</option>
                                @foreach (['Rusak', 'Tidak sesuai', 'Kelebihan kirim', 'Kedaluwarsa', 'Lainnya'] as $reason)
                                    <option value="{{ $reason }}" @selected(old('reason') === $reason)>{{ $reason }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea id="notes" name="notes" rows="3" maxlength="255"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Keterangan kondisi barang atau informasi tambahan">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" onclick="return confirm('Simpan retur dan kurangi stok barang?')">
                        <i class="bi bi-check-circle me-1"></i> Simpan Retur
                    </button>
                    <a href="{{ route('purchase-returns.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            @endif
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const product = document.getElementById('product_id');
    const quantity = document.getElementById('quantity');
    const help = document.getElementById('quantityHelp');

    function updateMaximum() {
        if (!product || !quantity) return;
        const option = product.options[product.selectedIndex];
        const maximum = option ? option.dataset.max : '';
        if (maximum) {
            quantity.max = maximum;
            help.textContent = 'Jumlah maksimal yang dapat diretur: ' + maximum + ' unit.';
        } else {
            quantity.removeAttribute('max');
            help.textContent = 'Pilih barang untuk melihat batas jumlah retur.';
        }
    }

    product?.addEventListener('change', updateMaximum);
    updateMaximum();
});
</script>
@endpush
