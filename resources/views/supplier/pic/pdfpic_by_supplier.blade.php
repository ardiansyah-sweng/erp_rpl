<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar PIC Supplier - {{ $supplier_id }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .small {
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h2>Daftar PIC Supplier ({{ $supplier_id }})</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Supplier ID</th>
                <th>Nama</th>
                <th>No. HP</th>
                <th>Email</th>
                <th>Active</th>
                <th>Avatar</th>
                <th>Tanggal Penugasan</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pics as $index => $pic)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pic->id }}</td>
                <td>{{ $pic->supplier_id }}</td>
                <td>{{ $pic->name }}</td>
                <td>{{ $pic->phone_number }}</td>
                <td class="small">{{ $pic->email }}</td>
                <td>{{ $pic->active == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                <td>
                    @if ($pic->avatar ?? false)
                        <img src="{{ $pic->avatar }}" alt="Foto" width="50">
                    @else
                        -
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($pic->assigned_date)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($pic->created_at)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($pic->updated_at)->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
