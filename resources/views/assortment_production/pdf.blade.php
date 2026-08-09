<!DOCTYPE html>
<html>
<head>
    <title>Laporan Assortment Production</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Data Assortment Production</h2>
    <table>
        <thead>
            <tr>
                <th>No</th><th>No. Produksi</th><th>SKU</th>
                <th>Tanggal Produksi</th><th>Tanggal Selesai</th>
                <th>Status</th><th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($production as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->production_number }}</td>
                <td>{{ $item->sku }}</td>
                <td>{{ $item->production_date }}</td>
                <td>{{ $item->finished_date ?? '-' }}</td>
                <td>{{ $item->in_production ? 'Sedang Produksi' : 'Selesai' }}</td>
                <td>{{ $item->description ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>