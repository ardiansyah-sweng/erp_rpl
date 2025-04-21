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
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-supplier="CV Amartya Sein">Delete</button>
                    </td>
                </tr>
                <!-- Tambahkan baris lain seperti di atas -->
                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong id="supplierName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</a>
                    </div>
                    </div>
                </div>
                </div>

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

