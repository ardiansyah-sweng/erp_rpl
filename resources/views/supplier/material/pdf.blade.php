<!DOCTYPE html>
<html>
<head>
    <title>Data Material Supplier</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        h2 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>Data Material dari Supplier: {{ $supplier_id }} - {{ $supplierName }}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Base Price</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $i => $m)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $m->product_id }}</td>
                    <td>{{ $m->product_name }}</td>
                    <td>{{ number_format($m->base_price) }}</td>
                    <td>{{ $m->created_at }}</td>
                    <td>{{ $m->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
