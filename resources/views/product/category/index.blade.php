<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar Kategori</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 15px;
        }
        th {
            background-color: #f2f2f2;
        }
        a.button {
            padding: 6px 12px;
            background-color: #3490dc;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        a.button:hover {
            background-color: #2779bd;
        }
    </style>
</head>
<body>
    <h2>Daftar Kategori</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->category }}</td>
                    <td>
                        <span >
                            {{ $category->active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a class="button" href="{{ url('/product/category/' . $category->id) }}">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Tidak ada kategori tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
