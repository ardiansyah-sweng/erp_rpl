@extends('layouts.app')

@section('title', 'Supplier List')

@section('page-title')
<h3 class="mb-0 me-2">Suppliers</h3>
<a href="#" class="btn btn-primary btn-sm">New Supplier</a>
<a href="{{ route('supplier.print-pdf') }}" class="btn btn-primary btn-sm" target="_blank">
    <i class="fa fa-file-pdf-o"></i> Cetak PDF
</a>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Supplier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <span>Show</span>
                        <form method="GET" id="pageLengthForm" class="d-flex align-items-center">
                            <input type="hidden" name="page" value="1">
                            <select name="pageLength" id="pageLength" class="form-select mx-2" style="width: auto;"
                                onchange="document.getElementById('pageLengthForm').submit()">
                                <option value="10" {{ request('pageLength', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('pageLength', 10) == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('pageLength', 10) == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span>entries</span>
                        </form>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="me-2">Search:</span>
                        <input type="text" id="supplierSearch" class="form-control" style="width: 200px;">
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="supplierTable" class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>ID Supplier</th>
                                <th>Company Name</th>
                                <th>Address</th>
                                <th>Phone Number</th>
                                <th>Bank Account</th>
                                <th>Order Frequency</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>PiC</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliersToShow as $index => $supplier)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $supplier->supplier_id }}</td>
                                <td>{{ $supplier->company_name }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td>{{ $supplier->telephone }}</td>
                                <td>{{ $supplier->bank_account }}</td>
                                <td class="text-center"><span class="badge bg-secondary">{{ $supplier->order_frequency ?? 0 }}</span></td>
                                <td>{{ $supplier->created_at }}</td>
                                <td>{{ $supplier->updated_at }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark">{{ $supplier->pic_count ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                        <a href="{{ route('supplier.detail', ['id' => $supplier->supplier_id]) }}" class="btn btn-warning btn-sm custom-btn">Edit</a>
                                        <a href="#" class="btn btn-info btn-sm text-white custom-btn">Create PO</a>
                                        <a href="#" class="btn btn-primary btn-sm custom-btn">Add Pic</a>
                                        <a href="{{ route('supplier.detail', ['id' => $supplier->supplier_id]) }}" class="btn btn-success btn-sm custom-btn">Detail</a>
                                        <button class="btn btn-danger btn-sm custom-btn" onclick="confirmDelete('{{ $supplier->supplier_id }}')">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">No data available in table</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $total == 0 ? 0 : ($page - 1) * $pageLength + 1 }}
                        to {{ min($page * $pageLength, $total) }}
                        of {{ $total }} entries
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $page <= 1 ? '#' : route('supplier.list', ['page' => $page - 1, 'pageLength' => $pageLength]) }}">
                                    Previous
                                </a>
                            </li>

                            @for ($i = 1; $i <= $totalPages; $i++)
                            <li class="page-item {{ $page == $i ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ route('supplier.list', ['page' => $i, 'pageLength' => $pageLength]) }}">
                                    {{ $i }}
                                </a>
                            </li>
                            @endfor

                            <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $page >= $totalPages ? '#' : route('supplier.list', ['page' => $page + 1, 'pageLength' => $pageLength]) }}">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmDelete(supplierId) {
        if (confirm("Apakah Anda yakin ingin menghapus supplier " + supplierId + "?")) {
            alert("Supplier " + supplierId + " dihapus (simulasi).");
        }
    }
</script>
@endpush
