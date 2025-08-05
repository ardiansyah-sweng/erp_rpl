<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Detail Cabang</title>
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
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img src={{ asset('assets/dist/assets/img/user2-160x160.jpg') }} class="user-image rounded-circle shadow" alt="User Image" />
                <span class="d-none d-md-inline">Admin</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li class="user-header text-bg-primary">
                  <img src={{ asset('assets/dist/assets/img/user2-160x160.jpg') }} class="rounded-circle shadow" alt="User Image" />
                  <p>Admin - Web Developer<small>Member since Jan. 2024</small></p>
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
            <img src={{ asset('assets/dist/assets/img/LogoRPL.png') }} alt="RPL" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">ERP RPL UAD</span>
          </a>
        </div>
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
              <li class="nav-item">
                <a href="/dashboard" class="nav-link">
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
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-person-circle"></i>
                  <p>Supplier<i class="nav-arrow bi bi-chevron-right"></i></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('purchase.orders') }}" class="nav-link active">
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
          </nav>
        </div>
      </aside>
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Detail Warehouse</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('warehouse.list') }}">Warehouse</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Detail Warehouse</li>
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
                    <h3 class="card-title"> </h3>
                  </div>
                  <div class="card-body">
                    <h6>ID Warehouse</h6>
                    <h4>{{ $warehouse['id'] ?? '-' }}</h4>
                    <h6>Warehouse Name</h6>
                    <h4>{{ $warehouse['warehouse_name'] ?? '-' }}</h4>
                    <h6>Warehouse Address</h6>
                    <h4>{{ $warehouse['warehouse_address'] ?? '-' }}</h4>
                    <h6>Warehouse Telephone</h6>
                    <h4>{{ $warehouse['warehouse_telephone'] ?? '-' }}</h4>
                    <h6>Status</h6>
                    <h4>
                      @if(isset($warehouse['is_active']) && $warehouse['is_active'])
                        <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Aktif</span>
                      @else
                        <span class="badge bg-danger"><i class="bi bi-x-circle-fill"></i> Tidak Aktif</span>
                      @endif
                    </h4>
                    <h6>Created At</h6>
                    <h4>{{ $warehouse['created_at'] ?? '-' }}</h4>
                    <h6>Updated At</h6>
                    <h4>{{ $warehouse['updated_at'] ?? '-' }}</h4>
                    <h6 class="mt-4">Warehouse Item List</h6>
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Location</th>
                            <th>Comments</th>
                            <th>Created At</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if(isset($items) && count($items))
                            @foreach ($items as $item)
                              <tr>
                                <td>{{ $item['id'] ?? '-' }}</td>
                                <td>{{ $item['name'] ?? '-' }}</td>
                                <td>{{ $item['quantity'] ?? '-' }}</td>
                                <td>{{ $item['location'] ?? '-' }}</td>
                                <td>{{ $item['comments'] ?? '-' }}</td>
                                <td>{{ $item['created_at'] ?? '-' }}</td>
                              </tr>
                            @endforeach
                          @else
                            <tr><td colspan="6" class="text-center">Tidak ada data item.</td></tr>
                          @endif
                        </tbody>
                      </table>
                    </div>
                  </div>
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
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src={{ asset('assets/dist/js/adminlte.js') }}></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(document).ready(function () {
        $('[data-widget="pushmenu"]').on('click', function (e) {
          e.preventDefault();
          $('body').toggleClass('sidebar-collapse');
        });
      });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src={{ asset('assets/dist/js/adminlte.js') }}></script>
</body>
</html>