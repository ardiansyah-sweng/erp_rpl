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
    <h2>Laporan Data Cabang</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Cabang</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branches as $branch)
            <tr>
                <td>{{ $branch->branch_name }}</td>
                <td>{{ $branch->branch_address }}</td>
                <td>{{ $branch->branch_telephone }}</td>
                <td>{{ $branch->branch_status == 1 ? 'Aktif' : 'Tidak Aktif'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>