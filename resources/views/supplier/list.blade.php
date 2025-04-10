@extends('layouts.app') {{-- sesuaikan jika kamu pakai layout lain --}}

@section('content')
<div class="container">
    <h2>Suppliers</h2>
    <a href="#" class="btn btn-primary mb-3">New Supplier</a>
    <table id="supplier-table" class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Supplier</th>
                <th>Name</th>
                <th>Address</th>
                <th>Telephone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $index => $supplier)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $supplier->id_supplier }}</td>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->address }}</td>
                <td>{{ $supplier->telephone }}</td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                    <a href="#" class="btn btn-sm btn-info">Create PO</a>
                    <a href="#" class="btn btn-sm btn-primary">Add Pic</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#supplier-table').DataTable();
});
</script>
@endpush
