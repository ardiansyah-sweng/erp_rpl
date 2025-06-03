<!DOCTYPE html>
<html>
<head>
    <title>Daftar Produk {{ $type }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        .table th, .table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background-color: #f4f4f4;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <h1 class="title">Daftar Produk</h1>
    <h3 class="subtitle">Tipe: {{ $type }}</h3>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Produk</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->product_id }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->category ? $product->category->category : '-' }}</td>
                <td>{{ $product->product_description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align: right; margin-top: 20px;">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
