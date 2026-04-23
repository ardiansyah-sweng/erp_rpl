<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #333; }
        .container { padding: 20px; border: 1px solid #eee; max-width: 600px; margin: auto; }
        h3, h4 { color: #333; }
        .header-table, .items-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .header-table td { padding: 5px 0; }
        .header-table .label { font-weight: bold; width: 120px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items-table th { background-color: #f2f2f2; font-weight: bold; }
        .items-table .text-right { text-align: right; }
        .footer-table { width: 100%; margin-top: 20px; }
        .footer-table td { padding: 5px 0; }
        .footer-table .label { font-weight: bold; }
        .footer-table .value { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h3>Purchase Order {{ $data['header']['po_number'] }}</h3>

        <table class="header-table">
            <tr>
                <td class="label">Cabang:</td>
                <td>{{ $data['header']['branch'] }}</td>
            </tr>
            <tr>
                <td class="label">Supplier:</td>
                <td>{{ $data['header']['supplier_name'] }} ({{ $data['header']['supplier_id'] }})</td>
            </tr>
            <tr>
                <td class="label">Tanggal Order:</td>
                <td>{{ \Carbon\Carbon::parse($data['header']['order_date'])->format('d-m-Y') }}</td>
            </tr>
        </table>

        <h4>Daftar Item:</h4>
        <table class="items-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nama</th>
                    <th>Qty</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['items'] as $item)
                <tr>
                    <td>{{ $item['sku'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td class="text-right">Rp{{ number_format($item['unitPrice'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($item['amount'], 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="footer-table">
             <tr>
                <td class="label">Subtotal:</td>
                <td class="value">Rp{{ number_format($data['subtotal'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Tax:</td>
                <td class="value">Rp{{ number_format($data['tax'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>