<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>ERP RPL UAD | Low Stock Alert</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE v4 | Dashboard" />
  <meta name="author" content="ColorlibHQ" />
  <meta
    name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
  <meta
    name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
  <!--end::Primary Meta Tags-->
  <!--begin::Fonts-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
    crossorigin="anonymous" />
  <!--end::Fonts-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
    integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href={{ asset("assets/dist/css/adminlte.css") }} />
  <!--end::Required Plugin(AdminLTE)-->
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
                alt="User Image" />
              <span class="d-none d-md-inline">Mimin Gantenk</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <li class="user-header text-bg-primary">
                <img
                  src={{ asset("assets/dist/assets/img/user2-160x160.jpg") }}
                  class="rounded-circle shadow"
                  alt="User Image" />
                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2023</small>
                </p>
              </li>
              <li class="user-footer">
                <a href="#" class="btn btn-default btn-flat">Profile</a>
                <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
              </li>
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
          <img
            src={{asset("assets/dist/assets/img/LogoRPL.png")}}
            alt="RPL"
            class="brand-image opacity-75 shadow" />
          <span class="brand-text fw-light">ERP RPL UAD</span>
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
              <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="nav-icon bi bi-speedometer"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('merks.index') }}" class="nav-link">
                <i class="nav-icon bi bi-tag-fill"></i>
                <p>Merk</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('product.list') }}" class="nav-link">
                <i class="nav-icon bi bi-box-seam-fill"></i>
                <p>Produk</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('warehouses.index') }}" class="nav-link">
                <i class="nav-icon bi bi-box2"></i>
                <p>Warehouse</p>
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
                  <a href="/supplier/add" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Tambah Supplier</p>
                  </a>
                </li>
                <li class="nav-item">
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
                <p>Purchase Orders</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('branches.index') }}" class="nav-link">
                <i class="nav-icon bi bi-clipboard-fill"></i>
                <p>Branch</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('item.list') }}" class="nav-link">
                <i class="nav-icon bi bi-clipboard-fill"></i>
                <p>Item</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('item.low-stock') }}" class="nav-link active">
                <i class="nav-icon bi bi-exclamation-triangle-fill"></i>
                <p>Low Stock Alert</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('categories.index') }}" class="nav-link">
                <i class="nav-icon bi bi-clipboard-fill"></i>
                <p>Category</p>
              </a>
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
          <div class="row align-items-center">
            <div class="col-sm-6 d-flex align-items-center">
              <h3 class="mb-0 me-2">
                <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                Low Stock Alert
              </h3>
              @if($lowStockCount > 0)
                <span class="badge bg-danger ms-2">{{ $lowStockCount }} item</span>
              @else
                <span class="badge bg-success ms-2">Stok Aman</span>
              @endif
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('item.list') }}">Item</a></li>
                <li class="breadcrumb-item active" aria-current="page">Low Stock Alert</li>
              </ol>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>
      <!--end::App Content Header-->

      @if($lowStockCount > 0)
      <div class="app-content pt-0 pb-0">
        <div class="container-fluid">
          <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>
              Terdapat <strong>{{ $lowStockCount }} item</strong> yang stoknya berada di bawah atau sama dengan batas minimum. Segera lakukan restock atau buat Purchase Order.
            </div>
          </div>
        </div>
      </div>
      @endif

      <div class="card mb-4">
        <div class="card-header">
          <h3 class="card-title">
            Daftar Item Stok Rendah
            <span class="badge bg-secondary ms-2">Total: {{ $lowStockCount }}</span>
          </h3>
          <div class="card-tools">
            <a href="{{ route('purchase.orders') }}" class="btn btn-sm btn-primary">
              <i class="bi bi-cart-plus me-1"></i> Buat Purchase Order
            </a>
            <a href="{{ route('item.list') }}" class="btn btn-sm btn-secondary ms-1">
              <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Item
            </a>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table class="table table-bordered">
            <thead class="text-center table-warning">
              <tr>
                <th style="width: 10px">No</th>
                <th>SKU</th>
                <th>Nama Item</th>
                <th>Satuan</th>
                <th>Stok Saat Ini</th>
                <th>Stok Minimum</th>
                <th>Selisih</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @forelse($items as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $item->sku }}</strong></td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->unit?->unit_name ?? '-' }}</td>
                <td>
                  <span class="badge {{ $item->stock_unit == 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                    {{ $item->stock_unit }}
                  </span>
                </td>
                <td>{{ $item->minimum_stock }}</td>
                <td>
                  @php $selisih = $item->stock_unit - $item->minimum_stock; @endphp
                  <span class="text-danger fw-bold">{{ $selisih }}</span>
                </td>
                <td>
                  @if($item->stock_unit == 0)
                    <span class="badge bg-danger">Habis</span>
                  @else
                    <span class="badge bg-warning text-dark">Stok Rendah</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center text-success">
                  <i class="bi bi-check-circle-fill me-1"></i>
                  Semua item memiliki stok di atas batas minimum. Tidak ada peringatan stok rendah.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>

    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
    <footer class="app-footer">
      <!--begin::To the end-->
      <div class="float-end d-none d-sm-inline">Anything you want</div>
      <!--end::To the end-->
      <!--begin::Copyright-->
      <strong>
        Copyright &copy; 2014-2024&nbsp;
        <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
      </strong>
      All rights reserved.
      <!--end::Copyright-->
    </footer>
    <!--end::Footer-->
  </div>
  <!--end::App Wrapper-->
  <!--begin::Script-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
    crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src={{ asset("assets/dist/js/adminlte.js") }}></script>
  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function() {
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
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- AdminLTE JS -->
  <script src={{ asset("assets/dist/js/adminlte.js") }}></script>
  <script>
    $(document).ready(function() {
      $('[data-widget="pushmenu"]').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-collapse');
      });
    });
  </script>
  <!--end::Script-->
</body>
<!--end::Body-->

</html>
