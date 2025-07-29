<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Supplier PIC</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 10px;
            border: 1px solid #000;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Data Supplier PIC untuk Supplier ID: {{ $supplier_id }}</h2>

    @php
        // Data dummy langsung di-inject di view
        $dummyPICs = [
            ['name' => 'Ahmad Faiz', 'email' => 'faiz@example.com', 'phone' => '0812-3456-7890'],
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '0821-1234-5678'],
            ['name' => 'Citra Lestari', 'email' => 'citra@example.com', 'phone' => '0856-7890-1234'],
        ];
    @endphp

    <table>
        <thead>
            <tr>
                <th>Nama PIC</th>
                <th>Email</th>
                <th>Telepon</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dummyPICs as $pic)
            <tr>
                <td>{{ $pic['name'] }}</td>
                <td>{{ $pic['email'] }}</td>
                <td>{{ $pic['phone'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
