@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar PIC Supplier</h1>
    <table id="pic-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama PIC</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Supplier</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pics as $index => $pic)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pic->nama }}</td>
                <td>{{ $pic->email }}</td>
                <td>{{ $pic->telepon }}</td>
                <td>{{ $pic->supplier->nama }}</td>
                <td>
                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<!-- Tambahkan DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#pic-table').DataTable();
    });
</script>
@endsection

public function index()
{
    $pics = PicSupplier::with('supplier')->get();
    return view('supplier.pic.list', compact('pics'));
}

<!-- resources/views/supplier/pic/list.blade.php -->

@extends('layouts.app') <!-- Bisa sesuaikan dengan layout utama yang kamu pakai -->

@section('content')
    <div class="container">
        <h1>Daftar Supplier PIC</h1>

        <!-- Tabel untuk DataTables -->
        <table id="picTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Supplier</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Assigned Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pics as $pic)
                    <tr>
                        <td>{{ $pic->id }}</td>
                        <td>{{ $pic->supplier->company_name }}</td> <!-- Menampilkan nama supplier -->
                        <td>{{ $pic->name }}</td>
                        <td>{{ $pic->phone_number }}</td>
                        <td>{{ $pic->email }}</td>
                        <td>{{ $pic->assigned_date }}</td>
                        <td>{{ $pic->active ? 'Active' : 'Inactive' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <!-- Tambahkan script DataTables -->
    <script>
        $(document).ready(function() {
            $('#picTable').DataTable();
        });
    </script>
@endsection

<!-- resources/views/supplier/pic/list.blade.php -->

@section('styles')
    <!-- Tambahkan CSS DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endsection

@section('scripts')
    <!-- Tambahkan JS DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#picTable').DataTable(); // Inisialisasi DataTables pada tabel
        });
    </script>
@endsection
