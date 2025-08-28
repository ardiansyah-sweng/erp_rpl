<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kategori</title>
</head>
<body>
    <h1>Daftar Kategori</h1>
    <ul>
        @forelse ($category as $item)
            <li>{{ $item->category }}</li>
        @empty
            <li>Tidak ada kategori ditemukan.</li>
        @endforelse
    </ul>
</body>
</html>
