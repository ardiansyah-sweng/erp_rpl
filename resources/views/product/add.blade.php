<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Tambah Produk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset("assets/dist/css/adminlte.css") }}" />
    </head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Beranda</a></li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Kontak</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ asset("assets/dist/assets/img/user2-160x160.jpg") }}" class="user-image rounded-circle shadow" alt="User Image" />
                            <span class="d-none d-md-inline">Mimin Gantenk</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <img src="{{ asset("assets/dist/assets/img/user2-160x160.jpg") }}" class="rounded-circle shadow" alt="User Image" />
                                <p>
                                    Alexander Pierce - Web Developer
                                    <small>Anggota sejak Nov. 2023</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <a href="#" class="btn btn-default btn-flat">Profil</a>
                                <a href="#" class="btn btn-default btn-flat float-end">Keluar</a>
                            </li>
                            </ul>
                    </li>
                    </ul>
                </div>
            </nav>
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="/dashboard" class="brand-link">
                    <img src="{{ asset("assets/dist/assets/img/LogoRPL.png") }}" alt="RPL" class="brand-image opacity-75 shadow" />
                    <span class="brand-text fw-light">ERP RPL UAD</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="/dashboard" class="nav-link">
                                <i class="nav-icon bi bi-speedometer"></i><p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active"> <i class="nav-icon bi bi-box-seam-fill"></i><p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-person-circle"></i>
                                <p>Supplier <i class="nav-arrow bi bi-chevron-right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="/supplier/pic/add" class="nav-link"><i class="nav-icon bi bi-circle"></i><p>Tambah PIC supplier</p></a></li>
                                <li class="nav-item"><a href="/supplier/material/add" class="nav-link"><i class="nav-icon bi bi-circle"></i><p>Tambah Supplier Item</p></a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('purchase.orders') }}" class="nav-link">
                                <i class="nav-icon bi bi-clipboard-fill"></i><p>Purchase Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('item.list') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i><p>Item</p>
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
                        <div class="col-sm-6"><h3 class="mb-0">Tambah Produk</h3></div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tambah Produk</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <form id="productForm" method="POST" action="{{ route('product.add') }}">
                                @csrf <div class="mb-3">
                                    <label for="product_id" class="form-label">ID Produk</label>
                                    <input type="text" class="form-control" id="product_id" name="product_id" required>
                                    <div class="invalid-feedback">ID Produk harus diisi.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="product_name" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                                    <div class="invalid-feedback">Nama Produk harus diisi.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="product_type" id="FG" value="FG" required>
                                            <label class="form-check-label" for="FG">Finished Good</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="product_type" id="HFG" value="HFG" required>
                                            <label class="form-check-label" for="HFG">Half Finished Goods</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="product_type" id="RM" value="RM" required>
                                            <label class="form-check-label" for="RM">Raw Material</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="product_category" class="form-label">Kategori</label>
                                    <select class="form-select" id="product_category" name="product_category" required>
                                        <option value="" disabled selected>-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Kategori harus dipilih.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="product_description" class="form-label">Deskripsi Produk</label>
                                    <textarea class="form-control" id="product_description" name="product_description" rows="3"></textarea>
                                </div>
                                <div class="d-flex justify-content-start mt-4">
                                    <div>
                                        <button type="submit" class="btn btn-primary">Tambah</button>
                                        <button type="reset" class="btn btn-secondary ms-2">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            </main>
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">Apa pun yang Anda inginkan</div>
            <strong>Hak Cipta &copy; 2014-2024&nbsp;<a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.</strong> Hak cipta dilindungi.
        </footer>
        </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
    <script src="{{ asset("assets/dist/js/adminlte.js") }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarWrapper = document.querySelector('.sidebar-wrapper');
            if (sidebarWrapper) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: 'os-theme-light',
                        autoHide: 'leave',
                        clickScroll: true,
                    },
                });
            }
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const productForm = document.getElementById('productForm');

            productForm.addEventListener('submit', function (event) {
                // Hentikan pengiriman default untuk validasi
                event.preventDefault();

                // Panggil fungsi validasi, jika lolos, submit form
                if (validateInputs()) {
                    this.submit();
                }
            });

            function validateInputs() {
                let isValid = true;

                // Reset semua pesan error sebelumnya
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').hide();

                const productId = $('#product_id');
                const productName = $('#product_name');
                const productType = $('input[name="product_type"]:checked');
                const category = $('#product_category');

                if (productId.val().trim() === '') {
                    productId.addClass('is-invalid');
                    productId.next('.invalid-feedback').show();
                    isValid = false;
                }
                
                if (productName.val().trim() === '') {
                    productName.addClass('is-invalid');
                    productName.next('.invalid-feedback').show();
                    isValid = false;
                }

                if (productType.length === 0) {
                    // Tampilkan pesan error untuk grup radio
                    $('input[name="product_type"]').addClass('is-invalid');
                    $('input[name="product_type"]').closest('.mb-3').find('.invalid-feedback').show();
                    isValid = false;
                }

                if (category.val() === '' || category.val() === null) {
                    category.addClass('is-invalid');
                    category.next('.invalid-feedback').show();
                    isValid = false;
                }

                return isValid;
            }
        });
    </script>
    </body>
</html>