<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Item per Kategori</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h3>Laporan Item Berdasarkan Kategori: {{ $productType }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>SKU</th>
                <th>Nama Item</th>
                <th>Unit</th>
                <th>Harga Dasar</th>
                <th>Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
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
