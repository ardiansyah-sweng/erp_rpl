<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Filled Form Warehouse</title>
    <!--begin::Primary Meta Tags-->
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
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href={{ asset("assets/dist/css/adminlte.css") }} />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />
  </head>
  <!--end::Head-->
  <!--begin::Body-->
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
                      <img
                        src={{asset("assets/dist/assets/img/user1-128x128.jpg")}}
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
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src={{ asset("assets/dist/assets/img/user8-128x128.jpg") }}
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
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
                      <img
                        src={{ asset("assets/dist/assets/img/user3-128x128.jpg") }}
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
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
                <img
                  src={{ asset("assets/dist/assets/img/user2-160x160.jpg") }}
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline">Mimin Gantenk</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src={{ asset("assets/dist/assets/img/user2-160x160.jpg") }}
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
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
          <a href="dashboard" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src={{asset("assets/dist/assets/img/LogoRPL.png")}}
              alt="RPL"
              class="brand-image opacity-75 shadow"
            />
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
            <ul
            class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="menu"
            data-accordion="false">
                <li class="nav-item">
                <a href="dashboard" class="nav-link active">
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
                <a href="{{ route('branch.list') }}" class="nav-link">
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
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Filled Form Warehouse</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit Warehouse</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="container">
                <!-- Filled Form Supplier -->
                 @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if(isset($error))
        <div class="alert alert-danger">
            {{ $error }}
        </div>
      @endif
      @if($warehouse)
        <form method="POST" action="#">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">ID Warehouse</label>
                <input type="text" class="form-control" value="{{ $warehouse->id }}" disabled>
                <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Gudang</label>
                <input type="text" name="warehouse_name" class="form-control" value="{{ old('warehouse_name', $warehouse->warehouse_name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat Gudang</label>
                <input type="text" name="warehouse_address" class="form-control" value="{{ old('warehouse_address', $warehouse->warehouse_address) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">No. Telepon Gudang</label>
                <input type="text" name="warehouse_telephone" class="form-control" value="{{ old('warehouse_telephone', $warehouse->warehouse_telephone) }}" required>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3 form-check">
                        <input type="hidden" name="is_rm_whouse" value="0">
                        <input type="checkbox" name="is_rm_whouse" id="is_rm_whouse" class="form-check-input" value="1" {{ old('is_rm_whouse', $warehouse->is_rm_whouse) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_rm_whouse">Gudang Bahan Baku (RM)</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 form-check">
                        <input type="hidden" name="is_fg_whouse" value="0">
                        <input type="checkbox" name="is_fg_whouse" id="is_fg_whouse" class="form-check-input" value="1" {{ old('is_fg_whouse', $warehouse->is_fg_whouse) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_fg_whouse">Gudang Barang Jadi (FG)</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $warehouse->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ url()->current() }}" class="btn btn-secondary">Batal</a>
        </form>
      @endif
          </div>
        </div>
        <!-- Form Input Produksi dan Material -->
        <div class="card p-4 mb-4 shadow-sm" style="max-width: 700px; margin: auto; border-radius: 16px; background: #fff;">
          <form id="productionInputForm" class="mb-3">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Nomor Produksi</label>
                <input type="text" class="form-control" id="nomorProduksi" placeholder="">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">SKU</label>
                <input type="text" class="form-control" id="sku" placeholder="">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Cabang</label>
                <select class="form-select" id="cabang">
                  <option value="">Pilih Cabang</option>
                  <option value="A">Cabang A</option>
                  <option value="B">Cabang B</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Gudang Finished Goods</label>
                <input type="date" class="form-control" id="gudangFG">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Gudang Raw Material</label>
                <select class="form-select" id="gudangRM">
                  <option value="">Pilih Gudang</option>
                  <option value="RM1">Gudang RM1</option>
                  <option value="RM2">Gudang RM2</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal Produksi</label>
                <input type="date" class="form-control" id="tanggalProduksi">
              </div>
            </div>
          </form>
          <div class="table-responsive mb-4">
            <table id="materialTable" class="table table-bordered align-middle mb-0" style="border-radius: 8px; overflow: hidden;">
              <thead class="table-light text-center">
                <tr>
                  <th>Nama Item</th>
                  <th>Quantity</th>
                  <th>Satuan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <!-- Baris material akan ditambah lewat JS -->
              </tbody>
            </table>
          </div>
          <!-- Form Add Material -->
          <form id="addMaterialForm" class="mb-4">
            <div class="row g-2 mb-2">
              <div class="col-md-4">
                <input type="text" class="form-control" id="materialName" placeholder="Nama Item" required>
              </div>
              <div class="col-md-3">
                <input type="number" class="form-control" id="materialQty" placeholder="Quantity" min="1" required>
              </div>
              <div class="col-md-3">
                <select class="form-select" id="materialUnit">
                  <option value="PCS">PCS</option>
                  <option value="KG">KG</option>
                  <option value="L">L</option>
                </select>
              </div>
              <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Tambah Material</button>
              </div>
            </div>
          </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
          <table id="productionTable" class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-light text-center">
              <tr>
                <th>No</th>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Tanggal Produksi</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- Static Data List -->
              <tr>
                    <td class="text-center">1</td>
                    <td>PROD001</td>
                    <td>Kopi Hitam</td>
                    <td>2025-06-01</td>
                    <td>100</td>
                    <td class="text-center">
                    <span class="badge bg-success">Selesai</span>
                    </td>
                    <td class="text-center">
                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                        <a href="#" class="btn btn-warning btn-sm custom-btn">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm custom-btn">Delete</a>
                    </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>PROD002</td>
                    <td>Latte</td>
                    <td>2025-06-02</td>
                    <td>80</td>
                    <td class="text-center">
                    <span class="badge bg-warning text-dark">Proses</span>
                    </td>
                    <td class="text-center">
                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                        <a href="#" class="btn btn-warning btn-sm custom-btn">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm custom-btn">Delete</a>
                    </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>PROD003</td>
                    <td>Cappuccino</td>
                    <td>2025-06-03</td>
                    <td>120</td>
                    <td class="text-center">
                    <span class="badge bg-success">Selesai</span>
                    </td>
                    <td class="text-center">
                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                        <a href="#" class="btn btn-warning btn-sm custom-btn">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm custom-btn">Delete</a>
                    </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td>PROD004</td>
                    <td>Espresso</td>
                    <td>2025-06-04</td>
                    <td>90</td>
                    <td class="text-center">
                    <span class="badge bg-danger">Batal</span>
                    </td>
                    <td class="text-center">
                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                        <a href="#" class="btn btn-warning btn-sm custom-btn">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm custom-btn">Delete</a>
                    </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">5</td>
                    <td>PROD005</td>
                    <td>Mocha</td>
                    <td>2025-06-05</td>
                    <td>110</td>
                    <td class="text-center">
                    <span class="badge bg-success">Selesai</span>
                    </td>
                    <td class="text-center">
                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                        <a href="#" class="btn btn-warning btn-sm custom-btn">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm custom-btn">Delete</a>
                    </div>
                    </td>
            </tbody>
          </table>
        </div>

        <!-- Pagination Info -->
        <div class="d-flex justify-content-between align-items-center mt-3">
          <div>Showing 1 to 3 of 3 entries</div>
          <nav>
            <ul class="pagination">
              <li class="page-item disabled">
                <a class="page-link" href="#">Previous</a>
              </li>
              <li class="page-item active">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item disabled">
                <a class="page-link" href="#">Next</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Custom CSS -->
<style>
.custom-btn {
  padding: 3px 8px;
  font-size: 0.75rem;
  line-height: 1.5;
}
</style>


<!--begin::Footer-->
<footer class="app-footer">
        <!--begin::To the end-->

      </main>
      <footer class="app-footer">

        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <strong>
          Copyright &copy; 2014-2024&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>

      </main>
      <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <strong>
          Copyright &copy; 2014-2024&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="../../dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
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
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- sortablejs -->
    <script
      src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
      integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ="
      crossorigin="anonymous"
    ></script>
    <!-- sortablejs -->
    <script>
      const connectedSortables = document.querySelectorAll('.connectedSortable');
      connectedSortables.forEach((connectedSortable) => {
        let sortable = new Sortable(connectedSortable, {
          group: 'shared',
          handle: '.card-header',
        });
      });
      const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
      cardHeaders.forEach((cardHeader) => {
        cardHeader.style.cursor = 'move';
      });
    </script>
    <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>
    <!-- ChartJS -->
    <script>
      // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
      // IT'S ALL JUST JUNK FOR DEMO
      // ++++++++++++++++++++++++++++++++++++++++++
      const sales_chart_options = {
        series: [
          {
            name: 'Digital Goods',
            data: [28, 48, 40, 19, 86, 27, 90],
          },
          {
            name: 'Electronics',
            data: [65, 59, 80, 81, 56, 55, 40],
          },
        ],
        chart: {
          height: 300,
          type: 'area',
          toolbar: {
            show: false,
          },
        },
        legend: {
          show: false,
        },
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: 'smooth',
        },
        xaxis: {
          type: 'datetime',
          categories: [
            '2023-01-01',
            '2023-02-01',
            '2023-03-01',
            '2023-04-01',
            '2023-05-01',
            '2023-06-01',
            '2023-07-01',
          ],
        },
        tooltip: {
          x: {
            format: 'MMMM yyyy',
          },
        },
      };
      const sales_chart = new ApexCharts(
        document.querySelector('#revenue-chart'),
        sales_chart_options,
      );
      sales_chart.render();
    </script>
    <!-- jsvectormap -->
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
      integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
      integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
      crossorigin="anonymous"
    ></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- AdminLTE JS -->
    <script src={{ asset("assets/dist/js/adminlte.js")}}></script>
    <!-- Custom Sidebar Toggle Script -->
    <script>
    $(document).ready(function () {
        $('[data-widget="pushmenu"]').on('click', function (e) {
            e.preventDefault();
            $('body').toggleClass('sidebar-collapse');
        });
    });
    </script>

    <!--end::Script-->
    <script>
    // Script Add Material ke tabel
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('addMaterialForm');
      const tableBody = document.querySelector('#materialTable tbody');
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        const name = document.getElementById('materialName').value;
        const qty = document.getElementById('materialQty').value;
        const unit = document.getElementById('materialUnit').value;
        if (name && qty) {
          const row = document.createElement('tr');
          row.innerHTML = `<td>${name}</td><td>${qty}</td><td>${unit}</td><td><button type='button' class='btn btn-outline-danger btn-sm btn-hapus'>Hapus</button></td>`;
          tableBody.appendChild(row);
          form.reset();
        }
      });
      tableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-hapus')) {
          e.target.closest('tr').remove();
        }
      });
    });
  </script>

    </script>
  </body>
</html>
