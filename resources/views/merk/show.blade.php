<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Detail Merk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.css') }}" />
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Header -->
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="/dashboard" class="brand-link">
                    <img src="{{ asset('assets/dist/assets/img/LogoRPL.png') }}" alt="RPL" class="brand-image opacity-75 shadow" />
                    <span class="brand-text fw-light">ERP RPL UAD</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                        <li class="nav-item"><a href="/dashboard" class="nav-link"><i class="nav-icon bi bi-speedometer"></i><p>Dashboard</p></a></li>
                        <li class="nav-item"><a href="{{ route('merk.index') }}" class="nav-link active"><i class="nav-icon bi bi-tags"></i><p>Merk</p></a></li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main content -->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Detail Merk</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('merk.index') }}">Merk</a></li>
                                <li class="breadcrumb-item active">Detail</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="bi bi-info-circle"></i> Detail Merk: {{ $merk->merk }}
                                    </h3>
                                    <div class="card-tools">
                                        {!! $merk->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>' !!}
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-borderless table-striped">
                                                <tr>
                                                    <td class="fw-bold" style="width: 30%;">ID:</td>
                                                    <td>{{ $merk->id }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Nama Merk:</td>
                                                    <td class="fs-5 fw-semibold">{{ $merk->merk }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Status:</td>
                                                    <td>
                                                        {!! $merk->is_active ? '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Aktif</span>' : '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Tidak Aktif</span>' !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Dibuat:</td>
                                                    <td>
                                                        {{ $merk->created_at ? $merk->created_at->format('d/m/Y H:i:s') : '-' }}
                                                        @if($merk->created_at)
                                                            <br><small class="text-muted">({{ $merk->created_at->diffForHumans() }})</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Terakhir Diupdate:</td>
                                                    <td>
                                                        {{ $merk->updated_at ? $merk->updated_at->format('d/m/Y H:i:s') : '-' }}
                                                        @if($merk->updated_at)
                                                            <br><small class="text-muted">({{ $merk->updated_at->diffForHumans() }})</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Additional Information Section -->
                                    <hr class="my-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="mb-3">
                                                <i class="bi bi-graph-up"></i> Informasi Tambahan
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="card bg-primary text-white">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-calendar-plus fs-2"></i>
                                                            <h6 class="card-title mt-2">Usia Data</h6>
                                                            <p class="card-text">
                                                                {{ $merk->created_at ? $merk->created_at->diffForHumans() : '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card bg-info text-white">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-pencil-square fs-2"></i>
                                                            <h6 class="card-title mt-2">Update Terakhir</h6>
                                                            <p class="card-text">
                                                                {{ $merk->updated_at ? $merk->updated_at->diffForHumans() : '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card bg-{{ $merk->is_active ? 'success' : 'secondary' }} text-white">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-{{ $merk->is_active ? 'check-circle' : 'x-circle' }} fs-2"></i>
                                                            <h6 class="card-title mt-2">Status</h6>
                                                            <p class="card-text">
                                                                {{ $merk->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('merks.index') }}" class="btn btn-secondary">
                                                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                                            </a>
                                        </div>
                                        <div>
                                            <a href="{{ route('merks.edit', $merk->id) }}" class="btn btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus merk <strong>"{{ $merk->merk }}"</strong>?</p>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Peringatan:</strong> Aksi ini tidak dapat dibatalkan!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Batal
                    </button>
                    <form action="{{ route('merk.destroy', $merk->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Ya, Hapus!
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
</body>
</html>
