<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order #{{ $purchaseOrder->po_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .po-info {
            margin-bottom: 20px;
        }
        .po-info table {
            width: 100%;
        }
        .po-info td {
            padding: 3px 0;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, 
        table.data-table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 11px;
        }
        table.data-table th {
            background-color: #f2f2f2;
            text-align: center; /* Mengubah alignment header menjadi center */
            vertical-align: middle; /* Memastikan konten vertikal juga berada di tengah */
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PURCHASE ORDER</h1>
        <p>No. {{ $purchaseOrder->po_number }}</p>
    </div>

    <div class="po-info">
        <table>
            <tr>
                <td width="150">Supplier</td>
                <td width="10">:</td>
                <td>{{ $purchaseOrder->supplier->company_name }} ({{ $purchaseOrder->supplier_id }})</td>
            </tr>
            <tr>
                <td>Alamat Supplier</td>
                <td>:</td>
                <td>{{ $purchaseOrder->supplier->address }}</td>
            </tr>
            <tr>
                <td>Tanggal Order</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td>{{ $purchaseOrder->status }}</td>
            </tr>
        </table>
    </div>

    <h3>Detail Item:</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($purchaseOrder->details as $index => $detail)
                @php 
                    $subtotal = $detail->quantity * $detail->amount;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $detail->product_id }}</td>
                    <td align="center">{{ $detail->quantity }}</td>
                    <td align="right">Rp {{ number_format($detail->amount, 0, ',', '.') }}</td>
                    <td align="right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" align="right">Total</th>
                <th align="right">Rp {{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 40px;">
        <table width="100%">
            <tr>
                <td width="50%" style="text-align: center;">
                    Disiapkan Oleh,
                    <br><br><br><br>
                    _______________
                    <br>
                    Purchasing
                </td>
                <td width="50%" style="text-align: center;">
                    Disetujui Oleh,
                    <br><br><br><br>
                    _______________
                    <br>
                    Manager
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak pada: {{ $generatedAt }}</p>
    </div>
</body>
</html>