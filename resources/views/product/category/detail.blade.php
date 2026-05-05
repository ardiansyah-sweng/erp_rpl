<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Detail Produk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard." />
    <meta name="keywords" content="bootstrap 5, admin dashboard, charts, tables" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.css') }}" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i
                                class="bi bi-list"></i></a>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="{{ url('/dashboard') }}" class="nav-link">Home</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ asset('assets/dist/assets/img/user2-160x160.jpg') }}"
                                class="user-image rounded-circle shadow" alt="User Image" />
                            <span class="d-none d-md-inline">Mimin Gantenks</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="{{ url('/dashboard') }}" class="brand-link">
                    <img src="{{ asset('assets/dist/assets/img/LogoRPL.png') }}" alt="RPL"
                        class="brand-image opacity-75 shadow" />
                    <span class="brand-text fw-light">ERP RPL UAD</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                        <li class="nav-item">
                            <a href="{{ url('/dashboard') }}" class="nav-link"><i
                                    class="nav-icon bi bi-speedometer"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('product.list') }}" class="nav-link active"><i
                                    class="nav-icon bi bi-box-seam-fill"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('branch.list') }}" class="nav-link"><i
                                    class="nav-icon bi bi-clipboard-fill"></i>
                                <p>Branch</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="app-main">
            <div class="content-wrapper">
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Detail Produk</h1>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="content">
                    <div class="container-fluid mt-4 ps-4">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white d-flex justify-content-between">
                                <h3 class="card-title">Informasi Produk: {{ $product->product_id }}</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th style="width: 30%">Database ID</th>
                                        <td>{{ $product->id ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Product ID (SKU)</th>
                                        <td><strong>{{ $product->product_id ?? 'Tidak ada data' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <td>{{ $product->product_name ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tipe Produk</th>
                                        <td>{{ is_object($product->type) ? $product->type->label() : $product->product_type ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Kategori</th>
                                        <td>{{ $product->categoryRelation->category ?? 'Tidak ada kategori' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <td>{{ $product->product_description ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dibuat Pada</th>
                                        <td>{{ $product->created_at ?? 'Tidak ada data' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="card-footer">
                                <a href="{{ url('/product/list') }}" class="btn btn-secondary">Kembali ke Daftar</a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">Informatika UAD</div>
            <strong>Copyright &copy; 2014-2024 <a href="https://adminlte.io"
                    class="text-decoration-none">AdminLTE.io</a>.</strong>
        </footer>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('[data-lte-toggle="sidebar"]').on('click', function(e) {
                e.preventDefault();
                $('body').toggleClass('sidebar-collapse');
            });
        });
    </script>
</body>

</html>
