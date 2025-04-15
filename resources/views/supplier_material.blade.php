<!DOCTYPE html>
<html>
<head>
    <title>Form Uji Coba Tambah BOM</title>
</head>
<body>
    <h2>Form Tambah Data Bill of Material (Uji Coba)</h2>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('supplier-material.store') }}" method="POST">
        @csrf

        <label>BOM ID:</label><br>
        <input type="text" name="bom_id" required><br><br>

        <label>Nama BOM:</label><br>
        <input type="text" name="bom_name" required><br><br>

        <label>Satuan Ukur (Measurement Unit):</label><br>
        <input type="text" name="measurement_unit"><br><br>

        <label>SKU:</label><br>
        <input type="text" name="sku" required><br><br>

        <label>Total Biaya (Total Cost):</label><br>
        <input type="number" name="total_cost" step="0.01"><br><br>

        <label>Status Aktif (1 = aktif, 0 = nonaktif):</label><br>
        <input type="number" name="active" min="0" max="1" value="1"><br><br>

        <button type="submit">Tambah</button>
    </form>
</body>
</html>
