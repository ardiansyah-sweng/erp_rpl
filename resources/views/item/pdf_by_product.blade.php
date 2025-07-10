//untuk desain tampilan pdfnya
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Item - {{ $productType }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Laporan Item Berdasarkan Product Type: {{ $productType }}</h2>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Item</th>
                <th>Satuan</th>
                <th>Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->measurement_unit }}</td>
                    <td>{{ number_format($item->selling_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>