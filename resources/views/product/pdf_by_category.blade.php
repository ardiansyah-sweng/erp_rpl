<!DOCTYPE html> 
<html>
<head>
    <title>Produk Kategori {{ $product_category }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Daftar Produk pada Kategori: {{ $product_category}}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Id product</th>
                <th>Product Name</th>
                <th>Product type</th>
                <th>Product Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product ->product_id }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->product_type }}</td>
                    <td>{{ $product->product_description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 
