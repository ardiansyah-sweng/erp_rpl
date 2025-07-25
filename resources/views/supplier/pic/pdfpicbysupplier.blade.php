<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar PIC Supplier</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .supplier-info {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <h2>Daftar PIC - Supplier {{ $supplier->name ?? '-' }}</h2>

    <div class="supplier-info">
        <strong>Supplier ID:</strong> {{ $supplier->supplier_id ?? '-' }}<br>
        <strong>Nama Supplier:</strong> {{ $supplier->name ?? '-' }}<br>
        <strong>Alamat:</strong> {{ $supplier->address ?? '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama PIC</th>
                <th>Email</th>
                <th>No. HP</th>
                <th>Tanggal Ditugaskan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pics as $index => $pic)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pic->name }}</td>
                    <td>{{ $pic->email }}</td>
                    <td>{{ $pic->phone_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($pic->assigned_date)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
