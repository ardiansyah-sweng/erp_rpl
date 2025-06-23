<!-- resources/views/item/pdf_by_product.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>PDF Item</title>
</head>
<body>
    <h2>PDF Item untuk berdasarkan category tertentu</h2>
    <p>Categori item: {{ $productId }}</p>
    <p>Jumlah item: {{ count($items) }}</p>

    {{-- Daftar item bisa ditampilkan di sini nanti --}}
</body>
</html>
