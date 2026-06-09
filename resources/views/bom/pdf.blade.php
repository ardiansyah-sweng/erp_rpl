<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bill of Material - {{ $bom->bom_id }}</title>
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
        .info-section {
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: bold;
            width: 20%;
            vertical-align: top;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Bill of Material - {{ $bom->bom_id }}</h2>

    <table>
        <tbody>
            <tr>
                <td class="info-label">BOM ID</td>
                <td>{{ $bom->bom_id }}</td>
            </tr>
            <tr>
                <td class="info-label">Nama BOM</td>
                <td>{{ $bom->bom_name }}</td>
            </tr>
            <tr>
                <td class="info-label">Measurement Unit</td>
                <td>{{ $bom->measurement_unit }}</td>
            </tr>
            <tr>
                <td class="info-label">Total Cost (HPP)</td>
                <td>Rp {{ number_format($bom->total_cost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="info-label">Status</td>
                <td>
                    @if($bom->active)
                        AKTIF
                    @else
                        TIDAK AKTIF
                    @endif
                </td>
            </tr>
            <tr>
                <td class="info-label">Tanggal Dibuat</td>
                <td>{{ \Carbon\Carbon::parse($bom->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td class="info-label">Terakhir Diperbarui</td>
                <td>{{ \Carbon\Carbon::parse($bom->updated_at)->format('d/m/Y H:i') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Detail Material / Komponen</div>

    @if($details->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>SKU</th>
                    <th>Qty</th>
                    <th>Cost (Rp)</th>
                    <th>Subtotal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php $totalSubtotal = 0; @endphp
                @foreach($details as $i => $detail)
                    @php
                        $subtotal = $detail->quantity * $detail->cost;
                        $totalSubtotal += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $detail->sku }}</td>
                        <td>{{ number_format($detail->quantity, 0, ',', '.') }}</td>
                        <td>{{ number_format($detail->cost, 0, ',', '.') }}</td>
                        <td>{{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="font-weight: bold; text-align: right;">Total</td>
                    <td style="font-weight: bold;">{{ number_format($totalSubtotal, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #666;">Belum ada detail material untuk BOM ini.</p>
    @endif

    <div style="margin-top: 30px; font-size: 12px; text-align: right;">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
