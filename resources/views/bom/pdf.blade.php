<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bill of Material</title>
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
        .detail-table {
            margin-top: 6px;
            width: 100%;
        }
        .detail-table th, .detail-table td {
            font-size: 11px;
        }
        .no-detail {
            font-size: 11px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h2>Laporan Daftar Bill of Material</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>BOM ID</th>
                <th>Nama BOM</th>
                <th>Measurement Unit</th>
                <th>Total Cost</th>
                <th>Status</th>
                <th>Tanggal Dibuat</th>
                <th>Detail Material</th>
            </tr>
        </thead>
        <tbody>
            @forelse($boms as $index => $bom)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $bom->bom_id }}</td>
                <td>{{ $bom->bom_name }}</td>
                <td>{{ $bom->measurement_unit }}</td>
                <td>Rp {{ number_format($bom->total_cost, 0, ',', '.') }}</td>
                <td>{{ $bom->active ? 'Aktif' : 'Tidak Aktif' }}</td>
                <td>{{ \Carbon\Carbon::parse($bom->created_at)->format('d/m/Y') }}</td>
                <td>
                    @if($bom->details && count($bom->details) > 0)
                        <table class="detail-table">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bom->details as $detail)
                                <tr>
                                    <td>{{ $detail->sku }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>Rp {{ number_format($detail->cost, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <span class="no-detail">Tidak ada detail</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data Bill of Material</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 30px; font-size: 12px; text-align: right;">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
