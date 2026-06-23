<!DOCTYPE html>
<html>
<head>
    <title>Data Seluruh Supplier Material</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; margin: 10px; }
        h1 { text-align: center; margin-bottom: 5px; font-size: 16px; }
        .date { text-align: center; margin-bottom: 15px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .supplier-section { margin-top: 20px; page-break-inside: avoid; }
        .supplier-title { font-weight: bold; font-size: 12px; margin-bottom: 10px; background-color: #e8e8e8; padding: 5px; }
    </style>
</head>
<body>
    <h1>Laporan Seluruh Supplier Material</h1>
    <div class="date">Tanggal: {{ date('d-m-Y') }}</div>

    @php
        $groupedMaterials = $materials->groupBy('supplier_id');
    @endphp

    @foreach($groupedMaterials as $supplierId => $items)
        <div class="supplier-section">
            <div class="supplier-title">Supplier: {{ $supplierId }} - {{ $items->first()->company_name ?? 'N/A' }}</div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Base Price</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i => $material)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $material->product_id }}</td>
                            <td>{{ $material->product_name }}</td>
                            <td>{{ number_format($material->base_price, 0, ',', '.') }}</td>
                            <td>{{ $material->created_at }}</td>
                            <td>{{ $material->updated_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div style="margin-top: 30px; text-align: center; font-size: 10px;">
        <p>Total Supplier: {{ $groupedMaterials->count() }}</p>
        <p>Total Material: {{ $materials->count() }}</p>
    </div>
</body>
</html>
