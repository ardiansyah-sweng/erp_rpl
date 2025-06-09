<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Daftar Produk {{ $type }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .preview-container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 1000px;
        }
        .preview-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .download-button {
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="preview-container">
            <div class="preview-header">
                <h2>Preview Daftar Produk</h2>
                <h4 class="text-muted">Tipe: {{ $type }}</h4>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
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
            </div>

            <div class="download-button">
                <p class="text-muted mb-3">Data yang akan dicetak sudah sesuai? Silakan klik tombol di bawah untuk mengunduh PDF.</p>
                <a href="{{ request()->url() }}?download=true" class="btn btn-primary">
                    <i class="bi bi-download"></i> Download PDF
                </a>
                <a href="{{ route('product.list') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="text-end text-muted mt-4">
                <small>Preview dibuat pada: {{ now()->format('d/m/Y H:i') }}</small>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
