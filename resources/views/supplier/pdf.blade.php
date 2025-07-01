<!DOCTYPE html>
<html>
<head>
    <title>Daftar Supplier</title>
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
            padding: 6px 10px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h3>Daftar Supplier</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Supplier</th>
                <th>Name</th>
                <th>Address</th>
                <th>Telephone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $i => $s)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $s->supplier_id }}</td>
                    <td>{{ $s->company_name ?? $s->name }}</td>
                    <td>{{ $s->address }}</td>
                    <td>{{ $s->phone_number ?? $s->telephone }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
