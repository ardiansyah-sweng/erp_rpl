<!DOCTYPE html>
<html>
<head>
    <title>Bill of Material - {{ $bom->bom_id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; line-height: 1.5; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 22px; text-transform: uppercase; color: #111; }
        .header .subtitle { font-size: 11px; color: #666; margin-top: 5px; }
        .bom-info { width: 100%; margin-bottom: 25px; }
        .bom-info td { padding: 4px 0; vertical-align: top; }
        .bom-info td.label { font-weight: bold; width: 20%; }
        .bom-info td.value { width: 30%; }
        .bom-info td.spacer { width: 10%; }
        .details-title { font-size: 14px; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; color: #555; }
        table.details-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.details-table th, table.details-table td { border: 1px solid #999; padding: 8px 10px; text-align: left; }
        table.details-table th { background-color: #f2f2f2; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        table.details-table tr:nth-child(even) td { background-color: #fafafa; }
        .text-right { text-align: right; }
        .badge { display: inline-block; padding: 2px 5px; font-size: 10px; font-weight: bold; border-radius: 3px; text-transform: uppercase; }
        .bg-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .bg-secondary { background-color: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ccc; padding-top: 5px; }
    </style>
</head>
<body>
    @php
        $measurement_units = \App\Models\MeasurementUnit::all();
        $matchedUnit = $measurement_units->firstWhere('id', $bom->measurement_unit);
    @endphp

    <div class="header">
        <h2>Bill of Material (BOM)</h2>
        <div class="subtitle">ERP RPL UAD &bull; Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</div>
    </div>

    <table class="bom-info">
        <tr>
            <td class="label">BOM ID</td>
            <td class="value">: <strong>{{ $bom->bom_id }}</strong></td>
            <td class="spacer"></td>
            <td class="label">Status</td>
            <td class="value">: 
                <span class="badge {{ $bom->active ? 'bg-success' : 'bg-secondary' }}">
                    {{ $bom->active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="label">Nama BOM</td>
            <td class="value">: {{ $bom->bom_name }}</td>
            <td class="spacer"></td>
            <td class="label">Tanggal Dibuat</td>
            <td class="value">: {{ \Carbon\Carbon::parse($bom->created_at)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td class="label">Satuan</td>
            <td class="value">: {{ $matchedUnit ? $matchedUnit->unit_name : $bom->measurement_unit }}</td>
            <td class="spacer"></td>
            <td class="label">Total Biaya (HPP)</td>
            <td class="value">: <strong>Rp. {{ number_format($bom->total_cost, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <div class="details-title">Komponen Penyusun (BOM Details)</div>
    <table class="details-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 42%;">SKU Item</th>
                <th style="width: 25%;">Quantity</th>
                <th style="width: 25%;" class="text-right">Cost Per Unit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $detail->sku }}</strong></td>
                    <td>{{ number_format($detail->quantity, 2, ',', '.') }}</td>
                    <td class="text-right">Rp. {{ number_format($detail->cost, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #999; padding: 15px;">Tidak ada komponen penyusun untuk BOM ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh Sistem ERP RPL UAD &copy; {{ date('Y') }}
    </div>
</body>
</html>
