@extends('layouts.app')

@section('title', 'Tambah Return Barang')

@section('page-title')
    <h3 class="mb-0">Tambah Return Barang</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('goods-returns.index') }}">Return Barang</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulir Return Barang ke Supplier</h3>
                </div>

                <form action="{{ route('goods-returns.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="grn_id" class="form-label">Goods Receipt Note <span class="text-danger">*</span></label>
                            <select name="grn_id" id="grn_id" class="form-select @error('grn_id') is-invalid @enderror" required>
                                <option value="">Pilih barang yang pernah diterima</option>
                                @foreach ($receiptNotes as $receiptNote)
                                    <option
                                        value="{{ $receiptNote->id }}"
                                        data-po="{{ $receiptNote->po_number }}"
                                        data-item="{{ $receiptNote->product_id }} - {{ $receiptNote->item?->name ?? 'Nama item tidak ditemukan' }}"
                                        data-delivery="{{ $receiptNote->delivery_date }}"
                                        data-received="{{ $receiptNote->delivered_quantity }}"
                                        data-stock="{{ $receiptNote->item?->stock_unit ?? 0 }}"
                                        data-maximum="{{ $receiptNote->available_return_quantity }}"
                                        {{ (string) old('grn_id', request('grn_id')) === (string) $receiptNote->id ? 'selected' : '' }}
                                    >
                                        GRN #{{ $receiptNote->id }} | {{ $receiptNote->po_number }} | {{ $receiptNote->product_id }} | Maks. {{ $receiptNote->available_return_quantity }} unit
                                    </option>
                                @endforeach
                            </select>
                            @if ($receiptNotes->isEmpty())
                                <small class="text-danger">Tidak ada barang diterima yang tersedia untuk direturn.</small>
                            @endif
                        </div>

                        <div id="receiptInformation" class="alert alert-info d-none">
                            <div><strong>Purchase Order:</strong> <span id="poNumber">-</span></div>
                            <div><strong>Item:</strong> <span id="itemName">-</span></div>
                            <div><strong>Jumlah diterima:</strong> <span id="receivedQuantity">0</span> unit</div>
                            <div><strong>Stok saat ini:</strong> <span id="currentStock">0</span> unit</div>
                            <div><strong>Maksimal dapat direturn:</strong> <span id="maximumQuantity">0</span> unit</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="return_date" class="form-label">Tanggal Return <span class="text-danger">*</span></label>
                                <input type="date" name="return_date" id="return_date" class="form-control @error('return_date') is-invalid @enderror" value="{{ old('return_date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="return_quantity" class="form-label">Jumlah Return <span class="text-danger">*</span></label>
                                <input type="number" name="return_quantity" id="return_quantity" class="form-control @error('return_quantity') is-invalid @enderror" value="{{ old('return_quantity') }}" min="1" required>
                                <small id="quantityHelp" class="form-text text-secondary">Pilih Goods Receipt Note terlebih dahulu.</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Alasan Return <span class="text-danger">*</span></label>
                            <textarea name="reason" id="reason" rows="4" maxlength="255" class="form-control @error('reason') is-invalid @enderror" placeholder="Contoh: Barang rusak, ukuran tidak sesuai, atau kualitas tidak memenuhi standar" required>{{ old('reason') }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" {{ $receiptNotes->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-save"></i> Simpan Return
                        </button>
                        <a href="{{ route('goods-returns.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var receiptSelect = document.getElementById('grn_id');
    var quantityInput = document.getElementById('return_quantity');
    var returnDateInput = document.getElementById('return_date');
    var receiptInformation = document.getElementById('receiptInformation');

    function updateReceiptInformation() {
        var option = receiptSelect.options[receiptSelect.selectedIndex];

        if (!option || !option.value) {
            receiptInformation.classList.add('d-none');
            quantityInput.removeAttribute('max');
            returnDateInput.removeAttribute('min');
            document.getElementById('quantityHelp').textContent = 'Pilih Goods Receipt Note terlebih dahulu.';
            return;
        }

        document.getElementById('poNumber').textContent = option.dataset.po;
        document.getElementById('itemName').textContent = option.dataset.item;
        document.getElementById('receivedQuantity').textContent = option.dataset.received;
        document.getElementById('currentStock').textContent = option.dataset.stock;
        document.getElementById('maximumQuantity').textContent = option.dataset.maximum;
        quantityInput.max = option.dataset.maximum;
        returnDateInput.min = option.dataset.delivery;
        document.getElementById('quantityHelp').textContent = 'Jumlah maksimal: ' + option.dataset.maximum + ' unit.';
        receiptInformation.classList.remove('d-none');
    }

    receiptSelect.addEventListener('change', updateReceiptInformation);
    updateReceiptInformation();
});
</script>
@endpush
