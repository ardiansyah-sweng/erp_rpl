<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Item</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Data Item</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Product ID</th>
                <th>SKU</th>
                <th>Item Name</th>
                <th>measurement unit</th>
                <th>avg base price</th>
                <th>selling_price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product_id }}</td>
                <td>{{ $item->sku }}</td>
                <td>{{ $item->item_name }}</td>
                <td>{{ $item->measurement_unit }}</td>
                <td>{{ $item->avg_base_price }}</td>
                <td>{{ $item->selling_price }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
