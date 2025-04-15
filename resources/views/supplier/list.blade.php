<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supplier List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Suppliers</h2>
        <a href="#" class="btn btn-primary mb-3">New Supplier</a>
        <table id="supplier-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Supplier</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Telephone</th>
                    <th>PiC</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contoh data static -->
                <tr>
                    <td>1</td>
                    <td>SUPP0001</td>
                    <td>CV Amartya Sein</td>
                    <td>Jl. Kaliurang No. 110</td>
                    <td>0274-345900</td>
                    <td><span class="badge bg-info text-dark">0</span></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning">Edit</a>
                        <a href="#" class="btn btn-sm btn-info">Create PO</a>
                        <a href="#" class="btn btn-sm btn-primary">Add Pic</a>
                    </td>
                </tr>
                <!-- Tambahkan baris lain seperti di atas -->
            </tbody>
        </table>
    </div>

    <!-- JS Section -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#supplier-table').DataTable();
        });
    </script>
</body>
</html>

