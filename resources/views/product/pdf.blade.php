<!DOCTYPE html>
<html>
<head>
    <title>Daftar Produk</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Daftar Produk</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Produk</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->product_id }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->product_type }}</td>
                <td>{{ $product->product_category }}</td>
                <td>{{ $product->product_description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
