<!DOCTYPE html>
<html>
<head>
    <title>Laporan Item per Kategori</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Item Berdasarkan Kategori (ID: {{ $categoryId }})</h2>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Item</th>
                <th>Unit</th>
                <th>Harga Jual</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->measurement_unit }}</td>
                    <td>{{ $item->selling_price }}</td>
                    <td>{{ $item->stock_unit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
