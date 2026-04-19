<!DOCTYPE html>
<html>
<head>
    {{-- Judul Tab Browser --}}
    <title>Laporan Kategori - {{ $typeName }}</title>
    <style>
        body { 
            font-family: sans-serif; 
            font-size: 14px; 
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
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
    {{-- Judul Laporan Dinamis (Bisa jadi Makanan / Minuman / Snack) --}}
    <h2>Laporan Daftar Kategori - {{ $typeName }}</h2>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Kategori Utama</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $index => $cat)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $cat->id }}</td>
                <td>{{ $cat->category }}</td>
                <td>{{ $cat->parent ? $cat->parent->category : '-' }}</td>
                <td>{{ $cat->is_active == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
            </tr>
            @empty
            <tr>
                {{-- Tetap di dalam tabel, tapi kasih keterangan --}}
                <td colspan="5" style="text-align: center; font-style: italic;">
                    Tidak ada data produk untuk tipe {{ $typeName }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 12px; text-align: right;">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>