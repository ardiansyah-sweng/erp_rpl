<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Warehouse Detail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href={{ asset('assets/dist/css/adminlte.css') }} />
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="mb-3">Warehouse Detail</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('warehouse.list') }}">Warehouse</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('warehouse.list') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to List</a>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Informasi Warehouse</h4>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">ID</dt>
                    <dd class="col-sm-9">{{ $warehouse['id'] ?? '-' }}</dd>

                    <dt class="col-sm-3">Warehouse Name</dt>
                    <dd class="col-sm-9">{{ $warehouse['warehouse_name'] ?? '-' }}</dd>

                    <dt class="col-sm-3">Warehouse Address</dt>
                    <dd class="col-sm-9">{{ $warehouse['warehouse_address'] ?? '-' }}</dd>

                    <dt class="col-sm-3">Warehouse Telephone</dt>
                    <dd class="col-sm-9">{{ $warehouse['warehouse_telephone'] ?? '-' }}</dd>

                    <dt class="col-sm-3">Aktif</dt>
                    <dd class="col-sm-9">
                        @if(isset($warehouse['is_active']) && $warehouse['is_active'])
                            <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Aktif</span>
                        @else
                            <span class="badge bg-danger"><i class="bi bi-x-circle-fill"></i> Tidak Aktif</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">Created At</dt>
                    <dd class="col-sm-9">{{ $warehouse['created_at'] ?? '-' }}</dd>

                    <dt class="col-sm-3">Updated At</dt>
                    <dd class="col-sm-9">{{ $warehouse['updated_at'] ?? '-' }}</dd>
                </dl>
            </div>
            <div class="card-footer text-end">
                <a href="#" class="btn btn-primary">Edit</a>
                <a href="#" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src={{ asset('assets/dist/js/adminlte.js') }}></script>
</body>
</html>
