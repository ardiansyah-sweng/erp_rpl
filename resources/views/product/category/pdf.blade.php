<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kategori</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Laporan Daftar Kategori</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Kategori Utama</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $cat)
                <tr>
                    <td>{{ $cat->id }}</td>
                    <td>{{ $cat->category }}</td>
                    <td>{{ $cat->parent ? $cat->parent->category : '-' }}</td>
                    <td>{{ $cat->active ? 'Aktif' : 'Tidak Aktif' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
