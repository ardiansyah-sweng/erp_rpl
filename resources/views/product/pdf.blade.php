<!DOCTYPE html>
<html>
<head>
    <title>Laporan Produk - {{ $type }}</title>
    <style>
        body { 
            font-family: sans-serif; 
            font-size: 14px; 
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #333; 
            padding: 8px; 
            text-align: left; 
            font-size: 12px;
        }
        th { 
            background-color: #f5f5f5; 
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Laporan Daftar Produk - {{ $type }}</h2>
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
                <!-- Kita coba beberapa nama kolom yang mungkin -->
                <td>{{ $product->product_id ?? $product->id }}</td> 
                <td>{{ $product->product_name ?? $product->name }}</td>
                <td>{{ $product->product_type ?? $product->type }}</td>
                <td>{{ $product->category_name }}</td>
                <td>{{ $product->product_description ?? $product->description ?? '-' }}</td>
            </tr>
            @endforeach
            @if(count($products) === 0)
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada produk untuk tipe ini</td>
                </tr>
            @endif
        </tbody>
    </table>
    <div style="margin-top: 30px; font-size: 12px; text-align: right;">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>