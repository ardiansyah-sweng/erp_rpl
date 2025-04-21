<!DOCTYPE html>
<html>
<head>
    <title>Laporan Daftar Cabang</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Laporan Daftar Cabang</h2>
    <p>Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Cabang</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $cabang)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $cabang->merk ?? '-' }}</td>
                <td>{{ $cabang->active ? 'Aktif' : 'Tidak Aktif' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
