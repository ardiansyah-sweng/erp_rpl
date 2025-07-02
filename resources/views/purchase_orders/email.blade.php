<h2>Notifikasi Purchase Order</h2>
<p>PO Number: {{ $data['po_number'] }}</p>
<p>Cabang: {{ $data['branch'] }}</p>
<p>Supplier ID: {{ $data['supplier_id'] }}</p>
<p>Nama Supplier: {{ $data['supplier_name'] }}</p>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Nama Item</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['items'] as $item)
            <tr>
                <td>{{ $item['sku'] }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['qty'] }}</td>
                <td>{{ $item['unitPrice'] }}</td>
                <td>{{ $item['amount'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<p>Subtotal: {{ $data['subtotal'] }}</p>
<p>Tax: {{ $data['tax'] }}</p>
