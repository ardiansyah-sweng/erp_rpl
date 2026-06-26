@extends('layouts.app')

@section('title', 'Goods Receipt Note')

@section('page-title')
<h3 class="mb-0 me-2">Goods Receipt Note</h3>
<a href="/goods_receipt_note/add" class="btn btn-primary btn-sm">Tambah GRN</a>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Goods Receipt Note</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">List Table</h3>
                <div class="d-flex align-items-center ms-auto gap-2">
                    <span class="me-2">Search:</span>
                    <input type="text" id="grnSearch" class="form-control form-control-sm" style="width: 200px;" placeholder="Cari PO Number / Product ID...">
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0" id="grnTable">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>PO Number</th>
                                <th>Product ID</th>
                                <th>Delivery Date</th>
                                <th>Delivery Quantity</th>
                                <th>Comments</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grns as $index => $grn)
                                <tr>
                                    <td class="text-center">{{ $grns->firstItem() + $index }}</td>
                                    <td>{{ $grn->po_number }}</td>
                                    <td>{{ $grn->product_id }}</td>
                                    <td class="text-center">{{ $grn->delivery_date }}</td>
                                    <td class="text-center">{{ $grn->delivered_quantity }}</td>
                                    <td>{{ $grn->comments ?? '-' }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($grn->created_at)->format('d-m-Y') }}</td>
                                    <td class="text-center">
                                        <a href="/goods_receipt_note/detail" class="btn btn-info btn-sm text-white">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data Goods Receipt Note</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                {{ $grns->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.custom-btn {
    padding: 3px 8px;
    font-size: 0.75rem;
    line-height: 1.5;
}
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('grnSearch').addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('#grnTable tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });
</script>
@endpush
