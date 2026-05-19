<!DOCTYPE html>
<html>
<head>
    <title>Laporan Warehouse</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2 class="text-center">Laporan Data Warehouse</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Warehouse Name</th>
                <th>Warehouse Address</th>
                <th>Warehouse Telephone</th>
                <th>RM Warehouse</th>
                <th>FG Warehouse</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($warehouse as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->warehouse_name }}</td>
                <td>{{ $item->warehouse_address }}</td>
                <td>{{ $item->warehouse_phone }}</td>
                <td class="text-center">{{ $item->is_rm_warehouse ? 'Ya' : 'Tidak' }}</td>
                <td class="text-center">{{ $item->is_fg_warehouse ? 'Ya' : 'Tidak' }}</td>
                <td class="text-center">{{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>