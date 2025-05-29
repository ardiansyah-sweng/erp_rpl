<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Purchase Order</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
        }

        .header-info {
            margin-bottom: 20px;
        }

        .header-info p {
            margin: 5px 0;
        }

        .total-row {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Purchase Order #{{ $purchaseOrder->po_number }}</h1>

    <table>
        <tr>
            <td width="150"><strong>Supplier:</strong></td>
            <td>{{ $purchaseOrder->supplier->name ?? ($purchaseOrder->supplier_id ?? '-') }}</td>
        </tr>
        <tr>
            <td width="150"><strong>Supplier:</strong></td>
            <td>{{ $purchaseOrder->supplier ? $purchaseOrder->supplier->company_name : 'Supplier not found' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal:</strong></td>
            <td>{{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d/m/Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Status:</strong></td>
            <td>{{ $purchaseOrder->status ?? 'Draft' }}</td>
        </tr>
        <tr>
            <td width="150"><strong>Total:</strong></td>
            <td>
                {{ $purchaseOrder->total ? 'Rp' . number_format($purchaseOrder->total, 0, ',', '.') : $purchaseOrder->supplier_id ?? '-' }}
            </td>
        </tr>
        <tr>
            <td width="150"><strong>Branch Id:</strong></td>
            <td>{{ $purchaseOrder->branch_id ?? ($purchaseOrder->supplier_id ?? '-') }}</td>
        </tr>
    </table>


</body>

</html>
