<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bill of Material (BOM)</title>
    <style>
        body {
            font-size: 10pt;
            margin: 30px;
            font-family: Arial, Helvetica, sans-serif;
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .info {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h3>Laporan Bill of Material (BOM)</h3>
    <div class="info">
        <strong>Generated At:</strong> {{ now()->format('d-m-Y H:i:s') }}
    </div>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th>ID BOM</th>
                <th>Nama BOM</th>
                <th>Measurement Unit</th>
                <th>Total Cost</th>
                <th class="text-center">Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($boms as $i => $bom)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $bom->bom_id }}</td>
                    <td>{{ $bom->bom_name }}</td>
                    <td>{{ $bom->measurement_unit }}</td>
                    <td>Rp{{ number_format($bom->total_cost, 0, ',', '.') }}</td>
                    <td class="text-center">
                        {{ $bom->active ? 'AKTIF' : 'NONAKTIF' }}
                    </td>
                    <td>{{ $bom->created_at ? $bom->created_at->format('d-m-Y') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
