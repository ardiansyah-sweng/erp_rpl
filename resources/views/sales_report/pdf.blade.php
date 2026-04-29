<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0 0 6px 0;
        }

        .meta {
            margin-bottom: 20px;
        }

        .meta table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta td {
            padding: 4px 0;
            vertical-align: top;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #cfcfcf;
            padding: 10px;
        }

        .summary-table th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .summary-table td:last-child {
            text-align: right;
        }

        .notes h2 {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .notes p {
            margin: 0 0 8px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN RINGKASAN PENJUALAN</h1>
        <p>ERP RPL UAD</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td width="140">Tanggal Cetak</td>
                <td width="10">:</td>
                <td>{{ $generatedAt }}</td>
            </tr>
            <tr>
                <td>Sumber Data</td>
                <td>:</td>
                <td>Tabel purchase_order</td>
            </tr>
        </table>
    </div>

    <table class="summary-table">
        <thead>
            <tr>
                <th>Metrik</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Sales</td>
                <td>Rp{{ number_format($totalSales, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Number of Transactions</td>
                <td>{{ number_format($transactionCount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Average Transaction Value</td>
                <td>Rp{{ number_format($averageTransactionValue, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="notes">
        <h2>Catatan Perhitungan</h2>
        <p>Total Sales dihitung dari penjumlahan seluruh nilai pada kolom <strong>purchase_order.total</strong>.</p>
        <p>Number of Transactions dihitung dari jumlah seluruh record pada tabel <strong>purchase_order</strong>.</p>
        <p>Average Transaction Value dihitung dari rata-rata nilai pada kolom <strong>purchase_order.total</strong>.</p>
    </div>
</body>
</html>
