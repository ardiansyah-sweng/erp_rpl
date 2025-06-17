<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>ERP RPL UAD | Daftar PIC Supplier</title>
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
  <!-- apexcharts -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
    crossorigin="anonymous" />
  <!-- jsvectormap -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
    integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
    crossorigin="anonymous" />
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
              <!-- Messages dropdown content -->
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
              <!-- Notifications dropdown content -->
              <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
          </li>
          <!--end::Notifications Dropdown Menu-->
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
              <!-- User menu content -->
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
              <a href="{{ route('product.list') }}" class="nav-link">
                <i class="nav-icon bi bi-box-seam-fill"></i>
                <p>Produk</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon bi bi-person-circle"></i>
                <p>
                  Supplier
                  <i class="nav-arrow bi bi-chevron-right"></i>
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
                  <a href="/supplier/pic/list" class="nav-link active">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Daftar PIC supplier</p>
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
              <a href="{{ route('branch.list') }}" class="nav-link">
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
              <h3 class="mb-0 me-2">Daftar PIC Supplier</h3>
              <a href="/supplier/pic/add" class="btn btn-primary btn-sm">Tambah</a>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar PIC Supplier</li>
              </ol>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>

      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title">List Table</h3>
          <form action="{{ route('supplier.pic.list') }}" method="GET" class="d-flex ms-auto">
            <div class="input-group input-group-sm ms-auto" style="width: 450px;">
              <input type="text" name="keywords" class="form-control" placeholder="Search PIC Supplier">
              <div class="input-group-append">
                <button type="submit" class="btn btn-default">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table class="table table-bordered">
            <thead class="text-center">
              <tr>
                <th>Supplier ID</th>
                <th>Nama Supplier</th>
                <th>Nama PIC</th>
                <th>Email</th>
                <th>Telephone</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pics as $pic)
              <tr>
                <td>{{ $pic->supplier_id }}</td>
                <td>{{ $pic->supplier ? $pic->supplier->company_name : 'N/A' }}</td>
                <td>{{ $pic->name }}</td>
                <td>{{ $pic->email }}</td>
                <td>{{ $pic->phone_number }}</td>
                <td>
                  <a href="/supplier/pic/detail/{{ $pic->id }}" class="btn btn-sm btn-info">Detail</a>
                  <a href="#" class="btn btn-sm btn-primary">Edit</a>
                  
                  <form action="{{ url('/supplier/pic/delete/' . $pic->id) }}" method="POST" class="delete-form d-inline" data-name="{{ $pic->name }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-name="{{ $pic->name }}">Hapus</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center">No data available in table</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          {{ $pics->links('pagination::bootstrap-4') }}
        </div>
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
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
  <script src={{ asset("assets/dist/js/adminlte.js") }}></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Custom Sidebar Toggle Script -->
  <script>
    $(document).ready(function() {
      $('[data-widget="pushmenu"]').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-collapse');
      });
    });
  </script>

<!-- Tambahkan SweetAlert2 di sini -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script confirmation delete -->
<script>
  $(document).ready(function () {
    $('.btn-delete').on('click', function (e) {
      e.preventDefault(); // hentikan form dari submit langsung

      const form = $(this).closest('form'); // ambil form terdekat
      const name = $(this).data('name'); // ambil nama PIC dari data attribute

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: `Data PIC "${name}" akan dihapus permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit(); // jika user setuju, kirim form
        }
      });
    });
  });
</script>



  <!--end::Script-->
</body>
<!--end::Body-->

</html>