<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Cetak Data Merk</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        h2 { text-align: center; }
        p.subtitle { text-align: center; margin-top: -10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px 10px; }
        th { background-color: #f0f0f0; text-align: center; }
        td.center { text-align: center; }
        .badge-aktif { color: green; font-weight: bold; }
        .badge-nonaktif { color: red; font-weight: bold; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <h2>Data Merk</h2>
    <p class="subtitle">ERP RPL UAD &mdash; Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Merk</th>
                <th>Status</th>
                <th>Dibuat</th>
                <th>Diperbarui</th>
            </tr>
        </thead>
        <tbody>
            @forelse($merks as $index => $merk)
                <tr>
                    <td class="center">{{ $merk->id }}</td>
                    <td>{{ $merk->merk }}</td>
                    <td class="center">
                        @if($merk->is_active)
                            <span class="badge-aktif">Aktif</span>
                        @else
                            <span class="badge-nonaktif">Nonaktif</span>
                        @endif
                    </td>
                    <td class="center">{{ $merk->created_at ? $merk->created_at->format('d/m/Y') : '-' }}</td>
                    <td class="center">{{ $merk->updated_at ? $merk->updated_at->format('d/m/Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data merk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>