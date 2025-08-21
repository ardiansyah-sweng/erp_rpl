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
    {{-- Jika semua kategori --}}
    @if(isset($categories))
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

    {{-- Jika hanya kategori tertentu --}}
    @elseif(isset($categoryList))
        @foreach ($categoryList as $category)
            <h3>Kategori: {{ $category->category }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID Produk</th>
                        <th>Nama Produk</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($category->products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->product_name }}</td>
                        </tr>
                    @endforeach
                </tbody> 
            </table>
            <br>
        @endforeach
    @endif

</body>
</html> 
