@extends('layouts.app')

@section('title', 'Daftar PIC Supplier')

@section('page-title')
<h3 class="mb-0 me-2">Daftar PIC Supplier</h3>
<a href="/supplier/pic/add" class="btn btn-primary btn-sm">Tambah</a>
<div class="btn-group">
    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Cetak PDF PIC
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="{{ url('/supplier-pic/cetak-pdf') }}" target="_blank">
                Cetak Semua
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>

        @foreach(\App\Models\Supplier::all() as $supplier)
            <li>
                <a class="dropdown-item" href="{{ route('supplier.pic.pdf.bySupplier', $supplier->supplier_id) }}" target="_blank">
                    {{ $supplier->supplier_id }} - {{ $supplier->company_name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Daftar PIC Supplier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">List Table</h3>
                <form action="{{ route('supplier.pic.list') }}" method="GET" class="d-flex ms-auto">
                    <div class="input-group input-group-sm ms-auto" style="width: 450px;">
                        <input type="text" name="keywords" class="form-control" placeholder="Search PIC Supplier">
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
                    <thead class="text-center">
                        <tr>
                            <th>Supplier ID</th>
                            <th>Nama Supplier</th>
                            <th>Nama PIC</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Durasi Penugasan</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pics as $pic)
                        <tr>
                            <td>{{ $pic->supplier_id }}</td>
                            <td>{{ $pic->supplier ? $pic->supplier->company_name : 'N/A' }}</td>
                            <td>{{ $pic->name }}</td>
                            <td>{{ $pic->email }}</td>
                            <td>{{ $pic->phone_number }}</td>
                            <td>
                                @php
                                    $duration = json_decode(\App\Models\SupplierPic::assignmentDuration($pic));
                                @endphp
                                @if(is_object($duration))
                                    {{ $duration->years }} tahun, {{ $duration->months }} bulan, {{ $duration->days }} hari
                                @else
                                    Tanggal belum tersedia
                                @endif
                            </td>
                            <td>
                                <a href="/supplier/pic/detail/{{ $pic->id }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('supplier.pic.edit', $pic->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('supplier.pic.delete', $pic->id) }}" method="POST" class="delete-form d-inline" data-name="{{ $pic->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-name="{{ $pic->name }}">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No data available in table</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $pics->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('.btn-delete').on('click', function (e) {
            e.preventDefault();

            const form = $(this).closest('form');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: `Data PIC "${name}" akan dihapus permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
