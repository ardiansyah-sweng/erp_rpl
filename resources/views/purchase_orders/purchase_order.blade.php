<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Purchase Order PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #000; text-align: left; }
    </style>
</head>
<body>
    <h2>Purchase Order</h2>
    <p><strong>PO Number:</strong> {{ $purchaseOrder->po_number }}</p>
    <p><strong>Supplier ID:</strong> {{ $purchaseOrder->supplier_id }}</p>
    <p><strong>Branch ID:</strong> {{ $purchaseOrder->branch_id }}</p>
    <p><strong>Order Date:</strong> {{ $purchaseOrder->order_date }}</p>
    <p><strong>Status:</strong> {{ $purchaseOrder->status }}</p>
    <p><strong>Total:</strong> Rp {{ number_format($purchaseOrder->total, 0, ',', '.') }}</p>
    <p><strong>Created At:</strong> {{ $purchaseOrder->created_at }}</p>
</body>
</html>
