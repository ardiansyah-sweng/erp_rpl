<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data PIC Supplier - {{ $supplierID }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            margin: 30px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1a1a1a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #2F80ED;
            color: white;
            font-weight: bold;
            padding: 6px;
            border: 1px solid #ccc;
            text-align: center;
        }
        td {
            padding: 6px;
            border: 1px solid #ccc;
            vertical-align: top;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 10px;
            color: #777;
        }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2>Daftar PIC Supplier<br><small>ID: {{ $supplierID }}</small></h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No. HP</th>
                <th>Status Aktif</th>
                <th>Tgl Ditugaskan</th>
                <th>Lama Ditugaskan</th>
                <th>Dibuat</th>
                <th>Diubah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($supplierPICs as $index => $pic)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $pic->name }}</td>
                    <td>{{ $pic->email }}</td>
                    <td>{{ $pic->phone_number }}</td>
                    <td class="text-center">{{ $pic->active ? 'Aktif' : 'Tidak Aktif' }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pic->assigned_date)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ $pic->lama_assigned }} hari</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pic->created_at)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pic->updated_at)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>