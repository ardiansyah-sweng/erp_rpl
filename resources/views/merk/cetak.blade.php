<!DOCTYPE html>
<html>
<head>
    <title> Cetak Laporan Merk</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Laporan Daftar Merk</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Merk</th>
                <th>Status Aktif</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($merks as $merk)
                <tr>
                    <td>{{ $merk->id }}</td>
                    <td>{{ $merk->merk }}</td>
                    <td>{{ $merk->active ? 'Aktif' : 'Tidak Aktif' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
