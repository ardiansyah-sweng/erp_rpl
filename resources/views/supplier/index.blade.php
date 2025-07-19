<!-- resources/views/supplier/index.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Supplier</title>
</head>
<body>
    <h1>Daftar Supplier</h1>

    @if($suppliers->isEmpty())
        <p>Tidak ada supplier.</p>
    @else
        <ul>
        @foreach($suppliers as $supplier)
            <li>{{ $supplier->company_name }} - {{ $supplier->orders_count }} pesanan</li>
        @endforeach
        </ul>
    @endif
</body>
</html>
