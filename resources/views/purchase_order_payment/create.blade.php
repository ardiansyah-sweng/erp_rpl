@extends('layouts.app')

@section('title', 'Tambah Pembayaran PO')

@section('page-title')
    <h3 class="mb-0">Tambah Pembayaran PO</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('po-payments.index') }}">Pembayaran PO</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulir Pembayaran Purchase Order</h3>
                </div>

                <form action="{{ route('po-payments.store') }}" method="POST">
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
                            <label for="po_number" class="form-label">Purchase Order <span class="text-danger">*</span></label>
                            <select name="po_number" id="po_number" class="form-select @error('po_number') is-invalid @enderror" required>
                                <option value="">Pilih Purchase Order yang belum lunas</option>
                                @foreach ($purchaseOrders as $po)
                                    <option
                                        value="{{ $po->po_number }}"
                                        data-supplier="{{ $po->supplier?->company_name ?? '-' }}"
                                        data-total="{{ $po->total }}"
                                        data-paid="{{ $po->paid_amount }}"
                                        data-remaining="{{ $po->remaining_amount }}"
                                        data-order-date="{{ $po->order_date }}"
                                        {{ (string) old('po_number') === (string) $po->po_number ? 'selected' : '' }}
                                    >
                                        {{ $po->po_number }} | {{ $po->supplier?->company_name ?? '-' }} | Sisa {{ number_format($po->remaining_amount, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($purchaseOrders->isEmpty())
                                <small class="text-danger">Tidak ada Purchase Order yang masih memiliki tagihan.</small>
                            @endif
                        </div>

                        <div id="poInformation" class="alert alert-info d-none">
                            <div><strong>Supplier:</strong> <span id="poSupplier">-</span></div>
                            <div><strong>Total PO:</strong> <span id="poTotal">0</span></div>
                            <div><strong>Sudah Dibayar:</strong> <span id="poPaid">0</span></div>
                            <div><strong>Sisa Tagihan:</strong> <span id="poRemaining">0</span></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_date" class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Jumlah Pembayaran <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" min="1" required>
                                <small id="amountHelp" class="form-text text-secondary">Pilih Purchase Order terlebih dahulu.</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="method" class="form-label">Metode Pembayaran</label>
                            <input type="text" name="method" id="method" class="form-control @error('method') is-invalid @enderror" placeholder="Contoh: Transfer Bank, Cash" value="{{ old('method') }}">
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Catatan</label>
                            <textarea name="note" id="note" rows="3" maxlength="255" class="form-control @error('note') is-invalid @enderror" placeholder="Catatan tambahan (opsional)">{{ old('note') }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" {{ $purchaseOrders->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-save"></i> Simpan Pembayaran
                        </button>
                        <a href="{{ route('po-payments.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var poSelect = document.getElementById('po_number');
    var amountInput = document.getElementById('amount');
    var paymentDateInput = document.getElementById('payment_date');
    var poInformation = document.getElementById('poInformation');

    function updatePoInformation() {
        var option = poSelect.options[poSelect.selectedIndex];

        if (!option || !option.value) {
            poInformation.classList.add('d-none');
            amountInput.removeAttribute('max');
            paymentDateInput.removeAttribute('min');
            document.getElementById('amountHelp').textContent = 'Pilih Purchase Order terlebih dahulu.';
            return;
        }

        document.getElementById('poSupplier').textContent = option.dataset.supplier;
        document.getElementById('poTotal').textContent = option.dataset.total;
        document.getElementById('poPaid').textContent = option.dataset.paid;
        document.getElementById('poRemaining').textContent = option.dataset.remaining;
        amountInput.max = option.dataset.remaining;
        paymentDateInput.min = option.dataset.orderDate;
        document.getElementById('amountHelp').textContent = 'Maksimal: ' + option.dataset.remaining;
        poInformation.classList.remove('d-none');
    }

    poSelect.addEventListener('change', updatePoInformation);
    updatePoInformation();
});
</script>
@endpush
