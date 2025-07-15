<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Tambah Kategori</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.css') }}" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />
    <style>
        /* Optional: Some custom styles for the layout matching the image */
        .scenario-container {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
        }
        .scenario-box {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            width: 45%; /* Adjust width for two columns */
            min-width: 300px; /* Minimum width to prevent too narrow boxes */
        }
        /* Tambahan style untuk menyesuaikan tata letak gambar */
        .form-check.sub-category-checkbox {
            margin-top: 1.5rem; /* Memberikan jarak dari input di atasnya */
            margin-bottom: 0.5rem; /* Sedikit jarak ke bawah */
        }
        .form-check.active-checkbox {
            margin-top: 1.5rem; /* Menyesuaikan jarak dengan gambar */
            margin-bottom: 1rem;
        }
        .card-body .form-group:first-of-type {
            margin-top: 1.5rem; /* Sedikit margin atas untuk input pertama */
        }
    </style>
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
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-chat-text"></i>
                            <span class="navbar-badge badge text-bg-danger">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img
                                            src="{{ asset('assets/dist/assets/img/user1-128x128.jpg') }}"
                                            alt="User Avatar"
                                            class="img-size-50 rounded-circle me-3"
                                        />
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            Brad Diesel
                                            <span class="float-end fs-7 text-danger"
                                            ><i class="bi bi-star-fill"></i
                                            ></span>
                                        </h3>
                                        <p class="fs-7">Call me whenever you can...</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-bell-fill"></i>
                            <span class="navbar-badge badge text-bg-warning">15</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <span class="dropdown-item dropdown-header">15 Notifications</span>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img
                                src="{{ asset('assets/dist/assets/img/user2-160x160.jpg') }}"
                                class="user-image rounded-circle shadow"
                                alt="User Image"
                            />
                            <span class="d-none d-md-inline">Admin</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <img
                                    src="{{ asset('assets/dist/assets/img/user2-160x160.jpg') }}"
                                    class="rounded-circle shadow"
                                    alt="User Image"
                                />
                                <p>
                                    Admin - Web Developer
                                    <small>Member since Jan. 2024</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                                <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="/dashboard" class="brand-link">
                    <img
                        src="{{ asset('assets/dist/assets/img/LogoRPL.png') }}"
                        alt="RPL"
                        class="brand-image opacity-75 shadow"
                    />
                    <span class="brand-text fw-light">ERP RPL UAD</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul
                        class="nav sidebar-menu flex-column"
                        data-lte-toggle="treeview"
                        role="menu"
                        data-accordion="false"
                    >
                        <li class="nav-item">
                            <a href="/dashboard" class="nav-link">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/product/list" class="nav-link">
                                <i class="nav-icon bi bi-box-seam-fill"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-person-circle"></i>
                                <p>
                                    Supplier
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./widgets/small-box.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Small Box</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/supplier/pic/add" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Tambah PIC supplier</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./widgets/cards.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Cards</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>Purchase Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('branch.list') }}" class="nav-link">
                                <i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>Branch</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('item.list') }}" class="nav-link active">
                                <i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>Item</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('product.category.add') }}" class="nav-link">
                                <i class="nav-icon bi bi-tags-fill"></i>
                                <p>Kategori Produk</p>
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
                        <div class="col-sm-6"><h3 class="mb-0">Tambah Kategori Produk</h3></div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="#">Kategori</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Tambah Kategori</h3>
                                </div>
                                <form action="{{ route('product.category.store') }}" method="POST" id="categoryForm">
                                    @csrf
                                    <div class="card-body">
                                        {{-- Skenario 1: Input for Nama Grup Kategori Produk (Default visible) --}}
                                        <div class="form-group" id="groupKategoriFormGroup">
                                            <label for="nama_grup_kategori">Nama Grup Kategori Produk</label>
                                            <input type="text" class="form-control" id="nama_grup_kategori" name="nama_grup_kategori" placeholder="Elektronik">
                                            <div class="invalid-feedback" id="nama_grup_kategori_feedback"></div>
                                        </div>

                                        {{-- Skenario 2: Input for Nama Sub Kategori Produk (Initially hidden) --}}
                                        <div class="form-group" id="subKategoriFormGroup" style="display: none;">
                                            <label for="nama_sub_kategori">Nama Sub Kategori Produk</label>
                                            <input type="text" class="form-control" id="nama_sub_kategori" name="nama_sub_kategori" placeholder="HP Second">
                                            <div class="invalid-feedback" id="nama_sub_kategori_feedback"></div>
                                        </div>

                                        {{-- Checkbox for Sub Kategori --}}
                                        <div class="form-check sub-category-checkbox">
                                            <input class="form-check-input" type="checkbox" id="subKategoriCheckbox" name="is_sub_category">
                                            <label class="form-check-label" for="subKategoriCheckbox">
                                                Sub Kategori
                                            </label>
                                        </div>

                                        {{-- Skenario 2: Dropdown for Grup Kategori Produk (Initially hidden) --}}
                                        <div class="form-group" id="grupKategoriDropdownGroup" style="display: none;">
                                            <label for="pilih_grup_kategori">Grup Kategori Produk</label>
                                            <select class="form-select" id="pilih_grup_kategori" name="id_parent_kategori">
                                                <option value="">Pilih Grup Kategori</option>
                                                {{-- Dummy data for demonstration. In real app, fetch from database --}}
                                                @if(isset($parentCategories))
                                                    @foreach($parentCategories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                @else
                                                    {{-- Fallback options if $parentCategories is not set (for testing/development) --}}
                                                    <option value="1">Elektronik (Dummy)</option>
                                                    <option value="2">Pakaian (Dummy)</option>
                                                    <option value="3">Makanan (Dummy)</option>
                                                @endif
                                            </select>
                                            <div class="invalid-feedback" id="pilih_grup_kategori_feedback"></div>
                                        </div>

                                        {{-- Checkbox for Aktif --}}
                                        <div class="form-check active-checkbox">
                                            <input class="form-check-input" type="checkbox" id="aktifCheckbox" name="aktif" checked>
                                            <label class="form-check-label" for="aktifCheckbox">
                                                Aktif
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-primary" onclick="validateCategoryForm()">Add</button>
                                        <button type="reset" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">Anything you want</div>
            <strong>
                Copyright &copy; 2014-2024&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
            </strong>
            All rights reserved.
        </footer>
    </div>
    
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-JLMUQfrMvhB/C+XTyqfc/TUlC6gGQE0H2hZFX5FJ1cM="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha256-3gQJhtmj7YnV1fmtbVcnAV6TiKH9jKLO9IZ1UCEUkKQ="
      crossorigin="anonymous"
    ></script>
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>

    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            // Toggle sidebar on menu button click
            $('[data-widget="pushmenu"]').on('click', function (e) {
                e.preventDefault();
                $('body').toggleClass('sidebar-collapse');
            });

            // Function to update form visibility based on checkbox
            function updateFormVisibility() {
                if ($('#subKategoriCheckbox').is(':checked')) {
                    // Skenario 2: Sub Kategori dipilih
                    $('#groupKategoriFormGroup').hide(); // Sembunyikan input grup utama
                    $('#subKategoriFormGroup').show();    // Tampilkan input sub kategori
                    $('#grupKategoriDropdownGroup').show(); // Tampilkan dropdown
                } else {
                    // Skenario 1: Hanya Grup Kategori Produk
                    $('#groupKategoriFormGroup').show();  // Tampilkan input grup utama
                    $('#subKategoriFormGroup').hide();    // Sembunyikan input sub kategori
                    $('#grupKategoriDropdownGroup').hide(); // Sembunyikan dropdown
                }
                // Clear validation feedback on change
                $('.invalid-feedback').text('');
                $('.form-control').removeClass('is-invalid');
            }

            // Initial call to set correct visibility on page load
            updateFormVisibility();

            // Handle checkbox change
            $('#subKategoriCheckbox').change(function() {
                updateFormVisibility(); // Call the function to update visibility
            });
        });

        function validateCategoryForm() {
            let isValid = true;
            $('.invalid-feedback').text(''); // Clear previous error messages
            $('.form-control').removeClass('is-invalid'); // Remove red borders

            const isSubKategori = $('#subKategoriCheckbox').is(':checked');

            if (isSubKategori) {
                // Skenario 2: Memasukkan nama sub-kategori produk
                const namaSubKategori = $('#nama_sub_kategori').val().trim();
                const pilihGrupKategori = $('#pilih_grup_kategori').val();

                if (namaSubKategori === '') {
                    $('#nama_sub_kategori_feedback').text('Nama Sub Kategori Produk harus diisi.').show();
                    $('#nama_sub_kategori').addClass('is-invalid');
                    isValid = false;
                }

                if (pilihGrupKategori === '') {
                    $('#pilih_grup_kategori_feedback').text('Grup Kategori Produk harus dipilih.').show();
                    $('#pilih_grup_kategori').addClass('is-invalid');
                    isValid = false;
                }

            } else {
                // Skenario 1: Hanya memasukkan nama grup kategori produk
                const namaGrupKategori = $('#nama_grup_kategori').val().trim();

                if (namaGrupKategori === '') {
                    $('#nama_grup_kategori_feedback').text('Nama Grup Kategori Produk harus diisi.').show();
                    $('#nama_grup_kategori').addClass('is-invalid');
                    isValid = false;
                }
            }

            if (isValid) {
                document.getElementById('categoryForm').submit();
            }
            return isValid;
        }
    </script>
</body>
</html>