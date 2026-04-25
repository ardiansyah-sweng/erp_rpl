<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Detail Produk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
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
                    <li class="nav-item d-none d-md-block"><a href="{{ route('dashboard') }}" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="navbar-search" href="#" role="button"><i
                                class="bi bi-search"></i></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-chat-text"></i>
                            <span class="navbar-badge badge text-bg-danger">3</span>
                        </a>
                    </li>
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
                <a href="{{ route('dashboard') }}" class="brand-link">
                    <img src="{{ asset('assets/dist/assets/img/LogoRPL.png') }}" alt="RPL"
                        class="brand-image opacity-75 shadow" />
                    <span class="brand-text fw-light">ERP RPL UAD</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link"><i
                                    class="nav-icon bi bi-speedometer"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="nav-icon bi bi-tag"></i>
                                <p>Merk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('product.list') }}" class="nav-link active"><i
                                    class="nav-icon bi bi-box-seam-fill"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="nav-icon bi bi-house-door"></i>
                                <p>Warehouse</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="nav-icon bi bi-person-circle"></i>
                                <p>Supplier <i class="nav-arrow bi bi-chevron-right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="#" class="nav-link"><i
                                            class="nav-icon bi bi-circle"></i>
                                        <p>Small Box</p>
                                    </a></li>
                                <li class="nav-item"><a href="/supplier/pic/add" class="nav-link"><i
                                            class="nav-icon bi bi-circle"></i>
                                        <p>Tambah PIC supplier</p>
                                    </a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>Purchase Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('branch.list') }}" class="nav-link"><i
                                    class="nav-icon bi bi-geo-alt"></i>
                                <p>Branch</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('item.list') }}" class="nav-link"><i class="nav-icon bi bi-circle"></i>
                                <p>Item</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="nav-icon bi bi-grid"></i>
                                <p>Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="nav-icon bi bi-tools"></i>
                                <p>Production</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1>Detail Produk</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid mt-4 ps-4">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">Informasi Produk: {{ $product->product_id }}
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th style="width: 30%">ID Database</th>
                                    <td>{{ $product->id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Produk ID</th>
                                    <td><strong>{{ $product->product_id ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Nama Produk</th>
                                    <td>{{ $product->product_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tipe Produk</th>
                                    <td>{{ is_object($product->type) ? $product->type->label() : $product->product_type ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kategori Produk</th>
                                    <td>{{ $product->categoryRelation->category ?? 'Tidak Ada' }}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{{ $product->product_description ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat Pada</th>
                                    <td>{{ $product->created_at ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Update Terakhir</th>
                                    <td>{{ $product->updated_at ?? '-' }}</td>
                                </tr>
                            </table>
                            <div class="mt-4">
                                <a href="{{ route('product.list') }}" class="btn btn-secondary"><i
                                        class="bi bi-arrow-left"></i> Kembali ke Daftar Produk</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">Anything you want</div>
            <strong>Copyright &copy; 2014-2024 <a href="https://adminlte.io"
                    class="text-decoration-none">AdminLTE.io</a>.</strong>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-JLMUQfrMvhB/C+XTyqfc/TUlC6gGQE0H2hZFX5FJ1cM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha256-3gQJhtmj7YnV1fmtbVcnAV6TiKH9jKLO9IZ1UCEUkKQ=" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
" " 
" " 
