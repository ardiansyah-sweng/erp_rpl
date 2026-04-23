<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kategori</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Daftar Kategori</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Parent</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $index => $cat)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $cat->category }}</td>
                <td>{{ $cat->parent_id ?? '-' }}</td>
                <td>{{ $cat->is_active ? 'Aktif' : 'Non-Aktif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>