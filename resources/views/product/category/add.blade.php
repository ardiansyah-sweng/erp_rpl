<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>ERP RPL UAD | Tambah Kategori</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.css') }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
                </ul>
                <!--end::Start Navbar Links-->
                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">
                    <!--begin::Navbar Search-->
                    <li class="nav-item">
                        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                    <!--end::Navbar Search-->
                    <!--begin::Messages Dropdown Menu-->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-chat-text"></i>
                            <span class="navbar-badge badge text-bg-danger">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src={{ asset('assets/dist/assets/img/user1-128x128.jpg') }}
                                            alt="User Avatar" class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            Brad Diesel
                                            <span class="float-end fs-7 text-danger"><i
                                                    class="bi bi-star-fill"></i></span>
                                        </h3>
                                        <p class="fs-7">Call me whenever you can...</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src={{ asset('assets/dist/assets/img/user8-128x128.jpg') }}
                                            alt="User Avatar" class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            John Pierce
                                            <span class="float-end fs-7 text-secondary">
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                        </h3>
                                        <p class="fs-7">I got your message bro</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src={{ asset('assets/dist/assets/img/user3-128x128.jpg') }}
                                            alt="User Avatar" class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            Nora Silvester
                                            <span class="float-end fs-7 text-warning">
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                        </h3>
                                        <p class="fs-7">The subject goes here</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                        </div>
                    </li>
                    <!--end::Messages Dropdown Menu-->
                    <!--begin::Notifications Dropdown Menu-->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-bell-fill"></i>
                            <span class="navbar-badge badge text-bg-warning">15</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <span class="dropdown-item dropdown-header">15 Notifications</span>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-envelope me-2"></i> 4 new messages
                                <span class="float-end text-secondary fs-7">3 mins</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-people-fill me-2"></i> 8 friend requests
                                <span class="float-end text-secondary fs-7">12 hours</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                                <span class="float-end text-secondary fs-7">2 days</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
                        </div>
                    </li>
                    <!--end::Notifications Dropdown Menu-->
                    <!--begin::Fullscreen Toggle-->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                        </a>
                    </li>
                    <!--end::Fullscreen Toggle-->
                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src={{ asset('assets/dist/assets/img/user2-160x160.jpg') }}
                                class="user-image rounded-circle shadow" alt="User Image" />
                            <span class="d-none d-md-inline">Mimin Gantenk</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <!--begin::User Image-->
                            <li class="user-header text-bg-primary">
                                <img src={{ asset('assets/dist/assets/img/user2-160x160.jpg') }}
                                    class="rounded-circle shadow" alt="User Image" />
                                <p>
                                    Alexander Pierce - Web Developer
                                    <small>Member since Nov. 2023</small>
                                </p>
                            </li>
                            <!--end::User Image-->
                            <!--begin::Menu Body-->
                            <li class="user-body">
                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-4 text-center"><a href="#">Followers</a></div>
                                    <div class="col-4 text-center"><a href="#">Sales</a></div>
                                    <div class="col-4 text-center"><a href="#">Friends</a></div>
                                </div>
                                <!--end::Row-->
                            </li>
                            <!--end::Menu Body-->
                            <!--begin::Menu Footer-->
                            <li class="user-footer">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                                <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
                            </li>
                            <!--end::Menu Footer-->
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->
        <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="/dashboard" class="brand-link">
                    <!--begin::Brand Image-->
                    <img src={{ asset('assets/dist/assets/img/LogoRPL.png') }} alt="RPL"
                        class="brand-image opacity-75 shadow" />
                    <!--end::Brand Image-->
                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light">ERP RPL UAD</span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="/dashboard" class="nav-link active">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('product.list') }}" class="nav-link">
                                <i class="nav-icon bi bi-box-seam-fill"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('warehouse.list') }}" class="nav-link">
                                <i class="nav-icon bi bi-box2"></i>
                                <p>Warehouse</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-person-circle"></i>
                                <p>
                                    Supplier
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/supplier/pic/add" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Tambah PIC supplier</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/supplier/material/add" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Tambah Supplier Item</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/supplier/add" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Tambah Supplier</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/supplier/material/list" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Supplier Material</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/supplier/pic/list" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>List PIC Supplier</p>
                                        <a href="/supplier/list" class="nav-link">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>List Supplier</p>
                                        </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('purchase.orders') }}" class="nav-link">
                                <i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>
                                    Purchase Orders
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('branches.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>
                                    Branch
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('item.list') }}" class="nav-link">
                                <i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>
                                    Item
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category.list') }}" class="nav-link">
                                <i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>
                                    category product
                                </p>
                            </a>
                        </li>
                        <li class="nav-item"><!--Tambah Bill Of Material-->
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>
                                    Production
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/bom/list" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Bill Of Material</p>
                                    </a>
                                </li>
                            </ul>

                        </li>
                    </ul>
                    <!--end::Sidebar Menu-->
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->

        <!-- MAIN CONTENT -->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Tambah Kategori</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="card p-3 card-primary">
                        <form action="{{ route('categories.add') }}" method="POST" id="formKategori">
                            @csrf

                            <!-- Nama Kategori -->
                            <div class="mb-3">
                                <label for="groupName" class="form-label">Nama Grup Kategori Produk</label>
                                <input type="text" name="category" class="form-control" id="groupName"
                                    placeholder="Elektronik" />
                            </div>

                            <!-- Sub Kategori -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="subKategori"
                                    name="subKategori" />
                                <label class="form-check-label" for="subKategori">Sub Kategori</label>
                            </div>

                            <div id="subKategoriFields" class="d-none">
                                <div class="mb-3">
                                    <label for="groupSelect" class="form-label">Grup Kategori Produk</label>
                                    <select id="groupSelect" name="parent_id" class="form-select"
                                        style="width:100%"></select>
                                </div>
                            </div>

                            <!-- Aktif -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" name="active" type="checkbox" id="aktif" />
                                <label class="form-check-label" for="aktif">Aktif</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- jQuery (only once!) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- AdminLTE JS -->
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>

    <script>
        $(document).ready(function() {
            const subKategoriCheck = $("#subKategori");
            const subKategoriFields = $("#subKategoriFields");

            // Toggle subKategori fields
            subKategoriCheck.on("change", function() {
                if ($(this).is(":checked")) {
                    subKategoriFields.removeClass("d-none");
                } else {
                    subKategoriFields.addClass("d-none");
                    $("#groupSelect").val(null).trigger("change");
                }
            });

            // Init select2
            $("#groupSelect").select2({
                placeholder: "Pilih Grup Kategori",
                allowClear: true
            });

            // Ambil data kategori parent
            $.ajax({
                url: "{{ route('categories.parent') }}",
                type: "GET",
                dataType: "json",
                success: function(res) {
                    const data = res.map(item => ({
                        id: item.id,
                        text: item.category
                    }));
                    $("#groupSelect").empty().select2({
                        data: data,
                        placeholder: "Pilih Grup Kategori",
                        allowClear: true
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error load categories:", error);
                }
            });

            // AJAX submit form
            $("#formKategori").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        alert(res.message ?? "Kategori berhasil ditambahkan!");
                        $("#formKategori")[0].reset();
                        $("#groupSelect").val(null).trigger("change");
                        subKategoriFields.addClass("d-none");
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const messages = Object.values(xhr.responseJSON.errors).flat().join(
                                "\n");
                            alert("❌ Error:\n" + messages);
                        } else {
                            alert("❌ Terjadi kesalahan pada server.");
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
