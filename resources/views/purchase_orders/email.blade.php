<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background-color: #004085; color: white; padding: 10px; text-align: center; }
        .content { padding: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { border: 1px solid #dee2e6; padding: 8px; }
        .table th { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Konfirmasi Purchase Order</h2>
    </div>
    <div class="content">
        <p>Halo,</p>
        <p>Berikut detail purchase order baru yang telah ditambahkan:</p>

        <p><strong>PO Number:</strong> {{ $formData['po_number'] }}</p>
        <p><strong>Cabang:</strong> {{ $formData['branch'] }}</p>
        <p><strong>Supplier:</strong> {{ $formData['supplier_name'] ?? $formData['supplier_id'] }}</p>

        <h4>Detail Barang:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Qty</th>
                    <th>Amount</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach ($formData['items'] as $item)
                    @php
                        $subtotal = $item['quantity'] * $item['amount'];
                        $grandTotal += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $item['product_id'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>Rp {{ number_format($item['amount'], 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Subtotal</strong></td>
                    <td>Rp {{ number_format($formData['subtotal'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Pajak</strong></td>
                    <td>Rp {{ number_format($formData['tax'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Grand Total</strong></td>
                    <td><strong>Rp {{ number_format($formData['subtotal'] + $formData['tax'], 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <p>Terima kasih.</p>
    </div>
</body>
</html>
