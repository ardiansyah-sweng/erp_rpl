<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order - {{ $purchaseOrder->po_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
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
            padding: 6px;
            font-size: 11px;
        }
        table.data-table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .total-row td {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .signature {
            margin-top: 60px;
            width: 100%;
        }
        .signature td {
            text-align: center;
            width: 33%;
            vertical-align: top;
        }
        .signature .line {
            margin-top: 50px;
            border-top: 1px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        .badge-approved { color: green; font-weight: bold; }
        .badge-pending  { color: orange; font-weight: bold; }
        .badge-other    { color: gray; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>PURCHASE ORDER</h1>
        <p>{{ config('app.name', 'ERP RPL UAD') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="150">No. PO</td>
            <td width="10">:</td>
            <td><strong>{{ $purchaseOrder->po_number }}</strong></td>
            <td width="150">Tanggal Order</td>
            <td width="10">:</td>
            <td>{{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Supplier</td>
            <td>:</td>
            <td>{{ $purchaseOrder->supplier->company_name ?? '-' }}</td>
            <td>Status</td>
            <td>:</td>
            <td>
                @if($purchaseOrder->status === 'Approved')
                    <span class="badge-approved">{{ $purchaseOrder->status }}</span>
                @elseif($purchaseOrder->status === 'Pending')
                    <span class="badge-pending">{{ $purchaseOrder->status }}</span>
                @else
                    <span class="badge-other">{{ $purchaseOrder->status }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td>ID Supplier</td>
            <td>:</td>
            <td>{{ $purchaseOrder->supplier_id }}</td>
            <td>Tanggal Cetak</td>
            <td>:</td>
            <td>{{ $generatedAt }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($purchaseOrder->details as $index => $detail)
                @php $grandTotal += $detail->amount; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->product_id }}</td>
                    <td align="right">{{ number_format($detail->quantity, 0, ',', '.') }}</td>
                    <td align="right">Rp {{ number_format($detail->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; font-style:italic;">
                        Tidak ada item pada Purchase Order ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" align="right">Grand Total</td>
                <td align="right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <table class="signature">
        <tr>
            <td>
                Dibuat oleh,
                <div class="line"></div>
                ( Purchasing )
            </td>
            <td>
                Disetujui oleh,
                <div class="line"></div>
                ( Manager )
            </td>
            <td>
                Diterima oleh,
                <div class="line"></div>
                ( Supplier )
            </td>
        </tr>
    </table>

</body>
</html>