<!DOCTYPE html>
<html>
<head>
    <title>Add Purchase Order</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Add Purchase Order</h2>
    <div class="form-group">
              <label for="po_number">PO Number</label>
              <input type="text" class="form-control" id="po_number" value="PO0001" readonly>
    </div>
    <form>
        <div class="form-group">
            <label for="branch">Cabang</label>
            <input type="text" class="form-control" id="branch" placeholder="Masukkan nama cabang">
        </div>
        <div class="form-group">
            <label for="supplier_id">ID Supplier</label>
            <input type="text" class="form-control" id="supplier_id" placeholder="Masukkan ID Supplier">
        </div>
        <div class="form-group">
            <label for="supplier_name">Nama Supplier</label>
            <input type="text" class="form-control" id="supplier_name" placeholder="Masukkan Nama Supplier">
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
            <tr>
                <td><input type="text" class="form-control sku"></td>
                <td><input type="text" class="form-control nama-item"></td>
                <td><input type="number" class="form-control qty" value="1"></td>
                <td><input type="number" class="form-control unit-price" value="0"></td>
                <td><input type="number" class="form-control amount" value="0" readonly></td>
                <td><button type="button" class="btn btn-danger remove">Hapus</button></td>
            </tr>
            </tbody>
        </table>
        <button type="button" id="addRow" class="btn btn-info mb-3">Tambah Barang</button>

        <div class="form-group">
            <label>Sub Total Rp.</label>
            <input type="text" class="form-control" id="subtotal" readonly>
        </div>
        <div class="form-group">
            <label>Tax Rp.</label>
            <input type="text" class="form-control" id="tax" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Add</button>
        <button type="button" class="btn btn-danger">Cancel</button>
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
        $("#tax").val(total.toLocaleString("id-ID")); // untuk sementara sama
    }

    $(document).on("input", ".qty, .unit-price", function () {
        let row = $(this).closest("tr");
        updateAmount(row);
    });

    $("#addRow").click(function () {
        let newRow = `<tr>
                <td><input type="text" class="form-control sku"></td>
                <td><input type="text" class="form-control nama-item"></td>
                <td><input type="number" class="form-control qty" value="1"></td>
                <td><input type="number" class="form-control unit-price" value="0"></td>
                <td><input type="number" class="form-control amount" value="0" readonly></td>
                <td><button type="button" class="btn btn-danger remove">Hapus</button></td>
            </tr>`;
        $("#itemsTable tbody").append(newRow);
    });

    $(document).on("click", ".remove", function () {
        $(this).closest("tr").remove();
        updateTotal();
    });
</script>
</body>
</html>
