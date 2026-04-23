<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Purchase Order</title>
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
        .report-info {
            margin-bottom: 20px;
        }
        .report-info table {
            width: 100%;
        }
        .report-info td {
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
            text-align: left;
        }
        .order-details {
            margin-bottom: 10px;
            padding: 5px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PURCHASE ORDER</h1>
    </div>

    <div class="report-info">
        <table>
            <tr>
                <td width="150">Nama Supplier</td>
                <td width="10">:</td>
                <td>{{ $supplier->company_name }}</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td>{{ $startDate }} s/d {{ $endDate }}</td>
            </tr>
            <tr>
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td>{{ $generatedAt }}</td>
            </tr>
        </table>
    </div>

    @if($purchaseOrders->count() > 0)
        @php $totalAmount = 0; @endphp

        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. PO</th>
                    <th>Tanggal Order</th>
                    <th>Status</th>
                    <th>Jumlah Item</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrders as $index => $po)
                    @php $totalAmount += $po->total; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $po->po_number }}</td>
                        <td>{{ \Carbon\Carbon::parse($po->order_date)->format('d-m-Y') }}</td>
                        <td>{{ $po->status }}</td>
                        <td>{{ $po->details->count() }}</td>
                        <td align="right">{{ number_format($po->total, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" align="right">Total</th>
                    <th align="right">{{ number_format($totalAmount, 2, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

    @else
        <div class="no-data">
            <p>Tidak ada data purchase order untuk periode dan supplier yang dipilih</p>
        </div>
    @endif

</body>
</html>