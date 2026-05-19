<!DOCTYPE html>
<html>
<head>
    <title>Laporan Cabang</title>
    <style>
        body { font-family: sans-serif; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
        }
    </style>
</head>
<body>
    <h2>Laporan Data Gudang</h2>
    <table>
        <thead>
            <tr>
                <th>Warehouse Name</th>
                <th>Warehouse Addres</th>
                <th>Warehouse Telephone</th>
                <th>Is Active</th>
            </tr>
        </thead>
        <tbody>
            @foreach($warehouse as $warehousePdf)
            <tr>
                <td>{{ $warehousePdf['warehouse_name'] }}</td>
                <td>{{ $warehousePdf['warehouse_address'] }}</td>
                <td>{{ $warehousePdf['warehouse_telephone'] }}</td>
                <td>{{ $warehousePdf['is_active'] ? '1' : '0'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
