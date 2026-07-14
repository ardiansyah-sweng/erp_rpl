<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $goodsReturn->return_number }} - Return Barang</title>
    <style>
        body {
            color: #222;
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        .header {
            border-bottom: 2px solid #333;
            margin-bottom: 24px;
            padding-bottom: 12px;
            text-align: center;
        }

        .header h1 {
            font-size: 20px;
            margin: 0 0 4px;
        }

        .header p {
            margin: 0;
        }

        .data-table {
            border-collapse: collapse;
            width: 100%;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #aaa;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        .data-table th {
            background-color: #f2f2f2;
            width: 32%;
        }

        .footer {
            color: #666;
            font-size: 10px;
            margin-top: 24px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUKTI RETURN BARANG</h1>
        <p>ERP RPL UAD</p>
    </div>

    <table class="data-table">
        <tr>
            <th>Nomor Return</th>
            <td>{{ $goodsReturn->return_number }}</td>
        </tr>
        <tr>
            <th>Goods Receipt Note</th>
            <td>GRN #{{ $goodsReturn->grn_id }}</td>
        </tr>
        <tr>
            <th>Purchase Order</th>
            <td>{{ $goodsReturn->po_number }}</td>
        </tr>
        <tr>
            <th>Supplier</th>
            <td>{{ $goodsReturn->purchaseOrder?->supplier?->company_name ?? 'Tidak ada data' }}</td>
        </tr>
        <tr>
            <th>Item</th>
            <td>{{ $goodsReturn->product_id }} - {{ $goodsReturn->item?->name ?? 'Nama item tidak ditemukan' }}</td>
        </tr>
        <tr>
            <th>Tanggal Penerimaan</th>
            <td>{{ \Carbon\Carbon::parse($goodsReturn->goodsReceiptNote?->delivery_date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Tanggal Return</th>
            <td>{{ $goodsReturn->return_date->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Jumlah Return</th>
            <td>{{ number_format($goodsReturn->return_quantity, 0, ',', '.') }} unit</td>
        </tr>
        <tr>
            <th>Alasan Return</th>
            <td>{{ $goodsReturn->reason }}</td>
        </tr>
        <tr>
            <th>Dicatat Pada</th>
            <td>{{ $goodsReturn->created_at->format('d/m/Y H:i:s') }}</td>
        </tr>
    </table>

    <div class="footer">
        Dokumen dicetak pada {{ $generatedAt }}
    </div>
</body>
</html>
