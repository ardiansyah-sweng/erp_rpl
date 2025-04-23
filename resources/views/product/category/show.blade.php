<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detail Kategori</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            max-width: 600px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h3 {
            margin-bottom: 10px;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            background-color: green;
        }
        .badge.inactive {
            background-color: red;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Detail Kategori</h2>

        <div class="section">
            <strong>ID:</strong> {{ $category->id }} <br>
            <strong>Nama Kategori:</strong> {{ $category->category }} <br>
            <strong>Status:</strong>
            <span class="badge {{ $category->active ? '' : 'inactive' }}">
                {{ $category->active ? 'Aktif' : 'Nonaktif' }}
            </span> <br>
            <strong>Dibuat pada:</strong> {{ $category->created_at }} <br>
            <strong>Terakhir diupdate:</strong> {{ $category->updated_at }}
        </div>

        <div class="section">
            <h3>Kategori Induk</h3>
            @if ($category->parent)
                <p>{{ $category->parent->category }}</p>
            @else
                <p><em>Tidak memiliki kategori induk</em></p>
            @endif
        </div>

        <div class="section">
            <h3>Sub-Kategori</h3>
            @if ($category->children->count())
                <ul>
                    @foreach ($category->children as $child)
                        <li>{{ $child->category }}</li>
                    @endforeach
                </ul>
            @else
                <p><em>Tidak memiliki sub-kategori</em></p>
            @endif
        </div>
    </div>
</body>
</html>
