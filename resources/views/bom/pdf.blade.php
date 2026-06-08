<!DOCTYPE html>
<html>
<head>
    <title>Laporan Daftar Bill of Material (BOM)</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0 0 10px 0; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 0; color: #666; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #999; padding: 10px 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        tr:nth-child(even) td { background-color: #fafafa; }
        .badge { display: inline-block; padding: 3px 7px; font-size: 10px; font-weight: bold; border-radius: 3px; text-transform: uppercase; }
        .bg-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .bg-secondary { background-color: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ccc; padding-top: 5px; }
    </style>
</head>
<body>
    @php
        $measurement_units = \App\Models\MeasurementUnit::all();
    @endphp

    <div class="header">
        <h2>Laporan Daftar Bill of Material (BOM)</h2>
        <p>ERP RPL UAD &bull; Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">ID BOM</th>
                <th style="width: 30%;">Nama BOM / Resep</th>
                <th style="width: 15%;">Satuan</th>
                <th style="width: 15%;" class="text-right">Total Cost</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 10%;">Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($boms as $index => $bom)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $bom->bom_id }}</strong></td>
                    <td>{{ $bom->bom_name }}</td>
                    <td>
                        @php
                            $matchedUnit = $measurement_units->firstWhere('id', $bom->measurement_unit);
                        @endphp
                        {{ $matchedUnit ? $matchedUnit->unit_name : $bom->measurement_unit }}
                    </td>
                    <td class="text-right">Rp. {{ number_format($bom->total_cost, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $bom->active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $bom->active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($bom->created_at)->format('d-m-Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #999;">Belum ada data Bill of Material.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem ERP RPL UAD &copy; {{ date('Y') }}
    </div>
</body>
</html>
