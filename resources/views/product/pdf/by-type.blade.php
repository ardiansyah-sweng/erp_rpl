<!DOCTYPE html>
<html>
<head>
    <title>Products List - {{ $type }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Products List - {{ $type }}</h2>
        <p>Generated on: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Jumlah Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->category?->category ?? 'N/A' }}</td>
                <td>{{ $product->product_description }}</td>
                <td>{{ $product->items_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} {{ config('app.name') }} - All rights reserved</p>
    </div>
</body>
</html>
