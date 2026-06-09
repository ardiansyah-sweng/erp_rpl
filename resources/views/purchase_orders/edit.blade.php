<!DOCTYPE html>
<html>
<head>
    <title>Edit Purchase Order</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Edit Purchase Order</h2>
    
    @if(session('error'))
        <div class="alert alert-danger">
            <strong>Gagal:</strong> {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            <strong>Berhasil:</strong> {{ session('success') }}
        </div>
    @endif

    <div class="form-group">
        <label for="po_number">PO Number</label>
        <input type="text" class="form-control" id="po_number" value="{{ $purchaseOrder->po_number }}" readonly>
    </div>
    
    <form action="/purchase_orders/update/{{ \App\Helpers\EncryptionHelper::encrypt($purchaseOrder->po_number) }}" method="POST" id="editForm">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="branch">Cabang</label>
            <input type="text" name="branch_id" class="form-control" id="branch" value="{{ $purchaseOrder->branch_id ?? $purchaseOrder->branch ?? '' }}" placeholder="Masukkan nama cabang">
        </div>
        <div class="form-group">
            <label for="supplier_id">Supplier</label>
            <select name="supplier_id" class="form-control" id="supplier_id" required>
                <option value="">-- Pilih Supplier --</option>
                @foreach($suppliers as $sup)
                    <option value="{{ $sup->supplier_id ?? $sup->id }}" 
                        {{ $purchaseOrder->supplier_id == ($sup->supplier_id ?? $sup->id) ? 'selected' : '' }}>
                        {{ $sup->supplier_id ?? $sup->id }} - {{ $sup->company_name ?? $sup->name ?? 'Tanpa Nama' }}
                    </option>
                @endforeach
            </select>
        </div>

        <table class="table" id="itemsTable">
            <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Item</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            {{-- Catatan: Ubah '$purchaseOrder->items' sesuai dengan nama relasi item di model PO kamu (misal: details atau items) --}}
            @if(isset($purchaseOrder->items) && count($purchaseOrder->items) > 0)
                @foreach($purchaseOrder->items as $item)
            <tr>
                    <td>
                        <input type="hidden" name="detail_id[]" value="{{ $item->id ?? '' }}"> 
                        <input type="text" name="sku[]" class="form-control sku" value="{{ $item->product_id ?? '' }}" readonly>
                    </td>
                    <td>
                        <input type="text" name="nama_item[]" class="form-control nama-item" value="{{ $item->item_name ?? 'Barang Tidak Terdaftar' }}" readonly>
                    </td>
                    <td>
                        <input type="number" name="qty[]" class="form-control qty" value="{{ $item->quantity ?? 1 }}">
                    </td>
                    <td>
                        <input type="number" name="unit_price[]" class="form-control unit-price" value="{{ $item->base_price ?? 0 }}">
                    </td>
                    <td>
                        <input type="number" class="form-control amount" value="0" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove">Hapus</button>
                    </td>
            </tr>
                @endforeach
            @else
                <tr>
                    <input type="hidden" name="detail_id[]" value=""> <td><input type="text" name="sku[]" class="form-control sku"></td>
                    <td><input type="text" name="nama_item[]" class="form-control nama-item"></td>
                    <td><input type="number" name="qty[]" class="form-control qty" value="1"></td>
                    <td><input type="number" name="unit_price[]" class="form-control unit-price" value="0"></td>
                    <td><input type="number" class="form-control amount" value="0" readonly></td>
                    <td><button type="button" class="btn btn-danger remove">Hapus</button></td>
                </tr>
            @endif
            </tbody>
        </table>
        <button type="button" id="addRow" class="btn btn-info mb-3">Tambah Barang</button>

        <div class="form-group">
            <label>Sub Total Rp.</label>
            <input type="text" class="form-control" id="subtotal" value="0" readonly>
            <input type="hidden" name="subtotal" id="raw_subtotal" value="0"> 
        </div>
        <div class="form-group">
            <label>Tax Rp.</label>
            <input type="text" class="form-control" id="tax" value="0" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Update Data</button>
        <a href="{{ route('purchase.orders') }}" class="btn btn-danger">Cancel</a>
    </form>
</div>

<script>
    function updateAmount(row) {
        let qty = parseFloat(row.find(".qty").val()) || 0;
        let price = parseFloat(row.find(".unit-price").val()) || 0;
        row.find(".amount").val(qty * price);
        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        $(".amount").each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        $("#subtotal").val(total.toLocaleString("id-ID"));
        $("#tax").val(total.toLocaleString("id-ID"));
        
        $("#raw_subtotal").val(total);
    }

    $(document).on("input", ".qty, .unit-price", function () {
        let row = $(this).closest("tr");
        updateAmount(row);
    });

    $("#addRow").click(function () {
        let newRow = `<tr>
            <input type="hidden" name="detail_id[]" value=""> <td><input type="text" name="sku[]" class="form-control sku"></td>
            <td><input type="text" name="nama_item[]" class="form-control nama-item"></td>
            <td><input type="number" name="qty[]" class="form-control qty" value="1"></td>
            <td><input type="number" name="unit_price[]" class="form-control unit-price" value="0"></td>
            <td><input type="number" class="form-control amount" value="0" readonly></td>
            <td><button type="button" class="btn btn-danger remove">Hapus</button></td>
    </tr>`;
        $("#itemsTable tbody").append(newRow);
    });

    $(document).on("click", ".remove", function () {
        $(this).closest("tr").remove();
        updateTotal();
    });

    $(document).ready(function() {
        $("#itemsTable tbody tr").each(function() {
            updateAmount($(this));
        });
    });
</script>
</body>
</html>