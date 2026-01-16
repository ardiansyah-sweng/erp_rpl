<!DOCTYPE html>
<html>
<head>
    <title>Laporan Item - {{ $productType }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; padding: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f5f5f5; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Laporan Daftar Item - {{ $productType }}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Produk</th>
                <th>SKU</th>
                <th>Nama Item</th>
                <th>Unit</th>
                <th>Harga Jual</th>
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
                <td>Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 30px; font-size: 12px; text-align: right;">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>