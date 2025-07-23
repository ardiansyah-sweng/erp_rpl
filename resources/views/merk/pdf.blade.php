<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Merk</title>
    <style>
        body { font-family: sans-serif; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #333;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Daftar Merk</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Merk</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $merk)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $merk->merk }}</td>
                <td>{{ $merk->active ? 'Aktif' : 'Non Aktif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
