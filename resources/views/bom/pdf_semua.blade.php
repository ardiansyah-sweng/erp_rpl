<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Daftar Bill of Material</title>
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
    <h2>Daftar Bill of Material</h2>

    @if($boms->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>BOM ID</th>
                    <th>Nama BOM</th>
                    <th>Measurement Unit</th>
                    <th>Total Cost (Rp)</th>
                    <th>Status</th>
                    <th>Tanggal Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($boms as $i => $bom)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $bom->bom_id }}</td>
                        <td>{{ $bom->bom_name }}</td>
                        <td>{{ $bom->measurement_unit }}</td>
                        <td>{{ number_format($bom->total_cost, 0, ',', '.') }}</td>
                        <td>
                            @if($bom->active)
                                AKTIF
                            @else
                                TIDAK AKTIF
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($bom->created_at)->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-top: 10px;">Total BOM: {{ $boms->count() }}</p>
    @else
        <p style="text-align: center; color: #666;">Belum ada data Bill of Material.</p>
    @endif

    <div style="margin-top: 30px; font-size: 12px; text-align: right;">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
